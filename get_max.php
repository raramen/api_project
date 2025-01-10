<?php
// Sertakan file koneksi.php untuk membuat koneksi ke database
include 'koneksi.php';

// Atur header agar API mengembalikan data dalam format JSON
header("Content-Type: application/json");

// Periksa apakah metode request adalah GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Query untuk mendapatkan data maksimum (speed dan battery) serta waktu pencapaiannya dalam 7 hari terakhir
    $sql = "SELECT 
                (SELECT MAX(speed) FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY) AS max_speed, 
                -- Mendapatkan nilai maksimum speed dari data dalam 7 hari terakhir
                
                (SELECT timestamp FROM sensor_data 
                    WHERE ROUND(speed, 2) = ROUND((SELECT MAX(speed) FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY), 2)
                    AND timestamp >= NOW() - INTERVAL 7 DAY 
                    LIMIT 1) AS date_max_speed, 
                -- Mendapatkan waktu (timestamp) saat speed maksimum tercatat

                (SELECT MAX(battery) FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY) AS max_battery, 
                -- Mendapatkan nilai maksimum battery dari data dalam 7 hari terakhir
                
                (SELECT timestamp FROM sensor_data 
                    WHERE ROUND(battery, 2) = ROUND((SELECT MAX(battery) FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY), 2)
                    AND timestamp >= NOW() - INTERVAL 7 DAY 
                    LIMIT 1) AS date_max_battery 
                -- Mendapatkan waktu (timestamp) saat battery maksimum tercatat
           ";

    // Eksekusi query
    $result = $conn->query($sql);

    // Ambil hasil query sebagai array asosiatif
    $data = $result->fetch_assoc();

    // Kembalikan hasil query dalam format JSON
    echo json_encode(["status" => "success", "data" => $data]);
} else {
    // Jika metode request bukan GET, kembalikan pesan error
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

// Tutup koneksi database
$conn->close();
?>