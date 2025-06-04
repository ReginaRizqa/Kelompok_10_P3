<?php
require_once 'includes/db.php';
session_start();
$user_id = 1; // nanti diganti session user_id

$stmt = $pdo->prepare("
    SELECT cart.*, menu.nama, menu.harga, menu.gambar
    FROM cart 
    JOIN menu ON cart.menu_id = menu.id 
    WHERE cart.user_id = ?
");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();

$total = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h1 class="mb-4 text-danger">Keranjang Belanja</h1>
    <?php if (count($items) === 0): ?>
        <p>Keranjang Anda kosong.</p>
    <?php else: ?>
        <table class="table table-bordered bg-white">
            <thead class="table-danger">
                <tr>
                    <th>Gambar</th>
                    <th>Menu</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): 
                    $subtotal = $item['jumlah'] * $item['harga'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><img src="assets/img/<?= $item['gambar'] ?>" width="80"></td>
                    <td><?= $item['nama'] ?></td>
                    <td><?= $item['jumlah'] ?></td>
                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h4 class="text-end">Total: <span class="text-danger">Rp <?= number_format($total, 0, ',', '.') ?></span></h4>
        <div class="text-end mt-3">
            <a href="checkout.php" class="btn btn-danger">Lanjut ke Checkout</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
