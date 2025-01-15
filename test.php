<?php
// Include the database connection file
include 'koneksi.php';

header('Content-Type: application/json');

// Function to handle GET requests
function handleGetRequest($pdo) {
    $results = [];
    $queries = [
        'water_level' => "SELECT * FROM water_level_data ORDER BY id DESC",
        'speed' => "SELECT * FROM speed_data ORDER BY id DESC",
        'battery' => "SELECT * FROM battery_data ORDER BY id DESC"
    ];

    foreach ($queries as $key => $query) {
        try {
            $stmt = $pdo->query($query);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $results[$key] = $data ? $data : "No data found for $key.";
        } catch (PDOException $e) {
            $results[$key] = "Failed to fetch data for $key: " . $e->getMessage();
        }
    }

    echo json_encode([
        'method' => 'GET',
        'message' => 'Fetched data from multiple tables.',
        'data' => $results
    ]);
}

// Function to handle POST requests
function handlePostRequest($pdo) {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!empty($data['battery'])) {
        $stmt = $pdo->prepare("INSERT INTO battery_data (capacity) VALUES (:capacity)");
        $stmt->execute(['capacity' => $data['battery']]);
        echo json_encode(['message' => 'Battery data inserted successfully.']);
    } elseif (!empty($data['water_level'])) {
        $stmt = $pdo->prepare("INSERT INTO water_level_data (level) VALUES (:level)");
        $stmt->execute(['level' => $data['water_level']]);
        echo json_encode(['message' => 'Water level data inserted successfully.']);
    } elseif (!empty($data['speed'])) {
        $stmt = $pdo->prepare("INSERT INTO speed_data (value) VALUES (:value)");
        $stmt->execute(['value' => $data['speed']]);
        echo json_encode(['message' => 'Speed data inserted successfully.']);
    } else {
        echo json_encode(['error' => 'No valid data provided for insertion.']);
    }
}

// Determine the type of HTTP request
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        handleGetRequest($pdo);
        break;
    case 'POST':
        handlePostRequest($pdo);
        break;
    default:
        echo json_encode(['error' => 'Unsupported HTTP method. Please use GET or POST.']);
}

// Optionally close the connection
// $pdo = null; // Unsetting PDO to close the connection
?>
