<?php

if (!isset($_SESSION['loggedin']) && isset($_COOKIE['remember_selector']) && isset($_COOKIE['remember_validator'])) {
    require_once 'koneksi.php'; // Pastikan koneksi DB tersedia

    $selector = $_COOKIE['remember_selector'];
    $validator = $_COOKIE['remember_validator'];

    $stmt = $koneksi->prepare("SELECT trt.user_id, trt.validator_hash, trt.expires_at, ta.id, ta.nama, ta.username, ta.email, ta.role, ta.status
                            FROM tb_remember_tokens trt
                            JOIN tb_admin ta ON trt.user_id = ta.id
                            WHERE trt.selector = ? AND trt.expires_at > NOW()");
    if ($stmt) {
        $stmt->bind_param("s", $selector);
        $stmt->execute();
        $result = $stmt->get_result();
        $token_data = $result->fetch_assoc();
        $stmt->close();

        if ($token_data && hash_equals($token_data['validator_hash'], hash('sha256', $validator))) {
            // Token valid, otentikasi pengguna
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $token_data['user_id'];
            $_SESSION['username'] = $token_data['username'];
            $_SESSION['nama_lengkap'] = $token_data['nama'];
            $_SESSION['role'] = $token_data['role'];
            $_SESSION['status'] = $token_data['status'];

            // cek status aktif pengguna dari data token
            if ($token_data['status'] == 0) {
                session_unset();
                session_destroy();
                // Hapus token dari DB dan cookie jika user non-aktif
                $stmt_delete = $koneksi->prepare("DELETE FROM tb_remember_tokens WHERE selector = ?");
                if ($stmt_delete) {
                    $stmt_delete->bind_param("s", $selector);
                    $stmt_delete->execute();
                    $stmt_delete->close();
                }
                setcookie('remember_selector', '', time() - 3600, '/');
                setcookie('remember_validator', '', time() - 3600, '/');
                // Tidak ada pesan ke session karena session sudah dihancurkan
                header("Location: /login/login.php?message=Akun+Anda+non-aktif.+Silakan+hubungi+administrator.");
                exit();
            }

            // Regenerate token untuk keamanan (prevent replay attacks)
            $new_validator = generateRandomToken(64);
            $new_validator_hash = hash('sha256', $new_validator);
            $expires_new = time() + (86400 * 7);

            $stmt_update_token = $koneksi->prepare("UPDATE tb_remember_tokens SET validator_hash = ?, expires_at = FROM_UNIXTIME(?) WHERE selector = ?");
            if ($stmt_update_token) {
                $stmt_update_token->bind_param("sis", $new_validator_hash, $expires_new, $selector);
                $stmt_update_token->execute();
                $stmt_update_token->close();

                setcookie('remember_selector', $selector, $expires_new, '/');
                setcookie('remember_validator', $new_validator, $expires_new, '/', '', false, true);
            }

            // Redirect untuk memuat ulang halaman dengan sesi baru
            // header("Location: " . $_SERVER['REQUEST_URI']); // Atau ke halaman dashboard utama
            // exit();
        } else {
            // Token tidak valid atau hash tidak cocok, hapus cookie dan token dari DB
            $stmt_delete = $koneksi->prepare("DELETE FROM tb_remember_tokens WHERE selector = ?");
            if ($stmt_delete) {
                $stmt_delete->bind_param("s", $selector);
                $stmt_delete->execute();
                $stmt_delete->close();
            }
            setcookie('remember_selector', '', time() - 3600, '/'); // Hapus cookie
            setcookie('remember_validator', '', time() - 3600, '/'); // Hapus cookie
        }
    } else {
        // Cookie ada tapi tidak ada di DB atau kedaluwarsa, hapus saja
        setcookie('remember_selector', '', time() - 3600, '/');
        setcookie('remember_validator', '', time() - 3600, '/');
    }
}
