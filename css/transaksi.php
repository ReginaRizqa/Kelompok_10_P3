<?php include 'partials/header.php'; include 'config/conn.php';

$user_id = $_SESSION['user']['id'];

// Ambil isi keranjang
$stmt = $pdo->prepare("SELECT c.*, m.nama, m.harga FROM cart c JOIN menu m ON c.menu_id = m.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($items)) {
    echo "<div class='alert alert-warning'>Keranjang kosong. <a href='menu.php'>Lihat Menu</a></div>";
    include 'partials/footer.php';
    exit;
}

// Hitung total
$total = array_reduce($items, fn($carry, $item) => $carry + ($item['harga'] * $item['jumlah']), 0);

// Tangani form kirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_lengkap'];
    $alamat = $_POST['alamat'];
    $metode = $_POST['metode_pembayaran'];

    // Simpan transaksi
    // Set status otomatis selesai jika COD, jika tidak maka pending
$status = ($metode === 'cod') ? 'selesai' : 'pending';

$pdo->prepare("INSERT INTO transactions (user_id, nama_lengkap, alamat, total_harga, metode_pembayaran, status, created_at) 
    VALUES (?, ?, ?, ?, ?, ?, NOW())")
    ->execute([$user_id, $nama, $alamat, $total, $metode, $status]);


    $transaction_id = $pdo->lastInsertId();

    // Simpan detail
    foreach ($items as $item) {
        $pdo->prepare("INSERT INTO transaction_items (transaction_id, menu_id, jumlah, harga_satuan) VALUES (?, ?, ?, ?)")
            ->execute([$transaction_id, $item['menu_id'], $item['jumlah'], $item['harga']]);
    }

    // Hapus keranjang
    $pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$user_id]);

    echo "<script>alert('Transaksi berhasil!'); window.location='riwayat.php';</script>";
    exit;
}
?>

<h4>Form Pembayaran</h4>
<form method="POST">
    <div class="mb-3">
        <label>Nama Lengkap</label>
        <input type="text" name="nama_lengkap" required class="form-control">
    </div>
    <div class="mb-3">
        <label>Alamat Pengiriman</label>
        <textarea name="alamat" required class="form-control"></textarea>
    </div>
    <div class="mb-3">
        <label>Metode Pembayaran</label>
        <select name="metode_pembayaran" required class="form-select">
            <option value="transfer">Transfer Bank</option>
            <option value="e-wallet">E-Wallet</option>
            <option value="cod">COD</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Total Bayar</label>
        <input type="text" class="form-control" value="Rp <?= number_format($total, 0, ',', '.') ?>" readonly>
    </div>
    <button class="btn btn-danger">Bayar Sekarang</button>
</form>

<?php include 'partials/footer.php'; ?>
