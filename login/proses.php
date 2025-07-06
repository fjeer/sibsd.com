<?php
require '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['user'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember_me = isset($_POST['remember_me']);

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
    $admin_data = $result->fetch_assoc();
    $stmt->close();

    if ($admin_data) {
        if (password_verify($password, $admin_data['password'])) {
            // Cek status aktif pengguna
            if ($admin_data['status'] == 0) {
                session_unset();
                session_destroy();
                $_SESSION['login_message'] = 'Akun Anda non-aktif. Silakan hubungi administrator.';
                $_SESSION['login_type'] = 'warning';
                header("Location: login.php");
                exit();
            }

            if($remember_me){
                setcookie('user', $user,time()+(86400*30),"/");
                setcookie('nama', $admin_data['nama'], time() + (86400 * 30), "/");
            }
            // LOGIN BERHASIL
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $admin_data['id'];
            $_SESSION['username'] = $admin_data['username'];
            $_SESSION['nama_lengkap'] = $admin_data['nama'];
            $_SESSION['role'] = $admin_data['role'];
            $_SESSION['status'] = $admin_data['status'];

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
