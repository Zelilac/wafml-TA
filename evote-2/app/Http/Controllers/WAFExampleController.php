<?php

namespace App\Http\Controllers;

use App\Services\WAFService;
use Illuminate\Http\Request;

/**
 * Example controller showing WAF integration
 * 
 * Usage in your controllers:
 *   $waf = app(WAFService::class);
 *   $result = $waf->check($userInput);
 *   if ($result['is_malicious']) {
 *       return response()->json(['error' => 'Request blocked'], 403);
 *   }
 */
class WAFExampleController extends Controller
{
    protected $waf;

    public function __construct(WAFService $waf)
    {
        $this->waf = $waf;
    }

    /**
     * Example: Check a search query
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        // Manually check with WAF
        $result = $this->waf->check($query);

        if ($result['error']) {
            return response()->json([
                'error' => 'WAF Service unavailable',
            ], 503);
        }

        if ($result['is_malicious']) {
            return response()->json([
                'error' => 'Malicious query detected',
                'confidence' => $result['confidence'],
            ], 403);
        }

        // Safe to process
        return response()->json([
            'query' => $query,
            'results' => [], // Your search logic here
            'waf_confidence' => $result['confidence'],
        ]);
    }

    /**
     * Example: Check multiple form fields
     */
    public function updateProfile(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'bio' => $request->input('bio'),
        ];

        // Batch check
        $results = $this->waf->checkBatch($data);

        foreach ($results as $field => $result) {
            if ($result['is_malicious']) {
                return response()->json([
                    'error' => "Field '$field' contains malicious content",
                    'confidence' => $result['confidence'],
                ], 403);
            }
        }

        // Update profile
        return response()->json(['success' => 'Profile updated']);
    }

    /**
     * WAF Health Status Endpoint
     */
    public function wafStatus()
    {
        $isHealthy = $this->waf->isHealthy();

        return response()->json([
            'status' => $isHealthy ? 'healthy' : 'unhealthy',
            'endpoint' => env('WAF_ENDPOINT'),
            'enabled' => env('WAF_ENABLED', true),
        ]);
    }

    /**
     * Reload WAF Models
     * (Admin only)
     */
    public function reloadWAF()
    {
        // Add authorization check here
        if (!auth()->user()?->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $success = $this->waf->reload();

        if ($success) {
            return response()->json(['message' => 'WAF models reloaded']);
        } else {
            return response()->json(['error' => 'Failed to reload WAF'], 503);
        }
    }
}
