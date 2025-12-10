# waf_api.py — standalone WAF microservice with dual-model detection for SQL Injection & XSS
# Usage: python waf_api.py
# - Expects model artifacts in:
#     - `models/`: SQL Injection detection models
#     - `models_xss/`: XSS detection models
# - Environment variables (optional):
#     WAF_THRESHOLD -> float threshold for probability to consider malicious (default: 0.5)
#
# Endpoints:
#  - GET  /predict?param=...   (or any query param, first value taken) -> returns HTML if browser
#  - POST /predict            (JSON: {"param": "..."} OR form-data) -> returns JSON for application/json requests, HTML for browser/form
#  - GET  /                   (dashboard HTML)
#  - POST /reload             (reload models & vectorizers)

from flask import Flask, request, jsonify, render_template_string, redirect, url_for
import os
import sys
import joblib
import logging
from typing import Any, Dict
import numpy as np
from collections import deque
import time
import re

# Configuration for SQL Injection Detection
SQLI_MODEL_DIR = os.environ.get('WAF_MODEL_DIR', 'models')
SQLI_MODEL = os.environ.get('WAF_MODEL', 'random_forest.joblib')
SQLI_VECTORIZER_NAME = os.environ.get('WAF_VECTORIZER', 'tfidf_vectorizer.joblib')

# Configuration for XSS Detection
XSS_MODEL_DIR = os.environ.get('WAF_XSS_MODEL_DIR', 'models_xss')
XSS_MODEL = os.environ.get('WAF_XSS_MODEL', 'random_forest_xss.joblib')
XSS_VECTORIZER_NAME = os.environ.get('WAF_XSS_VECTORIZER', 'tfidf_vectorizer_xss.joblib')

THRESHOLD = float(os.environ.get('WAF_THRESHOLD', '0.5'))

SQLI_MODEL_PATH = os.path.join(SQLI_MODEL_DIR, SQLI_MODEL)
SQLI_VECTORIZER_PATH = os.path.join(SQLI_MODEL_DIR, SQLI_VECTORIZER_NAME)
XSS_MODEL_PATH = os.path.join(XSS_MODEL_DIR, XSS_MODEL)
XSS_VECTORIZER_PATH = os.path.join(XSS_MODEL_DIR, XSS_VECTORIZER_NAME)

# Logging
logging.basicConfig(stream=sys.stdout, level=logging.INFO, format='[%(asctime)s] %(levelname)s - %(message)s')
logger = logging.getLogger('waf_api')

app = Flask(__name__)

# Load artifacts at startup
sqli_model = None
sqli_vectorizer = None
xss_model = None
xss_vectorizer = None

# In-memory stats for dashboard
TOTAL_REQUESTS = 0
MALICIOUS_COUNT = 0
BENIGN_COUNT = 0
SQLI_DETECTED = 0
XSS_DETECTED = 0
RECENT_PREDICTIONS = deque(maxlen=50)  # store dicts: {time, input, score, prediction, attack_type}


def load_artifacts() -> None:
    global sqli_model, sqli_vectorizer, xss_model, xss_vectorizer
    
    # Load SQL Injection models
    try:
        logger.info(f'Loading SQL Injection model from: {SQLI_MODEL_PATH}')
        sqli_model = joblib.load(SQLI_MODEL_PATH)
    except Exception as e:
        logger.error(f'Failed to load SQL Injection model at {SQLI_MODEL_PATH}: {e}')
        sqli_model = None

    try:
        logger.info(f'Loading SQL Injection vectorizer from: {SQLI_VECTORIZER_PATH}')
        sqli_vectorizer = joblib.load(SQLI_VECTORIZER_PATH)
    except Exception as e:
        logger.error(f'Failed to load SQL Injection vectorizer at {SQLI_VECTORIZER_PATH}: {e}')
        sqli_vectorizer = None
    
    # Load XSS models
    try:
        logger.info(f'Loading XSS model from: {XSS_MODEL_PATH}')
        xss_model = joblib.load(XSS_MODEL_PATH)
    except Exception as e:
        logger.error(f'Failed to load XSS model at {XSS_MODEL_PATH}: {e}')
        xss_model = None

    try:
        logger.info(f'Loading XSS vectorizer from: {XSS_VECTORIZER_PATH}')
        xss_vectorizer = joblib.load(XSS_VECTORIZER_PATH)
    except Exception as e:
        logger.error(f'Failed to load XSS vectorizer at {XSS_VECTORIZER_PATH}: {e}')
        xss_vectorizer = None
        vectorizer = None


load_artifacts()


