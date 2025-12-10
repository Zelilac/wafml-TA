# WAF XSS Implementation - Dual-Model Support

## Summary of Changes

The WAF (new-WAF/waf.py) has been updated to support dual-model detection for both **SQL Injection** and **XSS** attacks, following the same implementation pattern as SQL Injection detection.

## Key Changes Made

### 1. **Model Configuration** (Updated from single to dual-model)
- **SQL Injection Models** (models/):
  - `random_forest.joblib` - SQL Injection classifier
  - `tfidf_vectorizer.joblib` - SQL Injection vectorizer

- **XSS Models** (models_xss/):
  - `random_forest_xss.joblib` - XSS classifier
  - `tfidf_vectorizer_xss.joblib` - XSS vectorizer

### 2. **Attack Type Detection Function**
Added `detect_attack_type(text: str) -> str` function that:
- Analyzes input for XSS patterns: `<script>`, `javascript:`, `onerror=`, `onclick=`, etc.
- Analyzes input for SQL Injection patterns: `OR`, `UNION`, `SELECT`, `DROP`, `--`, etc.
- Returns: `'xss'`, `'sqli'`, or `'unknown'`

### 3. **Model Selection Logic**
Updated `/predict` endpoint to:
1. Detect attack type from incoming payload
2. Select appropriate model:
   - XSS model for XSS attacks
   - SQL Injection model for SQL injection/unknown attacks
3. Use corresponding vectorizer for transformation
4. Return prediction with `attack_type` field

### 4. **Response Format Enhanced**
Result now includes attack type:
```json
{
  "prediction": "malicious|benign",
  "score": 0.85,
  "threshold": 0.5,
  "action": "block|allow",
  "raw_input": "payload",
  "attack_type": "xss|sqli|unknown"
}
```

### 5. **Dashboard Updates**
- Title: "WAF-AI — Dashboard (Dual-Model: SQL Injection + XSS)"
- Shows status of both models (Loaded/Not Loaded)
- Displays separate attack type counts:
  - SQL Injection detected count
  - XSS detected count
- Recent predictions table includes attack type column

### 6. **Logging & Statistics**
New global counters:
- `SQLI_DETECTED` - Count of SQL Injection attacks
- `XSS_DETECTED` - Count of XSS attacks
- Recent predictions track attack type for each detection

## How It Works

1. **Request Arrives**: Laravel middleware extracts all parameters and sends to WAF API
2. **Attack Detection**: WAF analyzes pattern to determine attack type
3. **Model Selection**: Appropriate model loaded based on detected type
4. **Classification**: Model predicts malicious/benign with confidence score
5. **Response**: Returns action (block/allow) with attack type
6. **Blocking**: Laravel middleware returns HTTP 403 with blocked page

## Integration with evote-2

The existing Laravel middleware (`WAFMiddleware.php`) handles both response formats:
- Old format: `is_malicious` field
- New format: `action` and `prediction` fields

The middleware **doesn't need changes** - it already blocks requests based on the response.

## Testing

Run the test script:
```bash
cd /Users/macbookair/Documents/SEMESTER-7/ProyekTA/new-WAF
chmod +x test_dual_model.sh
./test_dual_model.sh
```

Or start WAF manually:
```bash
python3 waf.py
# Access dashboard at: http://localhost:5000/
```

## Benefits

✅ **Specialized Detection**: Each attack type uses its own trained model
✅ **Better Accuracy**: XSS and SQLi patterns require different feature extraction
✅ **Transparent Logging**: Know which attack type triggered the block
✅ **Easy Monitoring**: Dashboard shows separate counts for each attack type
✅ **Same Integration**: Works with existing evote-2 middleware (no changes needed)

## Next Steps (Optional)

1. Train separate models for other attack types (CSRF, Path Traversal, etc.)
2. Add more detailed attack classification in dashboard
3. Implement per-attack-type configuration (different thresholds)
4. Add explainability features showing which patterns triggered detection
