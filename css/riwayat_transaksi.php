<?php
require_once 'includes/db.php';
session_start();
$user_id = 1; // Ganti dengan $_SESSION['user_id'] setelah login

$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$transaksi = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h1 class="mb-4 text-danger">Riwayat Transaksi</h1>
    <?php if (count($transaksi) === 0): ?>
        <p>Anda belum melakukan transaksi.</p>
    <?php else: ?>
        <table class="table table-bordered bg-white">
            <thead class="table-danger">
                <tr>
                    <th>#</th>
                    <th>Waktu</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transaksi as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
                    <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                    <td><?= ucfirst($row['metode_pembayaran']) ?></td>
                    <td><a href="detail_transaksi.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger">Detail</a></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php endif ?>
</div>
</body>
</html>
