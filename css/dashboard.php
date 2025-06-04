<?php include 'partials/header.php'; include 'config/conn.php';

// Hitung total penjualan
$totalPenjualan = $pdo->query("SELECT COALESCE(SUM(total_harga), 0) FROM transactions WHERE status = 'selesai'")->fetchColumn();

// Pesanan hari ini
$pesananHariIni = $pdo->prepare("SELECT COUNT(*) FROM transactions WHERE DATE(created_at) = CURDATE()");
$pesananHariIni->execute();
$jumlahPesanan = $pesananHariIni->fetchColumn();

// Jumlah pelanggan
$jumlahPelanggan = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'pelanggan'")->fetchColumn();
?>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3">
            <h5 class="text-muted">Total Penjualan</h5>
            <h3 class="text-danger">Rp <?= number_format($totalPenjualan, 0, ',', '.') ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3">
            <h5 class="text-muted">Pesanan Hari Ini</h5>
            <h3 class="text-danger"><?= $jumlahPesanan ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3">
            <h5 class="text-muted">Jumlah Pelanggan</h5>
            <h3 class="text-danger"><?= $jumlahPelanggan ?></h3>
        </div>
    </div>
</div>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <hr>
<h4>Daftar Transaksi</h4>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Total</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $stmt = $pdo->query("SELECT * FROM transactions ORDER BY created_at DESC");
        $transaksi = $stmt->fetchAll();

        foreach ($transaksi as $t):
        ?>
        <tr>
            <td><?= htmlspecialchars($t['nama_lengkap']) ?></td>
            <td>Rp <?= number_format($t['total_harga'], 0, ',', '.') ?></td>
            <td><?= ucfirst($t['status']) ?></td>
            <td><?= $t['created_at'] ?></td>
            <td>
                <?php if ($t['status'] === 'pending'): ?>
                    <a href="verifikasi_pembayaran.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Verifikasi transaksi ini?')">Verifikasi</a>
                <?php else: ?>
                    <span class="badge bg-success">Selesai</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>

<?php include 'partials/footer.php'; ?>
