<?php
include 'koneksi.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT 
                (SELECT MAX(speed) FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY) AS max_speed,
                (SELECT timestamp FROM sensor_data 
                    WHERE ROUND(speed, 2) = ROUND((SELECT MAX(speed) FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY), 2)
                    AND timestamp >= NOW() - INTERVAL 7 DAY 
                    LIMIT 1) AS date_max_speed,
                (SELECT MAX(battery) FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY) AS max_battery,
                (SELECT timestamp FROM sensor_data 
                    WHERE ROUND(battery, 2) = ROUND((SELECT MAX(battery) FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY), 2)
                    AND timestamp >= NOW() - INTERVAL 7 DAY 
                    LIMIT 1) AS date_max_battery;
       ";
    $result = $conn->query($sql);

    $data = $result->fetch_assoc();
    echo json_encode(["status" => "success", "data" => $data]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
$conn->close();
?>