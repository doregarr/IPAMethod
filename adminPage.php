
<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';
?>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Data Pengguna</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Favicons -->
    <link href="assets/img/logodilmil.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .form-select {
            width: 150px;
        }
        .form-select option {
            white-space: nowrap;
        }
        .profile-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
    </style>
</head>
<?php
session_start();
if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// Menghitung jumlah pengisi kuesioner dan total skor dalam satu query
$query = "SELECT 
    COUNT(DISTINCT j.nama) AS total_responden, 
    j.id_pertanyaan, 
    COALESCE(p.pertanyaan, 'Pertanyaan tidak ditemukan') AS pertanyaan, 
    j.kategori, 
    SUM(j.jawaban) AS total_skor 
FROM tbl_jawaban j
LEFT JOIN tbl_pertanyaan p ON j.id_pertanyaan = p.id_pertanyaan
GROUP BY j.id_pertanyaan, p.pertanyaan, j.kategori";
$queryTotalResponden = "SELECT COUNT(DISTINCT nama) AS total_responden FROM tbl_jawaban";
$resultTotal = mysqli_query($koneksi, $queryTotalResponden);
$rowTotal = mysqli_fetch_assoc($resultTotal);
$totalResponden = $rowTotal['total_responden'] ?? 0;
$result = mysqli_query($koneksi, $query);

$pertanyaanList = []; // Simpan teks pertanyaan
$importanceScores = $performanceScores = [];

$totalSkorImportance = $totalSkorPerformance = 0;

while ($row = mysqli_fetch_assoc($result)) {
    if (!isset($row['id_pertanyaan']) || empty($row['id_pertanyaan'])) {
        $totalResponden = $row['total_responden']; // Simpan jumlah responden
    } else {
        $idPertanyaan = $row['id_pertanyaan'];
        $kategori = $row['kategori'];
        $pertanyaanList[$idPertanyaan] = $row['pertanyaan'] ?? 'Pertanyaan tidak ditemukan'; // Simpan pertanyaan

        if ($kategori === "Importance") {
            $importanceScores[$idPertanyaan] = $row['total_skor'];
            $totalSkorImportance += $row['total_skor'];
        } elseif ($kategori === "Performance") {
            $performanceScores[$idPertanyaan] = $row['total_skor'];
            $totalSkorPerformance += $row['total_skor'];
        }
    }
}


// Daftar UUID Importance dan Performance (sama)
$uuidList= [
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
    "a9671b66-5d41-46d7-967f-e7d2be52a37d"
];

?>



<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        <a href="adminPage.php" class="logo d-flex align-items-center">
            <img src="assets/img/logodilmil.png" alt="">
            <span class="d-none d-lg-block">Data Pengguna</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->
    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item d-block d-lg-none">
                <a class="nav-link nav-icon search-bar-toggle" href="#">
                    <i class="bi bi-search"></i>
                </a>
            </li><!-- End Search Icon-->
            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                <img src="<?php echo htmlspecialchars($fotoUrl); ?>" alt="Profile" class="profile-img">

                    <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo htmlspecialchars($_SESSION['nama']); ?></span>
                </a><!-- End Profile Image Icon -->
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6><?php echo htmlspecialchars($_SESSION['nama']); ?></h6>
                        <span><?php echo htmlspecialchars($_SESSION['role']); ?></span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="adminprofil.php">
                            <i class="bi bi-person"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="logout.php">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sign Out</span>
                        </a>
                    </li>
                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->
        </ul>
    </nav><!-- End Icons Navigation -->
</header><!-- End Header -->

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-journal-text"></i><span>Kuesioner</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="riwayatpengajuanAdminPage.php">
                        <i class="bi bi-circle"></i><span>Tambah Pertanyaan</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Forms Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-layout-text-window-reverse"></i><span>Tables</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="tabeldata.php">
                        <i class="bi bi-circle"></i><span>Tabel Data Jawaban</span>
                    </a>
                </li>
                <li>
                    <a href="performancedata.php">
                        <i class="bi bi-circle"></i><span>Tabel Data Performance</span>
                    </a>
                </li>
                <li>
                    <a href="importancedata.php">
                        <i class="bi bi-circle"></i><span>Tabel Data Importance</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Tables Nav -->
    </ul>
