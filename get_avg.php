<?php
// Sertakan file koneksi.php untuk membuat koneksi ke database
include 'koneksi.php';

// Atur header agar API mengembalikan data dalam format JSON
header("Content-Type: application/json");

// Periksa apakah metode request adalah GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Query untuk menghitung rata-rata kecepatan, rata-rata baterai, dan rentang tanggal dari data dalam 7 hari terakhir
    $sql = "SELECT 
                (SELECT AVG(speed) FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY) AS avg_speed,
                (SELECT AVG(battery) FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY) AS avg_battery,
                (SELECT MIN(timestamp) FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY) AS start_date,
                (SELECT MAX(timestamp) FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY) AS end_date
            ";

    // Eksekusi query
    $result = $conn->query($sql);

    // Periksa apakah query berhasil dieksekusi
    if ($result) {
        // Ambil hasil query sebagai array asosiatif
        $data = $result->fetch_assoc();

        // Kembalikan respons dalam format JSON
        echo json_encode([
            "status" => "success", // Status API sukses
            "data" => [
                "avg_speed" => $data['avg_speed'], // Rata-rata kecepatan
                "avg_battery" => $data['avg_battery'] // Rata-rata baterai
            ],
            "date_range" => [
                "start_date" => $data['start_date'], // Tanggal awal data
                "end_date" => $data['end_date'] // Tanggal akhir data
            ]
        ]);
    } else {
        // Jika query gagal, kembalikan pesan error
        echo json_encode(["status" => "error", "message" => "Failed to fetch data"]);
    }
} else {
    // Jika metode request bukan GET, kembalikan pesan error
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

// Tutup koneksi database setelah semua proses selesai
$conn->close();
?>
