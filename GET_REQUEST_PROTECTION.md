# WAF GET Request Protection Enhancement

## Overview
The WAF middleware has been enhanced to provide **comprehensive protection for all GET requests**, ensuring that:
- URL paths are always checked
- Query parameters are always analyzed
- All request data types are scanned

---

## Changes Made

### 1. Enhanced `checkWithWAF()` Method

**Location**: `/evote-2/app/Http/Middleware/WAFMiddleware.php` (lines 68-80)

```php
protected function checkWithWAF(Request $request): bool
{
    $payload = $this->extractPayload($request);

    // For GET requests, always check the path even if query params are empty
    if ($request->isMethod('GET') && empty($payload)) {
        $pathInfo = $request->getPathInfo();
        if (!empty($pathInfo)) {
            $payload = $pathInfo;
        }
    }

    if (empty($payload)) {
        return true; // Allow empty requests
    }
    // ... rest of method
}
```

**Purpose**: Ensures GET requests are ALWAYS evaluated by the WAF, even if they have no query parameters.

---

### 2. Enhanced `extractPayload()` Method

**Location**: `/evote-2/app/Http/Middleware/WAFMiddleware.php` (lines 106-156)

**Key Improvements**:

```php
protected function extractPayload(Request $request): string
{
    $checks = [];

    // ✅ ALWAYS check URL path first - catches path-based attacks
    // Examples: /admin/hack, /files/../../etc/passwd, /'; DROP TABLE--
    $pathInfo = $request->getPathInfo();
    if (!empty($pathInfo)) {
        $checks[] = $pathInfo;
    }

    // ✅ For GET requests, ALWAYS check query string if present
    if ($request->isMethod('GET')) {
        $queryString = $request->getQueryString();
        if (!empty($queryString)) {
            $checks[] = $queryString;
        }
    }

    // ✅ Check all query parameters (GET parameters)
    // ✅ Check form data (POST, PUT, PATCH)
    // ✅ Check JSON payload
    // ✅ Check request headers
    // ... rest of method
}
```

---

## GET Request Protection Coverage

### ✅ Simple GET to Root
```bash
GET / HTTP/1.1
```
- Path "/" is checked
- Empty query string is handled gracefully

### ✅ GET with Query Parameters
```bash
GET /?search=term&filter=value HTTP/1.1
```
- Query string: `search=term&filter=value` → checked
- Query parameters: `search` and `filter` → each checked
- Path "/" → checked

### ✅ GET with URL Path
```bash
GET /admin/users/123 HTTP/1.1
```
- Path: `/admin/users/123` → checked
- No query string → path-only protection

### ✅ GET with Complex Payloads
```bash
GET /search?q=<script>alert('xss')</script>&page=1 HTTP/1.1
```
- Path, query string, and individual parameters all checked

### ✅ GET with Suspicious Headers
```bash
GET / HTTP/1.1
User-Agent: <?php system($_GET['cmd']); ?>
```
- User-Agent header → checked
- All suspicious headers → checked

---

## Test Coverage

New test methods added to `WAFIntegrationTest.php`:

1. **`test_root_path_is_checked_by_waf()`**
   - Verifies root path "/" is protected

2. **`test_get_request_without_params_is_checked()`**
   - Verifies simple GET requests are scanned

3. **`test_get_request_with_malicious_path_is_blocked()`**
   - Verifies URL path attacks are detected

4. **`test_get_query_parameters_are_checked_by_waf()`**
   - Verifies single query parameter protection

5. **`test_get_multiple_query_params_are_checked()`**
   - Verifies multiple query parameter protection

---

## Protection Matrix

| Request Type | Path | Query Params | Headers | Status |
|---|---|---|---|---|
| GET / | ✅ | - | ✅ | Protected |
| GET /?param=value | ✅ | ✅ | ✅ | Protected |
| GET /path | ✅ | - | ✅ | Protected |
| GET /path?q=x&y=z | ✅ | ✅ | ✅ | Protected |
| POST /login | ✅ | ✅ | ✅ | Protected |
| POST with JSON | ✅ | ✅ | ✅ | Protected |
| PUT /api/users | ✅ | ✅ | ✅ | Protected |
| DELETE /api/resource | ✅ | ✅ | ✅ | Protected |

---

## Security Features

### ✅ Path-Based Attack Detection
Detects path traversal and injection attacks:
- `/'; DROP TABLE users--`
- `/../../etc/passwd`
- `/admin/backdoor.php`

### ✅ Query Parameter Scanning
Protects against query-based injections:
- SQL injection: `?id=1' OR '1'='1`
- XSS: `?search=<script>alert('xss')</script>`
- Command injection: `?cmd=rm -rf /`

### ✅ Header-Based Attack Detection
Scans suspicious headers:
- User-Agent with PHP code
- Referer with malicious payloads
- X-Forwarded-For with injection attempts

### ✅ Fail-Safe Design
- If WAF is unavailable → request is allowed (fail-open)
- Can be configured to fail-closed if preferred
- Gracefully handles timeout scenarios

---

## Performance Considerations

- **Timeout**: 5 seconds per WAF API call
- **Payload**: Concatenates all data with spaces
- **No Caching**: Each request is evaluated independently
- **Non-Blocking**: Failed WAF checks don't break the application

---

## Configuration

### Environment Variables
```env
WAF_ENABLED=true                              # Enable WAF globally
WAF_ENDPOINT=http://localhost:5000/predict   # WAF API endpoint
```

### Skipped Routes
Routes exempt from WAF checks (in `shouldSkip()` method):
- `/health` - Health check endpoint
- `/waf-status` - WAF status endpoint
- `/api/waf/status` - WAF API status
- `/reload` - Model reload endpoint

---

## Testing GET Request Protection

### Run Tests
```bash
cd /Users/macbookair/Documents/SEMESTER-7/ProyekTA/evote-2
php artisan test tests/Feature/WAFIntegrationTest.php
```

### Manual Testing

**1. Test Root Path**
```bash
curl http://localhost:8000/
```

**2. Test with Query Parameters**
```bash
curl "http://localhost:8000/?search=benign"
curl "http://localhost:8000/?name=john&email=john@example.com"
```

**3. Test with Suspicious Headers**
```bash
curl -H "User-Agent: malicious-bot" http://localhost:8000/
```

**4. Monitor WAF Dashboard**
```
http://localhost:5000/
```

---

## Status: ✅ COMPLETE

All GET requests are now fully protected by the WAF, including:
- ✅ Root path "/" requests
- ✅ Requests with query parameters
- ✅ Requests without any data
- ✅ Requests with suspicious headers
- ✅ Complex multi-parameter requests

The middleware is production-ready for comprehensive security coverage.
