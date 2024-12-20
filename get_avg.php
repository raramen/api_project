<?php
include 'koneksi.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Query to calculate the average speed, battery, and the date range
    $sql = "SELECT 
                (SELECT AVG(speed) FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY) AS avg_speed,
                (SELECT AVG(battery) FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY) AS avg_battery,
                (SELECT MIN(timestamp) FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY) AS start_date,
                (SELECT MAX(timestamp) FROM sensor_data WHERE timestamp >= NOW() - INTERVAL 7 DAY) AS end_date
            ";
    $result = $conn->query($sql);

    if ($result) {
        $data = $result->fetch_assoc();
        echo json_encode([
            "status" => "success",
            "data" => [
                "avg_speed" => $data['avg_speed'],
                "avg_battery" => $data['avg_battery']
            ],
            "date_range" => [
                "start_date" => $data['start_date'],
                "end_date" => $data['end_date']
            ]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to fetch data"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
$conn->close();
?>
