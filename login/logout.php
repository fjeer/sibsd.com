<?php
session_start();
require_once '../koneksi.php';

// Hapus sesi
session_unset();
session_destroy();

// Hapus cookie "remember me" jika ada
if (isset($_COOKIE['remember_selector'])) {
    $selector = $_COOKIE['remember_selector'];
    $stmt_delete_token = $koneksi->prepare("DELETE FROM tb_remember_tokens WHERE selector = ?");
    if ($stmt_delete_token) {
        $stmt_delete_token->bind_param("s", $selector);
        $stmt_delete_token->execute();
        $stmt_delete_token->close();
    }
    setcookie('remember_selector', '', time() - 3600, '/');
    setcookie('remember_validator', '', time() - 3600, '/');
}

header("Location: login.php");
exit();
