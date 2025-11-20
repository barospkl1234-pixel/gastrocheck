<?php
// config.php
$host = "db.fr-pari1.bengt.wasmernet.com";
$port = "10272"; 
$username = "e9d7f9f572e480006d6d45eb88a6"; 
$password = "0691e9d7-f9f5-74a7-8000-1f06d3903053";
$database = "user_app";

// Membuat koneksi
$conn = new mysqli($host,$port, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");
?>

