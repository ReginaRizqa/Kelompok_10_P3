<?php
session_start();
include 'config/conn.php';

// Cek login dan role admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "<div class='alert alert-danger'>Akses ditolak.</div>";
    include 'partials/footer.php';
    exit;
}

// Ambil ID transaksi
$id = $_GET['id'] ?? null;

if ($id) {
    // Ubah status menjadi 'selesai'
    $stmt = $pdo->prepare("UPDATE transactions SET status = 'selesai' WHERE id = ?");
    $stmt->execute([$id]);

    // Kembali ke dashboard admin
    header("Location: dashboard.php");
    exit;
} else {
    echo "<div class='alert alert-warning'>ID transaksi tidak ditemukan.</div>";
}
?>
