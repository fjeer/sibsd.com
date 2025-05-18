<?php
// Hitung nasabah aktif
$nasabah = $koneksi->query("SELECT COUNT(*) AS total FROM tb_nasabah WHERE status=true");
$jumlah_nasabah = $nasabah->fetch_assoc()['total'];

// Hitung petugas aktif
$petugas = $koneksi->query("SELECT COUNT(*) AS total FROM tb_admin WHERE role='petugas' AND status=true");
$jumlah_petugas = $petugas->fetch_assoc()['total'];

// Hitung semua riwayat transaksi
$transaksi = $koneksi->query("SELECT COUNT(*) AS total FROM tb_setor_sampah");
$jumlah_transaksi = $transaksi->fetch_assoc()['total'];

// Grafik setor sampah
$bulan_labels = [];
$jumlah_kg = [];

$query = $koneksi->query("SELECT MONTH(tanggal_transaksi) AS bulan, SUM(berat_sampah) AS total_kg FROM tb_setor_sampah GROUP BY MONTH(tanggal_transaksi)");

while ($row = $query->fetch_assoc()) {
    $bulan_labels[] = date('M', mktime(0, 0, 0, $row['bulan'], 1));
    $jumlah_kg[] = $row['total_kg'];
}