<?php
// Memuat koneksi database
require 'koneksi.php';

// Mendapatkan data dari URL
$speed = isset($_GET['speed']) ? (float)$_GET['speed'] : null;
$battery = isset($_GET['battery']) ? (float)$_GET['battery'] : null;
$water_level = isset($_GET['water_level']) ? (float)$_GET['water_level'] : null;

// Validasi data
if ($speed === null || $battery === null || $water_level === null) {
    http_response_code(400); // Bad Request
    die(json_encode(["status" => "error", "message" => "Incomplete data"]));
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
    die(json_encode(["status" => "error", "message" => $e->getMessage()]));
}

// Menutup koneksi
$conn->close();