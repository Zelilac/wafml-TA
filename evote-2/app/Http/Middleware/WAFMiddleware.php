<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WAFMiddleware
{
    /**
     * WAF API endpoint (can be set via config or env)
     */
    protected $wafEndpoint = 'http://localhost:5000/predict';
    protected $wafEnabled = true;

    public function __construct()
    {
        $this->wafEndpoint = env('WAF_ENDPOINT', 'http://localhost:5000/predict');
        $this->wafEnabled = env('WAF_ENABLED', true);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip WAF for certain routes (e.g., health checks)
        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        if (!$this->wafEnabled) {
            return $next($request);
        }

        try {
            // Check request with WAF
            if (!$this->checkWithWAF($request)) {
                Log::warning('WAF: Malicious request detected', [
                    'url' => $request->url(),
                    'method' => $request->method(),
                    'ip' => $request->ip(),
                ]);

                // Return HTML blocked page for better UX, or JSON for API requests
                if ($request->expectsJson() || $request->isJson()) {
                    return response()->json([
                        'error' => 'Request blocked by WAF',
                        'message' => 'Your request was detected as potentially malicious and has been blocked.',
                    ], 403);
                }
                
                return response()->view('waf.blocked', [
                    'details' => [
                        'timestamp' => now()->format('Y-m-d H:i:s'),
                        'ip_address' => $request->ip(),
                        'request_method' => $request->method(),
                        'request_path' => $request->getPathInfo(),
                        'user_agent' => $request->header('User-Agent'),
                        'referer' => $request->header('Referer') ?? 'N/A',
                    ]
                ], 403);
            }
        } catch (\Exception $e) {
            Log::error('WAF Error: ' . $e->getMessage());
            // Fail open - allow request if WAF is unavailable
            // Change to return 503 if you prefer fail-closed approach
        }

        return $next($request);
    }

    /**
     * Check request payload with WAF API
     * Protects all request types: GET, POST, PUT, DELETE, etc.
     */
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

        Log::info('WAF Check - Payload to analyze', [
            'payload' => substr($payload, 0, 200), // Log first 200 chars
            'endpoint' => $this->wafEndpoint,
        ]);

        try {
            $client = new Client(['timeout' => 5, 'connect_timeout' => 5]);
            $response = $client->post($this->wafEndpoint, [
                'json' => ['param' => $payload],
                'headers' => ['Content-Type' => 'application/json'],
            ]);

            $body = json_decode($response->getBody(), true);

            Log::info('WAF Response', [
                'is_malicious' => $body['is_malicious'] ?? null,
                'confidence' => $body['confidence'] ?? null,
                'action' => $body['action'] ?? null,
                'prediction' => $body['prediction'] ?? null,
                'score' => $body['score'] ?? null,
            ]);

            // Handle both response formats:
            // Format 1: { "is_malicious": bool, "confidence": float }
            // Format 2: { "action": "block"|"allow", "prediction": "malicious"|"benign", "score": float }
            $isMalicious = $body['is_malicious'] ?? false;
            
            // If using new format, check action or prediction
            if (!isset($body['is_malicious']) && isset($body['action'])) {
                $isMalicious = $body['action'] === 'block' || $body['prediction'] === 'malicious';
            }
            
            return !$isMalicious;
        } catch (\Exception $e) {
            Log::error('WAF Connection Error: ' . $e->getMessage(), [
                'endpoint' => $this->wafEndpoint,
                'exception' => get_class($e),
            ]);
            // Fail open - allow request if WAF is unavailable
            // To make it fail-closed (block on WAF error), return false here
            return true;
        }
    }

    /**
     * Extract request payload to check (including URL path, GET params, POST data, JSON, headers, route params)
     * This method ensures all GET requests are properly scanned
     */
    protected function extractPayload(Request $request): string
    {
        $checks = [];

        // ALWAYS check URL path first - this catches path-based attacks
        // Examples: /admin/hack, /files/../../etc/passwd, /'; DROP TABLE--
        $pathInfo = $request->getPathInfo();
        if (!empty($pathInfo)) {
            $checks[] = $pathInfo;
        }

        // Check route parameters (like {id}, {filter_periode}, etc.)
        $route = $request->route();
        if ($route) {
            $routeParams = $route->parameters();
            foreach ($routeParams as $key => $value) {
                if (is_string($value)) {
                    $checks[] = $value;
                }
            }
        }

        // For GET requests, ALWAYS check query string if present
        if ($request->isMethod('GET')) {
            $queryString = $request->getQueryString();
            if (!empty($queryString)) {
                $checks[] = $queryString;
            }
        }

        // Check all query parameters (GET parameters)
        foreach ($request->query() as $key => $value) {
            if (is_string($value)) {
                $checks[] = $value;
            }
        }

        // Check form data (POST, PUT, PATCH)
        foreach ($request->post() as $key => $value) {
            if (is_string($value)) {
                $checks[] = $value;
            }
        }

        // Check JSON payload
        if ($request->isJson()) {
            $json = $request->json()->all();
            foreach ($json as $key => $value) {
                if (is_string($value)) {
                    $checks[] = $value;
                }
            }
        }

        // Check request headers for attack patterns
        $suspiciousHeaders = ['User-Agent', 'Referer', 'X-Forwarded-For'];
        foreach ($suspiciousHeaders as $header) {
            $value = $request->header($header);
            if (!empty($value) && is_string($value)) {
                $checks[] = $value;
            }
        }

        // Check cookies for injection attempts
        // Note: Session cookies are typically encrypted/signed by Laravel
        // but we still check for tampering attempts or unusual patterns
        foreach ($request->cookies as $key => $value) {
            if (is_string($value) && !empty($value)) {
                // Skip Laravel's encrypted cookies (they're safe and create false positives)
                if (!in_array($key, ['laravel_session', 'XSRF-TOKEN'])) {
                    $checks[] = $value;
                }
            }
        }

        // Check file upload filenames (malicious filenames: shell.php, ../../etc/passwd, etc.)
        foreach ($request->allFiles() as $key => $file) {
            if ($file instanceof \Illuminate\Http\UploadedFile) {
                $originalName = $file->getClientOriginalName();
                if (!empty($originalName)) {
                    $checks[] = $originalName;
                }
            }
        }

        return implode(' ', $checks);
    }

    /**
     * Routes to skip WAF check
     * Add routes that don't need malicious content detection
     */
    protected function shouldSkip(Request $request): bool
    {
        // Get the path without query string
        $path = $request->getPathInfo();
        
        // Exact routes to skip
        $skipRoutes = [
            '/',
            '/login',
            '/logout',
            '/captcha',
            '/health',
            '/waf-status',
            '/api/waf/status',
            '/reload',
        ];

        // Check exact routes (compare path directly without query string)
        foreach ($skipRoutes as $route) {
            if ($path === $route) {
                return true;
            }
        }

        // Route patterns to skip (using wildcards and startsWith check)
        // NOTE: Removed /dashboard, /master-*, /tahun-periode, /mahasiswa to ensure route parameters are checked
        $skipPatterns = [
            '/api',
            '/assets',
            '/public',
            '/images',
            '/css',
            '/js',
            '/vendor',
            '/storage',
        ];

        // Check pattern routes
        foreach ($skipPatterns as $pattern) {
            if ($path === $pattern || strpos($path, $pattern . '/') === 0) {
                return true;
            }
        }

        return false;
    }
}
