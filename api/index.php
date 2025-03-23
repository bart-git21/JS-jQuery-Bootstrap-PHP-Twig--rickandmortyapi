<?php
require_once __DIR__ . '/../vendor/autoload.php';
$twigLoader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($twigLoader);
$apiEndpoint = 'https://rickandmortyapi.com/graphql';

// Get the request data from the client-side
$data = json_decode(file_get_contents('php://input'), true);
$name = filter_var($data['nameSelect'] ?? "", FILTER_SANITIZE_STRING);
$status = filter_var($data['statusSelect'] ?? "", FILTER_SANITIZE_STRING);
$gender = filter_var($data['genderSelect'] ?? "", FILTER_SANITIZE_STRING);
$species = filter_var($data['speciesSelect'] ?? "", FILTER_SANITIZE_STRING);
$locationIds = filter_var($data['locationInput'] ?? "", FILTER_SANITIZE_STRING);
$episodeIds = filter_var($data['episodeInput'] ?? "", FILTER_SANITIZE_STRING);

// Prepare the GraphQL query template with the provided data
$query = $twig->render('query.html.twig', [
    'name' => $name,
    'status' => $status,
    'gender' => $gender,
    'species' => $species,
    'locationIds' => $locationIds,
    'episodeIds' => $episodeIds,
]);

// Make the API request
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
