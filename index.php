<?php
//menyertakan code dari file koneksi
include "koneksi.php";
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap CSS -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />

  <link rel="stylesheet" href="style.css" />
  <title>Portofolio | Catur Wahyu Mulyanto</title>
</head>

<body>
  <!-- NAVBAR -->
  <nav
    class="navbar navbar-expand-lg navbar-dark fixed-top shadow-sm nav-main">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">CaturWahyuM</a>

      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item"><a class="nav-link" href="#hero">Home</a></li>

          <!-- NEW -->
          <li class="nav-item">
            <a class="nav-link" href="#article">Article</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="#schedule">Schedule</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#about">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#contact">Contact</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login.php" target="_blank">Login</a>
          </li>

          <li class="nav-item ms-3 d-flex">
            <button id="btn-dark" class="btn btn-outline-light btn-sm me-2">
              <i class="bi bi-moon-fill"></i>
            </button>
            <button id="btn-light" class="btn btn-outline-light btn-sm">
              <i class="bi bi-brightness-high-fill"></i>
            </button>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- HERO SECTION -->
  <section id="hero" class="hero">
    <div class="container hero-grid">
      <div class="hero-text">
        <h1 class="fw-bold">Hai, Saya <span>Catur Wahyu Mulyanto</span></h1>
        <p class="hero-desc">
          Mahasiswa yang sedang mempelajari Web Development menggunakan HTML,
          CSS, dan JavaScript.
        </p>

        <div class="hero-actions">
          <a href="#about" class="btn btn-gold">Tentang Saya</a>
          <a href="#contact" class="btn btn-outline-gold">Hubungi</a>
        </div>

        <div class="hero-info">
          <div>
            <h4>Mahasiswa</h4>
            <span>Universitas XYZ</span>
          </div>
          <div>
            <h4>Belajar Web</h4>
            <span>Frontend Basic</span>
          </div>
        </div>
      </div>

      <div class="hero-img-box">
        <img src="img/main.jpg" alt="Foto Profil" />
        <div class="hero-badge">Web Dev <span>Beginner</span></div>
      </div>
    </div>
  </section>

  <!-- article begin -->
  <section id="article" class="text-center p-5">
    <div class="container">
      <h1 class="fw-bold display-4 pb-3">article</h1>
      <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
        <?php
        $sql = "SELECT * FROM article ORDER BY tanggal DESC";
        $hasil = $conn->query($sql);

        while ($row = $hasil->fetch_assoc()) {
        ?>
          <div class="col">
            <div class="card h-100">
              <img src="img/<?= $row["gambar"] ?>" class="card-img-top" alt="..." />
              <div class="card-body">
                <h5 class="card-title"><?= $row["judul"] ?></h5>
                <p class="card-text">
                  <?= $row["isi"] ?>
                </p>
              </div>
              <div class="card-footer">
                <small class="text-body-secondary">
                  <?= $row["tanggal"] ?>
                </small>
              </div>
            </div>
          </div>
        <?php
        }
        ?>
      </div>
    </div>
  </section>
  <!-- article end -->


  <!-- ABOUT -->
  <section id="about" class="section-dark">
    <div class="container about-grid">
      <div>
        <h2 class="section-title">Tentang Saya</h2>
        <p>
          Saya memiliki minat besar terhadap dunia teknologi, terutama dalam
          web development dan desain.
        </p>
        <p>
          Saat ini saya sedang belajar HTML, CSS, JavaScript, serta Bootstrap
          untuk membangun website responsif.
        </p>
      </div>
      <img class="about-img" src="img/main.jpg" alt="Foto" />
    </div>
  </section>

  <!-- SCHEDULE SECTION -->
  <section id="schedule" class="section-default">
    <div class="container">
      <h2 class="section-title">Schedule</h2>
      <p class="section-subtitle">Aktivitas harian yang saya lakukan.</p>

      <div class="row g-4 justify-content-center">
        <div class="col-sm-6 col-lg-3">
          <div class="menu-card">
            <h3>Membaca</h3>
            <p>Membaca materi pbw dan belajar trade.</p>
            <span class="menu-tag">Daily</span>
          </div>
        </div>

        <div class="col-sm-6 col-lg-3">
          <div class="menu-card">
            <h3>Latihan</h3>
            <p>Membuat catatan coding & jurnal.</p>
            <span class="menu-tag">Often</span>
          </div>
        </div>

        <div class="col-sm-6 col-lg-3">
          <div class="menu-card">
            <h3>Movie</h3>
            <p>Menonton film untuk refreshing otak.</p>
            <span class="menu-tag">Weekend</span>
          </div>
        </div>

        <div class="col-sm-6 col-lg-3">
          <div class="menu-card">
            <h3>Trading</h3>
            <p>Trading untuk tambahan uang saku kuliah.</p>
            <span class="menu-tag">Flexible</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CONTACT -->
  <section id="contact" class="section-dark">
    <div class="container">
      <h2 class="section-title">Contact Me</h2>

      <div class="row justify-content-center">
        <div class="col-md-6">
          <form>
            <div class="mb-3">
              <label class="form-label">Nama</label>
              <input type="text" class="form-control" />
            </div>

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" />
            </div>

            <div class="mb-3">
              <label class="form-label">Pesan</label>
              <textarea class="form-control" rows="3"></textarea>
            </div>

            <button class="btn btn-gold w-100">Kirim</button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="footer text-center text-white py-3">
    <p>© 2025 Catur Wahyu Mulyanto</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.getElementById("btn-dark").onclick = () =>
      document.body.classList.add("dark-theme");
    document.getElementById("btn-light").onclick = () =>
      document.body.classList.remove("dark-theme");
  </script>
</body>

</html>