def detect_attack_type(text: str) -> str:
    """Detect the type of attack (SQL Injection or XSS) based on patterns.
    
    Returns: 'sqli', 'xss', or 'unknown'
    """
    text_lower = text.lower()
    
    # XSS patterns: HTML/JavaScript tags and entities
    xss_patterns = [
        r'<\s*script',
        r'<\s*iframe',
        r'<\s*img',
        r'<\s*svg',
        r'<\s*object',
        r'javascript:',
        r'onerror\s*=',
        r'onload\s*=',
        r'onclick\s*=',
        r'onmouseover\s*=',
        r'<\s*embed',
        r'<\s*frame',
        r'&#x',
        r'&#\d+',
    ]
    
    for pattern in xss_patterns:
        if re.search(pattern, text_lower, re.IGNORECASE):
            return 'xss'
    
    # SQL Injection patterns: SQL keywords and syntax
    sqli_patterns = [
        r'\bor\b\s+[\'\"]?[\da-z_\s]+[\'\"]?\s*=\s*[\'\"]?[\da-z_\s]+[\'\"]?',
        r'\bunion\b',
        r'\bselect\b',
        r'\bfrom\b',
        r'\bwhere\b',
        r'\bdrop\b',
        r'\binsert\b',
        r'\bupdate\b',
        r'\bdelete\b',
        r'\bexec\b',
        r'\bexecute\b',
        r'\b;',
        r'--',
        r'/\*.*\*/',
        r'xp_',
        r'sp_',
    ]
    
    for pattern in sqli_patterns:
        if re.search(pattern, text_lower, re.IGNORECASE):
            return 'sqli'
    
    return 'unknown'


def get_text_from_request(req: request) -> str:
    """Extract a single text string to classify from the incoming request.

    Priority order:
      1. JSON body with key 'param'
      2. JSON body with any string value (first found)
      3. Form data key 'param'
      4. Form data any key (first found)
      5. Query string 'param'
      6. Query string any key (first found)
    """
    # JSON
    if req.is_json:
        try:
            data = req.get_json(force=True)
            if isinstance(data, dict):
                if 'param' in data:
                    return str(data['param'])
                # take first string value
                for v in data.values():
                    if isinstance(v, (str, int, float)):
                        return str(v)
        except Exception:
            pass

    # Form data
    if req.form:
        if 'param' in req.form:
            return req.form.get('param')
        # first value
        for k in req.form.keys():
            return req.form.get(k)

    # Query params (GET)
    args = req.args
    if args:
        if 'param' in args:
            return args.get('param')
        for k in args.keys():
            return args.get(k)

    return ''


def client_wants_html(req: request) -> bool:
    """Heuristic: return True if client prefers HTML (browser) over JSON.
    Uses Accept header preference and also treats simple GET requests as browser tests.
    """
    # If explicit ?format=json param provided, prefer JSON
    if req.args.get('format') == 'json':
        return False
    best = req.accept_mimetypes.best_match(['application/json', 'text/html'])
    if best == 'text/html':
        return True
    # treat GET with no JSON body as likely browser/test form
    if req.method == 'GET':
        return True
    return False


