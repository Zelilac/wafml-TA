# WAF + evote-2 Integration - Quick Reference

## 🚀 Start Services

### Development (3 terminals)

```bash
# Terminal 1: WAF
cd new-WAF && source venv/bin/activate && python waf.py

# Terminal 2: evote-2
cd evote-2 && php artisan serve

# Terminal 3: Tests
bash test-integration.sh
```

### Docker (Single command)

```bash
docker-compose up -d
```

## 🔗 URLs

| Service | URL |
|---------|-----|
| evote-2 | http://localhost:8000 |
| WAF API | http://localhost:5000 |
| WAF Dashboard | http://localhost:5000/ |
| phpMyAdmin | http://localhost:8080 |

## 🔧 Quick Commands

```bash
# Setup (one-time)
bash setup-complete.sh

# Test integration
bash test-integration.sh

# Run migrations
cd evote-2 && php artisan migrate

# Generate key
cd evote-2 && php artisan key:generate

# Clear cache
cd evote-2 && php artisan cache:clear

# View logs
tail -f evote-2/storage/logs/laravel.log

# Check WAF status
curl http://localhost:5000/

# Check evote-2 status
curl http://localhost:8000/api/waf/status

# Reload WAF models
curl -X POST http://localhost:5000/reload
```

## 📁 Key Files

### evote-2
- **Middleware**: `app/Http/Middleware/WAFMiddleware.php`
- **Service**: `app/Services/WAFService.php`
- **Controller**: `app/Http/Controllers/WAFExampleController.php`
- **Routes**: `routes/web.php`
- **Config**: `.env`

### new-WAF
- **API**: `waf.py`
- **Models**: `models/`
- **Training**: `test.ipynb`
- **Requirements**: `requirements.txt`

## 🧪 Test Attacks

```bash
# Benign (should PASS)
curl "http://localhost:8000/dashboard?name=John"

# SQL Injection via URL path (should BLOCK)
curl "http://localhost:8000/'; DROP TABLE--"

# SQL Injection via query param (should BLOCK)
curl "http://localhost:8000/dashboard?payload='; DROP TABLE--"

# XSS (should BLOCK)
curl "http://localhost:8000/dashboard?payload=<script>alert(1)</script>"

# Direct attack in path (should BLOCK)
curl "http://localhost:8000/<script>alert(1)</script>"
```

## ⚙️ Configuration (.env)

```env
WAF_ENABLED=true
WAF_ENDPOINT=http://localhost:5000/predict
WAF_THRESHOLD=0.5
```

## 📊 Metrics

| Metric | Value |
|--------|-------|
| Response Time | 10-50ms |
| Accuracy | ~95% |
| False Positive Rate | <2% |
| Throughput | 50-100 req/s |

## 🐛 Common Issues

| Issue | Solution |
|-------|----------|
| Connection refused | Check if services running (ports 5000, 8000) |
| ModuleNotFoundError | `cd new-WAF && pip install -r requirements.txt` |
| Database error | Check MySQL running & `.env` configured |
| 403 Forbidden | WAF blocked request (see logs for details) |
| 500 Error | Check `storage/logs/laravel.log` |

## 📚 Documentation

- Full guide: `INTEGRATION_GUIDE.md`
- Deployment: `DEPLOYMENT_GUIDE.md`
- README: `README.md`

## 🔐 Security Tips

- [ ] Enable HTTPS in production
- [ ] Use strong database passwords
- [ ] Keep dependencies updated
- [ ] Monitor WAF logs regularly
- [ ] Backup database daily
- [ ] Use environment variables for secrets
- [ ] Enable PHP-FPM security settings
- [ ] Configure firewall rules

## 💡 Advanced Usage

### Manual WAF Check in Controller

```php
use App\Services\WAFService;

class MyController extends Controller {
    public function __construct(WAFService $waf) {
        $this->waf = $waf;
    }
    
    public function check(Request $request) {
        $result = $this->waf->check($request->input('data'));
        
        if ($result['is_malicious']) {
            return response('Blocked', 403);
        }
    }
}
```

### Batch Check

```php
$results = $this->waf->checkBatch([
    'name' => $request->input('name'),
    'email' => $request->input('email'),
]);
```

### WAF Status

```php
if ($this->waf->isHealthy()) {
    // WAF is operational
}
```

## 📞 Debugging

```bash
# Enable debug mode
export APP_DEBUG=true

# Watch logs in real-time
tail -f evote-2/storage/logs/laravel.log

# Test WAF directly
curl -X POST http://localhost:5000/predict \
  -H "Content-Type: application/json" \
  -d '{"param": "test input"}'

# Check request headers
curl -v http://localhost:8000/

# Profile response time
time curl http://localhost:8000/
```

## 📋 Deployment

```bash
# Build Docker images
docker-compose build

# Start in production
docker-compose up -d

# Scale WAF workers
# Edit docker-compose.yml or use:
docker-compose up -d --scale waf=2

# View logs
docker-compose logs -f waf
docker-compose logs -f evote

# Stop services
docker-compose down
```

---

**💾 Save this file**: Pin it to your workspace for quick reference!
