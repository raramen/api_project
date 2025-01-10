<?php
// Mengimpor file koneksi untuk terhubung ke database
include 'koneksi.php';

// Menentukan header untuk respons dalam format JSON
header("Content-Type: application/json");

// Mengecek metode request yang digunakan (harus POST atau GET)
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    // Mengambil nilai parameter 'speed' dan 'battery' dari request POST atau GET
    $speed = $_POST['speed'] ?? $_GET['speed'] ?? null; // Nilai 'speed' diambil dari POST atau GET, jika tidak ditemukan diatur null
    $battery = $_POST['battery'] ?? $_GET['battery'] ?? null; // Sama seperti 'speed', nilai diambil dari POST atau GET

    // Validasi input: pastikan 'speed' dan 'battery' tidak null
    if (is_null($speed) || is_null($battery)) {
        // Jika salah satu parameter tidak ditemukan, kirimkan error dalam format JSON
        echo json_encode(["status" => "error", "message" => "Speed and battery are required"]);
        exit; // Hentikan eksekusi script
    }

    // Menyiapkan statement SQL untuk menyisipkan data ke tabel 'sensor_data'
    $stmt = $conn->prepare("INSERT INTO sensor_data (speed, battery) VALUES (?, ?)");
    // Mengikat parameter untuk menghindari SQL Injection
    $stmt->bind_param("dd", $speed, $battery); // 'dd' menunjukkan bahwa parameter adalah angka desimal (double)

    // Mengeksekusi statement SQL
    if ($stmt->execute()) {
        // Jika eksekusi berhasil, kirimkan respons JSON dengan status sukses
        echo json_encode(["status" => "success", "message" => "Data saved successfully"]);
    } else {
        // Jika eksekusi gagal, kirimkan respons JSON dengan pesan error dari database
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    // Menutup statement SQL
    $stmt->close();
} else {
    // Jika metode request bukan POST atau GET, kirimkan respons JSON dengan pesan error
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

// Menutup koneksi database setelah semua proses selesai
$conn->close();
?>