<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "iot_project";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => $conn->connect_error]));
}
?>