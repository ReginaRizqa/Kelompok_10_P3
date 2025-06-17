<?php

include 'partials/header.php';
include 'config/conn.php';

// Cek role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "<div class='alert alert-danger'>Akses ditolak.</div>";
    include 'partials/footer.php';
    exit;
}

// Tambah Menu
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = (int) $_POST['harga'];
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    if ($gambar) {
        move_uploaded_file($tmp, "assets/img/$gambar");
        $pdo->prepare("INSERT INTO menu (nama, deskripsi, harga, gambar, rating, jumlah_rating) VALUES (?, ?, ?, ?, 0, 0)")
            ->execute([$nama, $deskripsi, $harga, $gambar]);
        echo "<script>alert('Menu berhasil ditambahkan!'); window.location='menu.php';</script>";
    } else {
        echo "<div class='alert alert-danger'>Gambar wajib diunggah!</div>";
    }
}

// Edit Menu
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = (int) $_POST['harga'];

    if ($_FILES['gambar']['name']) {
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($tmp, "assets/img/$gambar");
        $pdo->prepare("UPDATE menu SET nama=?, deskripsi=?, harga=?, gambar=? WHERE id=?")
            ->execute([$nama, $deskripsi, $harga, $gambar, $id]);
    } else {
        $pdo->prepare("UPDATE menu SET nama=?, deskripsi=?, harga=? WHERE id=?")
            ->execute([$nama, $deskripsi, $harga, $id]);
    }
    echo "<script>alert('Menu berhasil diubah!'); window.location='menu.php';</script>";
}

// Hapus Menu
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $pdo->prepare("DELETE FROM menu WHERE id=?")->execute([$id]);
    echo "<script>alert('Menu dihapus!'); window.location='menu.php';</script>";
}

// Ambil data menu
$menu = $pdo->query("SELECT * FROM menu")->fetchAll();
?>

<div class="container py-4">
    <h4 class="text-danger">Tambah Menu Baru</h4>
    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="mb-2"><input type="text" name="nama" class="form-control" placeholder="Nama Menu" required></div>
        <div class="mb-2"><textarea name="deskripsi" class="form-control" placeholder="Deskripsi" required></textarea></div>
        <div class="mb-2"><input type="number" name="harga" class="form-control" placeholder="Harga" required></div>
        <div class="mb-2"><input type="file" name="gambar" class="form-control" required></div>
        <button class="btn btn-danger" name="tambah">Simpan Menu</button>
    </form>

    <h4 class="text-danger">Daftar Menu</h4>
    <table class="table table-bordered bg-white">
        <thead class="table-danger">
            <tr>
                <th>#</th>
                <th>Gambar</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Rating</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($menu as $i => $m): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><img src="assets/img/<?= $m['gambar'] ?>" width="60"></td>
                <td><?= htmlspecialchars($m['nama']) ?></td>
                <td>Rp <?= number_format($m['harga'], 0, ',', '.') ?></td>
                <td><?= number_format($m['rating'], 1) ?> ⭐</td>
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $m['id'] ?>">Edit</button>
                    <a href="?hapus=<?= $m['id'] ?>" onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-sm btn-danger">Hapus</a>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>

<!-- Modal Edit (di luar table) -->
<?php foreach ($menu as $m): ?>
<div class="modal fade" id="editModal<?= $m['id'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Edit Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" value="<?= $m['id'] ?>">
                <div class="mb-2"><input type="text" name="nama" class="form-control" value="<?= $m['nama'] ?>" required></div>
                <div class="mb-2"><textarea name="deskripsi" class="form-control" required><?= $m['deskripsi'] ?></textarea></div>
                <div class="mb-2"><input type="number" name="harga" class="form-control" value="<?= $m['harga'] ?>" required></div>
                <div class="mb-2">
                    <label>Ganti Gambar (kosongkan jika tidak diubah)</label>
                    <input type="file" name="gambar" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" name="edit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'partials/footer.php'; ?>