@app.route('/predict', methods=['GET', 'POST'])
def predict() -> Any:
    global TOTAL_REQUESTS, MALICIOUS_COUNT, BENIGN_COUNT, SQLI_DETECTED, XSS_DETECTED, RECENT_PREDICTIONS

    text = get_text_from_request(request)
    if not text:
        if client_wants_html(request):
            html = """
            <html><body>
            <h3>WAF-AI — Predict</h3>
            <p style="color:orange">No input provided. Use ?param=... or send JSON/form-data with key 'param'.</p>
            </body></html>
            """
            return html, 400
        return jsonify({'error': 'no input provided. Send JSON {"param":"..."}, form-data param=..., or use query string ?param=...'}), 400

    text_clean = str(text).lower().strip()
    
    # Detect attack type
    attack_type = detect_attack_type(text_clean)
    
    # Select appropriate model and vectorizer
    if attack_type == 'xss':
        model = xss_model
        vectorizer = xss_vectorizer
        if model is None or vectorizer is None:
            return jsonify({'error': 'XSS model or vectorizer not loaded'}), 500
    else:  # 'sqli' or 'unknown' - use SQL Injection model as default
        model = sqli_model
        vectorizer = sqli_vectorizer
        if model is None or vectorizer is None:
            return jsonify({'error': 'SQL Injection model or vectorizer not loaded'}), 500

    try:
        vec = vectorizer.transform([text_clean])
    except Exception as e:
        logger.exception(f'Vectorization failed for {attack_type}')
        if client_wants_html(request):
            return f"<html><body><h3>Vectorization failed</h3><pre>{e}</pre></body></html>", 500
        return jsonify({'error': 'vectorization failed', 'detail': str(e)}), 500

    # Obtain a score/probability for the 'malicious' class (assumed to be label 1)
    score = None
    try:
        if hasattr(model, 'predict_proba'):
            prob = model.predict_proba(vec)
            if prob.shape[1] == 2:
                score = float(prob[0][1])
            else:
                score = float(prob[0].max())
        elif hasattr(model, 'decision_function'):
            df = model.decision_function(vec)
            val = float(df[0])
            score = float(1.0 / (1.0 + np.exp(-val)))
        else:
            pred = model.predict(vec)
            score = float(pred[0])
    except Exception as e:
        logger.exception(f'Model scoring failed for {attack_type}')
        if client_wants_html(request):
            return f"<html><body><h3>Model scoring failed</h3><pre>{e}</pre></body></html>", 500
        return jsonify({'error': 'model scoring failed', 'detail': str(e)}), 500

    pred_label = int(score >= THRESHOLD)

    result: Dict[str, Any] = {
        'prediction': 'malicious' if pred_label == 1 else 'benign',
        'score': score,
        'threshold': THRESHOLD,
        'action': 'block' if pred_label == 1 else 'allow',
        'raw_input': text_clean,
        'attack_type': attack_type,
    }

    # update stats
    TOTAL_REQUESTS += 1
    if pred_label == 1:
        MALICIOUS_COUNT += 1
        if attack_type == 'xss':
            XSS_DETECTED += 1
        elif attack_type == 'sqli':
            SQLI_DETECTED += 1
    else:
        BENIGN_COUNT += 1

    RECENT_PREDICTIONS.appendleft({'time': time.time(), 'input': text_clean, 'score': score, 'prediction': result['prediction'], 'attack_type': attack_type})

    # If client prefers HTML (browser), render a user-friendly page
    if client_wants_html(request):
        if pred_label == 1:
            # Malicious: show BLOCKED page. Return 403 to signal blocked if invoked from browser.
            html = f"""
            <!doctype html>
            <html><head><title>WAF-AI — Blocked</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
            <style>body{{padding:20px}}</style></head>
            <body>
              <div class="container">
                <div class="alert alert-danger" role="alert">
                  <h4 class="alert-heading">BLOCKED</h4>
                  <p>The input was classified as <strong>malicious</strong> and has been blocked by the WAF.</p>
                </div>
                <div class="card">
                  <div class="card-body">
                    <h6>Details</h6>
                    <p><strong>Input:</strong> <code>{text_clean}</code></p>
                    <p><strong>Score:</strong> {score:.4f} &nbsp; (threshold {THRESHOLD})</p>
                    <p><strong>Action:</strong> block</p>
                  </div>
                </div>
                <p class="mt-3"><a href="/">Back to dashboard</a></p>
              </div>
            </body></html>
            """
            return html, 403
        else:
            # Benign: simple allowed page with status 200
            html = f"""
            <!doctype html>
            <html><head><title>WAF-AI — Allowed</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
            <style>body{{padding:20px}}</style></head>
            <body>
              <div class="container">
                <div class="alert alert-success" role="alert">
                  <h4 class="alert-heading">ALLOWED</h4>
                  <p>The input was classified as <strong>benign</strong> and is allowed.</p>
                </div>
                <div class="card">
                  <div class="card-body">
                    <h6>Details</h6>
                    <p><strong>Input:</strong> <code>{text_clean}</code></p>
                    <p><strong>Score:</strong> {score:.4f} &nbsp; (threshold {THRESHOLD})</p>
                    <p><strong>Action:</strong> allow</p>
                  </div>
                </div>
                <p class="mt-3"><a href="/">Back to dashboard</a></p>
              </div>
            </body></html>
            """
            return html, 200

    # Otherwise return JSON (API client)
    return jsonify(result)


