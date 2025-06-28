<?php
session_start();
require '../koneksi.php';

// Fungsi untuk menghasilkan token acak yang aman
function generateRandomToken($length = 32)
{
    return bin2hex(random_bytes($length / 2));
}

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

            // LOGIN BERHASIL
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $admin_data['id'];
            $_SESSION['username'] = $admin_data['username'];
            $_SESSION['nama_lengkap'] = $admin_data['nama'];
            $_SESSION['role'] = $admin_data['role'];
            $_SESSION['status'] = $admin_data['status'];

            // Atur cookie "Remember Me" jika dicentang
            if ($remember_me) {
                $selector = generateRandomToken(12); // Selector 12 karakter hex
                $validator = generateRandomToken(64); // Validator 64 karakter hex
                $validator_hash = hash('sha256', $validator);

                // Cookie akan berlaku selama 7 hari
                $expires = time() + (86400 * 7); // 86400 detik = 1 hari

                // Simpan token ke database
                $stmt_insert_token = $koneksi->prepare("INSERT INTO tb_remember_tokens (user_id, selector, validator_hash, expires_at) VALUES (?, ?, ?, FROM_UNIXTIME(?))");
                if ($stmt_insert_token) {
                    $stmt_insert_token->bind_param("issi", $admin_data['id'], $selector, $validator_hash, $expires);
                    $stmt_insert_token->execute();
                    $stmt_insert_token->close();

                    // Set cookie ke browser pengguna
                    setcookie('remember_selector', $selector, $expires, '/'); // Path '/' agar berlaku di seluruh situs
                    setcookie('remember_validator', $validator, $expires, '/', '', false, true);
                }
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
