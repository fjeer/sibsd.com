<?php
session_start();
require '../koneksi.php';

$id = $_GET['id'];
$sql = "DELETE FROM tb_admin WHERE id = '$id'";
if ($koneksi->query($sql) === TRUE) {
    $_SESSION['pesan'] = 'Data petugas berhasil dihapus!';
    $_SESSION['tipe'] = 'success';
} else {
    $_SESSION['pesan'] = 'Data petugas gagal dihapus!';
    $_SESSION['tipe'] = 'danger';
}

header("Location: data_admin.php");
