<?php
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

// Ambil data nama dan email dari form
$nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
$email = mysqli_real_escape_string($koneksi, $_POST['email']);
$email = mysqli_real_escape_string($koneksi, $_POST['email']);
$email = mysqli_real_escape_string($koneksi, $_POST['email']);
$created_at = date('Y-m-d H:i:s');

// Loop untuk menangkap semua jawaban yang dikirim dari form
foreach ($_POST as $key => $value) {
    if (strpos($key, 'jawaban_') === 0) {
        $id_pertanyaan = str_replace('jawaban_', '', $key);
        $id_jawaban = generateUUID(); // Buat UUID untuk id_jawaban

        // Query untuk memasukkan jawaban ke dalam database
        $query = "INSERT INTO tbl_jawaban (id_jawaban, nama, email, id_pertanyaan, jawaban, created_at) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "ssssss", $id_jawaban, $nama, $email, $id_pertanyaan, $value, $created_at);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Redirect ke halaman kuesioner dengan notifikasi sukses menggunakan JavaScript
echo "<script>
        alert('Terima kasih! Jawaban Anda telah dikirim.');
        window.location.href = 'kuesionerimportance.php';
      </script>";
exit;
?>
