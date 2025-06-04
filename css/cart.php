<?php include 'partials/header.php'; include 'config/conn.php';

$user_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT c.*, m.nama, m.harga FROM cart c JOIN menu m ON c.menu_id = m.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung total
$total = 0;
foreach ($items as $item) {
    $total += $item['jumlah'] * $item['harga'];
}
?>

<h4 class="mb-3">Keranjang</h4>
<?php if (count($items) === 0): ?>
    <div class="alert alert-warning">Keranjang kamu kosong.</div>
<?php else: ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Menu</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nama']) ?></td>
                    <td><?= $item['jumlah'] ?></td>
                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($item['jumlah'] * $item['harga'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total</th>
                <th>Rp <?= number_format($total, 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>
    <a href="transaksi.php" class="btn btn-danger">Lanjut ke Pembayaran</a>
<?php endif; ?>

<?php include 'partials/footer.php'; ?>
