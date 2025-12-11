"""Statistics tracking for WAF"""
from collections import deque
import time
from typing import Dict, List
from app.config import Config


class Statistics:
    """Track WAF prediction statistics"""
    
    def __init__(self):
        self.total_requests = 0
        self.malicious_count = 0
        self.benign_count = 0
        self.sqli_detected = 0
        self.xss_detected = 0
        self.recent_predictions = deque(maxlen=Config.MAX_RECENT_PREDICTIONS)
    
    def record_prediction(self, text: str, score: float, prediction: str, attack_type: str) -> None:
        """Record a prediction
        
        Args:
            text: Input text
            score: Prediction score
            prediction: 'malicious' or 'benign'
            attack_type: Type of attack detected
        """
        self.total_requests += 1
        
        if prediction == 'malicious':
            self.malicious_count += 1
            if attack_type == 'xss':
                self.xss_detected += 1
            elif attack_type == 'sqli':
                self.sqli_detected += 1
        else:
            self.benign_count += 1
        
        self.recent_predictions.appendleft({
            'time': time.time(),
            'input': text,
            'score': score,
            'prediction': prediction,
            'attack_type': attack_type
        })
    
    def get_recent_predictions(self, limit: int = 20) -> List[Dict]:
        """Get recent predictions formatted for display
        
        Args:
            limit: Maximum number of predictions to return
            
        Returns:
            List of formatted prediction dictionaries
        """
        recent = list(self.recent_predictions)[:limit]
        recent_rows = []
        for r in recent:
            recent_rows.append({
                'ts': time.strftime('%Y-%m-%d %H:%M:%S', time.localtime(r['time'])),
                'input': r['input'],
                'score': f"{r['score']:.4f}",
                'prediction': r['prediction'],
                'attack_type': r.get('attack_type', 'unknown')
            })
        return recent_rows
    
    def get_stats(self) -> Dict:
        """Get all statistics
        
        Returns:
            Dictionary of statistics
        """
        return {
            'total': self.total_requests,
            'malicious': self.malicious_count,
            'benign': self.benign_count,
            'sqli': self.sqli_detected,
            'xss': self.xss_detected
        }


# Global statistics instance
stats = Statistics()
