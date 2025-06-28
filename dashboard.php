<?php
// Hitung nasabah aktif
$nasabah = $koneksi->query("SELECT COUNT(*) AS total FROM tb_nasabah WHERE status=true");
$jumlah_nasabah = $nasabah->fetch_assoc()['total'];

// Hitung petugas aktif
$petugas = $koneksi->query("SELECT COUNT(*) AS total FROM tb_admin WHERE role='petugas' AND status=true");
$jumlah_petugas = $petugas->fetch_assoc()['total'];

// Hitung semua riwayat transaksi
$transaksi = $koneksi->query("SELECT COUNT(*) AS total FROM tb_setoran");
$jumlah_transaksi = $transaksi->fetch_assoc()['total'];

// Grafik setor sampah
$bulan_labels = [];
$jumlah_kg = [];

$query_grafik = "
    SELECT 
        MONTH(ts.tanggal_transaksi) AS bulan, 
        SUM(tds.berat_kg) AS total_kg 
    FROM 
        tb_setoran ts
    JOIN 
        tb_detail_setoran tds ON ts.id = tds.id_setoran
    GROUP BY 
        MONTH(ts.tanggal_transaksi)
    ORDER BY
        bulan ASC;
";

$result_grafik = $koneksi->query($query_grafik);

while ($row = $result_grafik->fetch_assoc()) {
    $bulan_labels[] = date('M', mktime(0, 0, 0, $row['bulan'], 1));
    $jumlah_kg[] = $row['total_kg'];
}