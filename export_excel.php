<?php
require 'config/koneksi.php';

header("Content-Type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan_setor_sampah.xls");

$dari = $_GET['dari'] ?? '';
$sampai = $_GET['sampai'] ?? '';
$id_nasabah = $_GET['id_nasabah'] ?? '';
$id_sampah = $_GET['id_sampah'] ?? '';

$where = "WHERE 1=1";
if ($dari && $sampai)
    $where .= " AND s.tanggal_transaksi BETWEEN '$dari' AND '$sampai'";
if ($id_nasabah)
    $where .= " AND s.id_nasabah = '$id_nasabah'";
if ($id_sampah)
    $where .= " AND ds.id_sampah = '$id_sampah'";

$query = $koneksi->query("
    SELECT s.tanggal_transaksi, n.nama, sa.jenis_sampah, ds.berat_kg, ds.poin
    FROM tb_detail_setoran ds
    JOIN tb_setoran s ON s.id = ds.id_setoran
    JOIN tb_nasabah n ON n.id = s.id_nasabah
    JOIN tb_sampah sa ON sa.id = ds.id_sampah
    $where
    ORDER BY s.tanggal_transaksi ASC
");

echo "<table border='1'>
<tr>
<th>Tanggal</th><th>Nasabah</th><th>Jenis Sampah</th><th>Berat (kg)</th><th>Poin</th>
</tr>";

while ($row = $query->fetch_assoc()) {
    echo "<tr>
    <td>{$row['tanggal_transaksi']}</td>
    <td>{$row['nama']}</td>
    <td>{$row['jenis_sampah']}</td>
    <td>{$row['berat_kg']}</td>
    <td>{$row['poin']}</td>
    </tr>";
}
echo "</table>";
