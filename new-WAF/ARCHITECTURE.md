# WAF Application Architecture

## System Overview

```
┌─────────────────────────────────────────────────────────────┐
│                     WAF-AI Application                       │
│                 (Dual-Model Attack Detection)                │
└─────────────────────────────────────────────────────────────┘
                              │
                    ┌─────────┴─────────┐
                    │                   │
              ┌─────▼─────┐      ┌─────▼──────┐
              │  waf.py   │      │ waf_app.py │
              │ (Legacy)  │      │   (New)    │
              └─────┬─────┘      └─────┬──────┘
                    │                  │
                    └──────────┬───────┘
                               │
                      ┌────────▼────────┐
                      │  Flask App      │
                      │  (app package)  │
                      └────────┬────────┘
                               │
        ┌──────────────────────┼──────────────────────┐
        │                      │                      │
    ┌───▼───┐           ┌─────▼─────┐         ┌─────▼─────┐
    │Config │           │  Routes   │         │ Templates │
    └───────┘           └─────┬─────┘         └───────────┘
                              │
              ┌───────────────┼───────────────┐
              │               │               │
        ┌─────▼─────┐   ┌────▼────┐   ┌─────▼──────┐
        │ Detector  │   │ Request │   │ Statistics │
        │           │   │  Utils  │   │            │
        └─────┬─────┘   └─────────┘   └────────────┘
              │
        ┌─────▼──────┐
        │  Attack    │
        │ Classifier │
        └────────────┘
```

## Request Flow

```
HTTP Request
    │
    ▼
┌───────────────────┐
│ Flask Router      │
│ (routes.py)       │
└───────┬───────────┘
        │
        ▼
┌───────────────────┐
│ Request Utils     │
│ Extract Param     │
└───────┬───────────┘
        │
        ▼
┌───────────────────┐
│ Attack Classifier │
│ Detect Type       │
│ (SQLi/XSS)        │
└───────┬───────────┘
        │
        ▼
┌───────────────────┐
│ Model Detector    │
│ Load Model        │
│ Vectorize         │
│ Predict           │
└───────┬───────────┘
        │
        ▼
┌───────────────────┐
│ Statistics        │
│ Record Result     │
└───────┬───────────┘
        │
        ▼
┌───────────────────┐
│ Response          │
│ HTML or JSON      │
└───────────────────┘
```

## Module Dependencies

```
waf_app.py
    │
    ├─> app/__init__.py
    │       │
    │       └─> app/routes.py
    │               │
    │               ├─> app/detector.py
    │               │       │
    │               │       ├─> app/config.py
    │               │       └─> app/attack_classifier.py
    │               │
    │               ├─> app/request_utils.py
    │               ├─> app/statistics.py
    │               └─> app/templates/*
    │
    └─> app/detector.py
            │
            └─> app/config.py
```

## Data Flow for Prediction

```
┌─────────────────────────────────────────────────────────┐
│ 1. Client Request                                       │
│    GET /predict?param=<script>alert(1)</script>         │
└────────────────────────┬────────────────────────────────┘
                         │
┌────────────────────────▼────────────────────────────────┐
│ 2. Request Utils (request_utils.py)                     │
│    Extract: "<script>alert(1)</script>"                 │
│    Normalize: "<script>alert(1)</script>"               │
└────────────────────────┬────────────────────────────────┘
                         │
┌────────────────────────▼────────────────────────────────┐
│ 3. Attack Classifier (attack_classifier.py)             │
│    Detect patterns: <script> found → Type = XSS         │
└────────────────────────┬────────────────────────────────┘
                         │
┌────────────────────────▼────────────────────────────────┐
│ 4. Model Detector (detector.py)                         │
│    Select: XSS model + XSS vectorizer                   │
│    Vectorize: TF-IDF char n-grams                       │
│    Predict: Random Forest → Score: 0.97                 │
└────────────────────────┬────────────────────────────────┘
                         │
┌────────────────────────▼────────────────────────────────┐
│ 5. Statistics (statistics.py)                           │
│    Record: malicious, XSS, score=0.97                   │
│    Update: total_requests++, malicious++, xss++         │
└────────────────────────┬────────────────────────────────┘
                         │
┌────────────────────────▼────────────────────────────────┐
│ 6. Response (routes.py)                                 │
│    If HTML client: Render blocked.html (403)            │
│    If JSON client: {"prediction": "malicious", ...}     │
└─────────────────────────────────────────────────────────┘
```

## File Organization

```
new-WAF/
│
├── Entry Points
│   ├── waf_app.py          ← Recommended entry point
│   └── waf.py              ← Legacy entry point (backward compatible)
│
├── Core Application (app/)
│   ├── __init__.py         ← Flask app factory
│   ├── config.py           ← Configuration settings
│   ├── routes.py           ← HTTP endpoints
│   │
│   ├── Business Logic
│   │   ├── detector.py           ← ML model operations
│   │   ├── attack_classifier.py  ← Pattern detection
│   │   ├── request_utils.py      ← Request parsing
│   │   └── statistics.py         ← Stats tracking
│   │
│   └── Presentation (templates/)
│       ├── dashboard.html   ← Main dashboard
│       ├── blocked.html     ← Blocked request page
│       ├── allowed.html     ← Allowed request page
│       └── error.html       ← Error page
│
├── ML Models
│   ├── models/              ← SQL Injection models
│   │   ├── random_forest.joblib
│   │   └── tfidf_vectorizer.joblib
│   │
│   └── models_xss/          ← XSS models
│       ├── random_forest_xss.joblib
│       └── tfidf_vectorizer_xss.joblib
│
├── Training Data & Notebooks
│   ├── data/
│   ├── test.ipynb          ← SQLi model training
│   └── xss.ipynb           ← XSS model training
│
└── Documentation
    ├── STRUCTURE.md         ← Architecture overview
    ├── REFACTORING_SUMMARY.md ← Refactoring details
    └── requirements.txt     ← Dependencies
```

## Component Responsibilities

| Component | Responsibility | Lines |
|-----------|---------------|-------|
| `config.py` | Configuration management | 30 |
| `detector.py` | ML model loading & prediction | 130 |
| `attack_classifier.py` | Pattern-based attack detection | 75 |
| `request_utils.py` | HTTP request parsing | 75 |
| `statistics.py` | Statistics tracking | 85 |
| `routes.py` | Flask route definitions | 115 |
| `waf_app.py` | Application entry point | 50 |
| Templates | HTML presentation | ~80 each |

**Total: ~640 lines** (well-organized vs 530 monolithic lines)

## Key Design Principles

1. **Single Responsibility** - Each module does one thing
2. **Dependency Injection** - Components don't create dependencies
3. **Separation of Concerns** - Logic separated from presentation
4. **DRY (Don't Repeat Yourself)** - Shared utilities extracted
5. **Open/Closed Principle** - Easy to extend, hard to break
6. **Testability** - Each component can be tested independently