@app.route('/', methods=['GET'])
def dashboard() -> Any:
    # Prepare stats for rendering
    recent = list(RECENT_PREDICTIONS)[:20]
    recent_rows = []
    for r in recent:
        recent_rows.append({
            'ts': time.strftime('%Y-%m-%d %H:%M:%S', time.localtime(r['time'])),
            'input': r['input'],
            'score': f"{r['score']:.4f}",
            'prediction': r['prediction'],
            'attack_type': r.get('attack_type', 'unknown')
        })

    sqli_loaded = (sqli_model is not None) and (sqli_vectorizer is not None)
    xss_loaded = (xss_model is not None) and (xss_vectorizer is not None)

    # Basic HTML dashboard using Bootstrap + Chart.js CDNs
    html = """
    <!doctype html>
    <html lang="en">
      <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>WAF-AI Dashboard</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
        <style>
          body { padding: 20px; }
          .card { margin-bottom: 15px; }
          .small-input { max-width: 420px; }
          pre { white-space: pre-wrap; word-wrap: break-word; }
        </style>
      </head>
      <body>
        <div class="container-fluid">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>WAF-AI — Dashboard (Dual-Model: SQL Injection + XSS)</h3>
            <div>
              <form method="post" action="/reload" style="display:inline">
                <button class="btn btn-sm btn-outline-primary">Reload Models</button>
              </form>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3">
              <div class="card">
                <div class="card-body">
                  <h6 class="card-title">Service Status</h6>
                  <p class="card-text">SQL Injection Model: <strong>{{ 'Loaded' if sqli_loaded else 'Not Loaded' }}</strong></p>
                  <p class="card-text"><small><code>{{ sqli_model_path }}</code></small></p>
                  <p class="card-text">XSS Model: <strong>{{ 'Loaded' if xss_loaded else 'Not Loaded' }}</strong></p>
                  <p class="card-text"><small><code>{{ xss_model_path }}</code></small></p>
                  <p class="card-text">Threshold: <strong>{{ threshold }}</strong></p>
                </div>
              </div>

              <div class="card">
                <div class="card-body">
                  <h6 class="card-title">Counts</h6>
                  <p class="card-text">Total requests: <strong>{{ total }}</strong></p>
                  <p class="card-text text-danger">Malicious: <strong>{{ mal }}</strong></p>
                  <p class="card-text text-success">Benign: <strong>{{ ben }}</strong></p>
                  <hr style="margin: 10px 0;">
                  <p class="card-text text-warning">SQL Injection: <strong>{{ sqli_count }}</strong></p>
                  <p class="card-text text-warning">XSS Detected: <strong>{{ xss_count }}</strong></p>
                </div>
              </div>

              <div class="card">
                <div class="card-body">
                  <h6 class="card-title">Quick test</h6>
                  <form id="testForm" class="form-inline" method="get" action="/predict">
                    <input class="form-control mr-2 small-input" name="param" placeholder="test payload...">
                    <button class="btn btn-primary btn-sm" type="submit">Test</button>
                  </form>
                </div>
              </div>

            </div>

            <div class="col-md-9">
              <div class="card">
                <div class="card-body">
                  <h6 class="card-title">Prediction Summary</h6>
                  <canvas id="pieChart" width="400" height="150"></canvas>
                </div>
              </div>

              <div class="card">
                <div class="card-body">
                  <h6 class="card-title">Recent Predictions</h6>
                  <div style="max-height:360px; overflow:auto;">
                    <table class="table table-sm table-striped">
                      <thead>
                        <tr><th>Time</th><th>Input</th><th>Score</th><th>Prediction</th><th>Type</th></tr>
                      </thead>
                      <tbody>
                        {% for r in recent_rows %}
                        <tr>
                          <td>{{ r.ts }}</td>
                          <td><pre>{{ r.input }}</pre></td>
                          <td>{{ r.score }}</td>
                          <td>{{ r.prediction }}</td>
                          <td><span class="badge badge-info">{{ r.attack_type }}</span></td>
                        </tr>
                        {% endfor %}
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

            </div>
          </div>

        </div>

        <script>
          var ctx = document.getElementById('pieChart').getContext('2d');
          var pie = new Chart(ctx, {
              type: 'doughnut',
              data: {
                  labels: ['Malicious','Benign'],
                  datasets: [{
                      data: [{{ mal }}, {{ ben }}],
                      backgroundColor: ['#dc3545','#28a745']
                  }]
              },
              options: { responsive: true }
          });
        </script>
      </body>
    </html>
    """

    return render_template_string(html,
                                  sqli_loaded=sqli_loaded,
                                  xss_loaded=xss_loaded,
                                  sqli_model_path=SQLI_MODEL_PATH,
                                  xss_model_path=XSS_MODEL_PATH,
                                  threshold=THRESHOLD,
                                  total=TOTAL_REQUESTS,
                                  mal=MALICIOUS_COUNT,
                                  ben=BENIGN_COUNT,
                                  sqli_count=SQLI_DETECTED,
                                  xss_count=XSS_DETECTED,
                                  recent_rows=recent_rows)


@app.route('/reload', methods=['POST'])
def reload_artifacts() -> Any:
    """Reload model and vectorizer from disk (useful after retraining)."""
    load_artifacts()
    # clear stats? we keep stats but note reload time
    logger.info('Artifacts reloaded via /reload')
    return redirect(url_for('dashboard'))


if __name__ == '__main__':
    # For production, run with Gunicorn/Uvicorn. This Flask server is fine for testing.
    host = os.environ.get('WAF_HOST', '0.0.0.0')
    port = int(os.environ.get('WAF_PORT', '5000'))
    logger.info(f'Starting WAF API on {host}:{port} (SQLi={SQLI_MODEL_PATH}, XSS={XSS_MODEL_PATH})')
    app.run(host=host, port=port, debug=False)
