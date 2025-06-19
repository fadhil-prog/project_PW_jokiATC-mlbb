<?php
session_start();
include "koneksi.php"; 

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => ''];

if (!isset($_SESSION['user']['id'])) {
    $response['message'] = "Anda tidak memiliki akses. Silakan login kembali.";
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION['user']['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

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
    $types = "ssi";
    $params = [$username, $email, $user_id];

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query_update = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
        $types = "sssi";
        $params = [$username, $email, $hashedPassword, $user_id];
    } else {
        $query_update = "UPDATE users SET username = ?, email = ? WHERE id = ?";
    }

    $stmt_update = $conn->prepare($query_update);

    call_user_func_array([$stmt_update, 'bind_param'], array_merge([$types], $params));

    if ($stmt_update->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Profil berhasil diperbarui.';

        if (isset($_SESSION['user'])) {
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['email'] = $email;
        }
    } else {
        $response['message'] = 'Gagal memperbarui profil: ' . $conn->error;
    }
    $stmt_update->close();
    $conn->close();

    echo json_encode($response);
    exit();

} else {
    $query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
    $data = mysqli_fetch_assoc($query);
    $response['message'] = "Akses tidak sah.";
    echo json_encode($response);
    exit();
}
?>