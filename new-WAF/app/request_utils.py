"""Request utilities for extracting and validating input"""
from flask import Request
from typing import Optional


class RequestUtils:
    """Utilities for handling Flask requests"""
    
    @staticmethod
    def get_text_from_request(req: Request) -> str:
        """Extract a single text string to classify from the incoming request.

        Priority order:
          1. JSON body with key 'param'
          2. JSON body with any string value (first found)
          3. Form data key 'param'
          4. Form data any key (first found)
          5. Query string 'param'
          6. Query string any key (first found)
          
        Args:
            req: Flask request object
            
        Returns:
            Extracted text string or empty string
        """
        # JSON
        if req.is_json:
            try:
                data = req.get_json(force=True)
                if isinstance(data, dict):
                    if 'param' in data:
                        return str(data['param'])
                    # take first string value
                    for v in data.values():
                        if isinstance(v, (str, int, float)):
                            return str(v)
            except Exception:
                pass

        # Form data
        if req.form:
            if 'param' in req.form:
                return req.form.get('param')
            # first value
            for k in req.form.keys():
                return req.form.get(k)

        # Query params (GET)
        args = req.args
        if args:
            if 'param' in args:
                return args.get('param')
            for k in args.keys():
                return args.get(k)

        return ''
    
    @staticmethod
    def client_wants_html(req: Request) -> bool:
        """Heuristic: return True if client prefers HTML (browser) over JSON.
        Uses Accept header preference and also treats simple GET requests as browser tests.
        
        Args:
            req: Flask request object
            
        Returns:
            True if HTML response is preferred, False otherwise
        """
        # If explicit ?format=json param provided, prefer JSON
        if req.args.get('format') == 'json':
            return False
        best = req.accept_mimetypes.best_match(['application/json', 'text/html'])
        if best == 'text/html':
            return True
        # treat GET with no JSON body as likely browser/test form
        if req.method == 'GET':
            return True
        return False
