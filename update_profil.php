<?php
session_start();
include "koneksi.php"; // Pastikan file koneksi database Anda

// Set header untuk memberitahu klien bahwa respons adalah JSON
header('Content-Type: application/json');

// Inisialisasi array respons
$response = ['status' => 'error', 'message' => ''];

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user']['id'])) {
    $response['message'] = "Anda tidak memiliki akses. Silakan login kembali.";
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION['user']['id'];

// Periksa apakah permintaan adalah POST (dari AJAX di profil.php)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? ''; // Ini adalah password baru (jika diisi)

    // --- Validasi Data ---
    if (empty($username) || empty($email)) {
        $response['message'] = "Username dan Email tidak boleh kosong.";
        echo json_encode($response);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "Format email tidak valid.";
        echo json_encode($response);
        exit();
    }

    // Periksa apakah username atau email sudah digunakan oleh pengguna lain (selain pengguna yang sedang login)
    $stmt_check = $conn->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
    $stmt_check->bind_param("ssi", $username, $email, $user_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $response['message'] = "Username atau Email sudah digunakan oleh akun lain.";
        echo json_encode($response);
        $stmt_check->close();
        exit();
    }
    $stmt_check->close();

    // --- Pembaruan Database ---
    $query_update = "";
    $types = "ssi"; // default untuk username, email, user_id
    $params = [$username, $email, $user_id];

    if (!empty($password)) {
        // Jika password diisi, hash password baru
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query_update = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
        $types = "sssi"; // Menambahkan 's' untuk password
        $params = [$username, $email, $hashedPassword, $user_id];
    } else {
        // Jika password kosong, jangan perbarui kolom password
        $query_update = "UPDATE users SET username = ?, email = ? WHERE id = ?";
    }

    $stmt_update = $conn->prepare($query_update);

    // Menggunakan call_user_func_array untuk bind_param dengan parameter dinamis
    call_user_func_array([$stmt_update, 'bind_param'], array_merge([$types], $params));

    if ($stmt_update->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Profil berhasil diperbarui.';

        // Perbarui data di session agar langsung ter refleksi tanpa reload penuh
        if (isset($_SESSION['user'])) {
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['email'] = $email;
            // Penting: Jangan perbarui password di session jika Anda tidak ingin menyimpan plain text
            // Biarkan password di session tetap ter-hash atau jangan simpan sama sekali
        }
    } else {
        $response['message'] = 'Gagal memperbarui profil: ' . $conn->error;
    }
    $stmt_update->close();
    $conn->close();

    echo json_encode($response);
    exit();

} else {
    // Jika diakses langsung tanpa metode POST, kembalikan data profil (opsional)
    // Atau bisa juga dianggap sebagai akses tidak sah
    $query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
    $data = mysqli_fetch_assoc($query);

    // Anda bisa mengembalikan data ini dalam format JSON juga,
    // atau biarkan halaman ini tidak melakukan apa-apa jika diakses langsung
    // dan hanya berfungsi sebagai endpoint AJAX POST.
    // Untuk tujuan ini, kita akan menganggap akses langsung tanpa POST adalah tidak valid
    $response['message'] = "Akses tidak sah.";
    echo json_encode($response);
    exit();
}
?>