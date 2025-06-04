<?php
require_once 'includes/db.php';
session_start();
$user_id = 1;

// Ambil item dari keranjang
$stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $metode = $_POST['metode'];

    // Hitung total
    $total = 0;
    foreach ($items as $item) {
        $menu = $pdo->prepare("SELECT harga FROM menu WHERE id = ?");
        $menu->execute([$item['menu_id']]);
        $harga = $menu->fetchColumn();
        $total += $harga * $item['jumlah'];
    }

    // Insert ke transactions
    $insert = $pdo->prepare("INSERT INTO transactions (user_id, nama_lengkap, alamat, total_harga, metode_pembayaran) VALUES (?, ?, ?, ?, ?)");
    $insert->execute([$user_id, $nama, $alamat, $total, $metode]);
    $trans_id = $pdo->lastInsertId();

    // Insert items
    foreach ($items as $item) {
        $menu_id = $item['menu_id'];
        $jumlah = $item['jumlah'];
        $harga = $pdo->prepare("SELECT harga FROM menu WHERE id = ?");
        $harga->execute([$menu_id]);
        $harga_satuan = $harga->fetchColumn();

        $insertItem = $pdo->prepare("INSERT INTO transaction_items (transaction_id, menu_id, jumlah, harga_satuan) VALUES (?, ?, ?, ?)");
        $insertItem->execute([$trans_id, $menu_id, $jumlah, $harga_satuan]);
    }

    // Kosongkan keranjang
    $pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$user_id]);

    header("Location: transaksi_sukses.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h1 class="text-danger mb-4">Checkout</h1>
    <form method="post">
        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Alamat Pengiriman</label>
            <textarea name="alamat" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label>Metode Pembayaran</label>
            <select name="metode" class="form-select" required>
                <option value="transfer">Transfer Bank</option>
                <option value="e-wallet">E-Wallet</option>
                <option value="cod">Cash on Delivery</option>
            </select>
        </div>
        <button type="submit" class="btn btn-danger">Konfirmasi Pesanan</button>
    </form>
</div>
</body>
</html>
