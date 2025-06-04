<?php
require_once 'includes/db.php';

$stmt = $pdo->query("SELECT * FROM menu ORDER BY id DESC");
$menuList = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Menu Makanan - Wonton dan Gohyong</title>
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h1 class="mb-4 text-danger">Menu Makanan</h1>
    <div class="row">
        <?php foreach ($menuList as $menu): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <img src="assets/img/<?= htmlspecialchars($menu['gambar']) ?>" class="card-img-top" alt="<?= htmlspecialchars($menu['nama']) ?>">
                    <div class="card-body">
                        <h5 class="card-title text-danger"><?= htmlspecialchars($menu['nama']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($menu['deskripsi']) ?></p>
                        <p class="mb-1 fw-bold">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></p>
                        <p class="text-warning">⭐ <?= number_format($menu['rating'], 1) ?> / 5</p>
                        <a href="add_to_cart.php?id=<?= $menu['id'] ?>" class="btn btn-outline-danger">Tambah ke Keranjang</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
