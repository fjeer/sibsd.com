<?php
require_once '../config/koneksi.php';

// Hapus token dari database
if (isset($_COOKIE['remember_me'])) {
    list($selector, $validator) = explode(':', base64_decode($_COOKIE['remember_me']));
    $stmt = $koneksi->prepare("DELETE FROM tb_remember_tokens WHERE selector = ?");
    $stmt->bind_param("s", $selector);
    $stmt->execute();
    $stmt->close();
}

// Hapus cookie
setcookie('remember_me', '', time() - 3600, "/");

// Hapus session
session_unset();
session_destroy();

header("Location: login.php");
exit();

