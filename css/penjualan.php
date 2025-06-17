<?php include 'partials/header.php'; include 'config/conn.php';

if ($_SESSION['user']['role'] !== 'admin') {
    echo "<div class='alert alert-danger'>Akses ditolak.</div>";
    include 'partials/footer.php';
    exit;
}

$transaksi = $pdo->query("SELECT t.*, u.nama FROM transactions t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h4>Riwayat Penjualan</h4>
<?php if (empty($transaksi)): ?>
    <div class="alert alert-info">Belum ada transaksi penjualan.</div>
<?php else: ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Status</th>
                <th>Metode</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transaksi as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['nama_lengkap']) ?></td>
                    <td>Rp <?= number_format($t['total_harga'], 0, ',', '.') ?></td>
                    <td><?= ucfirst($t['status']) ?></td>
                    <td>
    <?php
    switch ($t['metode_pembayaran']) {
        case 'transfer':
            echo 'Transfer';
            break;
        case 'qris':
            echo 'Qris';
            break;
        case 'cod':
            echo 'Cod';
            break;
        default:
            echo ucfirst($t['metode_pembayaran']);
    }
    ?>
</td>

                    <td><?= $t['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include 'partials/footer.php'; ?>
