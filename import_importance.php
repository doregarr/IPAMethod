<?php
session_start();
require 'koneksi.php'; // Koneksi database
require 'vendor/autoload.php'; // Library PHPSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['upload1'])) {
    $file = $_FILES['file_excel']['tmp_name'];

    if (!$file) {
        echo "<script>alert('Harap pilih file Excel.'); window.location.href='tabeldata.php';</script>";
        exit;
    }

    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray();

    // ID Pertanyaan untuk P1 - P25
    $id_pertanyaan = [
        "acb2f014-f5f3-4f48-a74f-4eb7dd0f9d85",
        "40a9dbee-7a3e-4ac1-ad45-b3bd32b5f73d",
        "51ccb676-df9e-4ea4-8f54-109107bd8c4d",
        "229a7831-f3ef-417c-9ba5-0cead51c5e3b",
        "3327bfce-d370-4110-aef3-c84be6826864",
        "c4329bd3-783c-4335-aaed-1393303e526e",
        "465c1d8d-d97a-4887-8f32-4c40ceb3413b",
        "0f47fadb-63dc-4c60-9315-8f70302c416e",
        "28130103-811f-4cf4-bc86-3fffbfc3f423",
        "3cb7ff71-a996-4d77-a7b7-e9ea6ef61103",
        "6138a740-2f9b-4ed7-a660-d2150fb1c678",
        "413661a8-4171-4a9a-8b0e-d27444798fab",
        "b0437a21-7441-4bc0-80e2-e4a17ac5c3b7",
        "cad414eb-480e-40ca-bb49-15b9e9f7b055",
        "b46c242a-1464-4e6e-bc27-447931ddeb21",
        "9c912e9f-2434-4daa-8fd7-4bf1769be08d",
        "c86ed088-f842-40db-829f-3318606271d4",
        "03ef1ded-26d1-4a84-97a4-971546f76e2e",
        "aae3a995-0cab-4320-9035-237496720def",
        "c39c64a7-ad0a-4366-bf86-f3d69e10c1ad",
        "fc9aecc5-b5db-4dde-9462-2aa592b842f4",
        "93a37773-b66c-4249-bd5b-6d6e3f1bccb6",
        "17f722b4-7650-4739-aaf5-dcd9a879903c",
        "f34d8542-666a-43d2-8d57-af59c3dd1e75",
        "e3c50278-1d33-46a4-a2da-489f6dd536dc"
    ];

    array_shift($data); // Hapus header

    $success = 0;
    $failed = 0;

    foreach ($data as $row) {
        $nama = mysqli_real_escape_string($koneksi, $row[0]);
        $usia = $row[1];
        $jenis_kelamin = mysqli_real_escape_string($koneksi, $row[2]);

        for ($i = 0; $i < 25; $i++) {
            $id_jawaban = uniqid();
            $id_pertanyaan_terpilih = $id_pertanyaan[$i];

            // **Validasi dan konversi jawaban ke INT**
            $jawaban = isset($row[$i + 3]) ? trim($row[$i + 3]) : 0;
            if (!is_numeric($jawaban) || $jawaban === "") {
                $jawaban = 0; // Set ke 0 jika tidak valid
            } else {
                $jawaban = (int)$jawaban; // Paksa menjadi integer
            }

            // **Insert ke database**
            $query = "INSERT INTO tbl_jawaban (id_jawaban, usia, jeniskelamin, kategori, nama, email, id_pertanyaan, jawaban, created_at)
                      VALUES ('$id_jawaban', '$usia', '$jenis_kelamin', 'Importance', '$nama', '', '$id_pertanyaan_terpilih', $jawaban, NOW())";

            if (mysqli_query($koneksi, $query)) {
                $success++;
            } else {
                $failed++;
            }
        }
    }

    echo "<script>alert('Impor selesai: $success data berhasil, $failed data gagal.'); window.location.href='tabeldata.php';</script>";
    exit;
}
?>
