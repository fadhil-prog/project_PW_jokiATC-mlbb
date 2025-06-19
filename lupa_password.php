<?php
session_start();
$host = "localhost";      
$user = "root";            
$password = "";            
$database = "joki_web";

$conn = mysqli_connect($host, $user, $password, $database);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        if ($password_baru === $konfirmasi_password) {
            $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
            $update = "UPDATE users SET password = '$password_hash' WHERE email = '$email'";
            if (mysqli_query($conn, $update)) {
                header("Location: login.php?reset=success");
                exit;
            } else {
                $error = "Gagal mengubah password.";
            } 
        } else {
            $error = "Konfirmasi password tidak cocok.";
        }
    } else {
        $error = "Email tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - JokiATC</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Martel+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./main.css">
</head>
<body style="font-family: 'Martel Sans', sans-serif; background: linear-gradient(135deg, #222, #444); color: white; min-height: 100vh;">

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow-lg p-4" style="width: 100%; max-width: 450px; background-color: #2c2f33; border-radius: 20px;">
            <div class="text-center mb-4">
                <h3 class="mt-3">Lupa Password</h3>
                <p class="text-muted">Masukkan email Anda dan ubah password baru.</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="nama@email.com">
                </div>
                <div class="mb-3">
                    <label for="password_baru" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" id="password_baru" name="password_baru" required>
                </div>
                <div class="mb-3">
                    <label for="konfirmasi_password" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" required>
                <div class="d-flex gap-2 mt-3 mb-3">
                    <button type="submit" class="btn btn-primary w-50">Reset Password</button>
                    <a href="login.php" class="btn btn-primary w-50 text-center py-2">Kembali ke Login</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
