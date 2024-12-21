<?php
// Autoload Composer untuk menggunakan phpdotenv
require 'vendor/autoload.php';

// Muat file .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Tambahkan header untuk response JSON
header('Content-Type: application/json');

// Konfigurasi database
$host = "localhost";
$user = "root";
$password = "";
$dbname = "iot_project";

// Membuat koneksi ke database
$conn = new mysqli($host, $user, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["status" => "error", "message" => $conn->connect_error]));
}

// Mengambil API Key dari header atau query parameter
$apiKey = isset($_SERVER['HTTP_API_KEY']) ? $_SERVER['HTTP_API_KEY'] : (isset($_GET['API-Key']) ? $_GET['API-Key'] : null);

// Mengambil API Key yang valid dari file .env
$validApiKey = $_ENV['API_KEY'];

// Validasi API Key
if ($apiKey !== $validApiKey) {
    http_response_code(401);
    die(json_encode(["status" => "error", "message" => "API Key tidak valid."]));
}

// Jangan menutup koneksi di sini
// Tutup koneksi hanya di file get_all.php setelah semua query selesai
?>