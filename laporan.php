<?php
require 'middleware/auth.php';
require_once 'middleware/role_check.php';
cekHakAkses(['superadmin', 'admin', 'petugas']);


$filter_nasabah = $_GET['id_nasabah'] ?? '';
$filter_jenis = $_GET['id_sampah'] ?? '';
$start_date = $_GET['dari'] ?? '';
$end_date = $_GET['sampai'] ?? '';

$query = "
    SELECT s.tanggal_transaksi, n.nama AS nama_nasabah, sp.jenis_sampah, d.berat_kg, d.poin
    FROM tb_setoran s
    JOIN tb_nasabah n ON s.id_nasabah = n.id
    JOIN tb_detail_setoran d ON d.id_setoran = s.id
    JOIN tb_sampah sp ON d.id_sampah = sp.id
    WHERE 1
";

if ($filter_nasabah) {
    $query .= " AND s.id_nasabah = '$filter_nasabah'";
}
if ($filter_jenis) {
    $query .= " AND d.id_sampah = '$filter_jenis'";
}
if ($start_date && $end_date) {
    $query .= " AND s.tanggal_transaksi BETWEEN '$start_date' AND '$end_date'";
}
$query .= " ORDER BY s.tanggal_transaksi DESC";
$data = $koneksi->query($query);
?>

<?php require 'template/header.php'; ?>

<!-- Sidebar -->
<div class="sidebar">
    <div class="container">
        <ul class="nav nav-pills flex-column mt-3">
            <li class="nav-link"><a class="nav-link" href="index.php">Dashboard</a></li>
            <li class="nav-item mt-1 mb-1"><span class="text-muted text-uppercase fw-bold small">Data Master</span></li>
            <li class="nav-item mb-2"><a class="nav-link" href="data_admin.php">Data Admin</a></li>
            <li class="nav-item mb-2"><a class="nav-link" href="data_petugas.php">Data Petugas</a></li>
            <li class="nav-item mb-2"><a class="nav-link" href="data_nasabah.php">Data Nasabah</a></li>
            <li class="nav-item mb-2"><a class="nav-link" href="data_sampah.php">Data Sampah</a></li>
            <li class="nav-item mt-2 mb-1"><span class="text-muted text-uppercase fw-bold small">Transaksi</span></li>
            <li class="nav-item mb-2"><a class="nav-link" href="setor_sampah.php">Transaksi Setor Sampah</a></li>
            <li class="nav-item mb-2"><a class="nav-link" href="riwayat_setor.php">Riwayat Transaksi</a></li>
            <li class="nav-item mt-2 mb-1"><span class="text-muted text-uppercase fw-bold small">Laporan</span></li>
            <li class="nav-item mb-2"><a class="nav-link active text-white fw-bold" href="laporan.php">Laporan</a></li>
        </ul>
    </div>
</div>

<div class="content pt-5 ms-250 px-3">
    <h2 class="mb-3">Laporan Setoran Sampah</h2>

    <div class="card shadow-sm border-0 p-3">
        <div class="card-body"></div>
        <form class="row g-3 mb-3" method="GET">
            <div class="col-md-3">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" class="form-control" name="dari" value="<?= $_GET['dari'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" class="form-control" name="sampai" value="<?= $_GET['sampai'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Nasabah</label>
                <select name="id_nasabah" class="form-select">
                    <option value="">Semua</option>
                    <?php
                    $nasabahs = $koneksi->query("SELECT id, nama FROM tb_nasabah WHERE status = 1");
                    while ($n = $nasabahs->fetch_assoc()) {
                        $selected = ($_GET['id_nasabah'] ?? '') == $n['id'] ? 'selected' : '';
                        echo "<option value='{$n['id']}' $selected>{$n['nama']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Jenis Sampah</label>
                <select name="id_sampah" class="form-select">
                    <option value="">Semua</option>
                    <?php
                    $sampahs = $koneksi->query("SELECT id, jenis_sampah FROM tb_sampah WHERE status = 1");
                    while ($s = $sampahs->fetch_assoc()) {
                        $selected = ($_GET['id_sampah'] ?? '') == $s['id'] ? 'selected' : '';
                        echo "<option value='{$s['id']}' $selected>{$s['jenis_sampah']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
                <a href="laporan.php" class="btn btn-secondary">Reset</a>
                <a href="export_pdf.php?<?= http_build_query($_GET) ?>" target="_blank" class="btn btn-danger">Export
                    PDF</a>
                <a href="export_excel.php?<?= http_build_query($_GET) ?>" target="_blank" class="btn btn-success">Export
                    Excel</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Nasabah</th>
                        <th>Jenis Sampah</th>
                        <th>Berat (kg)</th>
                        <th>Poin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_berat = 0;
                    $total_poin = 0;
                    while ($row = $data->fetch_assoc()):
                        $total_berat += $row['berat_kg'];
                        $total_poin += $row['poin'];
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['tanggal_transaksi']) ?></td>
                            <td><?= htmlspecialchars($row['nama_nasabah']) ?></td>
                            <td><?= htmlspecialchars($row['jenis_sampah']) ?></td>
                            <td><?= number_format($row['berat_kg'], 2) ?></td>
                            <td><?= number_format($row['poin'], 0) ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <tr class="table-secondary fw-bold">
                        <td colspan="3" class="text-end">Total</td>
                        <td><?= number_format($total_berat, 2) ?></td>
                        <td><?= number_format($total_poin, 0) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>