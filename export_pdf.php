<?php
require 'vendor/autoload.php';
require 'config/koneksi.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Ambil dan filter data sesuai GET
$dari = $_GET['dari'] ?? '';
$sampai = $_GET['sampai'] ?? '';
$id_nasabah = $_GET['id_nasabah'] ?? '';
$id_sampah = $_GET['id_sampah'] ?? '';

// Bangun WHERE clause
$where = "WHERE 1=1";
if ($dari && $sampai)
    $where .= " AND s.tanggal_transaksi BETWEEN '$dari' AND '$sampai'";
if ($id_nasabah)
    $where .= " AND s.id_nasabah = '$id_nasabah'";
if ($id_sampah)
    $where .= " AND ds.id_sampah = '$id_sampah'";

// Ambil data
$query = $koneksi->query("
    SELECT s.tanggal_transaksi, n.nama, sa.jenis_sampah, ds.berat_kg, ds.poin
    FROM tb_detail_setoran ds
    JOIN tb_setoran s ON s.id = ds.id_setoran
    JOIN tb_nasabah n ON n.id = s.id_nasabah
    JOIN tb_sampah sa ON sa.id = ds.id_sampah
    $where
    ORDER BY s.tanggal_transaksi ASC
");

$html = '<h3>Laporan Setor Sampah</h3>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">
<tr>
<th>Tanggal</th><th>Nasabah</th><th>Jenis Sampah</th><th>Berat (kg)</th><th>Poin</th>
</tr>';

while ($row = $query->fetch_assoc()) {
    $html .= "<tr>
    <td>{$row['tanggal_transaksi']}</td>
    <td>{$row['nama']}</td>
    <td>{$row['jenis_sampah']}</td>
    <td>{$row['berat_kg']}</td>
    <td>{$row['poin']}</td>
    </tr>";
}
$html .= '</table>';

// Export ke PDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("laporan_setor_sampah.pdf", ["Attachment" => false]);
exit();
