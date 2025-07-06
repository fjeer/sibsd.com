<?php
function cekHakAkses(array $roles_diizinkan)
{
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: ./auth/login.php");
        exit();
    }

    if (!in_array($_SESSION['role'], $roles_diizinkan)) {
        echo "<script>alert('Anda tidak memiliki izin untuk mengakses halaman ini!'); window.location.href='index.php';</script>";
        exit();
    }
}
