<?php
include 'koneksi.php';

header("Content-Type: application/json");

// Check the request method
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    $speed = $_POST['speed'] ?? $_GET['speed'] ?? null;
    $battery = $_POST['battery'] ?? $_GET['battery'] ?? null;

    // Validate input
    if (is_null($speed) || is_null($battery)) {
        echo json_encode(["status" => "error", "message" => "Speed and battery are required"]);
        exit;
    }

    // Insert data into database
    $stmt = $conn->prepare("INSERT INTO sensor_data (speed, battery) VALUES (?, ?)");
    $stmt->bind_param("dd", $speed, $battery);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Data saved successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
$conn->close();
?>