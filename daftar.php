<?php
$koneksi = new mysqli("localhost", "root", "", "joki_web");

$errorMessage = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username && $email && $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $cekUsername = $koneksi->query("SELECT * FROM users WHERE username='$username'");
        if ($cekUsername->num_rows > 0) {
            $errorMessage = "Username sudah digunakan!";
        } else {
            $cekEmail = $koneksi->query("SELECT * FROM users WHERE email='$email'");
            if ($cekEmail->num_rows > 0) {
                $errorMessage = "Email sudah digunakan!";
            } else {
                $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
                if ($koneksi->query($query) === TRUE) {
                    $success = true;
                } else {
                    $errorMessage = "Gagal menyimpan data: " . $koneksi->error;
                }
            }
        }
    } else {
        $errorMessage = "Semua field harus diisi!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar</title>
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
  height: 550px;
  background: transparent;
  border: 2px solid rgba(255, 255, 255, .5);
  border-radius: 20px;
  display: flex;
  justify-content: center;
  align-items: center;
  backdrop-filter: blur(15px);
}

h2 {
  font-size: 2em;
  color: #fff;
  text-align: center;
}

.input-box {
  position: relative;
  width: 310px;
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
    height: 100vh;
    border: none;
    border-radius: 0;
  }

  .input-box {
    width: 290px;
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
.modal { display: none; position: fixed; ... }
.modal.show { display: flex; ... }
.modal-content { background: white; padding: 20px; border-radius: 10px; text-align: center; }
</style>

</head>


<body>
  <section>
    <div class="login-box">
      <form method="POST">
        <h2>Daftar</h2>
        <div class="input-box">
          <input type="text" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
          <label>Username</label>
        </div>
        <div class="input-box">
          <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
          <label>Email</label>
        </div>
        <div class="input-box">
          <input type="password" name="password" required>
          <label>Kata Sandi</label>
        </div>
        <button type="submit">Daftar</button>
        <div class="register-link">
          <p>Sudah punya akun? <a href="login.php">Masuk</a></p>
        </div>
      </form>
    </div>
  </section>

  <!-- Modal Success -->
  <div class="modal <?= $success ? 'show' : '' ?>" id="successModal">
    <div class="modal-content">
      <h3>Akun berhasil dibuat!</h3>
      <button onclick="window.location.href='index.html'">OK</button>
    </div>
  </div>

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
