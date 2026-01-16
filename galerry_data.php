<?php
include "koneksi.php";

$keyword = $_GET['keyword'] ?? '';
$keyword = mysqli_real_escape_string($conn, $keyword);

$res = $conn->query("
    SELECT * FROM gallery
    WHERE deskripsi LIKE '%$keyword%'
    ORDER BY dibuat_pada DESC
");

$no = 1;
while ($row = $res->fetch_assoc()) {
?>
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
            <img src="img/<?= $row['gambar'] ?>" class="img-thumbnail w-100" style="max-height:500px;object-fit:cover;">
        </td>
        <td>
            <a href="admin.php?page=gallery" class="btn btn-sm btn-secondary">Detail</a>
        </td>
    </tr>
<?php } ?>