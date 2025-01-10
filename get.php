<?php
// Sertakan file koneksi.php untuk mengatur koneksi ke database
include 'koneksi.php';  // Pastikan koneksi tetap terbuka

// Atur header untuk memastikan respons API menggunakan format JSON
header("Content-Type: application/json");

// Periksa apakah metode request yang diterima adalah GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Periksa apakah koneksi database masih aktif
    if (!$conn || $conn->connect_error) {
        // Jika koneksi tidak aktif, kembalikan pesan error dalam format JSON
        die(json_encode(["status" => "error", "message" => "Database connection lost."]));
    }

    // Ambil parameter 'days' dari URL query
    $days = isset($_GET['days']) ? (int)$_GET['days'] : 7;  // Default 7 hari jika tidak ada parameter

    // Pastikan parameter 'days' valid
    if ($days <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid 'days' parameter."]);
        exit;
    }

    // Query untuk mengambil data dari tabel sensor_data dalam rentang waktu yang ditentukan
    $sql = "SELECT * FROM sensor_data WHERE timestamp >= NOW() - INTERVAL $days DAY ORDER BY id DESC";

    // Eksekusi query dan simpan hasilnya ke dalam variabel $result
    $result = $conn->query($sql);

    // Inisialisasi array kosong untuk menyimpan data
    $data = [];

    // Looping untuk mengambil setiap baris data dari hasil query
    while ($row = $result->fetch_assoc()) {
        // Tambahkan data baris ke array $data
        $data[] = $row;
    }

    // Kembalikan data dalam format JSON dengan status sukses
    echo json_encode(["status" => "success", "data" => $data]);
} else {
    // Jika metode request bukan GET, kembalikan pesan error
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

// Tutup koneksi ke database setelah selesai
$conn->close();
?>