<?php include 'partials/header.php'; include 'config/conn.php';

// Ambil semua menu
$menus = $pdo->query("SELECT * FROM menu")->fetchAll(PDO::FETCH_ASSOC);

// Tangani tambah ke keranjang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['menu_id'])) {
    $menu_id = $_POST['menu_id'];
    $user_id = $_SESSION['user']['id'];

    // Cek apakah item sudah di keranjang
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND menu_id = ?");
    $stmt->execute([$user_id, $menu_id]);
    $item = $stmt->fetch();

    if ($item) {
        // Update jumlah
        $pdo->prepare("UPDATE cart SET jumlah = jumlah + 1 WHERE id = ?")->execute([$item['id']]);
    } else {
        // Tambah baru
        $pdo->prepare("INSERT INTO cart (user_id, menu_id, jumlah, created_at) VALUES (?, ?, 1, NOW())")
            ->execute([$user_id, $menu_id]);
    }

    header("Location: cart.php");
    exit;
}
?>

<div class="row">
    <?php foreach ($menus as $menu): ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <img src="assets/img/<?= $menu['gambar'] ?>" class="card-img-top" style="height:200px;object-fit:cover;">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($menu['nama']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($menu['deskripsi']) ?></p>
                    <p class="text-danger fw-bold">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></p>
                    <p>Rating: <?= number_format($menu['rating'], 1) ?> (<?= $menu['jumlah_rating'] ?> ulasan)</p>
                    <form method="POST">
                        <input type="hidden" name="menu_id" value="<?= $menu['id'] ?>">
                        <button class="btn btn-danger w-100">Tambah ke Keranjang</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'partials/footer.php'; ?>
