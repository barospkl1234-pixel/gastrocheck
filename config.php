<?php
// config.php
$host = "db.fr-pari1.bengt.wasmernet.com";
$username = "e5a20498714b8000a92ee2befda2"; 
$password = "0691e5a2-0498-7b31-8000-348fca14aa69";
$database = "user";

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

?>
