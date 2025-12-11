#!/usr/bin/env python3
"""
LEGACY ENTRY POINT - For backward compatibility

This file now imports and runs the new modular WAF application.
The new structure is located in the 'app/' directory.

For the new recommended entry point, use: waf_app.py
For documentation on the new structure, see: STRUCTURE.md

This legacy entry point will continue to work identically to before.
"""

import sys
import logging

# Import from new modular structure
from app import create_app
from app.config import Config
from app.detector import detector

if __name__ == '__main__':
    logger = logging.getLogger('waf_api')
    logger.info('=' * 60)
    logger.info('LEGACY ENTRY POINT - Using new modular structure')
    logger.info('For new entry point, use: python waf_app.py')
    logger.info('=' * 60)
    
    # Create app using new structure
    app = create_app()
    
    # Load models
    logger.info('Loading ML models...')
    detector.load_artifacts()
    
    # Start server
    logger.info(f'Starting WAF API on {Config.HOST}:{Config.PORT}')
    logger.info(f'  SQL Injection model: {Config.SQLI_MODEL_PATH}')
    logger.info(f'  XSS model: {Config.XSS_MODEL_PATH}')
    logger.info(f'  Threshold: {Config.THRESHOLD}')
    
    app.run(host=Config.HOST, port=Config.PORT, debug=Config.DEBUG)

