<?php
session_start();
include 'config/conn.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        $error = "Email sudah digunakan!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nama, $email, $pass, $role]);
        echo "<script>alert('Registrasi berhasil, silakan login!'); window.location='login.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrasi | GoWon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background:linear-gradient(rgba(255, 255, 255, 0.33), rgba(255, 255, 255, 0.33)), 
                       url('assets/img/bg wonton.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .card-title {
            color: rgba(156, 18, 18, 0.91);
            font-weight: bold;
        }
        .btn-danger {
            background-color: rgba(156, 18, 18, 0.91);
            border: none;
        }
        .btn-danger:hover {
            background-color: rgba(156, 18, 18, 0.91);
        }
        .logo {
        width: 100px;
        height: 100px;
        object-fit: cover;
        margin: 0 auto 15px;
        display: block;
        }
    </style>
</head>
<body>
    <div class="col-md-5">
        <div class="card p-4">
            <h4 class="card-title text-center mb-4">Buat Akun <span >GoWon</span></h4>
            <img src="assets/img/logo.png" alt="Logo GoWon" class="logo rounded-circle">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Buat password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Daftar Sebagai</label>
                    <select name="role" class="form-select" required>
                        <option value="pelanggan">Pelanggan</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-danger">Daftar</button>
                    <a href="login.php" class="btn btn-outline-danger">Sudah punya akun? Login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
