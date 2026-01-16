<?php
include "koneksi.php";

$username = $_SESSION['username'];

// 1. LOGIKA PROSES UPDATE PROFILE
if (isset($_POST['simpan'])) {
    $password = $_POST['password'];
    $foto_lama = $_POST['foto_lama'];
    $nama_foto = $_FILES['foto']['name'];

    // Jika ada foto baru yang diupload
    if ($nama_foto != '') {
        $tmp_name = $_FILES['foto']['tmp_name'];
        move_uploaded_file($tmp_name, 'img/' . $nama_foto);
        $foto_baru = $nama_foto;
        // Hapus foto lama jika bukan default
        if ($foto_lama != '' && file_exists("img/" . $foto_lama)) {
            unlink("img/" . $foto_lama);
        }
    } else {
        $foto_baru = $foto_lama;
    }

    // Update query (Jika password diisi, update password. Jika tidak, hanya update foto)
    if (!empty($password)) {
        $pass_md5 = md5($password); // Sesuaikan jika database pakai MD5 atau plain
        $sql = "UPDATE user SET password = ?, foto = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $pass_md5, $foto_baru, $username);
    } else {
        $sql = "UPDATE user SET foto = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $foto_baru, $username);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Profile berhasil diperbarui!'); document.location='admin.php?page=profile';</script>";
    }
}

// 2. AMBIL DATA USER UNTUK FORM
$sql = "SELECT * FROM user WHERE username = '$username'";
$result = $conn->query($sql);
$data = $result->fetch_assoc();
?>

<div class="container mt-4">
    <div class="card shadow-sm bg-dark text-white border-secondary">
        <div class="card-header text-white" style="background-color: #43aedfff;">
            <h5 class="mb-0">Profile User</h5>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <h6 class="text-thumbnail">Foto Profil Saat Ini</h6>
                        <img src="img/<?= ($data['foto'] != '') ? $data['foto'] : 'default.png' ?>"
                            class="rounded-circle shadow"
                            style="width: 200px; height: 200px; object-fit: cover; border: none;">
                        <input type="file" name="foto" class="form-control bg-secondary text-white border-0">
                        <input type="hidden" name="foto_lama" value="<?= $data['foto'] ?>">
                    </div>

                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label text-white">Username</label>
                            <input type="text" class="form-control bg-white text-dark border-0" name="username"
                                value="<?= $data['username'] ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Ganti Password</label>
                            <input type="password" class="form-control bg-white text-dark border-0" name="password"
                                placeholder="Kosongkan jika tidak ingin mengganti password">
                        </div>
                        <div class="text-end">
                            <button type="submit" name="simpan" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </div>
                </div>
        </div>
        </form>
    </div>
</div>
</div>