<?php 
//memulai session atau melanjutkan session yang sudah ada
session_start();

//menyertakan code dari file koneksi
include "koneksi.php";

//check jika sudah ada user yang login arahkan ke halaman admin
if (isset($_SESSION['username'])) { 
    header("location:admin.php"); 
    exit;
}

//-----------------------------------------
//  LOGIC PROSES LOGIN
//-----------------------------------------

//Proses dijalankan hanya jika method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ambil input
    $userInput = $_POST['username'];
    $passInput = $_POST['password'];

    // Validasi kosong
    //jika lolos validasi
    if ($userInput == "") {
        $errorMsg = "Username tidak boleh kosong!";
    } elseif ($passInput == "") {
        $errorMsg = "Password tidak boleh kosong!";
    } else {

        //tampilkan isi dari variable array POST menggunakan perulangan
        $username = $userInput; 
        $password = md5($passInput); 
        //menggunakan fungsi enkripsi md5 supaya sama dengan password yang tersimpan di database

        //prepared statement
        $stmt = $conn->prepare("SELECT * 
                                FROM user 
                                WHERE username=? AND password=?");

        //parameter binding 
        $stmt->bind_param("ss", $username, $password); //username string dan password string

        //database executes the statement
        $stmt->execute();

        //menampung hasil eksekusi
        $hasil = $stmt->get_result();

        //mengambil baris dari hasil sebagai array asosiatif
        $row = $hasil->fetch_array(MYSQLI_ASSOC);

        //jika lolos semua validasi
        // Cek kecocokan login
        //check apakah ada baris hasil data user yang cocok
        if (!empty($row)) { 
            //jika data ada (berhasil), alihkan ke halaman admin
            $_SESSION['username'] = $username; //simpan variabel username pada session
            header("location:admin.php");
            exit;
        } else {
            //jika data tidak ada (gagal), tetap di halaman login
            header("location:login.php?error=1");
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
    }

    .login-box {
      background: #181818;
      padding: 30px;
      border-radius: 12px;
      width: 350px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.5);
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

    <!-- Tampilkan pesan error -->
    <?php 
      if (isset($_GET['error'])) {
        if ($_GET['error'] == "empty") {
            echo "<p class='text-danger text-center'>Username / Password tidak boleh kosong!</p>";
        }
        if ($_GET['error'] == "wrong") {
            echo "<p class='text-danger text-center'>Username atau Password salah!</p>";
        }
      }
    ?>

    <!-- FORM POST -->
    <form method="POST" id="loginForm">
      <div class="mb-3">
        <label>Username</label>
        <input type="text" class="form-control" name="username" id="username" />
      </div>

      <div class="mb-3">
        <label>Password</label>
        <input type="password" class="form-control" name="password" id="password" />
      </div>

      <button class="btn btn-gold w-100 mt-3">Masuk</button>
      <p id="errorMsg" style="color: red; margin-top: 10px;"></p>
    </form>
  </div>

</body>
</html>
