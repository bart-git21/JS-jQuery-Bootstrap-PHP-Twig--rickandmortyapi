<?php
$apiEndpoint = 'https://rickandmortyapi.com/graphql';
$query = '{
  characters(page: 2, filter: { name: "rick" }) {
    info {
      count
    }
    results {
      name
    }
  }
  location(id: 1) {
    id
    name
  }
  episodesByIds(ids: [1, 2]) {
    id
    name
  }
}';

$ch = curl_init($apiEndpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['query' => $query]));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification for local testing


$response = curl_exec($ch);

// Log the request details for debugging
file_put_contents('debug_request.txt', json_encode(['query' => $query]));

// Check for cURL errors first
if ($response === false) {
    // Log cURL error for debugging
    file_put_contents('debug_error.txt', curl_error($ch));

    $curl_error = curl_error($ch);
    $error_response = [
        'error' => true,
        'message' => 'API request failed',
        'details' => $curl_error ? $curl_error : 'Unknown error'
    ];
    http_response_code(500);
    echo json_encode($error_response);
    curl_close($ch);
    exit;
}

// Check HTTP status code
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($http_code >= 400) {
    $error_response = [
        'error' => true,
        'message' => 'API returned an error',
        'details' => 'HTTP Status Code: ' . $http_code
    ];
    http_response_code($http_code);
    echo json_encode($error_response);
    curl_close($ch);
    exit;
}

$body = json_decode($response, true);
file_put_contents('debug_response.txt', $response);
if (isset($body['data'])) {
    header('Content-Type: application/json');
    echo json_encode($body);
} else {
    $error_response = [
        'error' => true,
        'message' => 'Failed to parse API response',
        'details' => json_last_error_msg(),
        'raw_response' => $response
    ];
    http_response_code(500);
    echo json_encode($error_response);
}

curl_close($ch);
