<?php
include "koneksi.php";

/**
 * BAGIAN 1: LOGIKA PEMPROSESAN DATA (BACK-END)
 * Bagian ini menangani komunikasi dengan database
 */

// Cek apakah tombol 'simpan' ditekan (untuk fungsi Tambah & Edit)
if (isset($_POST['simpan'])) {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $tanggal = date("Y-m-d H:i:s"); // Mengambil waktu saat ini
    $username = $_SESSION['username']; // Mengambil user yang sedang login
    $gambar = '';
    $nama_gambar = $_FILES['gambar']['name'];

    // Logika Pengelolaan File Gambar
    if ($nama_gambar != '') {
        // Jika user upload gambar, pindahkan file ke folder 'img/'
        $cek_upload = move_uploaded_file($_FILES['gambar']['tmp_name'], 'img/' . $nama_gambar);
        if ($cek_upload) {
            $gambar = $nama_gambar;
            // Jika sedang Edit (ada gambar lama), hapus file lama agar tidak menumpuk
            if (isset($_POST['gambar_lama']) && $_POST['gambar_lama'] != '') {
                unlink("img/" . $_POST['gambar_lama']);
            }
        }
    } else {
        // Jika tidak upload gambar baru, tetap gunakan nama gambar yang lama
        $gambar = $_POST['gambar_lama'] ?? '';
    }

    // Pembeda antara Update data atau Insert data baru
    if (isset($_POST['id']) && $_POST['id'] != '') {
        $id = $_POST['id'];
        // Query untuk memperbarui data yang sudah ada berdasarkan ID
        $sql = "UPDATE article SET judul='$judul', isi='$isi', gambar='$gambar', tanggal='$tanggal', username='$username' WHERE id='$id'";
    } else {
        // Query untuk memasukkan data baru ke tabel
        $sql = "INSERT INTO article (judul, isi, gambar, tanggal, username) VALUES ('$judul', '$isi', '$gambar', '$tanggal', '$username')";
    }

    // Eksekusi query ke database
    if ($conn->query($sql)) {
        echo "<script>alert('Simpan data sukses'); document.location='admin.php?page=article';</script>";
    }
}

// Cek apakah tombol 'hapus' ditekan
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $gambar = $_POST['gambar'];

    // Hapus file gambar asli di folder img sebelum data di database dihapus
    if ($gambar != '' && file_exists("img/$gambar")) {
        unlink("img/$gambar");
    }

    // Query untuk menghapus baris data berdasarkan ID
    $sql = "DELETE FROM article WHERE id = '$id'";
    if ($conn->query($sql)) {
        echo "<script>alert('Hapus data sukses'); document.location='admin.php?page=article';</script>";
    }
}
?>

<div class="container">
    <div class="row mb-2">
        <div class="col-md-6">
            <!-- Modal Tambah -->
            <button type="button" class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-lg"></i> Tambah Article
            </button>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" id="search" class="form-control" placeholder="Cari Artikel...">
                <span class="input-group-text">
                    <i class="bi bi-search"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="table-responsive">
            <table class="table table-hover border">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th class="w-25">Judul</th>
                        <th class="w-50">Isi</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="result">

                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Artikel Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" class="form-control" name="judul" placeholder="Judul..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Isi</label>
                        <textarea class="form-control" name="isi" rows="5" placeholder="Isi artikel..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar</label>
                        <input type="file" class="form-control" name="gambar">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        load_data(); // Panggil data saat halaman pertama dimuat

        function load_data(keyword) {
            $.ajax({
                url: "article_search.php",
                method: "POST",
                data: {
                    keyword: keyword
                },
                success: function(data) {
                    // Memasukkan hasil (tr) langsung ke dalam tbody
                    $('#result').html(data);
                }
            });
        }

        $('#search').on('keyup', function() {
            var keyword = $(this).val();
            load_data(keyword);
        });
    });
</script>