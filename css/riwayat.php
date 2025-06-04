<?php include 'partials/header.php'; include 'config/conn.php';

$user_id = $_SESSION['user']['id'];
$riwayat = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC");
$riwayat->execute([$user_id]);
$transaksi = $riwayat->fetchAll(PDO::FETCH_ASSOC);
?>

<h4>Riwayat Pembelian</h4>
<?php if (empty($transaksi)): ?>
    <div class="alert alert-info">Belum ada transaksi.</div>
<?php else: ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Total</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transaksi as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['nama_lengkap']) ?></td>
                    <td>Rp <?= number_format($t['total_harga'], 0, ',', '.') ?></td>
                    <td><?= ucfirst($t['metode_pembayaran']) ?></td>
                    <td><?= ucfirst($t['status']) ?></td>
                    <td><?= $t['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include 'partials/footer.php'; ?>
