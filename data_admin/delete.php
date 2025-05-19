<?php
require '../koneksi.php';

$id = $_GET['id'];
$sql = "DELETE FROM tb_admin WHERE id = '$id'";
$koneksi->query($sql);

header("Location: data_admin.php");
