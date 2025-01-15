<?php
// Autoload Composer packages
require 'vendor/autoload.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Define header to ensure response is returned in JSON format
header('Content-Type: application/json');

// Database configuration from environment variables
$host = $_ENV['DB_HOST'];        // Database host
$db = $_ENV['DB_NAME'];          // Database name
$user = $_ENV['DB_USER'];        // Database username
$pass = $_ENV['DB_PASSWORD'];    // Database password
$charset = 'utf8mb4';            // Character set (hardcoded in script as per your indication)

// DSN for PDO connection
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    http_response_code(500); // Set HTTP response code to 500 for server error
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit; // Terminate script execution after sending the response
}

// Retrieve API Key from HTTP headers or query parameters
$apiKeyReceived = $_SERVER['HTTP_API_KEY'] ?? $_GET['api_key'] ?? '';

// Get valid API Key from environment variables
$validApiKey = $_ENV['API_KEY'];

// Validate the API Key
if ($apiKeyReceived !== $validApiKey) {
    http_response_code(401); // Unauthorized access
    echo json_encode(['status' => 'error', 'message' => 'Invalid API Key.']);
    exit;
}

// API Key is valid, proceed with the rest of the script
// Example: Insert here the rest of your script logic for database operations or data processing
?>