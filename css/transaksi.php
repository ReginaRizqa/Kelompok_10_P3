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

    $status = ($metode === 'Qris') ? 'selesai' : 'pending';

    $pdo->prepare("INSERT INTO transactions (user_id, nama_lengkap, alamat, total_harga, metode_pembayaran, status, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, NOW())")
        ->execute([$user_id, $nama, $alamat, $total, $metode, $status]);

    $transaction_id = $pdo->lastInsertId();

    foreach ($items as $item) {
        $pdo->prepare("INSERT INTO transaction_items (transaction_id, menu_id, jumlah, harga_satuan) 
            VALUES (?, ?, ?, ?)")
            ->execute([$transaction_id, $item['menu_id'], $item['jumlah'], $item['harga']]);
    }

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
        <select name="metode_pembayaran" id="metode_pembayaran" required class="form-select">
            <option value="">-- Pilih Metode --</option>
            <option value="transfer">Transfer Bank</option>
            <option value="Qris">QRIS</option>
            <option value="cod">COD (Bayar di Tempat)</option>
        </select>
    </div>

    <!-- Area QRIS -->
    <div class="mb-3 d-none" id="qris_section">
        <label>Kode QRIS</label><br>
        <img src="assets/img/Qris.jpg" alt="QRIS Code" width="250">
        <p class="mt-2 text-muted">Silakan scan QR untuk menyelesaikan pembayaran.</p>
    </div>

    <!-- Area Transfer -->
    <div class="mb-3 d-none" id="transfer_section">
        <label>Rekening Transfer</label>
        <p class="mb-1"><strong>Bank BCA</strong></p>
        <p>No. Rekening: <strong>123-456-7890</strong></p>
        <p>Atas Nama: <strong>Wonton Gohyong</strong></p>
    </div>

    <div class="mb-3">
        <label>Total Bayar</label>
        <input type="text" class="form-control" value="Rp <?= number_format($total, 0, ',', '.') ?>" readonly>
    </div>
    <button class="btn btn-danger">Bayar Sekarang</button>
</form>

<script>
    const metodeSelect = document.getElementById('metode_pembayaran');
    const qrisSection = document.getElementById('qris_section');
    const transferSection = document.getElementById('transfer_section');

    metodeSelect.addEventListener('change', function () {
        const val = this.value;
        qrisSection.classList.add('d-none');
        transferSection.classList.add('d-none');

        if (val === 'Qris') {
            qrisSection.classList.remove('d-none');
        } else if (val === 'transfer') {
            transferSection.classList.remove('d-none');
        }
    });
</script>

<?php include 'partials/footer.php'; ?>
