<?php
// Include the database connection file
include 'koneksi.php';

header('Content-Type: application/json');

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags($data));
}

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get the number of days from the GET request, sanitize it, and set a default of 7 days if not specified
    $days = isset($_GET['days']) ? sanitizeInput($_GET['days']) : 7;
    $days = is_numeric($days) ? intval($days) : 7; // Ensure the days input is an integer

    // SQL query to retrieve the maximum values and their timestamps within the specified number of days
    $sql = "SELECT 
                (SELECT MAX(value) FROM speed_data WHERE created_at >= NOW() - INTERVAL $days DAY) AS max_speed, 
                (SELECT created_at FROM speed_data 
                 WHERE ROUND(value, 2) = ROUND((SELECT MAX(value) FROM speed_data WHERE created_at >= NOW() - INTERVAL $days DAY), 2)
                 AND created_at >= NOW() - INTERVAL $days DAY 
                 LIMIT 1) AS date_max_speed, 
                (SELECT MAX(capacity) FROM battery_data WHERE created_at >= NOW() - INTERVAL $days DAY) AS max_battery, 
                (SELECT created_at FROM battery_data 
                 WHERE ROUND(capacity, 2) = ROUND((SELECT MAX(capacity) FROM battery_data WHERE created_at >= NOW() - INTERVAL $days DAY), 2)
                 AND created_at >= NOW() - INTERVAL $days DAY 
                 LIMIT 1) AS date_max_battery,
                (SELECT MAX(level) FROM water_level_data WHERE created_at >= NOW() - INTERVAL $days DAY) AS max_water_level,
                (SELECT created_at FROM water_level_data 
                 WHERE level = (SELECT MAX(level) FROM water_level_data WHERE created_at >= NOW() - INTERVAL $days DAY)
                 AND created_at >= NOW() - INTERVAL $days DAY 
                 LIMIT 1) AS date_max_water_level
           ";

    // Execute the query
    $result = $pdo->query($sql);

    // Check if the result is valid
    if ($result) {
        // Fetch the result as an associative array
        $data = $result->fetch(PDO::FETCH_ASSOC);
        // Return the query results in JSON format
        echo json_encode(["status" => "success", "data" => $data]);
    } else {
        // Handle SQL error
        echo json_encode(["status" => "error", "message" => "Failed to retrieve data: " . $pdo->error]);
    }
} else {
    // Return an error message if the request method is not GET
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

// Optionally close the connection
$pdo = null; // Unsetting PDO to close the connection
?>