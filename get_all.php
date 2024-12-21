<?php
include 'koneksi.php';  // Pastikan koneksi tetap terbuka

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Pastikan koneksi masih aktif
    if (!$conn || $conn->connect_error) {
        die(json_encode(["status" => "error", "message" => "Database connection lost."]));
    }

    $sql = "SELECT * FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY";
    $result = $conn->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode(["status" => "success", "data" => $data]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

// Tutup koneksi di akhir
$conn->close();
?>