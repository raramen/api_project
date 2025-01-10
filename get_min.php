<?php
// Sertakan file koneksi.php untuk membuat koneksi ke database
include 'koneksi.php';

// Atur header agar API mengembalikan data dalam format JSON
header("Content-Type: application/json");

// Periksa apakah metode request adalah GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Query untuk mendapatkan nilai minimum (speed dan battery) serta waktu pencapaiannya dalam 7 hari terakhir
    $sql = "SELECT 
                (SELECT MIN(speed) FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY) AS min_speed,
                -- Mendapatkan nilai minimum speed dari data dalam 7 hari terakhir
                
                (SELECT timestamp FROM sensor_data 
                    WHERE ROUND(speed, 2) = ROUND((SELECT MIN(speed) FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY), 2)
                    AND timestamp >= NOW() - INTERVAL 7 DAY 
                    LIMIT 1) AS date_min_speed,
                -- Mendapatkan waktu (timestamp) saat speed minimum tercatat
                
                (SELECT MIN(battery) FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY) AS min_battery,
                -- Mendapatkan nilai minimum battery dari data dalam 7 hari terakhir
                
                (SELECT timestamp FROM sensor_data 
                    WHERE ROUND(battery, 2) = ROUND((SELECT MIN(battery) FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY), 2)
                    AND timestamp >= NOW() - INTERVAL 7 DAY 
                    LIMIT 1) AS date_min_battery;
                -- Mendapatkan waktu (timestamp) saat battery minimum tercatat
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