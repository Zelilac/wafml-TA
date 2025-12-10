## WAF Blocked Page - Quick Test Guide

### How the WAF Blocked Page Works

When the WAF detects a malicious request, it now displays a professional, user-friendly page instead of a plain JSON error.

---

### Setup

1. **Ensure WAF API is running**:
   ```bash
   cd /Users/macbookair/Documents/SEMESTER-7/ProyekTA/new-WAF
   python waf.py
   # Runs on http://localhost:5000
   ```

2. **Ensure Laravel is running**:
   ```bash
   cd /Users/macbookair/Documents/SEMESTER-7/ProyekTA/evote-2
   php artisan serve
   # Runs on http://localhost:8000
   ```

3. **Ensure `.env` has WAF enabled**:
   ```env
   WAF_ENABLED=true
   WAF_ENDPOINT=http://localhost:5000/predict
   ```

---

### Test 1: View Blocked Page in Browser

**Trigger a malicious request**:
```bash
curl "http://localhost:8000/?search=<script>alert('xss')</script>"
```

Or open in browser:
```
http://localhost:8000/?param='; DROP TABLE users--
```

**Expected Result**:
- ✅ Browser shows professional blocked page (403)
- ✅ Red header with shield icon
- ✅ Request details displayed
- ✅ Return to Home button works
- ✅ Beautiful animations

---

### Test 2: API Requests Return JSON

**Send JSON request**:
```bash
\curl -X POST http://localhost:8000/api/test \
     -H "Content-Type: application/json" \
     -d '{"param": "malicious_payload"}'
```

**Expected Result**:
- ✅ Returns JSON error (403)
- ✅ No HTML page (API clients expect JSON)
- ✅ Error and message fields populated

---

### Test 3: Normal Requests Pass Through

**Benign request**:
```bash
curl "http://localhost:8000/"
```

**Expected Result**:
- ✅ Request goes through WAF successfully
- ✅ Normal page loads (no block)
- ✅ User sees homepage

---

### Blocked Page Sections

#### 1. Header (Red Gradient)
- Shield icon that shakes
- "Request Blocked" title
- "Security Protection Active" subtitle

#### 2. Alert Section
- Explains what happened
- Friendly, non-technical language

#### 3. Reason Box (Yellow)
- Why the request was blocked
- Mentions AI-powered detection

#### 4. Details Grid
- Timestamp
- IP Address
- Request Method (GET/POST/PUT/DELETE)
- Request Path
- User Agent (truncated)
- Referer (if available)

#### 5. Recommendations (Green Box)
- Review request parameters
- Avoid special characters
- Contact support if needed

#### 6. Footer
- Return to Home button
- Contact Support button (email link)
- WAF-AI branding

---

### Customization Options

**Edit Support Email**:
Open `/evote-2/resources/views/waf/blocked.blade.php`

Find line with Contact Support button:
```blade
<a href="mailto:admin@example.com" class="btn-contact">
```

Change `admin@example.com` to your support email.

**Edit App Name**:
The page automatically uses your app name from `.env`:
```env
APP_NAME="E-Vote System"
```

---

### Files Overview

| File | Purpose |
|------|---------|
| `WAFBlockedController.php` | Handles blocked page display |
| `waf/blocked.blade.php` | HTML blocked page template |
| `layouts/waf.blade.php` | Reusable WAF layout |
| `WAFMiddleware.php` | Updated to return view on block |
| `web.php` | Routes for WAF pages |

---

### Troubleshooting

**Q: Blocked page doesn't show, only see JSON**
- A: Check if request is detected as API (has `Content-Type: application/json`)

**Q: Styling looks broken (no colors)**
- A: CDNs (Bootstrap, FontAwesome) need internet access

**Q: Can't see request details**
- A: Verify middleware is returning the `$details` array correctly

**Q: Getting 500 error instead of blocked page**
- A: Check Laravel logs: `storage/logs/laravel.log`

---

### Response Examples

#### Browser Request (HTML Response)
```
GET http://localhost:8000/?param=malicious
↓
HTTP/1.1 403 Forbidden
Content-Type: text/html

<!DOCTYPE html>
<html>
<head>
    <title>Request Blocked - E-Vote</title>
    ...
</head>
<body>
    <div class="blocked-container">
        <!-- Beautiful blocked page -->
    </div>
</body>
</html>
```

#### API Request (JSON Response)
```
POST http://localhost:8000/api/test
Content-Type: application/json

{
    "error": "Request blocked by WAF",
    "message": "Your request was detected as potentially malicious..."
}

HTTP/1.1 403 Forbidden
Content-Type: application/json
```

---

### What Gets Logged

Every blocked request is logged with:
- ✅ URL
- ✅ HTTP Method
- ✅ Client IP
- ✅ Timestamp
- ✅ WAF score/prediction

Check logs:
```bash
tail -f /Users/macbookair/Documents/SEMESTER-7/ProyekTA/evote-2/storage/logs/laravel.log
```

Look for:
```
[YYYY-MM-DD HH:MM:SS] local.WARNING: WAF: Malicious request detected {"url":"...",
```

---

### Status: ✅ COMPLETE

WAF blocked page is fully implemented and ready for production use!
