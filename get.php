<?php
// Include the database connection file
require 'koneksi.php';  // Ensure the connection is correctly handled in 'koneksi.php'

// Set the header to ensure the API response is in JSON format
header("Content-Type: application/json");

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if the database connection is still active
    if (!$conn || $conn->connect_error) {
        // If the connection is not active, return an error message in JSON format
        die(json_encode(["status" => "error", "message" => "Database connection lost."]));
    }

    // Retrieve the 'days' parameter from the URL query string
    $days = isset($_GET['days']) ? (int)$_GET['days'] : 7;  // Default to 7 days if no parameter provided

    // Validate the 'days' parameter
    if ($days <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid 'days' parameter."]);
        exit;
    }

    // Array to store data from all tables
    $allData = [
        'speed_data' => [],
        'battery_data' => [],
        'water_level_data' => []
    ];

    // Queries for each table
    $queries = [
        'speed_data' => "SELECT * FROM speed_data WHERE created_at >= NOW() - INTERVAL ? DAY ORDER BY id DESC",
        'battery_data' => "SELECT * FROM battery_data WHERE created_at >= NOW() - INTERVAL ? DAY ORDER BY id DESC",
        'water_level_data' => "SELECT * FROM water_level_data WHERE created_at >= NOW() - INTERVAL ? DAY ORDER BY id DESC"
    ];

    // Process each query
    foreach ($queries as $key => $sql) {
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('i', $days);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $allData[$key][] = $row;
            }
            $stmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to prepare the database query for $key."]);
            $conn->close();
            exit;
        }
    }

    // Return the data in JSON format with a status of success
    echo json_encode(["status" => "success", "data" => $allData]);
} else {
    // If the request method is not GET, return an error message
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

// Close the database connection after finishing
$conn->close();
?>
