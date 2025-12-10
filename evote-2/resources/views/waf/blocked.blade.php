<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Blocked - {{ config('app.name', 'E-Vote') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        
        .blocked-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 700px;
            width: 100%;
            overflow: hidden;
            animation: slideDown 0.6s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .blocked-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 50px 30px;
            text-align: center;
            border-bottom: 5px solid #a71d2a;
        }
        
        .blocked-header i {
            font-size: 70px;
            margin-bottom: 20px;
            display: block;
            animation: shake 0.6s;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .blocked-header h1 {
            font-size: 36px;
            font-weight: 700;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .blocked-header p {
            font-size: 15px;
            opacity: 0.95;
            margin-top: 10px;
        }
        
        .blocked-content {
            padding: 40px;
        }
        
        .alert-section {
            background: linear-gradient(135deg, #fff5f5 0%, #ffe6e6 100%);
            border-left: 5px solid #dc3545;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        
        .alert-section h3 {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }
        
        .alert-section h3 i {
            margin-right: 10px;
            color: #dc3545;
        }
        
        .alert-section p {
            color: #666;
            margin: 0;
            line-height: 1.6;
            font-size: 14px;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        @media (max-width: 576px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .detail-item {
            padding: 12px;
            background: white;
            border-radius: 6px;
            border-left: 3px solid #667eea;
        }
        
        .detail-label {
            font-size: 12px;
            font-weight: 700;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-size: 13px;
            color: #333;
            word-break: break-all;
            font-family: 'Courier New', monospace;
        }
        
        .detail-value.method {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            font-family: 'Segoe UI', sans-serif;
        }
        
        .reason-box {
            background: linear-gradient(135deg, #fff8e1 0%, #fffaf0 100%);
            border-left: 4px solid #ffc107;
            padding: 18px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        
        .reason-box p {
            color: #856404;
            margin: 0;
            font-size: 13px;
            line-height: 1.6;
        }
        
        .reason-box strong {
            color: #664d03;
        }
        
        .recommendation-box {
            background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%);
            border-left: 4px solid #28a745;
            padding: 18px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        
        .recommendation-box h5 {
            color: #2e7d32;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .recommendation-box h5 i {
            margin-right: 8px;
        }
        
        .recommendation-box ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .recommendation-box li {
            color: #558b2f;
            font-size: 13px;
            margin-bottom: 6px;
        }
        
        .blocked-footer {
            background: #f8f9fa;
            padding: 30px;
            border-top: 1px solid #e9ecef;
            text-align: center;
        }
        
        .btn-home {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 35px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .btn-contact {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-left: 10px;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .btn-contact:hover {
            background: #667eea;
            color: white;
            text-decoration: none;
        }
        
        .security-badge {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 10px 18px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .security-badge i {
            margin-right: 6px;
        }
        
        .footer-text {
            color: #666;
            font-size: 12px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="blocked-container">
        <!-- Header -->
        <div class="blocked-header">
            <i class="fas fa-shield-alt"></i>
            <h1>Request Blocked</h1>
            <p>Security Protection Active</p>
        </div>
        
        <!-- Content -->
        <div class="blocked-content">
            <!-- Security Badge -->
            <div style="text-align: center;">
                <span class="security-badge">
                    <i class="fas fa-check-circle"></i> WAF-AI Protection
                </span>
            </div>
            
            <!-- Alert Section -->
            <div class="alert-section">
                <h3><i class="fas fa-exclamation-triangle"></i> Blocked by WAF-ML</h3>
                <p>Your request has been blocked by our Web Application Firewall (WAF) with Machine Learning protection. This action was taken to protect the application from potentially malicious or suspicious activity.</p>
            </div>
            
            <!-- Reason Section -->
            <div class="reason-box">
                <p><strong><i class="fas fa-info-circle"></i> Why was this blocked?</strong></p>
                <p style="margin-top: 8px;">The content or pattern in your request matched our security detection rules. This is a protective measure designed by AI to ensure application security and prevent attacks.</p>
            </div>
            
            <!-- Details Section -->
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">Timestamp</div>
                    <div class="detail-value">{{ $details['timestamp'] }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Your IP Address</div>
                    <div class="detail-value">{{ $details['ip_address'] }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Request Method</div>
                    <div class="detail-value method">{{ $details['request_method'] }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Request Path</div>
                    <div class="detail-value">{{ $details['request_path'] }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">User Agent</div>
                    <div class="detail-value">{{ Str::limit($details['user_agent'], 50, '...') }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Referer</div>
                    <div class="detail-value">{{ $details['referer'] !== 'N/A' ? Str::limit($details['referer'], 50, '...') : 'N/A' }}</div>
                </div>
            </div>
            
            <!-- Recommendation Section -->
            <div class="recommendation-box">
                <h5><i class="fas fa-lightbulb"></i> What You Can Do</h5>
                <ul>
                    <li>Review your request parameters for suspicious characters or code</li>
                    <li>Ensure you're using the application as intended</li>
                    <li>If this was a legitimate request, please contact support</li>
                    <li>Avoid special characters or code-like syntax in your input</li>
                </ul>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="blocked-footer">
            <a href="/" class="btn-home">
                <i class="fas fa-home"></i> Return to Home
            </a>
            <a href="mailto:admin@example.com" class="btn-contact">
                <i class="fas fa-envelope"></i> Contact Support
            </a>
            
            <div class="footer-text">
                <p>{{ config('app.name', 'E-Vote System') }} | Security Protection Powered by WAF-AI</p>
            </div>
        </div>
    </div>
</body>
</html>
