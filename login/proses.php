<?php
session_start();
require '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['user'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($user) || empty($password)) {
        $_SESSION['login_message'] = 'Username/Email dan Password harus diisi!';
        $_SESSION['login_type'] = 'danger';
        header("Location: login.php");
        exit();
    }

    $stmt = $koneksi->prepare("SELECT id, nama, username, email, password, role, status FROM tb_admin WHERE username = ? OR email = ? LIMIT 1");

    if ($stmt === FALSE) {
        $_SESSION['login_message'] = 'Terjadi kesalahan sistem. Silakan coba lagi nanti. (Error: ' . $koneksi->error . ')';
        $_SESSION['login_type'] = 'danger';
        header("Location: login.php");
        exit();
    }

    $stmt->bind_param("ss", $user, $user);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {

        if (password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['status'] = $user['status'];

            // cek status aktif pengguna
            if ($user['status'] == 0) { 
                session_unset(); 
                session_destroy(); 
                $_SESSION['login_message'] = 'Akun Anda non-aktif. Silakan hubungi administrator.';
                $_SESSION['login_type'] = 'warning';
                header("Location: login.php");
                exit();
            }
            header("Location: ../index.php");
            exit();
        } else {
            $_SESSION['login_message'] = 'Password salah!';
            $_SESSION['login_type'] = 'danger';
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['login_message'] = 'Username atau Email tidak terdaftar!';
        $_SESSION['login_type'] = 'danger';
        header("Location: login.php");
        exit();
    }
} else {

    header("Location: login.php");
    exit();
}
