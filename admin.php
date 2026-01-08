<?php
session_start();

include "koneksi.php";

//check jika belum ada user yang login arahkan ke halaman login
if (!isset($_SESSION['username'])) {
  header("location:login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!-- Bootstrap -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    rel="stylesheet" />

  <!-- CSS ADMIN -->
  <link rel="stylesheet" href="admin.css" />

  <title>Admin Panel</title>
</head>

<body>
  <!-- TOP NAVBAR -->
  <header class="topbar">
    <div
      class="container-fluid px-3 h-100 d-flex align-items-center justify-content-between">
      <!-- kiri: logo + ADMIN -->
      <div class="d-flex align-items-center gap-2">
        <div class="brand-badge">A</div>
        <div class="brand-text">ADMIN</div>
      </div>

      <!-- user admin (icon + text admin) -->
      <div class="dropdown">
        <a
          class="top-link dropdown-toggle"
          href="#"
          role="button"
          data-bs-toggle="dropdown"
          aria-expanded="false">
          <i class="bi bi-person-circle me-1"></i> admin
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Pengaturan</a>
          </li>
          <li>
            <a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
          </li>
        </ul>
      </div>
      </nav>
    </div>
  </header>

  <!-- BODY -->
  <div class="layout">
    <!-- SIDEBAR -->
    <aside class="sidebar">
      <a class="side-btn" href="admin.php" title="Home Admin">
        <i class="bi bi-grid"></i>
      </a>

      <a class="side-btn" href="admin.php?page=dashboard" title="Dashboard">
        <i class="bi bi-speedometer2"></i>
      </a>

      <a class="side-btn" href="admin.php?page=article" title="Article">
        <i class="bi bi-journal-text"></i>
      </a>
      <a class="side-btn" href="admin.php?page=gallery" title="Gallery">
        <i class="bi bi-images"></i>
      </a>

      <a class="side-btn" href="admin.php?page=settings" title="Settings">
        <i class="bi bi-gear"></i>
      </a>
    </aside>

    <!-- MAIN -->
    <main class="main">
      <section id="content" class="p-5">
        <div class="container">
          <?php
          // Cek apakah parameter 'page' ada di URL
          if (isset($_GET['page'])) {
            $page = $_GET['page'];
          ?>
            <h4 class="lead display-6 pb-2 border-bottom border-danger-subtle">
              <?= ucfirst($page) ?>
            </h4>

            <?php
            // Panggil file halaman jika filenya ada
            if (file_exists($page . ".php")) {
              include($page . ".php");
            } else {
              echo "<div class='alert alert-danger'>Halaman tidak ditemukan.</div>";
            }
          } else {
            // HALAMAN PERTAMA (HOME ADMIN)
            // Hanya tampil jika tidak ada ?page= di URL
            // HALAMAN PERTAMA (HOME ADMIN)
            ?>
            <h4 class="lead display-6 pb-2 border-bottom border-danger-subtle">
              Selamat Datang, <?= ucfirst($_SESSION['username']) ?>
            </h4>
          <?php
          }
          ?>
        </div>
      </section>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <footer class="footer-admin text-center p-3">
    <div class="social-icons">
      <a href="https://www.instagram.com/udinusofficial" target="_blank">
        <i class="bi bi-instagram"></i>
      </a>

      <a href="https://twitter.com/udinusofficial" target="_blank">
        <i class="bi bi-twitter-x"></i>
      </a>

      <a href="https://wa.me/+62812685577" target="_blank">
        <i class="bi bi-whatsapp"></i>
      </a>
    </div>
    <div>Aprilyani Nur Safitri &copy; 2023</div>
  </footer>

</body>

</html>