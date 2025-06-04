<?php include 'partials/header.php'; include 'config/conn.php';

if ($_SESSION['user']['role'] !== 'admin') {
    echo "<div class='alert alert-danger'>Akses ditolak.</div>";
    include 'partials/footer.php';
    exit;
}

$pelanggan = $pdo->query("SELECT u.id, u.nama, u.email, COUNT(t.id) as total_transaksi
                          FROM users u
                          LEFT JOIN transactions t ON u.id = t.user_id
                          WHERE u.role = 'pelanggan'
                          GROUP BY u.id")->fetchAll(PDO::FETCH_ASSOC);
?>

<h4>Data Pelanggan</h4>
<?php if (empty($pelanggan)): ?>
    <div class="alert alert-info">Belum ada pelanggan.</div>
<?php else: ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Jumlah Pesanan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pelanggan as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nama']) ?></td>
                    <td><?= htmlspecialchars($p['email']) ?></td>
                    <td><?= $p['total_transaksi'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include 'partials/footer.php'; ?>
