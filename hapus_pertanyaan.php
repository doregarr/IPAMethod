<?php
include("koneksi.php");

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_pertanyaan = $_GET['id'];

    $query = "DELETE FROM tbl_pertanyaan WHERE id_pertanyaan = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("s", $id_pertanyaan);

    if ($stmt->execute()) {
        echo "<script>alert('Pertanyaan berhasil dihapus!'); window.location.href='riwayatpengajuanAdminPage.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus pertanyaan!'); window.location.href='riwayatpengajuanAdminPage.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='riwayatpengajuanAdminPage.php';</script>";
}

$koneksi->close();
?>
