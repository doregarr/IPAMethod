
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

// Pencarian berdasarkan nama dan kategori
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$kategoriFilter = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Pagination
$limit = 25;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Membuat klausa WHERE sesuai dengan filter pencarian
$whereClause = "WHERE 1=1";
if (!empty($search)) {
    $whereClause .= " AND j.nama LIKE '%$search%'";
}
if (!empty($kategoriFilter) && in_array($kategoriFilter, ['Performance', 'Importance'])) {
    $whereClause .= " AND j.kategori = '$kategoriFilter'";
}

// Query untuk mendapatkan total data
$totalQuery = "SELECT COUNT(*) AS total FROM tbl_jawaban j 
               JOIN tbl_pertanyaan p ON j.id_pertanyaan = p.id_pertanyaan 
               $whereClause";
$totalResult = mysqli_query($koneksi, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalData = $totalRow['total'];
$totalPages = ceil($totalData / $limit);

// Query untuk mendapatkan data sesuai halaman
$query = "SELECT j.id_jawaban, j.nama, j.email, p.pertanyaan, j.kategori, j.jawaban, j.created_at 
          FROM tbl_jawaban j 
          JOIN tbl_pertanyaan p ON j.id_pertanyaan = p.id_pertanyaan
          $whereClause
          ORDER BY j.id_jawaban DESC 
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($koneksi, $query);
?>

<body>
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
<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Kuesioner Performance</h5>

                        <!-- Form Pencarian -->
                       
                        <?php
                        include 'koneksi.php';

                        if (!$koneksi) {
                            die("Koneksi gagal: " . mysqli_connect_error());
                        }

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

                        $sql = "
                            SELECT 
                                j.nama,
                                MAX(j.usia) AS usia, 
                                MAX(j.jeniskelamin) AS jeniskelamin, ";

                        foreach ($id_pertanyaan as $index => $id) {
                            $p_num = $index + 1;
                            $sql .= " MAX(CASE WHEN j.id_pertanyaan = '$id' THEN j.jawaban END) AS p$p_num,";
                        }

                        $sql = rtrim($sql, ',') . "
                            FROM tbl_jawaban j
                            WHERE j.kategori = 'Importance'
                            GROUP BY j.nama
                            ORDER BY j.nama ASC
                            LIMIT 200;
                        ";

                        $result = mysqli_query($koneksi, $sql);

                        if (!$result) {
                            die("Query error: " . mysqli_error($koneksi));
                        }
                        ?>

                        <table class="table table-striped table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Usia</th>
                                    <th>Jenis Kelamin</th>
                                    <?php for ($i = 1; $i <= 25; $i++): ?>
                                        <th>P<?php echo $i; ?></th>
                                    <?php endfor; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($result) > 0) {
                                    $i = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>{$i}</td>";
                                        echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['usia']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['jeniskelamin']) . "</td>";

                                        for ($j = 1; $j <= 25; $j++) {
                                            $p_key = "p$j";
                                            echo "<td>" . (isset($row[$p_key]) ? htmlspecialchars($row[$p_key]) : '-') . "</td>";
                                        }

                                        echo "</tr>";
                                        $i++;
                                    }
                                } else {
                                    echo "<tr><td colspan='28' class='text-center'>Belum ada jawaban</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>



                        <!-- Pagination -->
         



                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<!-- Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Impor Data Performance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" action="import_performance.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="file_excel" class="form-label">Pilih file Excel:</label>
                        <input type="file" name="file_excel" id="file_excel" class="form-control" required>
                    </div>
                    <button type="submit" name="upload" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap & jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</script>

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