</aside><!-- End Sidebar -->

<!-- ======= Main ======= -->
<!-- ======= Main ======= -->
<!-- ======= Main ======= -->
<main id="main" class="main">
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-lg">
                        <div class="card-body text-center">
                            <h5 class="card-title mb-3">Total Skor</h5>
                            <p class="card-text">Klik tombol di bawah untuk melihat total skor Importance dan Performance.</p>
                            <div class="d-grid gap-3">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#skorModal">
                                    🔥🚀 Analisis IPA
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informasi</h5>
                            <p>Jumlah Pengisi Kuesioner: <strong><?php echo $totalResponden ?? 0; ?></strong> orang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Modal -->
<div class="modal fade" id="skorModal" tabindex="-1" aria-labelledby="skorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="skorModalLabel">🔥🚀 Total Skor & Rata-rata Importance, Performance, dan Quadrant Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Tab Navigasi -->
                <ul class="nav nav-tabs" id="skorTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="importance-tab" data-bs-toggle="tab" data-bs-target="#importance" type="button" role="tab">🔥 Importance</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="performance-tab" data-bs-toggle="tab" data-bs-target="#performance" type="button" role="tab">🚀 Performance</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="quadrant-tab" data-bs-toggle="tab" data-bs-target="#quadrant" type="button" role="tab">📊 Quadrant Data</button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-3" id="skorTabContent">
                    <!-- Tab Importance -->
<div class="tab-pane fade show active" id="importance" role="tabpanel">
    <ul class="list-group">
        <?php 
        $totalSkorImportance = 0;
        $totalRataRataImportance = 0;
        $jumlahPertanyaanImportance = count($uuidList);

        foreach ($uuidList as $index => $idPertanyaan) {
            if (isset($importanceScores[$idPertanyaan])) {
                $pertanyaan = isset($pertanyaanList[$idPertanyaan]) ? $pertanyaanList[$idPertanyaan] : "Pertanyaan tidak ditemukan";
                $totalSkor = $importanceScores[$idPertanyaan];
                $rataRata = $totalSkor / max(1, $totalResponden); // Mencegah pembagian dengan nol
                $totalSkorImportance += $totalSkor;
                $totalRataRataImportance += $rataRata;
        ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong><?php echo $pertanyaan; ?></strong>
                    <span class="badge bg-primary rounded-pill">
                        Total: <?php echo $totalSkor; ?> | Rata-rata: <?php echo number_format($rataRata, 2); ?>
                    </span>
                </li>
        <?php 
            }
        }
        ?>
    </ul>
    <hr>
    <div class="text-center">
        <h6><strong>🟢 Total Skor Keseluruhan:</strong> <?php echo $totalSkorImportance; ?></h6>
        <h6><strong>🔵 Rata-rata Keseluruhan:</strong> <?php echo number_format(($totalRataRataImportance / max(1, $jumlahPertanyaanImportance)), 2); ?></h6>
    </div>
</div>

