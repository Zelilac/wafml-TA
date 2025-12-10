#!/bin/bash

echo "=== WAF Dual-Model Test (SQL Injection + XSS) ==="
echo ""

# Test 1: XSS Payload
echo "1. Testing XSS Detection:"
curl -s -w "\nHTTP: %{http_code}\n" "http://localhost:5000/predict?param=%3Cscript%3Ealert(1)%3C/script%3E" | grep -E "attack_type|prediction|HTTP"
echo ""

# Test 2: SQL Injection Payload
echo "2. Testing SQL Injection Detection:"
curl -s -w "\nHTTP: %{http_code}\n" "http://localhost:5000/predict?param=1%27%20OR%20%271%27%3D%271" | grep -E "attack_type|prediction|HTTP"
echo ""

# Test 3: Benign Request
echo "3. Testing Benign Request:"
curl -s -w "\nHTTP: %{http_code}\n" "http://localhost:5000/predict?param=hello" | grep -E "attack_type|prediction|HTTP"
echo ""

echo "4. Dashboard:"
echo "   Open: http://localhost:5000/"
