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

// Menghitung jumlah pengisi kuesioner
$queryJumlah = "SELECT COUNT(DISTINCT email) AS total_responden FROM tbl_jawaban";
$resultJumlah = mysqli_query($koneksi, $queryJumlah);
$rowJumlah = mysqli_fetch_assoc($resultJumlah);
$totalResponden = $rowJumlah['total_responden'];
?>
<body>


<!-- ======= Header ======= -->
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
                    <a href="tambahdata.php">
                        <i class="bi bi-circle"></i><span>Tabel Data Performance</span>
                    </a>
                </li>
                <li>
                    <a href="tambahdata.php">
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
            <h5 class="card-title">Tambah Pertanyaan Kuesioner</h5>
            <form action="tambahpertanyaan.php" method="POST">
              <div class="mb-3">
                <label for="pertanyaan" class="form-label">Pertanyaan</label>
                <input type="text" class="form-control" id="pertanyaan" name="pertanyaan" required>
              </div>
              
           

              <div class="mb-3">
                <label for="jenis_pertanyaan" class="form-label">Jenis Pertanyaan</label>
                <select class="form-select" id="jenis_pertanyaan" name="jenis_pertanyaan" required>
                  <option value="Tampilan">Tampilan</option>
                  <option value="Kehandalan">Kehandalan</option>
                  <option value="Daya Tanggap">Daya Tanggap</option>
                  <option value="Jaminan">Jaminan</option>
                  <option value="Empati">Empati</option>
                </select>
              </div>
              <div class="mb-3">
  <label for="kategori_pertanyaan" class="form-label">Kategori Pertanyaan</label>
  <select class="form-select" id="kategori_pertanyaan" name="kategori_pertanyaan" required>
    <option value="Performance">Performance</option>
    <option value="Importance">Importance</option>
  </select>
</div>
              <button type="submit" class="btn btn-primary">Tambah Pertanyaan</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Daftar Pertanyaan Kuesioner</h5>
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Pertanyaan</th>
          
                  <th scope="col">Jenis</th>
                  <th scope="col">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                include("koneksi.php");
                $query = "SELECT * FROM tbl_pertanyaan ORDER BY created_at DESC";
                $result = $koneksi->query($query);
                $i = 1;
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<th scope='row'>" . $i++ . "</th>";
                  echo "<td>" . htmlspecialchars($row['pertanyaan']) . "</td>";
               
                  echo "<td>" . htmlspecialchars($row['jenis_pertanyaan']) . "</td>";
               
                  echo "<td><a href='hapus_pertanyaan.php?id=" . urlencode($row['id_pertanyaan']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a></td>";

                  echo "</tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>


<!-- ======= Footer ======= -->
<!-- <footer id="footer" class="footer">
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <div class="copyright">
          &copy; <strong><span>2024</span></strong>
        </div>
        <div class="credits">

          Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
      </div>
    </div>
  </div>
</footer> -->

<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/vendor/tinymce/tinymce.min.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="assets/vendor/jquery/jquery.min.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>
</body>
</html>
