<?php
require 'config/koneksi.php';

$id = $_GET['id'];
$sql = "DELETE FROM tb_setoran WHERE id = '$id'";
if ($koneksi->query($sql) === TRUE) {
    $_SESSION['pesan'] = 'Data transaksi berhasil dihapus!';
    $_SESSION['tipe'] = 'success';
} else {
    $_SESSION['pesan'] = 'Data transaksi gagal dihapus!';
    $_SESSION['tipe'] = 'danger';
}

header("Location: riwayat_setor.php");
