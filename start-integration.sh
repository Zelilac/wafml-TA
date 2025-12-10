#!/bin/bash

# WAF Integration Quick Start
# Run this to start both services

set -e

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
EVOTE_DIR="$SCRIPT_DIR/evote-2"
WAF_DIR="$SCRIPT_DIR/new-WAF"

echo "🔐 WAF Integration Quick Start"
echo "=============================="
echo ""

# Check if directories exist
if [ ! -d "$EVOTE_DIR" ]; then
    echo "❌ Error: evote-2 directory not found at $EVOTE_DIR"
    exit 1
fi

if [ ! -d "$WAF_DIR" ]; then
    echo "❌ Error: new-WAF directory not found at $WAF_DIR"
    exit 1
fi

echo "✓ Found evote-2 at: $EVOTE_DIR"
echo "✓ Found new-WAF at: $WAF_DIR"
echo ""

# Start WAF service in background
echo "🚀 Starting WAF service..."
cd "$WAF_DIR"

if [ ! -d "venv" ]; then
    echo "   Creating virtual environment..."
    python3 -m venv venv
fi

source venv/bin/activate
echo "   Activating venv..."

if [ ! -f "requirements.txt" ]; then
    echo "   ⚠️  No requirements.txt found. Installing defaults..."
    pip install flask guzzlehttp joblib scikit-learn pandas numpy tqdm -q
else
    pip install -r requirements.txt -q
fi

echo "   Starting Flask API on http://localhost:5000..."
python waf.py &
WAF_PID=$!

# Give WAF time to start
sleep 2

# Check if WAF started successfully
if ! kill -0 $WAF_PID 2>/dev/null; then
    echo "❌ Failed to start WAF service"
    exit 1
fi

echo "✓ WAF service started (PID: $WAF_PID)"
echo ""

# Start evote-2
echo "🚀 Starting evote-2 (Laravel)..."
cd "$EVOTE_DIR"

if [ ! -f ".env" ]; then
    echo "   Copying .env.example to .env..."
    cp .env.example .env 2>/dev/null || true
fi

# Add WAF configuration if not present
if ! grep -q "WAF_ENDPOINT" .env; then
    echo "   Adding WAF configuration to .env..."
    echo "" >> .env
    echo "# WAF Configuration" >> .env
    echo "WAF_ENABLED=true" >> .env
    echo "WAF_ENDPOINT=http://localhost:5000/predict" >> .env
fi

if [ ! -d "vendor" ]; then
    echo "   Installing composer dependencies..."
    composer install -q 2>/dev/null || true
fi

echo "   Starting Laravel server on http://localhost:8000..."
php artisan serve --port=8000 &
LARAVEL_PID=$!

# Give Laravel time to start
sleep 2

if ! kill -0 $LARAVEL_PID 2>/dev/null; then
    kill $WAF_PID 2>/dev/null || true
    echo "❌ Failed to start Laravel"
    exit 1
fi

echo "✓ Laravel server started (PID: $LARAVEL_PID)"
echo ""

echo "=================================================="
echo "✅ Integration Started Successfully!"
echo "=================================================="
echo ""
echo "Services running:"
echo "  • WAF API:    http://localhost:5000"
echo "  • evote-2:    http://localhost:8000"
echo ""
echo "Test WAF:"
echo "  curl 'http://localhost:8000/dashboard?test=normal'"
echo "  curl 'http://localhost:8000/dashboard?test='; DROP TABLE--'"
echo ""
echo "Logs:"
echo "  • WAF:     Check terminal above"
echo "  • Laravel: $EVOTE_DIR/storage/logs/laravel.log"
echo ""
echo "Stop services:"
echo "  kill $WAF_PID $LARAVEL_PID"
echo "  Or press Ctrl+C"
echo ""

# Keep script running
wait
