#!/bin/bash

# Test WAF + evote-2 Integration
# Tests various attack vectors and benign requests

set -e

EVOTE_URL="http://localhost:8000"
WAF_URL="http://localhost:5000"

echo ""
echo "╔══════════════════════════════════════════════════════════╗"
echo "║       WAF + evote-2 Integration - Test Suite            ║"
echo "╚══════════════════════════════════════════════════════════╝"
echo ""

# Check if services are running
echo "🔍 Checking if services are running..."
echo ""

if ! curl -s "$WAF_URL/" > /dev/null; then
    echo "❌ WAF Service is not running at $WAF_URL"
    echo "   Start it with: python waf.py (in new-WAF directory)"
    exit 1
fi
echo "✓ WAF API is running"

if ! curl -s "$EVOTE_URL/dashboard" > /dev/null 2>&1; then
    echo "❌ evote-2 is not running at $EVOTE_URL"
    echo "   Start it with: php artisan serve (in evote-2 directory)"
    exit 1
fi
echo "✓ evote-2 is running"
echo ""

# ============================================================================
# TEST 1: Benign Requests
# ============================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "TEST 1: Benign Requests (should PASS)"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

test_benign() {
    local name="$1"
    local param="$2"
    
    response=$(curl -s -w "\n%{http_code}" "$EVOTE_URL/dashboard?$param" 2>/dev/null | tail -1)
    
    if [ "$response" = "200" ] || [ "$response" = "302" ]; then
        echo "✓ $name (HTTP $response)"
        return 0
    else
        echo "✗ $name (HTTP $response) - Expected 200 or 302"
        return 1
    fi
}

test_benign "Simple name" "name=John%20Doe"
test_benign "Username" "username=admin123"
test_benign "Email" "email=test@example.com"
test_benign "Search query" "q=voting%20results"
test_benign "Numeric ID" "id=123456"
test_benign "Special chars (safe)" "msg=Hello%2C%20World%21"

echo ""

# ============================================================================
# TEST 2: SQL Injection Attempts
# ============================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "TEST 2: SQL Injection Attempts (should be BLOCKED)"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

test_sqli() {
    local name="$1"
    local param="$2"
    
    # URL encode the payload
    encoded=$(python3 -c "import urllib.parse; print(urllib.parse.quote('$param'))")
    response=$(curl -s -w "\n%{http_code}" "$EVOTE_URL/dashboard?payload=$encoded" 2>/dev/null | tail -1)
    
    if [ "$response" = "403" ]; then
        echo "✓ $name - BLOCKED (HTTP 403)"
        return 0
    else
        echo "⚠ $name (HTTP $response) - May not be detected"
        return 0
    fi
}

test_sqli "Classic DROP TABLE" "'; DROP TABLE users; --"
test_sqli "UNION based" "' UNION SELECT * FROM users --"
test_sqli "Time-based blind" "' OR SLEEP(5) --"
test_sqli "Stacked queries" "'; DELETE FROM users; --"
test_sqli "Comment bypass" "admin' #"

echo ""

# ============================================================================
# TEST 3: XSS Attempts
# ============================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "TEST 3: XSS (Cross-Site Scripting) Attempts (should be BLOCKED)"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

test_xss() {
    local name="$1"
    local param="$2"
    
    encoded=$(python3 -c "import urllib.parse; print(urllib.parse.quote('$param'))")
    response=$(curl -s -w "\n%{http_code}" "$EVOTE_URL/dashboard?payload=$encoded" 2>/dev/null | tail -1)
    
    if [ "$response" = "403" ]; then
        echo "✓ $name - BLOCKED (HTTP 403)"
        return 0
    else
        echo "⚠ $name (HTTP $response) - May not be detected"
        return 0
    fi
}

test_xss "Script tag" "<script>alert('XSS')</script>"
test_xss "Event handler" "<img src=x onerror=alert('XSS')>"
test_xss "SVG vector" "<svg onload=alert('XSS')>"
test_xss "JavaScript protocol" "<a href='javascript:alert(1)'>click</a>"
test_xss "HTML entity" "&#60;script&#62;alert('XSS')&#60;/script&#62;"

echo ""

# ============================================================================
# TEST 4: WAF API Direct Tests
# ============================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "TEST 4: Direct WAF API Tests"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

echo "Testing: $WAF_URL/predict"
echo ""

# Benign
echo "→ Benign request:"
curl -s -X POST "$WAF_URL/predict" \
  -H "Content-Type: application/json" \
  -d '{"param": "Hello world"}' | python3 -m json.tool | head -10
echo ""

# Malicious
echo "→ Malicious request (SQL injection):"
curl -s -X POST "$WAF_URL/predict" \
  -H "Content-Type: application/json" \
  -d '{"param": "'\'''; DROP TABLE users; --"}' | python3 -m json.tool | head -10
echo ""

# ============================================================================
# TEST 5: WAF Status
# ============================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "TEST 5: WAF Service Status"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

echo "WAF Dashboard: $WAF_URL/"
echo "WAF API Health: $WAF_URL/predict (POST)"
echo ""

# Get status from evote-2 API
echo "evote-2 WAF Status Endpoint: $EVOTE_URL/api/waf/status"
status=$(curl -s "$EVOTE_URL/api/waf/status" 2>/dev/null)
echo "$status" | python3 -m json.tool 2>/dev/null || echo "$status"
echo ""

# ============================================================================
# Summary
# ============================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "✅ Test Suite Complete!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

echo "📊 Results Summary:"
echo "  • If malicious requests were blocked (HTTP 403), WAF is working ✓"
echo "  • If benign requests passed (HTTP 200/302), detection is working ✓"
echo "  • If both conditions are met, integration is successful! ✓"
echo ""

echo "📖 Next Steps:"
echo "  1. Check Laravel logs: tail storage/logs/laravel.log"
echo "  2. View WAF dashboard: $WAF_URL/"
echo "  3. Check evote-2 status: $EVOTE_URL/api/waf/status"
echo ""
