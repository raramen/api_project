<?php
require 'vendor/autoload.php'; // Load Composer's autoloader
require 'koneksi.php';        // Memuat koneksi database

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Mendapatkan API Key dari .env
$apiKeyRequired = $_ENV['API_KEY'] ?? '';

// Mendapatkan API Key dari header atau query string
$apiKeyReceived = $_SERVER['HTTP_API_KEY'] ?? $_GET['API_KEY'] ?? '';

// Debugging API Key
if (!$apiKeyRequired) {
    echo json_encode(["status" => "error", "message" => "API Key tidak ditemukan di .env"]);
    exit;
}

if (!$apiKeyReceived) {
    echo json_encode(["status" => "error", "message" => "API Key tidak ditemukan dalam permintaan"]);
    exit;
}

// Validasi API Key
if ($apiKeyReceived !== $apiKeyRequired) {
    http_response_code(401); // Unauthorized
    echo json_encode(["status" => "error", "message" => "API Key tidak valid."]);
    exit;
}

// Mendapatkan data dari POST
$speed = isset($_POST['speed']) ? (float) $_POST['speed'] : null;
$battery = isset($_POST['battery']) ? (float) $_POST['battery'] : null;
$water_level = isset($_POST['water_level']) ? (float) $_POST['water_level'] : null;

// Validasi data yang dikirimkan
if ($speed === null || $battery === null || $water_level === null) {
    http_response_code(400); // Bad Request
    echo json_encode(["status" => "error", "message" => "Incomplete data. Pastikan speed, battery, dan water_level terkirim."]);
    exit;
}

// Simpan data ke database
try {
    $conn->begin_transaction();

    $stmt = $conn->prepare("INSERT INTO speed_data (value) VALUES (?)");
    $stmt->bind_param("d", $speed);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO battery_data (capacity) VALUES (?)");
    $stmt->bind_param("d", $battery);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO water_level_data (level) VALUES (?)");
    $stmt->bind_param("d", $water_level);
    $stmt->execute();
    $stmt->close();

    $conn->commit();
    echo json_encode(["status" => "success", "message" => "Data inserted successfully"]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500); // Internal Server Error
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    exit;
}

$conn->close();
?>