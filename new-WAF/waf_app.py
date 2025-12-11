#!/usr/bin/env python3
"""
WAF-AI Application Entry Point

A standalone WAF microservice with dual-model detection for SQL Injection & XSS.

Usage: 
    python waf_app.py

Environment Variables:
    WAF_MODEL_DIR         - Directory for SQL Injection models (default: 'models')
    WAF_MODEL             - SQL Injection model filename (default: 'random_forest.joblib')
    WAF_VECTORIZER        - SQL Injection vectorizer filename (default: 'tfidf_vectorizer.joblib')
    WAF_XSS_MODEL_DIR     - Directory for XSS models (default: 'models_xss')
    WAF_XSS_MODEL         - XSS model filename (default: 'random_forest_xss.joblib')
    WAF_XSS_VECTORIZER    - XSS vectorizer filename (default: 'tfidf_vectorizer_xss.joblib')
    WAF_THRESHOLD         - Detection threshold (default: 0.5)
    WAF_HOST              - Server host (default: '0.0.0.0')
    WAF_PORT              - Server port (default: 5000)
    WAF_DEBUG             - Debug mode (default: False)

Endpoints:
    GET  /                - Dashboard with statistics
    GET  /predict?param=  - Prediction endpoint (HTML response for browsers)
    POST /predict         - Prediction endpoint (JSON/form-data)
    POST /reload          - Reload models from disk
"""

import logging
from app import create_app
from app.config import Config
from app.detector import detector

logger = logging.getLogger('waf_api')


def main():
    """Main entry point for WAF application"""
    # Create Flask app
    app = create_app()
    
    # Load ML models
    logger.info('Loading ML models...')
    detector.load_artifacts()
    
    # Start server
    logger.info(f'Starting WAF API on {Config.HOST}:{Config.PORT}')
    logger.info(f'  SQL Injection model: {Config.SQLI_MODEL_PATH}')
    logger.info(f'  XSS model: {Config.XSS_MODEL_PATH}')
    logger.info(f'  Threshold: {Config.THRESHOLD}')
    
    app.run(host=Config.HOST, port=Config.PORT, debug=Config.DEBUG)


if __name__ == '__main__':
    main()
