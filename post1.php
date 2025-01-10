<?php
// Mengimpor file koneksi untuk terhubung ke database
include 'koneksi.php';
require('phpMQTT.php'); // Mengimpor pustaka phpMQTT

// Konfigurasi MQTT
$server = 'broker.hivemq.com'; // Alamat broker MQTT
$port = 1883; // Port broker MQTT
$username = ''; // Username MQTT (opsional)
$password = ''; // Password MQTT (opsional)
$client_id = '6f73d100-cf4b-4e49-9133-d512d38b430d' . uniqid(); // ID unik untuk klien MQTT

// Menentukan header untuk respons dalam format JSON
header("Content-Type: application/json");

// Membuat koneksi ke broker MQTT
$mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);

if (!$mqtt->connect(true, NULL, $username, $password)) {
    echo json_encode(["status" => "error", "message" => "Failed to connect to MQTT broker"]);
    exit;
}

// Variabel untuk menyimpan data dari topik MQTT
$speed = null;
$battery = null;

// Fungsi callback untuk menerima pesan dari topik
$mqtt->subscribe(['rc/baterailevel' => 0, 'rc/speed' => 0], function ($topic, $msg) use (&$speed, &$battery) {
    if ($topic === 'rc/baterailevel') {
        $battery = floatval($msg); // Mengonversi pesan ke angka desimal
    } elseif ($topic === 'rc/speed') {
        $speed = floatval($msg); // Mengonversi pesan ke angka desimal
    }
});

// Menunggu data diterima dari broker (timeout 10 detik)
$startTime = time();
while (is_null($speed) || is_null($battery)) {
    $mqtt->proc();
    if (time() - $startTime > 10) { // Timeout setelah 10 detik
        echo json_encode(["status" => "error", "message" => "Timeout while waiting for MQTT messages"]);
        $mqtt->close();
        exit;
    }
}

// Tutup koneksi MQTT
$mqtt->close();

// Validasi input: pastikan 'speed' dan 'battery' tidak null
if (is_null($speed) || is_null($battery)) {
    echo json_encode(["status" => "error", "message" => "Failed to retrieve speed and battery data"]);
    exit;
}

// Menyiapkan statement SQL untuk menyisipkan data ke tabel 'sensor_data'
$stmt = $conn->prepare("INSERT INTO sensor_data (speed, battery) VALUES (?, ?)");
$stmt->bind_param("dd", $speed, $battery); // 'dd' menunjukkan bahwa parameter adalah angka desimal (double)

// Mengeksekusi statement SQL
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Data saved successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}

// Menutup statement SQL
$stmt->close();

// Menutup koneksi database
$conn->close();
?>