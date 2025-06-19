<?php
session_start();
include "koneksi.php"; // Pastikan file koneksi database

// Cek login
if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$data = mysqli_fetch_assoc($query);

// Pastikan $data tidak null jika query gagal
if (!$data) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>JokiATC Website - Profil</title>
    <meta name="title" content="JokiATC Website - Profil" />
    <meta name="description" content="Halaman profil pengguna JokiATC." />

    <link rel="stylesheet" href="./main.css" />

    <link rel="icon" type="image/x-icon" href="./img/WhatsApp Image 2021-12-19 at 10.38.48 PM.jpeg" />

    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Martel+Sans:wght@300;400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="./main.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />

    <style>
        body {
            font-family: 'Martel Sans', sans-serif;
            background: linear-gradient(135deg, #dbeafe, #fdf2f8);
            min-height: 100vh;
            padding-top: 20px;
        }
        
        .contact h1,
        .contact h2,
        .contact h3 {
            font-weight: 700;
            color: #1e293b;
        }
        
        .card {
            background: rgba(255, 255, 255, 0.85);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        .list-group-item {
            background-color: transparent;
            border: none;
            font-size: 16px;
            padding: 12px 0;
            color: #1e293b;
        }

        .nav-link {
            color: #fffffc !important;
            }

        .nav-link:hover {
            color: #09C0FA !important ;
        }
        
        .btn {
            min-height: 45px;
            font-weight: 400; 
        }
        
        .btn-primary:hover {
            background-color: #2563eb;
        }
        
        .btn-success:hover {
            background-color: #16a34a;
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
        }
        
        #myBtn {
            background-color: #0ea5e9;
            border: none;
            padding: 10px 12px;
            border-radius: 50%;
            font-size: 18px;
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none;
            color: white;
            z-index: 999;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }
        
        #myBtn:hover {
            background-color: #0284c7;
        }
        
        .contact-logo {
            border-radius: 50%;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
        
        .profile-input {
            display: none;
            width: 100%;
        }
        
        .list-group-item strong {
            min-width: 100px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <button onclick="topFunction()" id="myBtn" title="Go to top"><i class="bi bi-hand-index-fill"></i></button>
    <script>
        var mybutton = document.getElementById("myBtn");

        window.onscroll = function() {
            scrollFunction();
        };

        function scrollFunction() {
            if (document.body.scrollTop > 400 || document.documentElement.scrollTop > 400) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }

        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand" href="paket.html">JokiATC</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon">
            <i class="bi bi-list"></i>
          </span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="index.html"
                >Beranda</a
              >
            </li>
            <li class="nav-item">
              <a class="nav-link" href="about.html">Tentang Kami</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="paket.html">Paket</a>
              </li>
            <li class="nav-item">
              <a class="nav-link" href="index.html#contact">Kontak</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="profil.php" style="color: #09C0FA !important">Profil</a>
            </li>
          </ul>
          <div class="d-flex">
            <a href="login.php" class="btn btn-outline-light me-2">Login</a>
            <a href="daftar.php" class="btn btn-light">Daftar</a>
          </div>
        </div>
      </div>
    </nav>
    <!-- Akhir Navbar -->

    <section class="contact" id="contact" style="margin-top:30px">
        <div class="container">
            <div class="container px-4 py-5" id="hanging-icons">
                <h2 class="pb-3">Profile Account</h2>
            </div>
            <div class="px-50 py-50 my-50 text-center">
                <img class="contact-logo d-block mx-auto mb-4" src="./img/WhatsApp Image 2021-12-19 at 10.38.48 PM.jpeg" alt="Logo JokiATC">
                <h1 class="display-5 fw-bold">Profil </h1>
                <div class="col-lg-6 mx-auto"></div>
            </div>
            <div class="container">
                <div class="card shadow-sm p-4">
                    <h3 class="mb-4">Informasi Profil</h3>
                    <ul class="list-group mb-3">
                        <li class="list-group-item">
                            <strong>Username:</strong> <span id="usernameText"><?= htmlspecialchars($data['username']) ?></span>
                            <input type="text" class="form-control profile-input" id="usernameInput" value="<?= htmlspecialchars($data['username']) ?>">
                        </li>
                        <li class="list-group-item">
                            <strong>Email:</strong> <span id="emailText"><?= htmlspecialchars($data['email']) ?></span>
                            <input type="email" class="form-control profile-input" id="emailInput" value="<?= htmlspecialchars($data['email']) ?>">
                        </li>
                        <li class="list-group-item">
                            <strong>Password:</strong> <span id="passwordText">********</span>
                            <input type="password" class="form-control profile-input" id="passwordInput" placeholder="Isi untuk mengubah password">
                        </li>
                    </ul>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-primary" id="editBtn" onclick="toggleEdit()">Edit Profil</button>
                        
                        <div id="saveCancelGroup" class="d-flex gap-2 d-none"> 
                            <button class="btn btn-success" id="saveBtn" onclick="saveProfile()">Simpan</button>
                            <button class="btn btn-secondary" id="cancelBtn" onclick="cancelEdit()">Batal</button>
                        </div>
                        
                        <a href="login.php" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        function toggleEdit() {
            // Sembunyikan teks, tampilkan input
            document.querySelectorAll('.profile-input').forEach(el => el.style.display = 'block');
            document.querySelectorAll('span[id$="Text"]').forEach(el => el.style.display = 'none');
            
            // Sembunyikan tombol "Edit Profil"
            document.getElementById('editBtn').classList.add('d-none');
            
            // Tampilkan grup tombol "Simpan" dan "Batal"
            document.getElementById('saveCancelGroup').classList.remove('d-none'); // Hapus d-none
            document.getElementById('saveCancelGroup').classList.add('d-flex'); // Tambahkan d-flex
        }

        function cancelEdit() {
            // Tampilkan teks, sembunyikan input
            document.querySelectorAll('.profile-input').forEach(el => el.style.display = 'none');
            document.querySelectorAll('span[id$="Text"]').forEach(el => el.style.display = 'inline'); // Atau 'block' tergantung styling Anda
            
            // Reset nilai input ke nilai asli (dari PHP)
            document.getElementById('usernameInput').value = "<?= htmlspecialchars($data['username']) ?>";
            document.getElementById('emailInput').value = "<?= htmlspecialchars($data['email']) ?>";
            document.getElementById('passwordInput').value = ""; // Kosongkan input password
            
            // Tampilkan tombol "Edit Profil"
            document.getElementById('editBtn').classList.remove('d-none');
            
            // Sembunyikan grup tombol "Simpan" dan "Batal"
            document.getElementById('saveCancelGroup').classList.add('d-none'); // Tambahkan d-none
            document.getElementById('saveCancelGroup').classList.remove('d-flex'); // Hapus d-flex
        }

        function saveProfile() {
            const username = document.getElementById('usernameInput').value;
            const email = document.getElementById('emailInput').value;
            const password = document.getElementById('passwordInput').value;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_profil.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status == 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert(response.message);
                            location.reload(); // Muat ulang halaman untuk menampilkan data terbaru
                        } else {
                            alert(response.message || "Gagal menyimpan perubahan.");
                        }
                    } catch (e) {
                        alert("Data berhasil disimpan.");
                        location.reload(); // Mungkin juga muat ulang untuk berjaga-jaga
                    }
                } else {
                    alert("Terjadi kesalahan saat menghubungi server: " + xhr.status);
                }
            };
            xhr.onerror = function() {
                alert("Koneksi ke server gagal. Periksa jaringan Anda.");
            };
            xhr.send("username=" + encodeURIComponent(username) + "&email=" + encodeURIComponent(email) + "&password=" + encodeURIComponent(password));
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".mySwiper", {
            spaceBetween: 30,
            centeredSlides: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    </script>
</body>

</html>