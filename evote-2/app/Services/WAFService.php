<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WAFService
{
    protected $client;
    protected $endpoint;
    protected $timeout = 5;

    public function __construct()
    {
        $this->endpoint = env('WAF_ENDPOINT', 'http://localhost:5000/predict');
        $this->client = new Client(['timeout' => $this->timeout]);
    }

    /**
     * Check if a string/payload is malicious
     *
     * @param string $payload
     * @return array { is_malicious: bool, confidence: float, model: string }
     */
    public function check(string $payload): array
    {
        try {
            $response = $this->client->post($this->endpoint, [
                'json' => ['param' => $payload],
                'headers' => ['Content-Type' => 'application/json'],
            ]);

            $data = json_decode($response->getBody(), true);

            return [
                'is_malicious' => $data['is_malicious'] ?? false,
                'confidence' => $data['confidence'] ?? 0,
                'model' => $data['model'] ?? 'unknown',
                'error' => false,
            ];
        } catch (\Exception $e) {
            Log::error('WAF Service Error: ' . $e->getMessage());

            return [
                'is_malicious' => false,
                'confidence' => 0,
                'model' => null,
                'error' => true,
                'error_message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check multiple payloads
     *
     * @param array $payloads
     * @return array
     */
    public function checkBatch(array $payloads): array
    {
        $results = [];
        foreach ($payloads as $name => $payload) {
            if (is_string($payload)) {
                $results[$name] = $this->check($payload);
            }
        }
        return $results;
    }

    /**
     * Check if request is safe to process
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    public function isRequestSafe(\Illuminate\Http\Request $request): bool
    {
        $payload = $this->extractPayload($request);

        if (empty($payload)) {
            return true;
        }

        $result = $this->check($payload);
        return !$result['is_malicious'];
    }

    /**
     * Get health status of WAF service
     *
     * @return bool
     */
    public function isHealthy(): bool
    {
        try {
            $response = $this->client->get(str_replace('/predict', '/', $this->endpoint), [
                'timeout' => 2,
            ]);
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Reload WAF models
     *
     * @return bool
     */
    public function reload(): bool
    {
        try {
            $response = $this->client->post(str_replace('/predict', '/reload', $this->endpoint), [
                'timeout' => 10,
            ]);
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            Log::error('WAF Reload Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Extract request payload (including URL path)
     */
    protected function extractPayload(\Illuminate\Http\Request $request): string
    {
        $checks = [];

        // Check URL path itself (catches attacks like /'; DROP TABLE--)
        $pathInfo = $request->getPathInfo();
        if (!empty($pathInfo) && $pathInfo !== '/') {
            $checks[] = $pathInfo;
        }

        // Check full URL query string
        $queryString = $request->getQueryString();
        if (!empty($queryString)) {
            $checks[] = $queryString;
        }

        // Query parameters
        foreach ($request->query() as $value) {
            if (is_string($value)) {
                $checks[] = $value;
            }
        }

        // POST data
        foreach ($request->post() as $value) {
            if (is_string($value)) {
                $checks[] = $value;
            }
        }

        // JSON payload
        if ($request->isJson()) {
            foreach ($request->json()->all() as $value) {
                if (is_string($value)) {
                    $checks[] = $value;
                }
            }
        }

        // Suspicious headers
        $suspiciousHeaders = ['User-Agent', 'Referer', 'X-Forwarded-For'];
        foreach ($suspiciousHeaders as $header) {
            $value = $request->header($header);
            if (!empty($value) && is_string($value)) {
                $checks[] = $value;
            }
        }

        return implode(' ', $checks);
    }
}
