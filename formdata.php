<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Pengajuan Cuti</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Favicons -->
  <link href="assets/img/logodilmil.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

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

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: May 30 2023 with Bootstrap v5.3.0
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>
<?php
include 'koneksi.php'; // Pastikan ada file koneksi database

// Ambil pertanyaan dari database
$query = "SELECT * FROM tbl_pertanyaan";
$result = mysqli_query($koneksi, $query);
?><?php
session_start();
if (empty($_SESSION['user']) || $_SESSION['role'] !== 'member') {
    header("Location: login.php");
    exit;
}
?>
<body>

<?php
include 'koneksi.php';
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user']; // Ambil username dari sesi login
$nama = $_SESSION['nama'];
$email = $_SESSION['email'];

// Ambil semua pertanyaan dari database
$queryPertanyaan = "SELECT * FROM tbl_pertanyaan";
$resultPertanyaan = mysqli_query($koneksi, $queryPertanyaan);

// Ambil jawaban user berdasarkan username
$queryJawaban = "SELECT id_pertanyaan, jawaban FROM tbl_jawaban WHERE username = ?";
$stmt = mysqli_prepare($koneksi, $queryJawaban);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$resultJawaban = mysqli_stmt_get_result($stmt);

// Simpan jawaban user dalam array untuk memudahkan pengecekan
$jawabanUser = [];
while ($row = mysqli_fetch_assoc($resultJawaban)) {
    $jawabanUser[$row['id_pertanyaan']] = $row['jawaban'];
}

mysqli_stmt_close($stmt);
?>
  <!-- Header -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="userPage.php" class="logo d-flex align-items-center">
        <img src="assets/img/logodilmil.png" alt="">
        <span class="d-none d-lg-block">Pengajuan Cuti</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="<?php echo htmlspecialchars($fotoUrl); ?>" alt="Profile" class="profile-img">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo htmlspecialchars($_SESSION['nama']); ?></span>
          </a>

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
      
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="user.php">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>
          </ul>
        </li>

      </ul>
    </nav>

  </header>


  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
    
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Pengajuan</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="formdata.php">
              <i class="bi bi-circle"></i><span>Form Pengajuan</span>
            </a>
          </li>
          <li>
            <a href="riwayatpengajuan.php">
              <i class="bi bi-circle"></i><span>Riwayat Pengajuan</span>
            </a>
          </li>
          
        </ul>
      </li><!-- End Forms Nav -->

      <!-- <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Riwayat Cuti</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="tabeldata.php">
              <i class="bi bi-circle"></i><span>Riwayat Cuti</span>
            </a>
          </li>
          -->
        </ul>
      </li><!-- End Tables Nav -->

    </ul>
  </aside><!-- End Sidebar-->

  
  <main id="main" class="main">
    <div class="pagetitle">
        <h1>Kuesioner</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Silahkan isi data berikut :</h5>

                        <form action="tambahjawaban.php" method="POST">
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Nama</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($nama); ?>" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="email" value="<?= htmlspecialchars($email); ?>" readonly>
                                </div>
                            </div>

                            <!-- Menampilkan pertanyaan dari database berdasarkan jenis_pertanyaan -->
                            <?php
                            $sudahMenjawabSemua = true; // Flag untuk cek apakah semua pertanyaan sudah dijawab
                            $jenis_sebelumnya = "";

                            // Query untuk mengambil pertanyaan dari database
                            $query = "SELECT id_pertanyaan, pertanyaan, jenis_pertanyaan FROM tbl_pertanyaan ORDER BY jenis_pertanyaan";
                            $resultPertanyaan = mysqli_query($koneksi, $query);

                            while ($row = mysqli_fetch_assoc($resultPertanyaan)) :
                                $id_pertanyaan = $row['id_pertanyaan'];
                                $pertanyaan = $row['pertanyaan'];
                                $jenis_pertanyaan = $row['jenis_pertanyaan']; // Menggunakan jenis_pertanyaan dari database
                                $jawaban = isset($jawabanUser[$id_pertanyaan]) ? $jawabanUser[$id_pertanyaan] : null;

                                if ($jawaban === null) {
                                    $sudahMenjawabSemua = false; // Set false jika ada pertanyaan yang belum dijawab
                                }

                                // Tampilkan jenis pertanyaan jika berubah
                                if ($jenis_pertanyaan !== $jenis_sebelumnya) {
                                    echo "<h5 class='mt-4'><strong>" . htmlspecialchars($jenis_pertanyaan) . "</strong></h5>";
                                    $jenis_sebelumnya = $jenis_pertanyaan;
                                }
                            ?>
                                <div class="row mb-3">
                                    <label class="col-sm-12 col-form-label"><?= htmlspecialchars($pertanyaan) ?></label>
                                    <div class="col-sm-12">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" 
                                                    name="jawaban_<?= $id_pertanyaan ?>" 
                                                    value="<?= $i ?>" 
                                                    <?= ($jawaban == $i) ? "checked" : "" ?> 
                                                    <?= ($jawaban !== null) ? "disabled" : "" ?> 
                                                    required>
                                                <label class="form-check-label"><?= $i ?></label>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>

                            <?php if (!$sudahMenjawabSemua) : ?>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Ajukan</button>
                                    </div>
                                </div>
                            <?php else : ?>
                                <div class="alert alert-success">Anda sudah menjawab semua pertanyaan. Tidak dapat mengubah jawaban.</div>
                            <?php endif; ?>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>


  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
