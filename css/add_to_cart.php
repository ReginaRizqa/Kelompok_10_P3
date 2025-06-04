<?php
session_start();
require_once 'includes/db.php';

// Simulasi user login (sementara hardcoded user_id = 1)
$user_id = 1;

if (isset($_GET['id'])) {
    $menu_id = (int) $_GET['id'];

    $stmt = $pdo->prepare("INSERT INTO cart (user_id, menu_id, jumlah) VALUES (?, ?, 1)");
    $stmt->execute([$user_id, $menu_id]);

    header("Location: menu.php?status=added");
    exit;
} else {
    echo "ID menu tidak valid.";
}
