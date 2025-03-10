<?php
session_start();
require 'koneksi.php'; // Koneksi database
require 'vendor/autoload.php'; // Library PHPSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['upload'])) {
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
        "255c861f-eda2-4fc0-915f-cf85e031f1ae",
        "76efe634-d452-493e-8448-d4ff56abb9a8",
        "e22decb4-d7c1-47d8-b790-ebe9f88324bd",
        "69fb6275-74c0-4c7c-8870-6c79678939ad",
        "f9be3657-f52f-4abd-86d6-66f78d59ec06",
        "189ce4aa-b93d-4fd6-bd8e-5757567bd5d8",
        "457596a0-f353-4972-9fc7-630391b6ad65",
        "666762dd-b411-46a9-b116-55a9ec6e784e",
        "a5830e38-3808-4bc2-8d65-e3721be53fcb",
        "c308be53-0b47-4f8b-b8cd-a43caa56e6bb",
        "6fb8f21d-cc5b-41b7-aed0-c3c6aff09683",
        "5d224b5c-c654-4218-8227-b7eef08e1dd2",
        "fd487632-bb03-4f38-aecb-483055009a31",
        "27a552fa-9be0-424d-abfd-11a6fd052b32",
        "487b1bd6-c65c-4338-a99c-3b219ad8fc9e",
        "b3ffad04-c501-42f4-b8b5-153e130c7cf4",
        "878b555d-1fbb-4c50-b372-d2aa1152f14c",
        "cfbc2b6c-d517-4050-9e87-198d555de8c6",
        "32b31a6b-b116-4471-8e38-12bcb7f8383f",
        "e3fc3c80-1398-4d6d-bd1d-328954881c54",
        "347690d3-9ead-4d02-9af4-8b6fb012c26b",
        "d8b73db5-2aeb-4a4c-95f0-20631caef04b",
        "8bc404fc-d261-4084-b989-fb3007d79104",
        "06c669a9-73c2-4444-ae12-82d35eb7f1b0",
        "d26c8207-ecfb-4a6a-aad3-5d5b4193063b"
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
                      VALUES ('$id_jawaban', '$usia', '$jenis_kelamin', 'Performance', '$nama', '', '$id_pertanyaan_terpilih', $jawaban, NOW())";

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
