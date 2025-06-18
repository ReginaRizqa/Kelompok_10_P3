<?php
session_start();
include 'config/conn.php';

if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user'] = $user;
        header('Location: index.php');
        exit;
    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login | GoWon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(rgba(255, 255, 255, 0.33), rgba(255, 255, 255, 0.33)),
                        url('assets/img/bg wonton.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            padding: 30px;
            background-color: white;
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
            background-color: rgba(120, 10, 10, 0.91);
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
    <div class="col-md-4">
        <div class="card">
            <h4 class="card-title text-center mb-4">Login ke <span>GoWon</span></h4>
            <img src="assets/img/logo.png" alt="Logo GoWon" class="logo rounded-circle">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-danger">Login</button>
                    <a href="registrasi.php" class="btn btn-outline-danger">Belum punya akun? Daftar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

   
