<?php
// $host = "localhost";  // Jika MySQL di server yang sama
// $user = "webhans_user";  // Username MySQL
// $pass = "passwordku123";  // Password MySQL
// $db   = "dbhans";
$host = "localhost";  // Jika MySQL di server yang sama
$user = "root";  // Username MySQL
$pass = "";  // Password MySQL
$db   = "dbhans";
// Koneksi ke MySQL
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
} else {
}
?>
