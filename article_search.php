<?php
include "koneksi.php";
$keyword = $_POST['keyword'] ?? '';
$search = "%$keyword%";

$sql = "SELECT * FROM article 
        WHERE judul LIKE ? OR isi LIKE ? 
        ORDER BY tanggal DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $search, $search);
$stmt->execute();
$hasil = $stmt->get_result();
$no = 1;

if ($hasil->num_rows > 0) {
    while ($row = $hasil->fetch_assoc()) { ?>
        <tr>
            <td><?= $no++ ?></td>
            <td>
                <strong><?= $row['judul'] ?></strong><br>
                <small class="text-muted"><?= $row['tanggal'] ?> oleh <?= $row['username'] ?></small>
            </td>
            <td><?= nl2br($row['isi']) ?></td>
            <td>
                <?php if ($row['gambar'] !== '' && file_exists('img/' . $row['gambar'])): ?>
                    <img src="img/<?= $row['gambar'] ?>" width="100" class="img-thumbnail">
                <?php endif; ?>
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

                <div class="modal fade" id="modalEdit<?= $row["id"] ?>" data-bs-backdrop="static" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content text-dark">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">Edit Article</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="post" enctype="multipart/form-data">
                                <div class="modal-body text-start">
                                    <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                    <input type="hidden" name="gambar_lama" value="<?= $row["gambar"] ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Judul</label>
                                        <input type="text" class="form-control" name="judul" value="<?= $row["judul"] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Isi</label>
                                        <textarea class="form-control" name="isi" rows="5" required><?= $row["isi"] ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Ganti Gambar</label>
                                        <input type="file" class="form-control" name="gambar">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalHapus<?= $row["id"] ?>" data-bs-backdrop="static" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content text-dark">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">Konfirmasi Hapus</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="post">
                                <div class="modal-body text-start">
                                    <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                    <input type="hidden" name="gambar" value="<?= $row["gambar"] ?>">
                                    <p>Yakin ingin menghapus artikel "<strong><?= $row["judul"] ?></strong>"?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </td>
        </tr>
<?php }
} else {
    echo "<tr><td colspan='5' class='text-center'>Data tidak ditemukan</td></tr>";
}
?>