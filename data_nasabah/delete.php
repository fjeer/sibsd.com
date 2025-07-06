<?php
require '../koneksi.php';

$id = $_GET['id'];
$sql = "DELETE FROM tb_nasabah WHERE id = '$id'";
if ($koneksi->query($sql) === TRUE) {
    $_SESSION['pesan'] = 'Data nasabah berhasil dihapus!';
    $_SESSION['tipe'] = 'success';
} else {
    $_SESSION['pesan'] = 'Data nasabah gagal dihapus!';
    $_SESSION['tipe'] = 'danger';
}

header("Location: data_nasabah.php");
