<?php
ob_start(); // Memulai output buffering
include 'koneksi.php';

// Fungsi untuk membuat UUID secara manual
function generateUUID() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

// Ambil data dari form
$nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
$email = mysqli_real_escape_string($koneksi, $_POST['email']);
$usia = mysqli_real_escape_string($koneksi, $_POST['usia']);
$jenkel = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
$created_at = date('Y-m-d H:i:s');

// Ambil kategori dari form (berbeda untuk tiap halaman)
$kategori = basename($_SERVER['PHP_SELF']) == "tambahjawabanimportance.php" ? "Importance" : "Performance";

// Loop untuk mengecek apakah email sudah menjawab pertanyaan tertentu dalam kategori yang sama
foreach ($_POST as $key => $value) {
    if (strpos($key, 'jawaban_') === 0) {
        $id_pertanyaan = str_replace('jawaban_', '', $key);

        // Cek apakah email sudah menjawab pertanyaan ini dalam kategori yang sama
        $cek_email_query = "SELECT email FROM tbl_jawaban WHERE email = ? AND id_pertanyaan = ? AND kategori = ?";
        $stmt = mysqli_prepare($koneksi, $cek_email_query);
        mysqli_stmt_bind_param($stmt, "sss", $email, $id_pertanyaan, $kategori);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            mysqli_stmt_close($stmt);
            echo "<script>
                    alert('Anda sudah menjawab pertanyaan ini dalam kategori $kategori! Silakan gunakan email lain atau isi kategori lain.');
                    window.location.href = 'kuesionerperformance.php';
                  </script>";
            ob_end_flush();
            exit;
        }
        mysqli_stmt_close($stmt);
    }
}

// Jika email belum menjawab pertanyaan dalam kategori yang sama, simpan jawaban
foreach ($_POST as $key => $value) {
    if (strpos($key, 'jawaban_') === 0) {
        $id_pertanyaan = str_replace('jawaban_', '', $key);
        $id_jawaban = generateUUID();

        // Simpan ke database
        $query = "INSERT INTO tbl_jawaban (id_jawaban, usia, jeniskelamin, nama, email, id_pertanyaan, kategori, jawaban, created_at) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "sssssssss", $id_jawaban, $usia, $jenkel, $nama, $email, $id_pertanyaan, $kategori, $value, $created_at);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Jika sukses
echo "<script>
        alert('Terima kasih! Jawaban Anda telah dikirim.');
        window.location.href = 'kuesionerperformance.php';
      </script>";
ob_end_flush();
exit;
