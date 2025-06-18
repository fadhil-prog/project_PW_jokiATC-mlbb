<?php
// Koneksi database
$host = "localhost";
$user = "root";
$pass = "";
$db = "joki_web";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data terakhir (misal yang paling baru)
$result = $conn->query("SELECT * FROM pemesanan ORDER BY id_pemesan DESC LIMIT 1");

$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Pemesanan</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      padding: 30px;
    }
    .container {
      background: #fff;
      border-radius: 10px;
      padding: 20px 30px;
      max-width: 600px;
      margin: auto;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
    }
    table {
      width: 100%;
      margin-top: 20px;
    }
    td {
      padding: 10px;
      vertical-align: top;
    }
    .qris {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }
    .qris img {
      max-width: 300px;
      border: 1px solid #ccc;
      border-radius: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Detail Pemesanan Anda</h2>
    <table>
      <tr><td><strong>Nama:</strong></td><td><?= $data['nama_pemesan']; ?></td></tr>
      <tr><td><strong>ID MoLe:</strong></td><td><?= $data['id_mole']; ?></td></tr>
      <tr><td><strong>Nama MoLe:</strong></td><td><?= $data['nama_mole']; ?></td></tr>
      <tr><td><strong>Paket:</strong></td><td><?= $data['paketan']; ?></td></tr>
      <tr><td><strong>Rank:</strong></td><td><?= $data['rank']; ?></td></tr>
      <tr><td><strong>Jumlah Bintang:</strong></td><td><?= $data['bintang']; ?></td></tr>
      <tr><td><strong>Harga:</strong></td><td>Rp <?= number_format($data['harga'], 0, ',', '.'); ?></td></tr>
      <tr><td><strong>Nomor WA:</strong></td><td><?= $data['no_wa']; ?></td></tr>
      <tr><td><strong>Email:</strong></td><td><?= $data['email']; ?></td></tr>
    </table>

    <div class="qris">
      <img src="img/qris.jpg" alt="QRIS Pembayaran">
    </div>
  </div>
</body>
</html>
