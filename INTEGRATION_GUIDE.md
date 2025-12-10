# WAF Integration Guide: evote-2 + new-WAF

## Architecture

The integration uses a **microservice approach**:
- **new-WAF** runs as a standalone Flask API service on port 5000
- **evote-2** (Laravel) makes HTTP calls to WAF API for request validation
- WAF validates parameters against trained ML models (Random Forest or Decision Tree)

## Setup

### 1. Start new-WAF Service

```bash
cd /Users/macbookair/Documents/SEMESTER-7/ProyekTA/new-WAF

# Activate venv
source venv/bin/activate

# Run the WAF API (by default on localhost:5000)
python waf.py
```

Expected output:
```
 * Running on http://127.0.0.1:5000
 * Press CTRL+C to quit
```

### 2. Configure evote-2

#### Step 2.1: Add environment variables to `.env`

```bash
cd /Users/macbookair/Documents/SEMESTER-7/ProyekTA/evote-2
```

Edit `.env` file and add:
```env
WAF_ENABLED=true
WAF_ENDPOINT=http://localhost:5000/predict
```

#### Step 2.2: Register Middleware in Kernel

Edit `app/Http/Kernel.php`:

Find the `$middleware` array and add WAFMiddleware:

```php
protected $middleware = [
    // ... existing middleware ...
    \App\Http\Middleware\WAFMiddleware::class,
];
```

Or add to specific route groups in `$middlewareGroups`:

```php
'api' => [
    // ... existing middleware ...
    \App\Http\Middleware\WAFMiddleware::class,
],
```

#### Step 2.3: Install Guzzle (HTTP client)

```bash
composer require guzzlehttp/guzzle
```

### 3. Run evote-2 with WAF Protection

```bash
cd /Users/macbookair/Documents/SEMESTER-7/ProyekTA/evote-2

# Using Laravel's built-in server
php artisan serve

# OR using the included server.php
php server.php
```

## Testing

### Test 1: Benign Request

```bash
curl "http://localhost:8000/master-presiden-bem?name=John%20Doe"
```
Expected: Request goes through ✓

### Test 2: Malicious Request (SQL Injection)

```bash
curl "http://localhost:8000/master-presiden-bem?name='; DROP TABLE users; --"
```
Expected: Request blocked with 403 Forbidden ✗

### Test 3: Check WAF Status

```bash
curl http://localhost:5000/
```
Returns WAF dashboard HTML

## Configuration Options

### env Variables

| Variable | Default | Description |
|----------|---------|-------------|
| `WAF_ENABLED` | `true` | Enable/disable WAF middleware |
| `WAF_ENDPOINT` | `http://localhost:5000/predict` | WAF API endpoint |
| `WAF_MODEL_DIR` | `models` | Directory for ML models (in new-WAF) |
| `WAF_MODEL` | `random_forest.joblib` | Model filename to use |
| `WAF_THRESHOLD` | `0.5` | Confidence threshold for blocking (0.0-1.0) |

### Customize WAF Behavior

Edit `app/Http/Middleware/WAFMiddleware.php`:

- `shouldSkip()`: Define routes to exclude from WAF
- `extractPayload()`: Control which parameters are checked
- `checkWithWAF()`: Adjust timeout, error handling, response parsing

## Architecture Diagram

```
┌─────────────────────────────┐
│   HTTP Request to evote-2   │
│   /master-presiden-bem      │
└──────────────┬──────────────┘
               │
               ▼
        ┌──────────────┐
        │ WAFMiddleware│
        │ (Laravel)    │
        └──────┬───────┘
               │ Extract params
               │ HTTP POST to WAF API
               ▼
    ┌──────────────────────┐
    │  new-WAF Flask API   │
    │  /predict endpoint   │
    │                      │
    │ TF-IDF Vectorizer    │
    │ + Random Forest Model│
    └──────────┬───────────┘
               │ is_malicious?
               ▼
        ┌──────────────┐
        │ JSON Response│
        │ {is_malicious}
        └──────┬───────┘
               │
      ┌────────┴────────┐
      │                 │
   YES(403)           NO(200)
      │                 │
      ▼                 ▼
   Block Request    Process Request
```

## Troubleshooting

### "Connection refused" error
- Check if new-WAF is running: `curl http://localhost:5000/`
- Verify port 5000 is not in use: `lsof -i :5000`

### WAF not loaded
- Verify middleware is registered in `Kernel.php`
- Check `WAF_ENABLED=true` in `.env`

### Slow requests
- Increase timeout in middleware (default 5s)
- Check WAF service logs: look at new-WAF terminal output

### False positives (benign requests blocked)
- Lower `WAF_THRESHOLD` in `.env` (default 0.5)
- Review blocked requests in Laravel logs: `storage/logs/laravel.log`

## Future Enhancements

- [ ] Add WAF dashboard link in evote-2 admin
- [ ] Log all WAF decisions to database
- [ ] Add whitelist/blacklist rules
- [ ] Support multiple WAF models
- [ ] Add rate limiting based on WAF alerts
- [ ] Create alert system for attack patterns

## File Structure

```
evote-2/
├── app/Http/Middleware/
│   └── WAFMiddleware.php          ← NEW
├── app/Http/Kernel.php            ← MODIFY
├── .env                            ← ADD WAF_* variables
└── ...

new-WAF/
├── waf.py                          ← Flask API service
├── models/
│   ├── random_forest.joblib
│   └── tfidf_vectorizer.joblib
└── ...
```
