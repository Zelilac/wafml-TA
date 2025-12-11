"""Flask routes for WAF application"""
from flask import Flask, request, jsonify, render_template, redirect, url_for
import logging
from typing import Any

from app.config import Config
from app.detector import detector
from app.attack_classifier import AttackClassifier
from app.request_utils import RequestUtils
from app.statistics import stats

logger = logging.getLogger('waf_api')


def register_routes(app: Flask) -> None:
    """Register all routes with the Flask app
    
    Args:
        app: Flask application instance
    """
    
    @app.route('/predict', methods=['GET', 'POST'])
    def predict() -> Any:
        """Main prediction endpoint - accepts GET/POST with param data"""
        text = RequestUtils.get_text_from_request(request)
        
        if not text:
            if RequestUtils.client_wants_html(request):
                return render_template('error.html', 
                                     message="No input provided. Use ?param=... or send JSON/form-data with key 'param'."), 400
            return jsonify({
                'error': 'no input provided. Send JSON {"param":"..."}, form-data param=..., or use query string ?param=...'
            }), 400

        text_clean = str(text).lower().strip()
        
        # Detect attack type
        attack_type = AttackClassifier.detect_attack_type(text_clean)
        
        # Perform prediction
        result = detector.predict(text_clean, attack_type)
        
        # Handle errors
        if 'error' in result:
            status_code = result.get('status_code', 500)
            if RequestUtils.client_wants_html(request):
                return render_template('error.html', 
                                     message=f"{result['error']}: {result.get('detail', '')}"), status_code
            return jsonify({'error': result['error'], 'detail': result.get('detail', '')}), status_code
        
        # Record statistics
        stats.record_prediction(
            text_clean, 
            result['score'], 
            result['prediction'], 
            attack_type
        )
        
        # Prepare response
        response_data = {
            'prediction': result['prediction'],
            'score': result['score'],
            'threshold': result['threshold'],
            'action': result['action'],
            'raw_input': text_clean,
            'attack_type': attack_type,
        }
        
        # Return HTML or JSON based on client preference
        if RequestUtils.client_wants_html(request):
            if result['pred_label'] == 1:
                # Malicious - show blocked page
                return render_template('blocked.html',
                                     text=text_clean,
                                     score=f"{result['score']:.4f}",
                                     threshold=Config.THRESHOLD,
                                     attack_type=attack_type), 403
            else:
                # Benign - show allowed page
                return render_template('allowed.html',
                                     text=text_clean,
                                     score=f"{result['score']:.4f}",
                                     threshold=Config.THRESHOLD,
                                     attack_type=attack_type), 200
        
        # Return JSON for API clients
        return jsonify(response_data)
    
    
    @app.route('/', methods=['GET'])
    def dashboard() -> Any:
        """Dashboard page showing statistics and recent predictions"""
        recent_rows = stats.get_recent_predictions(limit=20)
        statistics = stats.get_stats()
        
        return render_template('dashboard.html',
                             sqli_loaded=detector.is_sqli_loaded(),
                             xss_loaded=detector.is_xss_loaded(),
                             sqli_model_path=Config.SQLI_MODEL_PATH,
                             xss_model_path=Config.XSS_MODEL_PATH,
                             threshold=Config.THRESHOLD,
                             total=statistics['total'],
                             mal=statistics['malicious'],
                             ben=statistics['benign'],
                             sqli_count=statistics['sqli'],
                             xss_count=statistics['xss'],
                             recent_rows=recent_rows)
    
    
    @app.route('/reload', methods=['POST'])
    def reload_artifacts() -> Any:
        """Reload model and vectorizer from disk (useful after retraining)"""
        detector.load_artifacts()
        logger.info('Artifacts reloaded via /reload')
        return redirect(url_for('dashboard'))
