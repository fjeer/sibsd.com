<?php
session_start();
require '../koneksi.php';

$id = $_GET['id'];
$sql = "DELETE FROM tb_sampah WHERE id = '$id'";
if ($koneksi->query($sql) === TRUE) {
    $_SESSION['pesan'] = 'Data sampah berhasil dihapus!';
    $_SESSION['tipe'] = 'success';
} else {
    $_SESSION['pesan'] = 'Data sampah gagal dihapus!';
    $_SESSION['tipe'] = 'danger';
}

header("Location: data_sampah.php");
