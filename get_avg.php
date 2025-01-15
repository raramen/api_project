<?php
// Include the koneksi.php file to set up the connection to the database
include 'koneksi.php';

// Set the header to ensure the API response is in JSON format
header("Content-Type: application/json");

// Function to sanitize input data
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags($data));
}

// Check if the request method received is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if the database connection is still active
    if (!isset($pdo) || $pdo === null) {
        die(json_encode(["status" => "error", "message" => "Database connection lost."]));
    }

    // Retrieve 'days' from GET parameters and sanitize it
    $days = isset($_GET['days']) ? intval(sanitizeInput($_GET['days'])) : 7; // Default to 7 days if not specified

    // Calculate the date 'n' days ago
    $dateInterval = new DateInterval('P' . $days . 'D');
    $pastDate = new DateTime();
    $pastDate->sub($dateInterval);
    $pastDateString = $pastDate->format('Y-m-d');

    try {
        $averages = [
            "average_speed" => null,
            "average_battery" => null,
            "average_water_level" => null
        ];

        // Query to calculate the average speed from the speed_data table within the last 'n' days
        $sql = "SELECT AVG(value) AS average_speed FROM speed_data WHERE created_at >= '$pastDateString'";
        $result = $pdo->query($sql);
        if ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $averages["average_speed"] = $row["average_speed"];
        }

        // Query to calculate the average battery capacity from the battery_data table within the last 'n' days
        $sql = "SELECT AVG(capacity) AS average_battery FROM battery_data WHERE created_at >= '$pastDateString'";
        $result = $pdo->query($sql);
        if ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $averages["average_battery"] = $row["average_battery"];
        }

        // Query to calculate the average water level from the water_level_data table within the last 'n' days
        $sql = "SELECT AVG(level) AS average_water_level FROM water_level_data WHERE created_at >= '$pastDateString'";
        $result = $pdo->query($sql);
        if ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $averages["average_water_level"] = $row["average_water_level"];
        }

        echo json_encode(["status" => "success", "averages" => $averages]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    // Handle non-GET requests
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

// Optionally close the connection
// $pdo = null; // Unsetting PDO to close the connection
?>