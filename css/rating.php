<?php include 'partials/header.php'; include 'config/conn.php';

$user_id = $_SESSION['user']['id'];

// Ambil menu yang pernah dibeli user
$menus = $pdo->prepare("
    SELECT DISTINCT m.id, m.nama 
    FROM transaction_items ti 
    JOIN transactions t ON ti.transaction_id = t.id 
    JOIN menu m ON ti.menu_id = m.id 
    WHERE t.user_id = ? AND t.status = 'selesai'
");
$menus->execute([$user_id]);
$menus = $menus->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu_id = $_POST['menu_id'];
    $rating = (int) $_POST['rating'];

    $stmt = $pdo->prepare("UPDATE menu SET rating = (rating * jumlah_rating + ?) / (jumlah_rating + 1), jumlah_rating = jumlah_rating + 1 WHERE id = ?");
    $stmt->execute([$rating, $menu_id]);

    echo "<script>alert('Terima kasih atas rating Anda!'); window.location='menu.php';</script>";
    exit;
}
?>

<h4>Beri Rating Menu</h4>
<?php if (empty($menus)): ?>
    <div class="alert alert-warning">Kamu belum menyelesaikan transaksi apapun.</div>
<?php else: ?>
    <form method="POST">
        <div class="mb-3">
            <label>Menu</label>
            <select name="menu_id" required class="form-select">
                <?php foreach ($menus as $m): ?>
                    <option value="<?= $m['id'] ?>"><?= $m['nama'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Rating (1–5)</label>
            <input type="number" name="rating" class="form-control" min="1" max="5" required>
        </div>
        <button class="btn btn-danger">Kirim Rating</button>
    </form>
<?php endif; ?>

<?php include 'partials/footer.php'; ?>
