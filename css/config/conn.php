<?php
// config/db.php
$host = 'localhost';
$db = 'db_wonton_gohyong';
$user = 'root'; // ganti jika ada user lain
$pass = '';     // ganti jika ada password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
