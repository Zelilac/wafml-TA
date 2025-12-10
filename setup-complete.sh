#!/bin/bash

# Complete setup script for WAF + evote-2 integration
# This script installs all dependencies and prepares both projects

set -e

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
EVOTE_DIR="$SCRIPT_DIR/evote-2"
WAF_DIR="$SCRIPT_DIR/new-WAF"

echo ""
echo "╔══════════════════════════════════════════════════════════╗"
echo "║     WAF + evote-2 Integration - Complete Setup          ║"
echo "╚══════════════════════════════════════════════════════════╝"
echo ""

# Check directories
if [ ! -d "$EVOTE_DIR" ]; then
    echo "❌ Error: evote-2 directory not found"
    exit 1
fi

if [ ! -d "$WAF_DIR" ]; then
    echo "❌ Error: new-WAF directory not found"
    exit 1
fi

echo "📁 Found projects:"
echo "   • evote-2:  $EVOTE_DIR"
echo "   • new-WAF:  $WAF_DIR"
echo ""

# ============================================================================
# STEP 1: Setup new-WAF (Python)
# ============================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "STEP 1: Setting up new-WAF (Python)"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

cd "$WAF_DIR"

# Create venv if needed
if [ ! -d "venv" ]; then
    echo "📦 Creating Python virtual environment..."
    python3 -m venv venv
    echo "✓ Virtual environment created"
else
    echo "✓ Virtual environment already exists"
fi

# Activate venv
echo "🔌 Activating virtual environment..."
source venv/bin/activate

# Install requirements
if [ -f "requirements.txt" ]; then
    echo "📥 Installing Python dependencies from requirements.txt..."
    pip install -q -r requirements.txt
    echo "✓ Dependencies installed"
else
    echo "⚠️  No requirements.txt found. Installing essential packages..."
    pip install -q flask flask-cors joblib scikit-learn pandas numpy tqdm
    echo "✓ Essential packages installed"
fi

# Check for models
if [ ! -f "models/random_forest.joblib" ] && [ ! -f "models/decision_tree.joblib" ]; then
    echo "⚠️  WARNING: No trained model found in models/ directory"
    echo "   Please run: jupyter notebook test.ipynb (to train models)"
    echo "   Or copy existing models to models/ directory"
else
    echo "✓ ML models found"
fi

echo ""

# ============================================================================
# STEP 2: Setup evote-2 (Laravel)
# ============================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "STEP 2: Setting up evote-2 (Laravel)"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

cd "$EVOTE_DIR"

# Check composer.json
if [ -f "composer.json" ]; then
    echo "✓ composer.json found"
    
    # Check if guzzlehttp/guzzle is installed
    if grep -q "guzzlehttp/guzzle" composer.json; then
        echo "✓ guzzlehttp/guzzle already in composer.json"
    else
        echo "📦 Adding guzzlehttp/guzzle to composer.json..."
        # This would require composer command; just note it
        echo "   Please run: composer require guzzlehttp/guzzle"
    fi
else
    echo "❌ Error: composer.json not found in evote-2"
    exit 1
fi

# Check vendor directory
if [ -d "vendor" ]; then
    echo "✓ vendor directory exists"
else
    echo "📥 Running 'composer install'..."
    echo "   Note: This may take a few minutes..."
    composer install -q 2>/dev/null || echo "⚠️  Composer install had issues; continue setup"
fi

# Check .env file
if [ -f ".env" ]; then
    echo "✓ .env file exists"
    
    if grep -q "WAF_ENDPOINT" .env; then
        echo "✓ WAF configuration already in .env"
    else
        echo "🔧 Adding WAF configuration to .env..."
        echo "" >> .env
        echo "# WAF Configuration (Web Application Firewall)" >> .env
        echo "WAF_ENABLED=true" >> .env
        echo "WAF_ENDPOINT=http://localhost:5000/predict" >> .env
        echo "✓ WAF configuration added"
    fi
else
    echo "📝 Creating .env file from .env.example..."
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo "" >> .env
        echo "# WAF Configuration (Web Application Firewall)" >> .env
        echo "WAF_ENABLED=true" >> .env
        echo "WAF_ENDPOINT=http://localhost:5000/predict" >> .env
        echo "✓ .env file created with WAF config"
    else
        echo "⚠️  .env.example not found; create .env manually"
    fi
fi

# Generate app key if needed
if ! grep -q "APP_KEY=base64:" .env || grep -q "APP_KEY=$" .env; then
    echo "🔑 Generating Laravel application key..."
    php artisan key:generate --force
    echo "✓ Application key generated"
else
    echo "✓ Application key already set"
fi

echo ""

# ============================================================================
# STEP 3: Verify Integration Files
# ============================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "STEP 3: Verifying Integration Files"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

FILES_OK=true

# Check middleware
if [ -f "app/Http/Middleware/WAFMiddleware.php" ]; then
    echo "✓ WAFMiddleware.php"
else
    echo "❌ WAFMiddleware.php not found"
    FILES_OK=false
fi

# Check service
if [ -f "app/Services/WAFService.php" ]; then
    echo "✓ WAFService.php"
else
    echo "❌ WAFService.php not found"
    FILES_OK=false
fi

# Check controller
if [ -f "app/Http/Controllers/WAFExampleController.php" ]; then
    echo "✓ WAFExampleController.php"
else
    echo "❌ WAFExampleController.php not found"
    FILES_OK=false
fi

echo ""

if [ "$FILES_OK" = false ]; then
    echo "⚠️  Some integration files are missing!"
fi

# ============================================================================
# STEP 4: Display Next Steps
# ============================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "✅ Setup Complete!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

echo "📚 QUICK START:"
echo ""
echo "Terminal 1 - Start WAF Service:"
echo "  cd $WAF_DIR"
echo "  source venv/bin/activate"
echo "  python waf.py"
echo ""
echo "Terminal 2 - Start Laravel:"
echo "  cd $EVOTE_DIR"
echo "  php artisan serve"
echo ""
echo "Terminal 3 - (Optional) Run Tests:"
echo "  curl 'http://localhost:8000/dashboard?name=test'"
echo ""

echo "🔗 Access Points:"
echo "  • evote-2:  http://localhost:8000"
echo "  • WAF API:  http://localhost:5000"
echo "  • WAF Dashboard: http://localhost:5000/"
echo ""

echo "📖 Documentation:"
echo "  See: INTEGRATION_GUIDE.md"
echo ""

echo "🧪 Test WAF Protection:"
echo ""
echo "  Benign request (should pass):"
echo "    curl 'http://localhost:8000/dashboard?name=John'"
echo ""
echo "  Malicious request (should be blocked):"
echo "    curl 'http://localhost:8000/dashboard?payload='; DROP TABLE users--'"
echo ""

echo "⚙️  Environment Variables:"
echo "  WAF_ENABLED=true                 (enable/disable WAF)"
echo "  WAF_ENDPOINT=http://localhost:5000/predict"
echo ""

echo "📝 Logs:"
echo "  • Laravel logs: $EVOTE_DIR/storage/logs/laravel.log"
echo "  • WAF logs: Console output from WAF terminal"
echo ""

echo "═══════════════════════════════════════════════════════════════════════"
echo ""
