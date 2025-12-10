# WAF Blocked Page Implementation

## Overview
A professional, user-friendly blocked page has been added to display when the WAF blocks a malicious request. The page provides security information and incident details while maintaining a good user experience.

---

## Files Created

### 1. Controller
**File**: `/evote-2/app/Http/Controllers/WAFBlockedController.php`

Handles the display of the blocked page with request details.

```php
public function show(Request $request)
{
    // Captures request details for display
    return view('waf.blocked', compact('details'));
}
```

### 2. View
**File**: `/evote-2/resources/views/waf/blocked.blade.php`

Beautiful, responsive HTML page displayed when a request is blocked by WAF.

**Features**:
- ✅ Professional red warning design
- ✅ Request details display (timestamp, IP, method, path)
- ✅ Animated alert icon
- ✅ Recommendations for users
- ✅ Responsive mobile design
- ✅ Contact support link
- ✅ Return to home button

### 3. Layout
**File**: `/evote-2/resources/views/layouts/waf.blade.php`

Reusable layout for WAF-related pages.

---

## Files Modified

### 1. Middleware
**File**: `/evote-2/app/Http/Middleware/WAFMiddleware.php`

Enhanced to return the blocked view instead of JSON for browser requests:

```php
// Return HTML blocked page for better UX, or JSON for API requests
if ($request->expectsJson() || $request->isJson()) {
    return response()->json([...], 403);
}

return response()->view('waf.blocked', [...], 403);
```

### 2. Routes
**File**: `/evote-2/routes/web.php`

Added controller import and route:

```php
use App\Http\Controllers\WAFBlockedController;

Route::get('/waf/blocked', [WAFBlockedController::class, 'show'])->name('waf.blocked');
```

---

## Blocked Page Features

### 🎨 Design Elements

1. **Header Section**
   - Shield icon with animation
   - Title: "Request Blocked"
   - Subtitle: "Security Protection Active"
   - Gradient red background

2. **Alert Section**
   - Explains what happened
   - Friendly security message

3. **Reason Box**
   - Yellow warning box
   - Explains why request was blocked
   - Mentions AI-powered detection

4. **Details Grid**
   - Timestamp of blocking
   - User's IP address
   - HTTP method (GET, POST, etc.)
   - Request path
   - User Agent
   - Referer

5. **Recommendations Box**
   - Green box with suggestions
   - Tips for preventing blocks:
     - Avoid special characters
     - Review request parameters
     - Contact support if needed

6. **Footer Section**
   - Return to Home button
   - Contact Support button
   - WAF-AI branding

---

## Response Behavior

### For Browser Requests
**Content-Type**: `text/html`
**Status Code**: `403`
**Response**: Formatted HTML page with details

```
GET /?id=<script>alert('xss')</script>
↓
WAF Detects Malicious Pattern
↓
Returns Beautiful HTML Blocked Page (403)
```

### For API Requests
**Content-Type**: `application/json`
**Status Code**: `403`
**Response**: JSON error response

```json
{
    "error": "Request blocked by WAF",
    "message": "Your request was detected as potentially malicious and has been blocked."
}
```

**Detection Method**:
- `$request->expectsJson()` - Checks Accept header
- `$request->isJson()` - Checks Content-Type
- Falls back to HTML for ambiguous requests

---

## Request Details Displayed

| Field | Source | Purpose |
|-------|--------|---------|
| Timestamp | `now()` | When block occurred |
| IP Address | `$request->ip()` | User's IP for logging |
| Method | `$request->method()` | GET, POST, PUT, DELETE |
| Path | `$request->getPathInfo()` | URL path that was blocked |
| User Agent | `$request->header('User-Agent')` | Browser/client info |
| Referer | `$request->header('Referer')` | Previous page (if any) |

---

## Styling Highlights

### Colors
- **Header**: Red gradient `#dc3545` → `#c82333`
- **Buttons**: Purple gradient `#667eea` → `#764ba2`
- **Alert**: Red `#dc3545`
- **Reason**: Yellow `#ffc107`
- **Recommendation**: Green `#28a745`
- **Background**: Purple gradient

### Animations
- **Slide Down**: Page slides in smoothly
- **Shake**: Shield icon shakes for emphasis
- **Hover**: Buttons have lift effect on hover

### Responsive
- ✅ Mobile-friendly (small screens)
- ✅ Tablet-friendly (medium screens)
- ✅ Desktop-optimized (large screens)
- ✅ Details grid converts to single column on mobile

---

## Configuration

### Environment
No additional environment variables required.

### Customization

**Edit email in Contact Support button**:
```blade
<a href="mailto:admin@example.com" class="btn-contact">
```

**Customize app name**:
```blade
{{ config('app.name', 'E-Vote System') }}
```

---

## Usage Flow

### 1. Request Received
```
User submits GET /?param='; DROP TABLE users--
↓
WAFMiddleware intercepts
```

### 2. WAF Evaluation
```
Extracts payload: "/ '; DROP TABLE users--"
↓
Sends to WAF API for analysis
↓
Model classifies as MALICIOUS (score > 0.5)
```

### 3. Response Decision
```
checkWithWAF() returns false
↓
Check if API/Browser request
  - JSON/API → return JSON 403
  - Browser → return HTML 403
```

### 4. Blocked Page Display
```
User sees professional blocked page
↓
Shows request details
↓
Provides recommendations
↓
Offers support contact link
```

---

## Testing

### Test 1: Block a Request
```bash
curl "http://localhost:8000/?search=<script>alert('xss')</script>"
```

**Result**: 403 HTML blocked page displays in browser

### Test 2: API Request
```bash
curl -H "Content-Type: application/json" \
     -X POST http://localhost:8000/api/test \
     -d '{"param": "malicious"}'
```

**Result**: 403 JSON error response

### Test 3: Browser Test
Open in browser: `http://localhost:8000/?param='; DROP TABLE--`

**Result**: Beautiful blocked page displays

---

## Security Considerations

✅ **Safe Display**: Details are truncated to avoid information disclosure
✅ **No Sensitive Data**: Password fields not logged
✅ **Rate Limiting Ready**: Can integrate with throttling middleware
✅ **Logging**: All blocks are logged with full details
✅ **User-Friendly**: Doesn't expose WAF internals

---

## Troubleshooting

### Page doesn't display
1. Check view file exists: `/resources/views/waf/blocked.blade.php`
2. Verify controller is imported in routes
3. Check middleware is registered in Kernel.php

### Styling looks broken
1. Verify Bootstrap and FontAwesome CDNs are accessible
2. Check browser cache (Ctrl+Shift+Delete)
3. Ensure no other CSS conflicts

### Details not showing
1. Check `$details` array is passed from middleware
2. Verify Blade syntax is correct `{{ $variable }}`
3. Check `Str::limit()` helper is available (Laravel 8+)

---

## Status: ✅ COMPLETE

The WAF blocked page system is fully implemented and production-ready. Users now see a professional, informative page when their requests are blocked by the security system.

### Summary of Changes
- ✅ Created WAFBlockedController
- ✅ Created professional blocked page view
- ✅ Updated middleware to return HTML/JSON based on request type
- ✅ Added route for blocked page
- ✅ Fully responsive and animated
- ✅ Detailed request information display
- ✅ Support contact integration
