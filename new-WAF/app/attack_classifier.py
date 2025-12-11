"""Attack type classification based on pattern matching"""
import re

class AttackClassifier:
    """Classifies attack types based on pattern matching"""
    
    # XSS patterns: HTML/JavaScript tags and entities
    XSS_PATTERNS = [
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
    
    # SQL Injection patterns: SQL keywords and syntax
    SQLI_PATTERNS = [
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
    
    @classmethod
    def detect_attack_type(cls, text: str) -> str:
        """Detect the type of attack (SQL Injection or XSS) based on patterns.
        
        Args:
            text: Input text to analyze
            
        Returns:
            'sqli', 'xss', or 'unknown'
        """
        text_lower = text.lower()
        
        # Check for XSS patterns first
        for pattern in cls.XSS_PATTERNS:
            if re.search(pattern, text_lower, re.IGNORECASE):
                return 'xss'
        
        # Check for SQL Injection patterns
        for pattern in cls.SQLI_PATTERNS:
            if re.search(pattern, text_lower, re.IGNORECASE):
                return 'sqli'
        
        return 'unknown'
