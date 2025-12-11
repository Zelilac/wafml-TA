# Configuration settings for WAF application
import os

class Config:
    """WAF Configuration"""
    
    # SQL Injection Detection Configuration
    SQLI_MODEL_DIR = os.environ.get('WAF_MODEL_DIR', 'models')
    SQLI_MODEL = os.environ.get('WAF_MODEL', 'random_forest.joblib')
    SQLI_VECTORIZER_NAME = os.environ.get('WAF_VECTORIZER', 'tfidf_vectorizer.joblib')
    
    # XSS Detection Configuration
    XSS_MODEL_DIR = os.environ.get('WAF_XSS_MODEL_DIR', 'models_xss')
    XSS_MODEL = os.environ.get('WAF_XSS_MODEL', 'random_forest_xss.joblib')
    XSS_VECTORIZER_NAME = os.environ.get('WAF_XSS_VECTORIZER', 'tfidf_vectorizer_xss.joblib')
    
    # Detection Threshold
    THRESHOLD = float(os.environ.get('WAF_THRESHOLD', '0.5'))
    
    # Server Configuration
    HOST = os.environ.get('WAF_HOST', '0.0.0.0')
    PORT = int(os.environ.get('WAF_PORT', '5000'))
    DEBUG = os.environ.get('WAF_DEBUG', 'False').lower() == 'true'
    
    # Paths
    SQLI_MODEL_PATH = os.path.join(SQLI_MODEL_DIR, SQLI_MODEL)
    SQLI_VECTORIZER_PATH = os.path.join(SQLI_MODEL_DIR, SQLI_VECTORIZER_NAME)
    XSS_MODEL_PATH = os.path.join(XSS_MODEL_DIR, XSS_MODEL)
    XSS_VECTORIZER_PATH = os.path.join(XSS_MODEL_DIR, XSS_VECTORIZER_NAME)
    
    # Statistics
    MAX_RECENT_PREDICTIONS = 50
