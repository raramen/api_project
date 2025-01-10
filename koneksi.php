<?php
// Autoload Composer untuk memuat library phpdotenv
require 'vendor/autoload.php';

// Memuat file .env menggunakan library phpdotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); // Mengarahkan ke direktori saat ini
$dotenv->load(); // Memuat variabel dari file .env ke dalam variabel lingkungan ($_ENV)

// Menentukan header untuk mengatur bahwa response akan dikembalikan dalam format JSON
header('Content-Type: application/json');

// Konfigurasi koneksi ke database
$host = "localhost"; // Host database (biasanya localhost)
$user = "root"; // Username database
$password = ""; // Password database (kosong jika tidak ada password)
$dbname = "iot_project"; // Nama database yang digunakan

// Membuat koneksi ke database menggunakan MySQLi
$conn = new mysqli($host, $user, $password, $dbname);

// Memeriksa apakah koneksi berhasil
if ($conn->connect_error) {
    // Jika koneksi gagal, kirimkan status HTTP 500 dan pesan error dalam format JSON
    http_response_code(500);
    die(json_encode(["status" => "error", "message" => $conn->connect_error]));
}

// Mengambil API Key dari header HTTP atau query parameter
$apiKey = isset($_SERVER['HTTP_API_KEY']) 
    ? $_SERVER['HTTP_API_KEY']  // Jika ada di header, gunakan ini
    : (isset($_GET['API-Key']) 
        ? $_GET['API-Key']  // Jika tidak, cek di query parameter
        : null); // Jika tidak ditemukan, atur ke null

// Mengambil API Key yang valid dari file .env
$validApiKey = $_ENV['API_KEY']; // API Key disimpan di file .env untuk keamanan

// Memvalidasi API Key yang diterima dengan API Key yang ada di .env
if ($apiKey !== $validApiKey) {
    // Jika API Key tidak valid, kirimkan status HTTP 401 (Unauthorized)
    http_response_code(401);
    die(json_encode(["status" => "error", "message" => "API Key tidak valid."]));
}

// Catatan: 
// Jangan menutup koneksi di sini, karena file ini hanya bertugas menyiapkan koneksi dan validasi API Key.
// Penutupan koneksi harus dilakukan di file lain (misalnya `get_all.php`) setelah semua proses selesai.
?>