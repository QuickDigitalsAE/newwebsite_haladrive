<?php
/**
 * Pure cURL based API Handler Class
 * Supports form data and JSON requests with Bearer token authentication
 */
class ApiHandler {
    private $baseUrl;
    private $token;
    private $endpoints;
    private $headers;
    private $lastResponse;
    private $lastHttpCode;
    
    /**
     * Constructor
     */
    public function __construct() {
        // Load endpoints configuration
        $this->endpoints = require_once 'api_endpoints.php';
        $this->baseUrl = $this->endpoints['base_url'];
        $this->headers = [];
        $this->token = $this->getStoredToken();
        
        // Set default headers
        $this->setDefaultHeaders();
    }
    
    /**
     * Get stored token from session or storage
     */
    private function getStoredToken() {
        // You can modify this to get token from session, database, or file
        if (isset($_SESSION['api_token'])) {
            return $_SESSION['api_token'];
        }
        return null;
    }
    
    /**
     * Set default headers
     */
    private function setDefaultHeaders() {
        $this->headers = [
            'Accept: application/json',
            'User-Agent: API-Client/1.0'
        ];
        
        if ($this->token) {
            $this->headers[] = 'Authorization: Bearer ' . $this->token;
        }
    }
    
    /**
     * Set Bearer token
     */
    public function setToken($token) {
        $this->token = $token;
        $_SESSION['api_token'] = $token; // Store in session
        $this->setDefaultHeaders(); // Update headers with new token
    }
    
    /**
     * Get token from API (login)
     */
    public function getToken($credentials) {
        $response = $this->post('auth', 'login', $credentials, 'json');
        
        if ($response && isset($response['access_token'])) {
            $this->setToken($response['access_token']);
            return $response;
        }
        
        return false;
    }
    
    /**
     * Refresh token
     */
    public function refreshToken($refreshToken) {
        $data = ['refresh_token' => $refreshToken];
        $response = $this->post('auth', 'refresh_token', $data, 'json');
        
        if ($response && isset($response['access_token'])) {
            $this->setToken($response['access_token']);
            return $response;
        }
        
        return false;
    }
    
    /**
     * Make GET request
     */
    public function get($category, $endpoint, $params = [], $id = null) {
        $url = $this->buildUrl($category, $endpoint, $id);
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return $this->makeRequest('GET', $url);
    }
    
    /**
     * Make POST request
     */
    public function post($category, $endpoint, $data = [], $contentType = 'json', $id = null) {
        $url = $this->buildUrl($category, $endpoint, $id);
        return $this->makeRequest('POST', $url, $data, $contentType);
    }
    
    /**
     * Make PUT request
     */
    public function put($category, $endpoint, $data = [], $contentType = 'json', $id = null) {
        $url = $this->buildUrl($category, $endpoint, $id);
        return $this->makeRequest('PUT', $url, $data, $contentType);
    }
    
    /**
     * Make PATCH request
     */
    public function patch($category, $endpoint, $data = [], $contentType = 'json', $id = null) {
        $url = $this->buildUrl($category, $endpoint, $id);
        return $this->makeRequest('PATCH', $url, $data, $contentType);
    }
    
    /**
     * Make DELETE request
     */
    public function delete($category, $endpoint, $id = null) {
        $url = $this->buildUrl($category, $endpoint, $id);
        return $this->makeRequest('DELETE', $url);
    }
    
    /**
     * Build URL from endpoints configuration
     */
    private function buildUrl($category, $endpoint, $id = null) {
        if (!isset($this->endpoints[$category][$endpoint])) {
            throw new Exception("Endpoint not found: $category -> $endpoint");
        }
        
        $url = $this->endpoints[$category][$endpoint];
        
        // Replace ID placeholder if provided
        if ($id !== null) {
            $url = str_replace('{id}', $id, $url);
        }
        
        return $this->baseUrl . $url;
    }
    
    /**
     * Main cURL request handler
     */
    private function makeRequest($method, $url, $data = [], $contentType = 'json') {
        $ch = curl_init();
        
        // Set basic cURL options
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSL_VERIFYPEER => false, // Set to true in production
            CURLOPT_HEADER => false,
        ]);
        
        // Prepare headers and data based on content type
        $headers = $this->headers;
        
        if (in_array($method, ['POST', 'PUT', 'PATCH']) && !empty($data)) {
            if ($contentType === 'json') {
                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            } elseif ($contentType === 'form') {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            } elseif ($contentType === 'multipart') {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                // Let cURL set Content-Type automatically for multipart
            }
        }
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        // Execute request
        $response = curl_exec($ch);
        $this->lastHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        // Check for cURL errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception("cURL Error: " . $error);
        }
        
        curl_close($ch);
        
        // Store last response
        $this->lastResponse = $response;
        
        // Decode JSON response
        $decodedResponse = json_decode($response, true);
        
        return $decodedResponse ?? $response;
    }
    
    /**
     * Load data before page rendering
     */
    public function loadData($category, $endpoint, $params = [], $id = null) {
        // You can add pre-rendering logic here
        // For example: cache handling, data transformation, etc.
        
        $data = $this->get($category, $endpoint, $params, $id);
        
        // Process data before returning
        return $this->processResponse($data);
    }
    
    /**
     * Process API response
     */
    private function processResponse($response) {
        if (is_array($response)) {
            // Add any additional processing here
            if (isset($response['error'])) {
                // Handle API errors
                error_log("API Error: " . $response['error']);
                return ['success' => false, 'error' => $response['error']];
            }
            
            return ['success' => true, 'data' => $response];
        }
        
        return ['success' => false, 'error' => 'Invalid response format'];
    }
    
    /**
     * Get last HTTP status code
     */
    public function getLastHttpCode() {
        return $this->lastHttpCode;
    }
    
    /**
     * Get last raw response
     */
    public function getLastResponse() {
        return $this->lastResponse;
    }
    
    /**
     * Check if token is valid
     */
    public function isTokenValid() {
        if (!$this->token) {
            return false;
        }
        
        // Simple check - you might want to validate with API
        return !empty($this->token);
    }
    
    /**
     * Clear token (logout)
     */
    public function clearToken() {
        $this->token = null;
        unset($_SESSION['api_token']);
        $this->setDefaultHeaders();
    }
    
    /**
     * Upload file with multipart form data
     */
    public function uploadFile($category, $endpoint, $filePath, $additionalData = [], $id = null) {
        if (!file_exists($filePath)) {
            throw new Exception("File not found: $filePath");
        }
        
        $data = $additionalData;
        
        // Add file to POST data
        $data['file'] = new CURLFile($filePath);
        
        return $this->post($category, $endpoint, $data, 'multipart', $id);
    }
}
?>