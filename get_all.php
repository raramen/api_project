<?php
include 'koneksi.php'; // Include koneksi database

// Mengambil data dari tabel battery_data
$queryBattery = "SELECT * FROM battery_data";
$resultBattery = $conn->query($queryBattery);

// Mengambil data dari tabel sensor_data
$querySensor = "SELECT * FROM sensor_data";
$resultSensor = $conn->query($querySensor);

// Mengambil data dari tabel speed_data
$querySpeed = "SELECT * FROM speed_data";
$resultSpeed = $conn->query($querySpeed);

// Mengambil data dari tabel water_level_data
$queryWaterLevel = "SELECT * FROM water_level_data";
$resultWaterLevel = $conn->query($queryWaterLevel);

$data = [];

if ($resultBattery->num_rows > 0) {
    $data['battery_data'] = $resultBattery->fetch_all(MYSQLI_ASSOC);
}

if ($resultSensor->num_rows > 0) {
    $data['sensor_data'] = $resultSensor->fetch_all(MYSQLI_ASSOC);
}

if ($resultSpeed->num_rows > 0) {
    $data['speed_data'] = $resultSpeed->fetch_all(MYSQLI_ASSOC);
}

if ($resultWaterLevel->num_rows > 0) {
    $data['water_level_data'] = $resultWaterLevel->fetch_all(MYSQLI_ASSOC);
}

// Menampilkan data JSON
header('Content-Type: application/json');
if (empty($data)) {
    echo json_encode(['status' => 'error', 'message' => 'Incomplete data']);
} else {
    echo json_encode(['status' => 'success', 'data' => $data]);
}

$conn->close(); // Menutup koneksi database