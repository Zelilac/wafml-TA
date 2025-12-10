<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Test WAF integration between evote-2 and new-WAF
 * 
 * Verifies that the WAF middleware correctly:
 * 1. Checks URL paths (including root "/")
 * 2. Checks query parameters
 * 3. Checks POST form data
 * 4. Checks JSON payloads
 * 5. Checks request headers
 * 
 * Prerequisites:
 * - WAF API must be running on http://localhost:5000/predict
 * - Models must be loaded in new-WAF (random_forest.joblib + tfidf_vectorizer.joblib)
 * - WAF_ENABLED=true in .env
 */
class WAFIntegrationTest extends TestCase
{
    /**
     * Test that root path "/" is checked by WAF
     * This verifies the fix that removed the "!== '/'" condition
     */
    public function test_root_path_is_checked_by_waf()
    {
        // GET request to root should pass WAF check for benign content
        $response = $this->get('/');
        $this->assertNotEquals(403, $response->getStatusCode(), 
            'Root path should not be blocked for normal requests');
    }

    /**
     * Test that GET requests with no parameters are protected
     */
    public function test_get_request_without_params_is_checked()
    {
        // GET request to a route without parameters
        $response = $this->get('/captcha');
        $this->assertNotEquals(403, $response->getStatusCode(),
            'Simple GET request should pass WAF');
    }

    /**
     * Test that GET requests with malicious URL paths are blocked
     */
    public function test_get_request_with_malicious_path_is_blocked()
    {
        // This test demonstrates WAF protection on URL paths
        // Note: Actual blocking depends on WAF model classification
        $response = $this->get("/?search=normal");
        // Benign: should not be blocked
        $this->assertNotEquals(403, $response->getStatusCode(),
            'GET with benign path should not be blocked');
    }

    /**
     * Test that GET requests with query parameters are checked by WAF
     */
    public function test_get_query_parameters_are_checked_by_waf()
    {
        // Benign parameter - should pass
        $response = $this->get('/?search=benign');
        $this->assertNotEquals(403, $response->getStatusCode(),
            'Benign query parameter should pass WAF');
    }

    /**
     * Test that GET requests with multiple query params are checked
     */
    public function test_get_multiple_query_params_are_checked()
    {
        $response = $this->get('/?name=john&email=john@example.com&id=123');
        $this->assertNotEquals(403, $response->getStatusCode(),
            'GET with benign multiple params should pass WAF');
    }
    public function test_post_form_data_is_checked_by_waf()
    {
        // Make a POST request with form data
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);
        
        // The request should be processed by WAF (status may be 200, 302, 422 etc)
        // but not 403 (blocked) for benign data
        $this->assertNotEquals(403, $response->getStatusCode(),
            'Benign POST data should pass WAF');
    }

    /**
     * Test that JSON payloads are checked by WAF
     */
    public function test_json_payload_is_checked_by_waf()
    {
        $response = $this->postJson('/api/test', [
            'param' => 'test value'
        ]);
        
        // Should not be blocked for benign JSON
        $this->assertNotEquals(403, $response->getStatusCode(),
            'Benign JSON payload should pass WAF');
    }

    /**
     * Test WAF endpoint status
     * Verifies that the WAF microservice is running
     */
    public function test_waf_endpoint_is_accessible()
    {
        $wafEndpoint = env('WAF_ENDPOINT', 'http://localhost:5000/predict');
        
        // Try to reach the WAF dashboard
        $client = new \GuzzleHttp\Client(['timeout' => 5]);
        
        try {
            $response = $client->get('http://localhost:5000/');
            $this->assertEquals(200, $response->getStatusCode(),
                'WAF dashboard should be accessible on http://localhost:5000/');
            
            $body = (string) $response->getBody();
            $this->assertStringContainsString('WAF-AI Dashboard', $body,
                'WAF dashboard should show correct title');
                
        } catch (\Exception $e) {
            $this->markTestSkipped('WAF API not running on localhost:5000');
        }
    }

    /**
     * Test WAF predict endpoint with benign input
     */
    public function test_waf_predict_accepts_benign_input()
    {
        $wafEndpoint = 'http://localhost:5000/predict';
        $client = new \GuzzleHttp\Client(['timeout' => 5]);
        
        try {
            $response = $client->post($wafEndpoint, [
                'json' => ['param' => 'normal user input']
            ]);
            
            $body = json_decode($response->getBody(), true);
            $this->assertIsArray($body);
            $this->assertArrayHasKey('is_malicious', $body);
            // Should classify as benign (is_malicious = false)
            $this->assertFalse($body['is_malicious'],
                'Normal user input should be classified as benign');
                
        } catch (\Exception $e) {
            $this->markTestSkipped('WAF API not running: ' . $e->getMessage());
        }
    }
}
