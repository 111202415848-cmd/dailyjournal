<?php
include "koneksi.php";

/**
 * BAGIAN 1: LOGIKA PEMPROSESAN DATA (BACK-END)
 * Catatan: session_start() sudah ada di admin.php, jadi tidak perlu ditulis lagi di sini
 */

// Simpan & Edit Data
if (isset($_POST['simpan'])) {
    $deskripsi = $_POST['deskripsi'];
    $id_gallery = $_POST['id_gallery'] ?? '';
    $dibuat_pada = date("Y-m-d H:i:s");
    $dibuat_oleh = $_SESSION['username']; // Mengambil dari session login
    $gambar = '';
    $nama_gambar = $_FILES['gambar']['name'];

    if ($nama_gambar != '') {
        // Upload ke folder img agar konsisten dengan artikel
        $cek_upload = move_uploaded_file($_FILES['gambar']['tmp_name'], 'img/' . $nama_gambar);
        if ($cek_upload) {
            $gambar = $nama_gambar;
            // Hapus gambar lama jika sedang edit
            if (!empty($_POST['gambar_lama']) && file_exists("img/" . $_POST['gambar_lama'])) {
                unlink("img/" . $_POST['gambar_lama']);
            }
        }
    } else {
        $gambar = $_POST['gambar_lama'] ?? '';
    }

    if ($id_gallery != '') {
        // Update data berdasarkan id_gallery
        $sql = "UPDATE gallery SET deskripsi=?, gambar=?, dibuat_pada=?, dibuat_oleh=? WHERE id_gallery=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $deskripsi, $gambar, $dibuat_pada, $dibuat_oleh, $id_gallery);
    } else {
        // Tambah data baru
        $sql = "INSERT INTO gallery (deskripsi, gambar, dibuat_oleh, dibuat_pada) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $deskripsi, $gambar, $dibuat_oleh, $dibuat_pada);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Simpan data sukses'); document.location='admin.php?page=gallery';</script>";
    }
}

// Hapus Data
if (isset($_POST['hapus'])) {
    $id_gallery = $_POST['id_gallery'];
    $gambar = $_POST['gambar'];
    if ($gambar != '' && file_exists("img/$gambar")) {
        unlink("img/$gambar");
    }
    $sql = "DELETE FROM gallery WHERE id_gallery = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_gallery);
    if ($stmt->execute()) {
        echo "<script>alert('Hapus data sukses'); document.location='admin.php?page=gallery';</script>";
    }
}
?>

<div class="container">
    <div class="row mb-2">
        <div class="col-md-6">
            <button type="button" class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahGallery">
                <i class="bi bi-plus-lg"></i> Tambah Gallery
            </button>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" id="search_gallery" class="form-control" placeholder="Cari Gallery...">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="table-responsive">
            <table class="table table-hover border">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 25%;">Deskripsi</th>
                        <th style="width: 60%;">Gambar</th>
                        <th style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="result_gallery">
                    <?php
                    $res = $conn->query("SELECT * FROM gallery ORDER BY dibuat_pada DESC");
                    $no = 1;
                    while ($row = $res->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <strong><?= htmlspecialchars($row['deskripsi']) ?></strong><br>
                                <small class="text-muted">
                                    pada : <?= $row['dibuat_pada'] ?><br>
                                    oleh : <?= htmlspecialchars($row['dibuat_oleh']) ?>
                                </small>
                            </td>
                            <td>
                                <img src="img/<?= $row['gambar'] ?>" class="img-thumbnail w-100" style="object-fit: cover; max-height: 500px;">
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_gallery'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row['id_gallery'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <div class="modal fade" id="modalEdit<?= $row['id_gallery'] ?>" tabindex="-1">
                                    <div class="modal-dialog text-dark">
                                        <form method="post" enctype="multipart/form-data" class="modal-content">
                                            <div class="modal-header">
                                                <h5>Edit Gallery</h5>
                                            </div>
                                            <div class="modal-body text-start">
                                                <input type="hidden" name="id_gallery" value="<?= $row['id_gallery'] ?>">
                                                <input type="hidden" name="gambar_lama" value="<?= $row['gambar'] ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Deskripsi</label>
                                                    <textarea name="deskripsi" class="form-control" required><?= $row['deskripsi'] ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Ganti Gambar</label>
                                                    <input type="file" name="gambar" class="form-control">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="simpan" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="modal fade" id="modalHapus<?= $row['id_gallery'] ?>" tabindex="-1">
                                    <div class="modal-dialog text-dark">
                                        <form method="post" class="modal-content">
                                            <div class="modal-body text-start">
                                                <input type="hidden" name="id_gallery" value="<?= $row['id_gallery'] ?>">
                                                <input type="hidden" name="gambar" value="<?= $row['gambar'] ?>">
                                                <p>Yakin ingin menghapus gallery: <strong><?= $row['deskripsi'] ?></strong>?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
    </div>
</div>

<div class="modal fade" id="modalTambahGallery" tabindex="-1">
    <div class="modal-dialog text-dark">
        <form method="post" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5>Tambah Gallery Baru</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" placeholder="Tulis deskripsi gambar..." required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gambar</label>
                    <input type="file" name="gambar" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>


<script>
    $(document).ready(function() {
        $("#search_gallery").keyup(function() {
            let keyword = $(this).val();
            $.ajax({
                url: "gallery_data.php",
                method: "GET",
                data: {
                    keyword: keyword
                },
                success: function(data) {
                    $("#result_gallery").html(data);
                }
            });
        });
    });
</script>