#!/bin/bash

# WAF Testing - Dual Model (SQL Injection & XSS)
# Start WAF first: cd new-WAF && python3 waf.py

echo "═══════════════════════════════════════════════════════════════"
echo "WAF API Testing - SQL Injection & XSS Detection"
echo "═══════════════════════════════════════════════════════════════"
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Test function
test_payload() {
    local name=$1
    local payload=$2
    local expected=$3
    
    echo -e "${YELLOW}Testing: $name${NC}"
    echo "Payload: $payload"
    
    response=$(curl -s "http://localhost:5000/predict?param=$(echo -n "$payload" | jq -sRr @uri)")
    
    echo "Response:"
    echo "$response" | jq '.' 2>/dev/null || echo "$response"
    echo ""
}

# SQL Injection Tests
echo -e "${YELLOW}════════════════════════════════════════${NC}"
echo -e "${YELLOW}SQL INJECTION TESTS${NC}"
echo -e "${YELLOW}════════════════════════════════════════${NC}"
echo ""

test_payload "SQL: OR 1=1" "1' OR '1'='1" "block"
test_payload "SQL: UNION SELECT" "1 UNION SELECT * FROM users" "block"
test_payload "SQL: DROP TABLE" "'; DROP TABLE users;--" "block"
test_payload "SQL: Comment injection" "1 OR 1=1 -- comment" "block"

# XSS Tests
echo -e "${YELLOW}════════════════════════════════════════${NC}"
echo -e "${YELLOW}XSS TESTS${NC}"
echo -e "${YELLOW}════════════════════════════════════════${NC}"
echo ""

test_payload "XSS: Script tag" "<script>alert('xss')</script>" "block"
test_payload "XSS: Event handler" "<img src=x onerror=alert('xss')>" "block"
test_payload "XSS: JavaScript protocol" "javascript:alert('xss')" "block"
test_payload "XSS: SVG injection" "<svg onload=alert('xss')>" "block"

# Benign Tests
echo -e "${YELLOW}════════════════════════════════════════${NC}"
echo -e "${YELLOW}BENIGN REQUESTS (Should Allow)${NC}"
echo -e "${YELLOW}════════════════════════════════════════${NC}"
echo ""

test_payload "Benign: Hello World" "hello world" "allow"
test_payload "Benign: User email" "john@example.com" "allow"
test_payload "Benign: Search query" "how to learn python" "allow"
test_payload "Benign: Number" "12345" "allow"

# Evote-2 Integration Tests
echo -e "${YELLOW}════════════════════════════════════════${NC}"
echo -e "${YELLOW}EVOTE-2 INTEGRATION TESTS${NC}"
echo -e "${YELLOW}════════════════════════════════════════${NC}"
echo ""

echo -e "${YELLOW}Testing through Laravel middleware:${NC}"
echo ""

test_waf_integration() {
    local name=$1
    local route=$2
    local payload=$3
    
    echo -e "${YELLOW}Route Test: $name${NC}"
    echo "URL: http://127.0.0.1:8000$route"
    echo "Payload: $payload"
    
    response=$(curl -s -w "\nHTTP: %{http_code}" "http://127.0.0.1:8000$route")
    http_code=$(echo "$response" | tail -1 | cut -d' ' -f2)
    body=$(echo "$response" | head -n -1)
    
    if [[ $http_code == "403" ]]; then
        echo -e "${GREEN}✓ BLOCKED (HTTP 403)${NC}"
    else
        echo -e "${RED}✗ ALLOWED (HTTP $http_code)${NC}"
    fi
    echo ""
}

# Make sure Laravel app is running
if curl -s "http://127.0.0.1:8000/" > /dev/null 2>&1; then
    test_waf_integration "SQLi in route param" "/master-hima/get-constraint/1' OR '1'='1" "SQL Injection"
    test_waf_integration "XSS in route param" "/master-hima/get-constraint/%3Cscript%3Ealert(1)%3C/script%3E" "XSS"
else
    echo -e "${RED}Laravel app not running on http://127.0.0.1:8000/${NC}"
fi

echo ""
echo "═══════════════════════════════════════════════════════════════"
echo "Dashboard: http://localhost:5000/"
echo "═══════════════════════════════════════════════════════════════"
