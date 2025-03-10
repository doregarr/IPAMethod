
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
                        <h5 class="card-title">Data Jawaban</h5>

                        <!-- Form Pencarian -->
                        <form method="GET" action="">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="text" name="search" id="searchInput" class="form-control" 
                                           placeholder="Cari berdasarkan nama..." value="<?php echo htmlspecialchars($search); ?>">
                                </div>
                                <div class="col-md-3">
                                    <select name="kategori" class="form-select">
                                        <option value="">Semua Kategori</option>
                                        <option value="Performance" <?php echo ($kategoriFilter == 'Performance') ? 'selected' : ''; ?>>Performance</option>
                                        <option value="Importance" <?php echo ($kategoriFilter == 'Importance') ? 'selected' : ''; ?>>Importance</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">Cari</button>
                                   
                                </div>
                                <div class="col-md-6">
   
 <!-- Tombol untuk membuka modal -->
<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadModal">
    Impor Performance
</button>

<button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#uploadModal1">
    Impor Importance
</button>

                            </div>
                            
                        </form>

                        <!-- Tabel Data -->
                        <table class="table table-striped table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Pertanyaan</th>
                                    <th>Kategori</th>
                                    <th>Jawaban</th>
                                    <th>Dijawab Pada</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($result) > 0) {
                                    $i = $offset + 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>{$i}</td>";
                                        echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['pertanyaan']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['kategori']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['jawaban']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                        echo "</tr>";
                                        $i++;
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center'>Belum ada jawaban</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <nav>
    <ul class="pagination">
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?search=<?php echo urlencode($search); ?>&kategori=<?php echo urlencode($kategoriFilter); ?>&page=<?php echo $page - 1; ?>">Sebelumnya</a>
            </li>
        <?php endif; ?>

        <!-- Halaman pertama -->
        <li class="page-item <?php echo ($page == 1) ? 'active' : ''; ?>">
            <a class="page-link" href="?search=<?php echo urlencode($search); ?>&kategori=<?php echo urlencode($kategoriFilter); ?>&page=1">1</a>
        </li>

        <?php if ($page > 3): ?>
            <li class="page-item disabled"><span class="page-link">...</span></li>
        <?php endif; ?>

        <!-- Menampilkan 2 halaman sebelum dan sesudah halaman aktif -->
        <?php for ($i = max(2, $page - 2); $i <= min($totalPages - 1, $page + 2); $i++): ?>
            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                <a class="page-link" href="?search=<?php echo urlencode($search); ?>&kategori=<?php echo urlencode($kategoriFilter); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $totalPages - 2): ?>
            <li class="page-item disabled"><span class="page-link">...</span></li>
        <?php endif; ?>

        <!-- Halaman terakhir -->
        <?php if ($totalPages > 1): ?>
            <li class="page-item <?php echo ($page == $totalPages) ? 'active' : ''; ?>">
                <a class="page-link" href="?search=<?php echo urlencode($search); ?>&kategori=<?php echo urlencode($kategoriFilter); ?>&page=<?php echo $totalPages; ?>"><?php echo $totalPages; ?></a>
            </li>
        <?php endif; ?>

        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="?search=<?php echo urlencode($search); ?>&kategori=<?php echo urlencode($kategoriFilter); ?>&page=<?php echo $page + 1; ?>">Selanjutnya</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>


                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<!-- Modal 1-->
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
<!-- Modal 2-->
<div class="modal fade" id="uploadModal1" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Impor Data Importance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" action="import_importance.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="file_excel" class="form-label">Pilih file Excel:</label>
                        <input type="file" name="file_excel" id="file_excel" class="form-control" required>
                    </div>
                    <button type="submit" name="upload1" class="btn btn-primary">Upload</button>
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
