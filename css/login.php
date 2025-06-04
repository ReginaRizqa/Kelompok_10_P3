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
    <title>Login | Wonton & Gohyong</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #fff5f5, #ffeaea);
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
            color: #d32f2f;
            font-weight: bold;
        }
        .btn-danger {
            background-color: #d32f2f;
            border: none;
        }
        .btn-danger:hover {
            background-color: #b71c1c;
        }
    </style>
</head>
<body>
    <div class="col-md-4">
        <div class="card p-4">
            <h4 class="card-title text-center mb-4">Login ke <span style="color:#d32f2f;">Wonton & Gohyong</span></h4>
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
