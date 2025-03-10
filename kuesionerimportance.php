<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Kuesioner</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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

  <!-- Custom CSS for profile image -->
  <style>
    .profile-img {
      border-radius: 50%;
      width: 40px;
      height: 40px;
      object-fit: cover;
    }
  </style>
</head>

<body>

  <!-- Header -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="userPage.php" class="logo d-flex align-items-center">
        <img src="assets/img/logodilmil.png" alt="">
        <span class="d-none d-lg-block">Kuesioner</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          
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

  <!-- Sidebar -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Daftar Kuesioner</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="kuesionerimportance.php">
              <i class="bi bi-circle"></i><span>Kuesioner Importance</span>
            </a>
          </li>
          <li>
            <a href="kuesionerperformance.php">
              <i class="bi bi-circle"></i><span>Kuesioner Performance</span>
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </aside>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<?php
include 'koneksi.php';

// Ambil semua pertanyaan dengan kategori "Performance" dari database
$queryPertanyaan = "SELECT * FROM tbl_pertanyaan WHERE kategori = 'Importance' ORDER BY jenis_pertanyaan";
$resultPertanyaan = mysqli_query($koneksi, $queryPertanyaan);
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Kuesioner Importance</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Silahkan isi data berikut :</h5>

                        <form action="tambahjawabanimportance.php" method="POST">
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Nama</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="nama" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-6">
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                            </div>
                            <div class="row mb-3">
    <label class="col-sm-2 col-form-label">Usia</label>
    <div class="col-sm-6">
        <input type="number" class="form-control" name="usia" required>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label">Jenis Kelamin</label>
    <div class="col-sm-6">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="jenis_kelamin" value="Laki-laki" required>
            <label class="form-check-label">Laki-laki</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="jenis_kelamin" value="Perempuan" required>
            <label class="form-check-label">Perempuan</label>
        </div>
    </div>
</div>

                            <?php
                            $jenis_sebelumnya = "";
                            while ($row = mysqli_fetch_assoc($resultPertanyaan)) :
                                $id_pertanyaan = $row['id_pertanyaan'];
                                $pertanyaan = $row['pertanyaan'];
                                $jenis_pertanyaan = $row['jenis_pertanyaan'];

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
                                                    required>
                                                <label class="form-check-label"><?= $i ?></label>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Ajukan</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
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
