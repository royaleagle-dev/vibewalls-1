<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

function fetchWithCurl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [$response, $httpCode];
}

try {
    $apiUrl = 'https://vibewalls.42web.io/index/getRandom';
    
    // Try file_get_contents first, then cURL as fallback
    $response = @file_get_contents($apiUrl);
    print_r($response);
    
    if ($response === FALSE) {
        list($response, $httpCode) = fetchWithCurl($apiUrl);
        
        if ($httpCode !== 200 || $response === FALSE) {
            throw new Exception("Failed to fetch data. HTTP Code: $httpCode");
        }
    }
    
    // Decode and validate JSON
    $data = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON response: ' . json_last_error_msg());
    }
    
    // Success response
    echo json_encode([
        'success' => true,
        'data' => $data,
        'source' => 'vibewalls.42web.io',
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_PRETTY_PRINT);
}
?>