<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db = "joki_web"; // ganti sesuai nama database kamu

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$nama_pemesan   = $_POST['nama'];
$id_mole        = $_POST['id_mole'];
$nama_mole      = $_POST['nama_mole'];
$jenis_pembelian= $_POST['jenis_pembelian'];
$paket          = ($jenis_pembelian == "paket") ? $_POST['paket'] : "-";
$rank           = ($jenis_pembelian == "per_star") ? $_POST['rank'] : "-";
$bintang        = ($jenis_pembelian == "per_star") ? $_POST['jumlah_bintang'] : 0;
$harga = isset($_POST['harga']) ? str_replace('.', '', $_POST['harga']) : 0;
$no_wa          = $_POST['no_wa'];
$email          = $_POST['email'];

// Simpan ke database
$sql = "INSERT INTO pemesanan (nama_pemesan, id_mole, nama_mole, paketan, rank, bintang, harga, no_wa, email)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssss", $nama_pemesan, $id_mole, $nama_mole, $paket, $rank, $bintang, $harga, $no_wa, $email);

if ($stmt->execute()) {
    echo "<script>
        alert('Data berhasil disimpan!');
        window.location.href = 'paket.html'; 
    </script>";
} else {
    echo "Gagal menyimpan data: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