<!-- Tab Performance -->
<div class="tab-pane fade" id="performance" role="tabpanel">
    <ul class="list-group">
        <?php 
        $totalSkorPerformance = 0;
        $totalRataRataPerformance = 0;
        $jumlahPertanyaanPerformance = count($uuidList);

        foreach ($uuidList as $index => $idPertanyaan) {
            if (isset($performanceScores[$idPertanyaan])) {
                $pertanyaan = isset($pertanyaanList[$idPertanyaan]) ? $pertanyaanList[$idPertanyaan] : "Pertanyaan tidak ditemukan";
                $totalSkor = $performanceScores[$idPertanyaan];
                $rataRata = $totalSkor / max(1, $totalResponden); // Mencegah pembagian dengan nol
                $totalSkorPerformance += $totalSkor;
                $totalRataRataPerformance += $rataRata;
        ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong><?php echo $pertanyaan; ?></strong>
                    <span class="badge bg-success rounded-pill">
                        Total: <?php echo $totalSkor; ?> | Rata-rata: <?php echo number_format($rataRata, 2); ?>
                    </span>
                </li>
        <?php 
            }
        }
        ?>
    </ul>
    <hr>
    <div class="text-center">
        <h6><strong>🟢 Total Skor Keseluruhan:</strong> <?php echo $totalSkorPerformance; ?></h6>
        <h6><strong>🔵 Rata-rata Keseluruhan:</strong> <?php echo number_format(($totalRataRataPerformance / max(1, $jumlahPertanyaanPerformance)), 2); ?></h6>
    </div>
</div>

                   <!-- Tab Quadrant -->
<div class="tab-pane fade" id="quadrant" role="tabpanel">
    <h5 class="text-center">📊 Quadrant Data</h5>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Pertanyaan</th>
                <th>Importance Score</th>
                <th>Performance Score</th>
                <th>Quadrant</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Hitung rata-rata importance & performance
            $avgImportance = !empty($importanceScores) ? array_sum($importanceScores) / count($importanceScores) : 0;
            $avgPerformance = !empty($performanceScores) ? array_sum($performanceScores) / count($performanceScores) : 0;

            foreach ($uuidList as $index => $idPertanyaan) {
                $nomorPertanyaan = "P" . ($index + 1);
                $importanceScore = isset($importanceScores[$idPertanyaan]) ? $importanceScores[$idPertanyaan] : 0;
                $performanceScore = isset($performanceScores[$idPertanyaan]) ? $performanceScores[$idPertanyaan] : 0;

                // Tentukan kuadran berdasarkan rata-rata
                if ($importanceScore >= $avgImportance && $performanceScore >= $avgPerformance) {
                    $quadrant = "I - Keep Up the Good Work 🟢";
                } elseif ($importanceScore >= $avgImportance && $performanceScore < $avgPerformance) {
                    $quadrant = "II - Concentrate Here 🔴";
                } elseif ($importanceScore < $avgImportance && $performanceScore < $avgPerformance) {
                    $quadrant = "III - Low Priority 🟡";
                } else {
                    $quadrant = "IV - Possible Overkill 🔵";
                }
            ?>
                <tr>
                    <td><?php echo $nomorPertanyaan; ?></td>
                    <td><?php echo $importanceScore; ?></td>
                    <td><?php echo $performanceScore; ?></td>
                    <td><?php echo $quadrant; ?></td>
                </tr>
            <?php 
            } 
            ?>
        </tbody>
    </table>
</div>

<!-- Tambahkan Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById("ipaChart").getContext("2d");
        var ipaChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Atribut 1", "Atribut 2", "Atribut 3", "Atribut 4"],
                datasets: [{
                    label: "Nilai Kepentingan",
                    data: [4.5, 3.8, 4.2, 4.0], // Ganti dengan nilai sesuai
                    backgroundColor: "rgba(54, 162, 235, 0.5)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 1
                }, {
                    label: "Nilai Kinerja",
                    data: [3.8, 4.0, 3.5, 4.2], // Ganti dengan nilai sesuai
                    backgroundColor: "rgba(255, 99, 132, 0.5)",
                    borderColor: "rgba(255, 99, 132, 1)",
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5
                    }
                }
            }
        });
    });
</script>



<!-- Edit Modal -->



<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/vendor/quill/quill.min.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="assets/vendor/tinymce/tinymce.min.js"></script>
<script src="assets/vendor/jquery/jquery.min.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>


<!-- Your existing code... -->



</body>
</html>
