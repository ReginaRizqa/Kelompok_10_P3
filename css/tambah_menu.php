<?php include 'partials/header.php'; include 'config/conn.php';

if ($_SESSION['user']['role'] !== 'admin') {
    echo "<div class='alert alert-danger'>Akses ditolak.</div>";
    include 'partials/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
?>

<h4>Tambah Menu Baru</h4>
<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Nama Menu</label>
        <input type="text" name="nama" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
        <label>Harga</label>
        <input type="number" name="harga" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Gambar</label>
        <input type="file" name="gambar" class="form-control" accept="image/*" required>
    </div>
    <button class="btn btn-danger">Simpan Menu</button>
</form>

<?php include 'partials/footer.php'; ?>
