<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WAFBlockedController extends Controller
{
    /**
     * Display WAF blocked page with details
     */
    public function show(Request $request)
    {
        $details = [
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'ip_address' => $request->ip(),
            'request_method' => $request->method(),
            'request_path' => $request->getPathInfo(),
            'user_agent' => $request->header('User-Agent'),
            'referer' => $request->header('Referer') ?? 'N/A',
        ];

        return view('waf.blocked', compact('details'));
    }
}
