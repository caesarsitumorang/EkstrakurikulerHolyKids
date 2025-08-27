<?php
$host = "localhost";      
$user = "root";           
$password = "";           
$database = "pendaftaran_ekstrakurikuler"; 

// Buat koneksi
$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
// echo "Koneksi berhasil!";
?>
