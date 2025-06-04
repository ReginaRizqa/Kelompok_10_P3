<?php
require_once 'includes/db.php';
session_start();
$user_id = 1;

$id = $_GET['id'];
// Pastikan transaksi milik user yang sedang login
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);
$transaksi = $stmt->fetch();

if (!$transaksi) {
    echo "Transaksi tidak ditemukan.";
    exit;
}

// Ambil item transaksi
$itemStmt = $pdo->prepare("
    SELECT ti.jumlah, ti.harga_satuan, m.nama 
    FROM transaction_items ti
    JOIN menu m ON ti.menu_id = m.id
    WHERE ti.transaction_id = ?
");
$itemStmt->execute([$id]);
$items = $itemStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h1 class="mb-4 text-danger">Detail Transaksi</h1>
    <p><strong>Nama:</strong> <?= htmlspecialchars($transaksi['nama_lengkap']) ?></p>
    <p><strong>Alamat:</strong> <?= nl2br(htmlspecialchars($transaksi['alamat'])) ?></p>
    <p><strong>Metode Pembayaran:</strong> <?= ucfirst($transaksi['metode_pembayaran']) ?></p>
    <p><strong>Tanggal:</strong> <?= date('d-m-Y H:i', strtotime($transaksi['created_at'])) ?></p>

    <table class="table table-bordered bg-white mt-4">
        <thead class="table-danger">
            <tr>
                <th>Menu</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): 
                $subtotal = $item['jumlah'] * $item['harga_satuan'];
            ?>
            <tr>
                <td><?= $item['nama'] ?></td>
                <td><?= $item['jumlah'] ?></td>
                <td>Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h4 class="text-end mt-3">Total: <span class="text-danger">Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?></span></h4>

    <a href="riwayat_transaksi.php" class="btn btn-outline-danger mt-3">Kembali</a>
</div>
</body>
</html>
