<?php
session_start(); // Memulai sesi untuk menyimpan data login user
include "koneksi.php"; // Menghubungkan ke database

// Jika user sudah login sebelumnya, langsung lempar ke halaman admin
if (isset($_SESSION['username'])) {
  header("location:admin.php");
  exit;
}

// Mengecek apakah form login sudah disubmit (dikirim)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $userInput = $_POST['username'];
  $passInput = $_POST['password'];

  // Validasi dasar: jangan biarkan input kosong
  if ($userInput == "" || $passInput == "") {
    header("location:login.php?error=empty");
    exit;
  } else {
    $username = $userInput;
    $password = md5($passInput); // Enkripsi password ke MD5 agar cocok dengan DB

    // Gunakan Prepared Statement untuk keamanan dari SQL Injection
    $stmt = $conn->prepare("SELECT * FROM user WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password); // "ss" artinya dua data bertipe string
    $stmt->execute();
    $hasil = $stmt->get_result();
    $row = $hasil->fetch_array(MYSQLI_ASSOC);

    // Jika data user ditemukan di database
    if (!empty($row)) {
      $_SESSION['username'] = $username; // Simpan username ke sesi
      header("location:admin.php"); // Masuk ke admin
      exit;
    } else {
      // Jika salah password atau username
      header("location:login.php?error=wrong");
      exit;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Catur Wahyu</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <style>
    body {
      background: #1b0f0a;
      color: white;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
    }

    .login-box {
      background: #181818;
      padding: 30px;
      border-radius: 12px;
      width: 350px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .login-box h3 {
      text-align: center;
      margin-bottom: 20px;
      color: #d39c5b;
    }

    .btn-gold {
      background: #d39c5b;
      border: none;
      color: black;
      font-weight: 600;
    }
  </style>
</head>

<body>

  <div class="login-box">
    <h3>Login</h3>

    <?php
    if (isset($_GET['error'])) {
      if ($_GET['error'] == "empty") {
        echo "<p class='text-danger text-center'>Username / Password tidak boleh kosong!</p>";
      } elseif ($_GET['error'] == "wrong") {
        echo "<p class='text-danger text-center'>Username atau Password salah!</p>";
      }
    }
    ?>

    <form method="POST" id="loginForm">
      <div class="mb-3">
        <label>Username</label>
        <input type="text" class="form-control" name="username" id="username" required />
      </div>

      <div class="mb-3">
        <label>Password</label>
        <input type="password" class="form-control" name="password" id="password" required />
      </div>

      <button type="submit" class="btn btn-gold w-100 mt-3">Masuk</button>
      <p id="errorMsg" style="color: red; margin-top: 10px;"></p>
    </form>
  </div>

</body>

</html>