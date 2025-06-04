<?php
require_once 'includes/db.php';

// Total Penjualan
$totalPenjualan = $pdo->query("SELECT SUM(total_harga) FROM transactions")->fetchColumn();

// Pesanan Hari Ini
$hariIni = date('Y-m-d');
$pesananHariIni = $pdo->prepare("SELECT COUNT(*) FROM transactions WHERE DATE(created_at) = ?");
$pesananHariIni->execute([$hariIni]);
$jumlahPesananHariIni = $pesananHariIni->fetchColumn();

// Jumlah Pelanggan
$jumlahPelanggan = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'pelanggan'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h1 class="mb-4 text-danger">Dashboard Admin</h1>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Penjualan</h5>
                    <p class="card-text fs-4">Rp <?= number_format($totalPenjualan, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Pesanan Hari Ini</h5>
                    <p class="card-text fs-4"><?= $jumlahPesananHariIni ?> Pesanan</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-secondary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Pelanggan</h5>
                    <p class="card-text fs-4"><?= $jumlahPelanggan ?> Orang</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
