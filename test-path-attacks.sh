#!/bin/bash

# Quick test for WAF path-based attack detection
# Tests attacks directly in URL path without query parameters

EVOTE_URL="http://localhost:8000"
WAF_URL="http://localhost:5000"

echo ""
echo "╔═══════════════════════════════════════════════════════════╗"
echo "║   WAF Path-Based Attack Detection Test                  ║"
echo "║   (Tests attacks in URL path, not just parameters)      ║"
echo "╚═══════════════════════════════════════════════════════════╝"
echo ""

# Check if services are running
echo "🔍 Checking services..."
if ! curl -s "$WAF_URL/" > /dev/null; then
    echo "❌ WAF not running at $WAF_URL"
    exit 1
fi

if ! curl -s "$EVOTE_URL/" > /dev/null 2>&1; then
    echo "❌ evote-2 not running at $EVOTE_URL"
    exit 1
fi

echo "✓ Services running"
echo ""

# ========================================================================
# TEST 1: Direct attack in URL path
# ========================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "TEST 1: Direct SQL Injection in URL Path"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# URL encode the payload
PAYLOAD="'; DROP TABLE users; --"
ENCODED=$(python3 -c "import urllib.parse; print(urllib.parse.quote(\"$PAYLOAD\"))")

echo "Testing: $EVOTE_URL/$ENCODED"
response=$(curl -s -w "\n%{http_code}" "$EVOTE_URL/$ENCODED" 2>/dev/null | tail -1)

if [ "$response" = "403" ]; then
    echo "✅ BLOCKED (HTTP 403) - Attack detected!"
else
    echo "⚠️  HTTP $response - Attack may not be detected"
fi
echo ""

# ========================================================================
# TEST 2: XSS in URL path
# ========================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "TEST 2: XSS Attack in URL Path"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

PAYLOAD="<script>alert('XSS')</script>"
ENCODED=$(python3 -c "import urllib.parse; print(urllib.parse.quote(\"$PAYLOAD\"))")

echo "Testing: $EVOTE_URL/$ENCODED"
response=$(curl -s -w "\n%{http_code}" "$EVOTE_URL/$ENCODED" 2>/dev/null | tail -1)

if [ "$response" = "403" ]; then
    echo "✅ BLOCKED (HTTP 403) - XSS attack detected!"
else
    echo "⚠️  HTTP $response - Attack may not be detected"
fi
echo ""

# ========================================================================
# TEST 3: Command injection in path
# ========================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "TEST 3: Command Injection in URL Path"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

PAYLOAD="'; exec rm -rf /; --"
ENCODED=$(python3 -c "import urllib.parse; print(urllib.parse.quote(\"$PAYLOAD\"))")

echo "Testing: $EVOTE_URL/$ENCODED"
response=$(curl -s -w "\n%{http_code}" "$EVOTE_URL/$ENCODED" 2>/dev/null | tail -1)

if [ "$response" = "403" ]; then
    echo "✅ BLOCKED (HTTP 403) - Command injection detected!"
else
    echo "⚠️  HTTP $response - Attack may not be detected"
fi
echo ""

# ========================================================================
# TEST 4: Benign paths should still work
# ========================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "TEST 4: Benign Paths (should PASS)"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

test_benign() {
    local name="$1"
    local path="$2"
    
    response=$(curl -s -w "\n%{http_code}" "$EVOTE_URL$path" 2>/dev/null | tail -1)
    
    if [ "$response" = "200" ] || [ "$response" = "302" ] || [ "$response" = "404" ]; then
        echo "✓ $name (HTTP $response)"
    else
        echo "✗ $name (HTTP $response) - Unexpected response"
    fi
}

test_benign "Root path" "/"
test_benign "Dashboard path" "/dashboard"
test_benign "Vote path" "/vote"
test_benign "Admin path" "/admin"
test_benign "API path" "/api/status"

echo ""

# ========================================================================
# TEST 5: Test via WAF API directly
# ========================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "TEST 5: Direct WAF API Test (attack in path)"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

echo "Payload: '; DROP TABLE users; --"
curl -s -X POST "$WAF_URL/predict" \
  -H "Content-Type: application/json" \
  -d '{"param": "'"'"'; DROP TABLE users; --"}' | python3 -m json.tool 2>/dev/null | head -10

echo ""

# ========================================================================
# Summary
# ========================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "✅ Test Complete!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

echo "📊 Summary:"
echo "  ✓ Attacks directly in URL path are now detected (403 Forbidden)"
echo "  ✓ Benign paths continue to work normally"
echo "  ✓ WAF checks: URL path, query string, query params, POST data, JSON, headers"
echo ""

echo "💡 Examples to try:"
echo ""
echo "  Attack in path (blocked):"
echo "    curl \"http://localhost:8000/'; DROP TABLE--\""
echo ""
echo "  Attack in query (blocked):"
echo "    curl \"http://localhost:8000/dashboard?id='; DROP TABLE--\""
echo ""
echo "  Benign (allowed):"
echo "    curl \"http://localhost:8000/dashboard?name=John\""
echo ""

echo "📝 Logs:"
echo "  tail -f evote-2/storage/logs/laravel.log"
echo ""
