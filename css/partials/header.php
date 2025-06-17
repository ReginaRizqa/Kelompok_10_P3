<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Wonton & Gohyong </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #fff5f5; }
        .navbar, .btn-danger, .text-danger { background-color:rgba(156, 18, 18, 0.91) !important; color: white !important; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container-fluid">
    <a class="navbar-brand text-white" href="dashboard.php">GoWon</a>
    <div>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link text-white" href="menu.php">Menu</a></li>
        
        <?php if ($user['role'] !== 'admin'): ?>
          <li class="nav-item"><a class="nav-link text-white" href="cart.php">Keranjang</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="transaksi.php">Transaksi</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="riwayat.php">Riwayat</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="rating.php">Rating</a></li>
        <?php endif; ?>

        <?php if ($user['role'] === 'admin'): ?>
          <li class="nav-item"><a class="nav-link text-white" href="penjualan.php">Penjualan</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="pelanggan.php">Pelanggan</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="tambah_menu.php">Tambah Menu</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="dashboard.php">Dashboard</a></li>
        <?php endif; ?>

        <li class="nav-item"><a class="nav-link text-white" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container mt-4">
