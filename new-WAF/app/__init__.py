"""WAF Application Package"""
from flask import Flask
import logging
import sys

def create_app():
    """Application factory for WAF"""
    app = Flask(__name__, template_folder='templates')
    
    # Configure logging
    logging.basicConfig(
        stream=sys.stdout,
        level=logging.INFO,
        format='[%(asctime)s] %(levelname)s - %(message)s'
    )
    
    # Register routes
    from app.routes import register_routes
    register_routes(app)
    
    return app
