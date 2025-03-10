<?php
include("koneksi.php"); // Pastikan file koneksi ke database tersedia

// Fungsi untuk menghasilkan UUID v4
function generateUUID() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pertanyaan = generateUUID();
    $pertanyaan = trim($_POST["pertanyaan"]);
    $type = "radio";
    $jenis_pertanyaan = trim($_POST["jenis_pertanyaan"]);
    $kategori_pertanyaan = trim($_POST["kategori_pertanyaan"]); // Tambahkan kategori
    $created_at = date("Y-m-d H:i:s");

    // Validasi input tidak boleh kosong
    if (empty($pertanyaan) || empty($type) || empty($jenis_pertanyaan) || empty($kategori_pertanyaan)) {
        echo "<script>alert('Semua field harus diisi!'); window.location.href='index.php';</script>";
        exit();
    }

    // Query untuk memasukkan data ke database
    $query = "INSERT INTO tbl_pertanyaan (id_pertanyaan, pertanyaan, type, jenis_pertanyaan, kategori, created_at) 
              VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("ssssss", $id_pertanyaan, $pertanyaan, $type, $jenis_pertanyaan, $kategori_pertanyaan, $created_at);

    if ($stmt->execute()) {
        echo "<script>alert('Pertanyaan berhasil ditambahkan!'); window.location.href='riwayatpengajuanAdminPage.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan pertanyaan!'); window.location.href='index.php';</script>";
    }

    $stmt->close();
    $koneksi->close();
} else {
    echo "<script>alert('Akses tidak sah!'); window.location.href='index.php';</script>";
}
?>
