<?php
$koneksi = new mysqli("localhost", "root", "", "joki_web");


session_start();



$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = $koneksi->query("SELECT * FROM users WHERE email='$email'");
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            echo "<script>window.location.href='index.html';</script>";
            exit;
        } else {
            $errorMessage = "Password salah!";
        }
    } else {
        $errorMessage = "Email tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    section {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
      height: 100vh;
      background-image: url(../jokiazy-mlbb-main/img/bg/pexels-photo-2098428.jpeg);
      background-size: cover;
      background-position: center;
      animation: animateBg 5s linear infinite;
    }

    @keyframes animateBg {
      100% {
        filter: hue-rotate(360deg);
      }
    }

    .login-box {
      position: relative;
      width: 400px;
      background: transparent;
      border: 2px solid rgba(255, 255, 255, .5);
      border-radius: 20px;
      padding: 40px 30px;
      backdrop-filter: blur(15px);
    }

    h2 {
      font-size: 2em;
      color: #fff;
      text-align: center;
    }

    .input-box {
      position: relative;
      width: 100%;
      margin: 30px 0;
      border-bottom: 2px solid #fff;
    }

    .input-box label {
      position: absolute;
      top: 50%;
      left: 5px;
      transform: translateY(-50%);
      font-size: 1em;
      color: #fff;
      pointer-events: none;
      transition: .5s;
    }

    .input-box input:focus~label,
    .input-box input:valid~label {
      top: -5px;
    }

    .input-box input {
      width: 100%;
      height: 50px;
      background: transparent;
      border: none;
      outline: none;
      font-size: 1em;
      color: #fff;
      padding: 0 35px 0 5px;
    }

    .input-box .icon {
      position: absolute;
      right: 8px;
      color: #fff;
      font-size: 1.2em;
      line-height: 57px;
    }

    button {
      width: 100%;
      height: 40px;
      background: #fff;
      border: none;
      outline: none;
      border-radius: 40px;
      cursor: pointer;
      font-size: 1em;
      color: #000;
      font-weight: 500;
    }

    .forgot-password-wrapper {
      margin-top: 20px;
      text-align: right;
    }

    .forgot-link {
      color: #ffc107;
      font-weight: 600;
      font-size: 0.95em;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .forgot-link:hover {
      color: #ffd65c;
      text-decoration: underline;
    }

    .register-link {
      font-size: .9em;
      color: #fff;
      text-align: center;
      margin: 25px 0 10px;
    }

    .register-link p a {
      color: #fff;
      text-decoration: none;
      font-weight: 600;
    }

    .register-link p a:hover {
      text-decoration: underline;
    }

    @media (max-width:360px) {
      .login-box {
        width: 100%;
        height: auto;
        border: none;
        border-radius: 0;
        padding: 20px;
      }
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      backdrop-filter: blur(5px);
      background-color: rgba(0, 0, 0, 0.4);
      justify-content: center;
      align-items: center;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.4s ease;
    }

    .modal.show {
      display: flex;
      opacity: 1;
      pointer-events: auto;
    }

    .modal-content {
      background-color: #fff;
      padding: 30px;
      border-radius: 15px; 
      text-align: center;
      width: 80%;
      max-width: 400px;
      transform: scale(0.8);
      opacity: 0;
      animation: fadeInModal 0.4s forwards;
    }

    .modal-content h3 {
      margin-bottom: 20px;
      color: #333;
    }

    .modal-content button {
      padding: 10px 25px;
      border: none;
      background-color: #007bff;
      color: white;
      border-radius: 25px;
      font-size: 1em;
      cursor: pointer;
    }

    @keyframes fadeInModal {
      to {
        transform: scale(1);
        opacity: 1;
      }
    }
  </style>
</head>
<body>
  <section>
    <div class="login-box">
      <form method="POST">
        <h2>Masuk</h2>
        <div class="input-box">
          <span class="icon"><ion-icon name="mail"></ion-icon></span>
          <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
          <label>Email</label>
        </div>
        <div class="input-box">
          <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
          <input type="password" name="password" required>
          <label>Kata Sandi</label>
        </div>
        <button type="submit">Masuk</button>
        <div class="forgot-password-wrapper">
          <a class="forgot-link" href="lupa_password.php">🔒 Lupa Kata Sandi?</a>
        </div>
        <div class="register-link">
          <p>Tidak punya akun? <a href="daftar.php">Daftar</a></p>
        </div>
      </form>
    </div>
  </section>

  <!-- Modal Error -->
  <div class="modal <?= $errorMessage ? 'show' : '' ?>" id="errorModal">
    <div class="modal-content">
      <h3><?= $errorMessage ?></h3>
      <button onclick="document.getElementById('errorModal').classList.remove('show')">OK</button>
    </div>
  </div>

  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>