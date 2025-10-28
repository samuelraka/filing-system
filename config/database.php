<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "archives_db"; // sesuaikan nama database kamu

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode([
        "success" => false,
        "message" => "Koneksi gagal: " . $conn->connect_error
    ]));
}

$conn->set_charset("utf8");
?>
