"""ML Model loader and detector"""
import logging
import joblib
import numpy as np
from typing import Dict, Any, Optional, Tuple
from app.config import Config

logger = logging.getLogger('waf_api')


class ModelDetector:
    """Handles loading and prediction for ML models"""
    
    def __init__(self):
        self.sqli_model = None
        self.sqli_vectorizer = None
        self.xss_model = None
        self.xss_vectorizer = None
        
    def load_artifacts(self) -> None:
        """Load SQL Injection and XSS models and vectorizers"""
        # Load SQL Injection models
        try:
            logger.info(f'Loading SQL Injection model from: {Config.SQLI_MODEL_PATH}')
            self.sqli_model = joblib.load(Config.SQLI_MODEL_PATH)
        except Exception as e:
            logger.error(f'Failed to load SQL Injection model at {Config.SQLI_MODEL_PATH}: {e}')
            self.sqli_model = None

        try:
            logger.info(f'Loading SQL Injection vectorizer from: {Config.SQLI_VECTORIZER_PATH}')
            self.sqli_vectorizer = joblib.load(Config.SQLI_VECTORIZER_PATH)
        except Exception as e:
            logger.error(f'Failed to load SQL Injection vectorizer at {Config.SQLI_VECTORIZER_PATH}: {e}')
            self.sqli_vectorizer = None
        
        # Load XSS models
        try:
            logger.info(f'Loading XSS model from: {Config.XSS_MODEL_PATH}')
            self.xss_model = joblib.load(Config.XSS_MODEL_PATH)
        except Exception as e:
            logger.error(f'Failed to load XSS model at {Config.XSS_MODEL_PATH}: {e}')
            self.xss_model = None

        try:
            logger.info(f'Loading XSS vectorizer from: {Config.XSS_VECTORIZER_PATH}')
            self.xss_vectorizer = joblib.load(Config.XSS_VECTORIZER_PATH)
        except Exception as e:
            logger.error(f'Failed to load XSS vectorizer at {Config.XSS_VECTORIZER_PATH}: {e}')
            self.xss_vectorizer = None
            
    def get_model_and_vectorizer(self, attack_type: str) -> Tuple[Optional[Any], Optional[Any]]:
        """Get the appropriate model and vectorizer based on attack type
        
        Args:
            attack_type: 'sqli', 'xss', or 'unknown'
            
        Returns:
            Tuple of (model, vectorizer)
        """
        if attack_type == 'xss':
            return self.xss_model, self.xss_vectorizer
        else:  # 'sqli' or 'unknown' - use SQL Injection model as default
            return self.sqli_model, self.sqli_vectorizer
    
    def predict(self, text: str, attack_type: str) -> Dict[str, Any]:
        """Perform prediction on input text
        
        Args:
            text: Cleaned input text
            attack_type: Type of attack detected
            
        Returns:
            Dictionary with prediction results or error
        """
        model, vectorizer = self.get_model_and_vectorizer(attack_type)
        
        if model is None or vectorizer is None:
            return {
                'error': f'{attack_type.upper()} model or vectorizer not loaded',
                'status_code': 500
            }
        
        # Vectorize input
        try:
            vec = vectorizer.transform([text])
        except Exception as e:
            logger.exception(f'Vectorization failed for {attack_type}')
            return {
                'error': 'vectorization failed',
                'detail': str(e),
                'status_code': 500
            }
        
        # Get prediction score
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
            return {
                'error': 'model scoring failed',
                'detail': str(e),
                'status_code': 500
            }
        
        pred_label = int(score >= Config.THRESHOLD)
        
        return {
            'score': score,
            'pred_label': pred_label,
            'prediction': 'malicious' if pred_label == 1 else 'benign',
            'action': 'block' if pred_label == 1 else 'allow',
            'attack_type': attack_type,
            'threshold': Config.THRESHOLD,
        }
    
    def is_sqli_loaded(self) -> bool:
        """Check if SQL Injection model is loaded"""
        return self.sqli_model is not None and self.sqli_vectorizer is not None
    
    def is_xss_loaded(self) -> bool:
        """Check if XSS model is loaded"""
        return self.xss_model is not None and self.xss_vectorizer is not None


# Global detector instance
detector = ModelDetector()
