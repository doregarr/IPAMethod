<?php
include 'koneksi.php'; // Pastikan file koneksi.php ada dan benar

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["csvFile"])) {
    $file = $_FILES["csvFile"]["tmp_name"];
    
    if (($handle = fopen($file, "r")) !== FALSE) {
        fgetcsv($handle); // Lewati baris header
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (count($data) < 5) {
                continue; // Lewati baris yang tidak memiliki cukup kolom
            }
            
            $username = $koneksi->real_escape_string(trim($data[0]));
            $password = trim($data[1]);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $nama = $koneksi->real_escape_string(trim($data[2]));
            $email = $koneksi->real_escape_string(trim($data[3]));
            $role = $koneksi->real_escape_string(trim($data[4]));
            
            $sql = "INSERT INTO login (username, passs, nama, email, role) VALUES (?, ?, ?, ?, ?)";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("sssss", $username, $hashed_password, $nama, $email, $role);
            $stmt->execute();
        }
        
        fclose($handle);
        echo "Upload dan insert data berhasil.";
    } else {
        echo "Gagal membaca file.";
    }
} else {
    echo "Harap unggah file CSV.";
}

$koneksi->close();
?>
