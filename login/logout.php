<?php
session_start();
require_once '../koneksi.php';

// Hapus sesi
session_unset();
session_destroy();

// Hapus cookie "remember me" jika ada
if (isset($_COOKIE['user'])) {
    setcookie('user', '', time() - 3600, '/');
    setcookie('nama', '', time() - 3600, '/');
}

header("Location: login.php");
exit();
