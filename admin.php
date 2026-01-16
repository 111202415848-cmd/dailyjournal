<?php
session_start();

include "koneksi.php";

// check jika belum ada user yang login arahkan ke halaman login
if (!isset($_SESSION['username'])) {
  header("location:login.php");
  exit;
}

// 1. Ambil data user untuk ditampilkan di Home & Navbar (SOAL 3)
$user_login = $_SESSION['username'];
$query_user = $conn->query("SELECT foto FROM user WHERE username = '$user_login'");
$data_user = $query_user->fetch_assoc();

// Variabel ini digunakan untuk di Navbar dan di Halaman Home
$foto_tampil = (!empty($data_user['foto'])) ? 'img/' . $data_user['foto'] : 'img/default.png';
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />

  <link rel="stylesheet" href="admin.css" />

  <title>Admin Panel</title>
</head>

<body>
  <header class="topbar">
    <div class="container-fluid px-3 h-100 d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-2">
        <div class="brand-badge">A</div>
        <div class="brand-text">ADMIN</div>
      </div>

      <div class="dropdown">
        <a class="top-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="<?= $foto_tampil ?>" width="30" height="30" class="rounded-circle" style="object-fit: cover;">
          <?= $_SESSION['username'] ?>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="admin.php?page=profile">
              <i class="bi bi-person-vcard me-2"></i>Profile
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <a class="dropdown-item text-danger" href="logout.php">
              <i class="bi bi-box-arrow-right me-2"></i>Logout
            </a>
          </li>
        </ul>
      </div>
    </div>
  </header>

  <div class="layout">
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

      <a class="side-btn" href="admin.php?page=profile" title="Profile Settings">
        <i class="bi bi-person-gear"></i>
      </a>
    </aside>

    <main class="main">
      <section id="content" class="p-5">
        <div class="container">
          <?php
          if (isset($_GET['page'])) {
            $page = $_GET['page'];
          ?>
            <h4 class="lead display-6 pb-2 border-bottom border-danger-subtle">
              <?= ucfirst($page) ?>
            </h4>

            <?php
            if (file_exists($page . ".php")) {
              include($page . ".php");
            } else {
              echo "<div class='alert alert-danger'>Halaman tidak ditemukan.</div>";
            }
          } else {
            // HALAMAN PERTAMA (HOME ADMIN)
            ?>
            <div class="text-center">
              <h4 class="lead display-6 pb-2 border-bottom border-danger-subtle">
                Selamat Datang, <?= ucfirst($_SESSION['username']) ?>
              </h4>
              <div class="mt-4">
                <img src="<?= $foto_tampil ?>"
                  class="rounded-circle shadow"
                  style="width: 200px; height: 200px; object-fit: cover; border: none; background-color: transparent;">

                <h5 class="mt-3 text-white"><?= $_SESSION['username'] ?></h5>
                <p class="text-muted">Terakhir login: <?= date("d M Y H:i") ?></p>
              </div>
            </div>
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
      <a href="