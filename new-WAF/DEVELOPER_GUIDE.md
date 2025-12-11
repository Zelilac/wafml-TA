# WAF Developer Quick Reference

## 🚀 Getting Started

### Running the Application

```bash
# New way (recommended)
python waf_app.py

# Old way (still works)
python waf.py
```

### Project Structure
```
app/
├── __init__.py          # Flask app factory
├── config.py            # Configuration
├── routes.py            # Endpoints
├── detector.py          # ML models
├── attack_classifier.py # Pattern detection
├── request_utils.py     # Request parsing
├── statistics.py        # Stats tracking
└── templates/           # HTML files
```

---

## 📝 Common Tasks

### Adding a New Endpoint

**File**: `app/routes.py`

```python
@app.route('/your-endpoint', methods=['GET', 'POST'])
def your_endpoint() -> Any:
    """Your endpoint description"""
    # Your logic here
    return jsonify({'status': 'ok'})
```

### Adding a New Configuration

**File**: `app/config.py`

```python
class Config:
    # Add your configuration
    NEW_SETTING = os.environ.get('NEW_SETTING', 'default_value')
```

### Adding a New Attack Pattern

**File**: `app/attack_classifier.py`

```python
class AttackClassifier:
    # Add to XSS_PATTERNS or SQLI_PATTERNS
    NEW_PATTERNS = [
        r'your_pattern_here',
    ]
```

### Adding a New Template

**File**: `app/templates/your_template.html`

```html
<!doctype html>
<html>
<head><title>Your Page</title></head>
<body>
    <h1>{{ your_variable }}</h1>
</body>
</html>
```

**Usage in routes.py**:
```python
return render_template('your_template.html', your_variable='value')
```

---

## 🔍 Finding Things

### Where is the configuration?
→ `app/config.py`

### Where are the ML models loaded?
→ `app/detector.py` → `load_artifacts()`

### Where are the endpoints defined?
→ `app/routes.py`

### Where is attack type detection?
→ `app/attack_classifier.py` → `detect_attack_type()`

### Where is request parsing?
→ `app/request_utils.py` → `get_text_from_request()`

### Where are statistics tracked?
→ `app/statistics.py`

### Where are the HTML templates?
→ `app/templates/`

---

## 🧪 Testing

### Manual Testing

```bash
# Start server
python waf_app.py

# In another terminal:

# Test benign request
curl "http://localhost:5000/predict?param=hello"

# Test SQLi
curl "http://localhost:5000/predict?param='; DROP TABLE--"

# Test XSS
curl "http://localhost:5000/predict?param=<script>alert(1)</script>"

# Check dashboard
open http://localhost:5000/
```

### Unit Testing (Future)

```python
# tests/test_attack_classifier.py
from app.attack_classifier import AttackClassifier

def test_xss_detection():
    result = AttackClassifier.detect_attack_type('<script>alert(1)</script>')
    assert result == 'xss'

def test_sqli_detection():
    result = AttackClassifier.detect_attack_type("'; DROP TABLE--")
    assert result == 'sqli'
```

---

## 🐛 Debugging

### Enable Debug Mode

```bash
export WAF_DEBUG="true"
python waf_app.py
```

### Check Logs

All modules use Python logging:
```python
import logging
logger = logging.getLogger('waf_api')
logger.info('Your message')
logger.error('Error message')
```

### Common Issues

**Models not loading?**
→ Check paths in `app/config.py`
→ Verify `models/` and `models_xss/` directories exist

**Import errors?**
→ Ensure you're in the `new-WAF/` directory
→ Check Python path includes current directory

**Template not found?**
→ Verify template exists in `app/templates/`
→ Check filename spelling

---

## 📦 Module API Reference

### `app/config.py`

```python
Config.SQLI_MODEL_DIR      # SQL Injection model directory
Config.XSS_MODEL_DIR       # XSS model directory
Config.THRESHOLD           # Detection threshold (0.0 - 1.0)
Config.HOST                # Server host
Config.PORT                # Server port
```

### `app/detector.py`

```python
detector = ModelDetector()

detector.load_artifacts()                    # Load all models
detector.predict(text, attack_type)          # Make prediction
detector.is_sqli_loaded()                    # Check SQLi model status
detector.is_xss_loaded()                     # Check XSS model status
detector.get_model_and_vectorizer(type)      # Get model pair
```

### `app/attack_classifier.py`

```python
AttackClassifier.detect_attack_type(text)    # Returns 'sqli', 'xss', or 'unknown'
```

### `app/request_utils.py`

```python
RequestUtils.get_text_from_request(req)      # Extract parameter from request
RequestUtils.client_wants_html(req)          # Check if client wants HTML
```

### `app/statistics.py`

```python
stats = Statistics()

stats.record_prediction(text, score, pred, type)  # Record a prediction
stats.get_recent_predictions(limit=20)            # Get recent predictions
stats.get_stats()                                 # Get all statistics
```

---

## 🔧 Environment Variables

```bash
# SQL Injection Model
export WAF_MODEL_DIR="models"
export WAF_MODEL="random_forest.joblib"
export WAF_VECTORIZER="tfidf_vectorizer.joblib"

# XSS Model
export WAF_XSS_MODEL_DIR="models_xss"
export WAF_XSS_MODEL="random_forest_xss.joblib"
export WAF_XSS_VECTORIZER="tfidf_vectorizer_xss.joblib"

# Detection
export WAF_THRESHOLD="0.5"

# Server
export WAF_HOST="0.0.0.0"
export WAF_PORT="5000"
export WAF_DEBUG="false"
```

---

## 📊 API Endpoints

### `GET /` - Dashboard
Shows statistics and recent predictions

**Response**: HTML dashboard page

### `GET /predict?param=<value>` - Prediction (HTML)
Predict if parameter is malicious

**Parameters**:
- `param` (query) - Text to analyze

**Response**: HTML (blocked.html or allowed.html)

### `POST /predict` - Prediction (JSON)
Predict if parameter is malicious

**Request Body** (JSON):
```json
{
  "param": "your text here"
}
```

**Response** (JSON):
```json
{
  "prediction": "malicious",
  "score": 0.97,
  "threshold": 0.5,
  "action": "block",
  "raw_input": "your text here",
  "attack_type": "xss"
}
```

### `POST /reload` - Reload Models
Reload ML models from disk

**Response**: Redirect to dashboard

---

## 💡 Tips & Best Practices

1. **Always use the Config class** for settings
2. **Import from app package** when adding modules
3. **Use type hints** for better IDE support
4. **Log important events** for debugging
5. **Keep modules under 150 lines** when possible
6. **Write docstrings** for public functions
7. **Use templates** instead of inline HTML
8. **Test both HTML and JSON responses** for endpoints

---

## 📚 Further Reading

- `STRUCTURE.md` - Detailed structure overview
- `REFACTORING_SUMMARY.md` - What changed and why
- `ARCHITECTURE.md` - Visual diagrams and flow
- `requirements.txt` - Python dependencies
