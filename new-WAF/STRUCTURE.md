# WAF Application Structure

## 📁 New Modular Structure

```
new-WAF/
├── waf_app.py              # Main entry point (new)
├── waf.py                  # Legacy entry point (backward compatible)
├── app/                    # Application package
│   ├── __init__.py         # Flask app factory
│   ├── config.py           # Configuration settings
│   ├── routes.py           # Flask routes/endpoints
│   ├── detector.py         # ML model loader and predictor
│   ├── attack_classifier.py # Attack type detection
│   ├── request_utils.py    # Request parsing utilities
│   ├── statistics.py       # Statistics tracking
│   └── templates/          # HTML templates
│       ├── dashboard.html  # Main dashboard
│       ├── blocked.html    # Blocked request page
│       ├── allowed.html    # Allowed request page
│       └── error.html      # Error page
├── models/                 # SQL Injection models
├── models_xss/             # XSS models
└── data/                   # Training data

```

## 🚀 Running the Application

### New Way (Recommended)
```bash
python waf_app.py
```

### Old Way (Still Works)
```bash
python waf.py
```

Both entry points work identically!

## 📦 Module Descriptions

### `app/config.py`
- Centralized configuration
- Environment variable handling
- Path management

### `app/detector.py`
- ML model loading
- Prediction logic
- Model selection (SQLi vs XSS)

### `app/attack_classifier.py`
- Pattern-based attack type detection
- Regex patterns for SQLi and XSS

### `app/request_utils.py`
- Request parameter extraction
- Content-type detection
- Client preference detection

### `app/statistics.py`
- Request counting
- Recent predictions tracking
- Statistics reporting

### `app/routes.py`
- `/predict` - Main prediction endpoint
- `/` - Dashboard
- `/reload` - Model reloading

### `app/templates/`
- Separated HTML templates
- Bootstrap-styled UI
- Clean, maintainable markup

## 🔧 Configuration

All configuration is done via environment variables:

```bash
# SQL Injection Model
export WAF_MODEL_DIR="models"
export WAF_MODEL="random_forest.joblib"
export WAF_VECTORIZER="tfidf_vectorizer.joblib"

# XSS Model
export WAF_XSS_MODEL_DIR="models_xss"
export WAF_XSS_MODEL="random_forest_xss.joblib"
export WAF_XSS_VECTORIZER="tfidf_vectorizer_xss.joblib"

# Server
export WAF_HOST="0.0.0.0"
export WAF_PORT="5000"
export WAF_THRESHOLD="0.5"
```

## 🧪 Testing

```bash
# Start the server
python waf_app.py

# Test benign request
curl "http://localhost:5000/predict?param=hello"

# Test SQL injection
curl "http://localhost:5000/predict?param='; DROP TABLE--"

# Test XSS
curl "http://localhost:5000/predict?param=<script>alert(1)</script>"
```

## 📈 Benefits of New Structure

✅ **Modularity** - Each component has a single responsibility  
✅ **Testability** - Easy to unit test individual modules  
✅ **Maintainability** - Clear separation of concerns  
✅ **Scalability** - Easy to add new features  
✅ **Readability** - Clean, organized codebase  
✅ **Backward Compatible** - Old `waf.py` still works  

## 🔄 Migration Guide

If you're using the old `waf.py`:

1. **No immediate changes needed** - `waf.py` still works
2. **Gradual migration** - Start using `waf_app.py` when ready
3. **Environment stays the same** - Same env variables work
4. **API unchanged** - All endpoints work identically

## 📝 Development

To add a new feature:

1. Add configuration to `app/config.py`
2. Add business logic to appropriate module
3. Add route to `app/routes.py`
4. Add template if needed to `app/templates/`
5. Update this README

## 🐛 Debugging

Enable debug mode:
```bash
export WAF_DEBUG="true"
python waf_app.py
```

Check logs:
- All components use Python's logging module
- Logs go to stdout
- Format: `[timestamp] LEVEL - message`
