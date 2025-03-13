<?php
include 'koneksi.php'; // Pastikan koneksi sudah benar

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']); 
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = "member"; // Role default

    // Validasi data tidak boleh kosong
    if (empty($nama) || empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        die("<script>alert('Harap isi semua kolom!'); window.history.back();</script>");
    }

    // Periksa apakah password dan konfirmasi password cocok
    if ($password !== $confirm_password) {
        die("<script>alert('Konfirmasi password tidak cocok!'); window.history.back();</script>");
    }

    // Hash password sebelum disimpan ke database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query untuk menyimpan data ke database
    $query = "INSERT INTO login (username, passs, nama, email, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssss", $username, $hashed_password, $nama, $email, $role);
        $execute = mysqli_stmt_execute($stmt);

        if ($execute) {
            echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Registrasi gagal! Username atau email mungkin sudah digunakan.'); window.history.back();</script>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Terjadi kesalahan pada sistem.'); window.history.back();</script>";
    }

    // Tutup koneksi
    mysqli_close($koneksi);
}
?>
