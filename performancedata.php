
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
                            WHERE j.kategori = 'Performance'
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
