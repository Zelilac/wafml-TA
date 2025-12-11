# Migration Checklist: Old waf.py → New Modular Structure

## ✅ Pre-Migration Checklist

- [ ] Backup current `waf.py` file
- [ ] Note any custom modifications to `waf.py`
- [ ] Document current environment variables
- [ ] Check current Python version (3.8+ recommended)
- [ ] Verify all ML models are present in `models/` and `models_xss/`

---

## 🔄 Migration Options

### Option A: No Migration Needed (Recommended for Most Users)

**If you're happy with current setup:**

✅ **Keep using `python waf.py`** - It now uses the new structure automatically!

**What changed:**
- Old `waf.py` redirects to new modular structure
- All functionality identical
- Same environment variables
- Same API responses

**Action required:** None!

---

### Option B: Gradual Migration (For Active Development)

**If you want to adopt new structure gradually:**

#### Week 1: Testing
- [ ] Test current setup: `python waf.py` (should work as before)
- [ ] Test new entry point: `python waf_app.py` (should work identically)
- [ ] Verify both show "LEGACY ENTRY POINT" message for `waf.py`
- [ ] Compare responses from both entry points
- [ ] Check dashboard at `http://localhost:5000/`

#### Week 2: Development
- [ ] Read `STRUCTURE.md` for new architecture
- [ ] Read `DEVELOPER_GUIDE.md` for development tasks
- [ ] Familiarize yourself with new module layout
- [ ] Start using `python waf_app.py` in development

#### Week 3: Documentation
- [ ] Update internal documentation
- [ ] Update deployment scripts to use `waf_app.py`
- [ ] Update monitoring/alerts if needed
- [ ] Update team runbooks

#### Week 4: Production
- [ ] Switch production to `python waf_app.py`
- [ ] Update CI/CD pipelines
- [ ] Update Docker files (if applicable)
- [ ] Monitor for any issues

---

### Option C: Full Migration (For New Features)

**If you want to fully adopt the new structure and add features:**

#### 1. Understand New Structure
- [ ] Review `ARCHITECTURE.md` for visual diagrams
- [ ] Review `REFACTORING_SUMMARY.md` for detailed changes
- [ ] Understand module responsibilities

#### 2. Migrate Custom Code
If you modified the old `waf.py`:

**Custom configuration?**
→ Add to `app/config.py`

**Custom ML logic?**
→ Extend `app/detector.py`

**Custom attack patterns?**
→ Add to `app/attack_classifier.py`

**Custom endpoints?**
→ Add to `app/routes.py`

**Custom HTML?**
→ Add to `app/templates/`

**Custom statistics?**
→ Extend `app/statistics.py`

#### 3. Testing Custom Changes
- [ ] Unit test new modules (if tests exist)
- [ ] Integration test full workflow
- [ ] Load test (if applicable)
- [ ] Security test (if applicable)

#### 4. Deployment
- [ ] Update start scripts
- [ ] Update systemd/supervisor configs
- [ ] Update Docker Compose (if applicable)
- [ ] Update reverse proxy configs (if applicable)

---

## 🔍 Verification Steps

After migration, verify everything works:

### 1. Server Starts
```bash
python waf_app.py
# Should see:
# Loading ML models...
# Starting WAF API on 0.0.0.0:5000
```

### 2. Dashboard Works
```bash
open http://localhost:5000/
# Should show statistics and recent predictions
```

### 3. Predictions Work
```bash
# Benign request
curl "http://localhost:5000/predict?param=hello"
# Should return 200 OK

# Malicious request
curl "http://localhost:5000/predict?param='; DROP TABLE--"
# Should return 403 Forbidden
```

### 4. JSON API Works
```bash
curl -X POST http://localhost:5000/predict \
  -H "Content-Type: application/json" \
  -d '{"param": "test"}'
# Should return JSON response
```

### 5. Model Reload Works
```bash
curl -X POST http://localhost:5000/reload
# Should reload models and redirect to dashboard
```

---

## 🐛 Troubleshooting

### Issue: "ModuleNotFoundError: No module named 'app'"
**Solution**: Ensure you're running from the `new-WAF/` directory
```bash
cd /path/to/new-WAF
python waf_app.py
```

### Issue: "Models not loaded"
**Solution**: Check model paths
```bash
ls -la models/
ls -la models_xss/
# Should see .joblib files
```

### Issue: "Template not found"
**Solution**: Verify template directory structure
```bash
ls -la app/templates/
# Should see: dashboard.html, blocked.html, allowed.html, error.html
```

### Issue: Old `waf.py` doesn't work
**Solution**: Check if it's using new structure
```bash
python waf.py
# Should see: "LEGACY ENTRY POINT - Using new modular structure"
```

### Issue: Different behavior between waf.py and waf_app.py
**Solution**: They should be identical. Check:
- Same environment variables
- Same model files
- Same Python version
- Compare logs side-by-side

---

## 📋 Rollback Plan

If you need to rollback to the original monolithic `waf.py`:

1. **Restore from backup**
   ```bash
   cp waf.py.backup waf.py
   ```

2. **Restart service**
   ```bash
   python waf.py
   ```

3. **Verify functionality**
   Test all endpoints to ensure they work

---

## 📊 Migration Success Criteria

✅ Server starts without errors  
✅ Dashboard displays correctly  
✅ Benign requests are allowed  
✅ Malicious requests are blocked  
✅ JSON API returns correct responses  
✅ Statistics are tracked correctly  
✅ Model reload works  
✅ No performance degradation  
✅ All custom features work  
✅ Logs are clear and helpful  

---

## 🎓 Training Resources

For team members who need to understand the new structure:

1. **Quick Start** (5 minutes)
   - Read `STRUCTURE.md` overview section
   - Run `python waf_app.py`
   - Browse dashboard

2. **Development** (30 minutes)
   - Read `DEVELOPER_GUIDE.md`
   - Try adding a simple endpoint
   - Review module structure

3. **Deep Dive** (2 hours)
   - Read `ARCHITECTURE.md`
   - Read `REFACTORING_SUMMARY.md`
   - Trace a request through all modules
   - Review each module's code

---

## 📞 Support

If you encounter issues during migration:

1. Check troubleshooting section above
2. Review error logs carefully
3. Compare with `ARCHITECTURE.md` diagrams
4. Review `DEVELOPER_GUIDE.md` for common tasks

---

## ✨ Benefits After Migration

Once migrated, you'll have:

- ✅ **Cleaner codebase** - Easy to understand and maintain
- ✅ **Better testability** - Each module can be tested independently
- ✅ **Faster development** - Clear structure for new features
- ✅ **Easier debugging** - Isolated components
- ✅ **Better documentation** - Self-documenting code
- ✅ **Team friendly** - Easy onboarding for new developers

---

## 📝 Post-Migration Tasks

After successful migration:

- [ ] Update team documentation
- [ ] Update deployment runbooks
- [ ] Add to knowledge base
- [ ] Schedule training session for team
- [ ] Archive old `waf.py` backup
- [ ] Celebrate! 🎉
