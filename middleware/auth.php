<?php
require './config/koneksi.php';
require_once './helpers/cookies.php';

// Jika sudah login via session, lanjutkan
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    return;
}

// Jika belum login tapi punya cookie remember_me
$user_id = verifyRememberMe($koneksi);
if ($user_id) {
    // Ambil data user berdasarkan ID
    $stmt = $koneksi->prepare("SELECT id, username, nama, role, status FROM tb_admin WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user && $user['status'] == 1) {
        // Login ulang dengan session
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['status'] = $user['status'];
        return;
    }
}

// Jika semua gagal, redirect ke login
header("Location: login.php");
exit();
