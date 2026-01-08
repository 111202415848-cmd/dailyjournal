<?php
include "koneksi.php";

// --- 1. LOGIKA PROSES (SIMPAN & HAPUS) ---
if (isset($_POST['simpan'])) {
    $judul = $_POST['judul'];
    $tanggal = date("Y-m-d H:i:s");
    $gambar = "";
    $nama_gambar = $_FILES['gambar']['name'];

    if ($nama_gambar != '') {
        $tmp_name = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($tmp_name, 'img/' . $nama_gambar);
        $gambar = $nama_gambar;
        if (isset($_POST['gambar_lama'])) {
            unlink("img/" . $_POST['gambar_lama']);
        }
    } else {
        $gambar = $_POST['gambar_lama'] ?? '';
    }

    if (isset($_POST['id']) && $_POST['id'] != '') {
        $id = $_POST['id'];
        $sql = "UPDATE gallery SET judul=?, gambar=?, tanggal=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $judul, $gambar, $tanggal, $id);
    } else {
        $sql = "INSERT INTO gallery (judul, gambar, tanggal) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $judul, $gambar, $tanggal);
    }
    $stmt->execute();
    echo "<script>alert('Berhasil!'); document.location='admin.php?page=gallery';</script>";
}

if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $gambar = $_POST['gambar'];
    if ($gambar != '') {
        unlink("img/$gambar");
    }
    $conn->query("DELETE FROM gallery WHERE id = '$id'");
    echo "<script>alert('Terhapus!'); document.location='admin.php?page=gallery';</script>";
}
?>

<div class="container">
    <button class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg"></i> Tambah Foto
    </button>

    <table class="table table-hover border">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Judul & Tanggal</th>
                <th>Preview</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $res = $conn->query("SELECT * FROM gallery ORDER BY tanggal DESC");
            $no = 1;
            while ($row = $res->fetch_assoc()) { ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <strong><?= $row['judul'] ?></strong><br>
                        <small class="text-muted"><?= $row['tanggal'] ?></small>
                    </td>
                    <td>
                        <img src="img/<?= $row['gambar'] ?>" width="100" class="img-thumbnail">
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id'] ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row['id'] ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>

                        <div class="modal fade" id="modalEdit<?= $row['id'] ?>" tabindex="-1">
                            <div class="modal-dialog text-dark">
                                <form method="post" enctype="multipart/form-data" class="modal-content">
                                    <div class="modal-header">
                                        <h5>Edit Gallery</h5>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="gambar_lama" value="<?= $row['gambar'] ?>">
                                        <label>Judul</label>
                                        <input type="text" name="judul" class="form-control mb-2" value="<?= $row['judul'] ?>" required>
                                        <label>Ganti Gambar</label>
                                        <input type="file" name="gambar" class="form-control">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="modal fade" id="modalHapus<?= $row['id'] ?>" tabindex="-1">
                            <div class="modal-dialog text-dark">
                                <form method="post" class="modal-content">
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="gambar" value="<?= $row['gambar'] ?>">
                                        <p>Yakin hapus "<?= $row['judul'] ?>"?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog text-dark">
        <form method="post" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5>Tambah Gallery</h5>
            </div>
            <div class="modal-body">
                <label>Judul</label>
                <input type="text" name="judul" class="form-control mb-2" required>
                <label>Gambar</label>
                <input type="file" name="gambar" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>