<?php
require 'middleware/auth.php';

// Proses pencarian
$where = "";
$keyword = isset($_GET['search']) ? $_GET['search'] : '';
if (!empty($keyword)) {
    $where = "WHERE (nin LIKE '%$keyword%' 
    OR jenis_sampah LIKE '%$keyword%' 
    OR harga_per_kg LIKE '%$keyword%'
    OR deskripsi LIKE '%$keyword%')";
}

// Query data
$result = $koneksi->query("SELECT * FROM tb_sampah $where");
$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
?>

<?php require_once 'template/header.php'; ?>

<!-- Sidebar -->
<div class="sidebar">
    <div class="container">
        <ul class="nav nav-pills flex-column mt-3">
            <li class="nav-link"><a class="nav-link" href="index.php">Dashboard</a></li>
            <li class="nav-item mt-1 mb-1"><span class="text-muted text-uppercase fw-bold small">Data Master</span></li>
            <li class="nav-item mb-2"><a class="nav-link" href="data_admin.php">Data Admin</a></li>
            <li class="nav-item mb-2"><a class="nav-link" href="data_petugas.php">Data Petugas</a></li>
            <li class="nav-item mb-2"><a class="nav-link" href="data_nasabah.php">Data Nasabah</a></li>
            <li class="nav-item mb-2"><a class="nav-link active fw-bold" href="data_sampah.php">Data Sampah</a></li>
            <li class="nav-item mt-2 mb-1"><span class="text-muted text-uppercase fw-bold small">Transaksi</span></li>
            <li class="nav-item mb-2"><a class="nav-link" href="setor_sampah.php">Transaksi Setor Sampah</a></li>
            <li class="nav-item mb-2"><a class="nav-link" href="riwayat_setor.php">Riwayat Transaksi</a></li>
            <li class="nav-item mt-2 mb-1"><span class="text-muted text-uppercase fw-bold small">Laporan</span></li>
            <li class="nav-item mb-2"><a class="nav-link" href="laporan.php">Laporan</a></li>
        </ul>
    </div>
</div>

<!-- Content -->
<div class="content pt-5 ms-250 px-3">
    <!-- Alert Pesan -->
    <?php
    if (isset($_SESSION['pesan'])):
        ?>
        <div class="alert alert-<?= $_SESSION['tipe'] ?> alert-dismissible fade show mb-3" role="alert">
            <strong><?= $_SESSION['pesan'] ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
        unset($_SESSION['pesan']);
        unset($_SESSION['tipe']);
    endif;
    ?>

    <h2 class="mb-4">Data Sampah</h2>
    <div class="card shadow-sm border-0 p-3">
        <div class="card-body">
            <!-- Form Pencarian -->
            <form class="d-flex mb-3" method="GET">
                <input class="form-control me-2" type="search" name="search" placeholder="Cari sampah..."
                    value="<?= htmlspecialchars($keyword) ?>">
                <button class="btn btn-outline-primary" type="submit">Cari</button>
            </form>

            <!-- Tombol Tambah -->
            <a href="tambah_sampah.php" class="btn btn-success mb-3">+ Tambah Sampah</a>

            <table class="table table-striped-columns table-hover">
                <thead class="table-info">
                    <tr>
                        <th>No</th>
                        <th>Jenis Sampah</th>
                        <th>Harga per Kg</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-secondary">
                    <?php if (empty($data)): ?>
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data sampah ditemukan.</td>
                        </tr>
                    <?php else: ?>
                        <?php
                        foreach ($data as $index => $row):
                            ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($row['jenis_sampah']) ?></td>
                                <td><?= htmlspecialchars($row['harga_per_kg']) ?></td>
                                <td>
                                    <?php if ($row['status'] == true): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <!-- tombol lihat detail -->
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalDetail<?= $row['id'] ?>">
                                        Detail
                                    </button>
                                    <!-- tombol edit -->
                                    <a href="ubah_sampah.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <!-- tombol hapus -->
                                    <a href="hapus_sampah.php?id=<?= $row['id'] ?>"
                                        onclick="return confirm('Yakin ingin menghapus data ini?')"
                                        class="btn btn-sm btn-danger">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Modal Detail -->
            <?php foreach ($data as $row): ?>
                <div class="modal fade" id="modalDetail<?= $row['id'] ?>" tabindex="-1"
                    aria-labelledby="modalDetailLabel<?= $row['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalDetailLabel<?= $row['id'] ?>">Detail Nasabah</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Jenis Sampah:</strong> <?= htmlspecialchars($row['jenis_sampah']) ?></p>
                                <p><strong>Harga per Kg:</strong> <?= htmlspecialchars($row['harga_per_kg']) ?></p>
                                <p><strong>Deskripsi:</strong> <?= htmlspecialchars($row['deskripsi']) ?></p>
                                <p><strong>Status:</strong>
                                    <?= $row['status'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' ?>
                                </p>
                                <p><strong>Tanggal Input:</strong> <?= htmlspecialchars($row['created_at']) ?></p>
                                <p><strong>Tanggal Update:</strong> <?= htmlspecialchars($row['updated_at']) ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>