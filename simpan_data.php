<?php
session_start();

// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db = "joki_web";

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$nama_pemesan    = $_POST['nama'];
$id_mole         = $_POST['id_mole'];
$nama_mole       = $_POST['nama_mole'];
$jenis_pembelian = $_POST['jenis_pembelian'];
$daftar_paket = [
    "1" => "Classic",
    "2" => "Mythic Grading",
    "3" => "MRO",
    "4" => "10 Star Master",
    "5" => "10 Star Grandmaster",
    "6" => "22 Star Epic",
    "7" => "18 Star Epic",
    "8" => "9 Star Mythic Biasa",
    "9" => "8 Star Mythic Honor",
    "10" => "6 Star Mythic Glory",
    "11" => "5 Star Mythic Immortal",
];
$paket = "-";
if ($jenis_pembelian == "paket" && isset($_POST['paket']) && isset($daftar_paket[$_POST['paket']])) {
    $paket = $daftar_paket[$_POST['paket']];
}
$rank            = ($jenis_pembelian == "per_star") ? $_POST['rank'] : "-";
$bintang         = ($jenis_pembelian == "per_star") ? intval($_POST['jumlah_bintang']) : 0;
$harga = !empty($_POST['total_harga']) ? intval($_POST['total_harga']) : 0;
$no_wa           = $_POST['no_wa'];
$email           = $_POST['email'];


// Simpan ke database
$sql = "INSERT INTO pemesanan (nama_pemesan, id_mole, nama_mole, paketan, rank, bintang, harga, no_wa, email)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssiiss", $nama_pemesan, $id_mole, $nama_mole, $paket, $rank, $bintang, $harga, $no_wa, $email);

if ($stmt->execute()) {
    $last_id = $conn->insert_id;
    header("Location: detail_pemesanan.php?id=" . $last_id);
    exit(); 
} else {
    echo "Gagal menyimpan data: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
