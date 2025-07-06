<?php
require_once 'middleware/auth.php';

$query = "
    SELECT
        ts.id,
        n.nin,
        n.nama AS nama_nasabah,
        ts.total_poin,
        n.saldo_poin,
        ts.tanggal_transaksi,
        ts.status_transaksi,
        ts.created_at,
        ts.updated_at,
        a.nama AS nama_admin,
        a.role
    FROM
        tb_setoran ts
    JOIN
        tb_nasabah n ON ts.id_nasabah = n.id
    JOIN
        tb_admin a ON ts.id_admin = a.id
    ORDER BY
        ts.tanggal_transaksi DESC, ts.created_at DESC;
";

$result = $koneksi->query($query);
$detail_transaksi = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $detail_transaksi[] = $row;
    }
}
?>
<?php require_once 'template/header.php'; ?>

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
                    <li class="nav-item mb-2"><a class="nav-link active fw-bold" href="riwayat_setor.php">Riwayat Transaksi</a></li>
                    <li class="nav-item mt-2 mb-1"><span class="text-muted text-uppercase fw-bold small">Laporan</span></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="#">Laporan</a></li>
                </ul>
            </div>
        </div>

        <div class="content pt-5 ms-250 px-3">
            <?php
            // Alert
            if (isset($_SESSION['pesan'])) :
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

            <h2 class="mb-4">Riwayat Setor Sampah</h2>
            <div class="card shadow-sm border-0 p-3">
                <div class="card-body"></div>

                <table class="table table-striped-columns table-hover">
                    <thead class="table-info">
                        <tr>
                            <th>No</th>
                            <th>Nama Nasabah</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Petugas</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-secondary">
                        <?php if (empty($detail_transaksi)) : ?>
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada riwayat transaksi ditemukan.</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($detail_transaksi as $index => $row) : ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($row['nama_nasabah']) ?></td>
                                    <td><?= htmlspecialchars($row['tanggal_transaksi']) ?></td>
                                    <td>
                                        <?php if ($row['status_transaksi'] == 1) :
                                        ?>
                                            <span class="badge bg-success">Berhasil</span>
                                        <?php else :
                                        ?>
                                            <span class="badge bg-danger">Gagal/Dibatalkan</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['nama_admin']) ?></td>
                                    <td>
                                        <!-- tombol lihat detail -->
                                        <button
                                            class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDetail<?= $row['id'] ?>">
                                            Detail
                                        </button>
                                        <a href="hapus_transaksi.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus transaksi ini beserta detailnya?')" class="btn btn-sm btn-danger">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Modal Detail -->
                <?php foreach ($detail_transaksi as $row): ?>
                    <div class="modal fade" id="modalDetail<?= $row['id'] ?>" tabindex="-1" aria-labelledby="modalDetailLabel<?= $row['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalDetailLabel<?= $row['id'] ?>">Detail Petugas</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Nomor Induk Nasabah:</strong> <?= htmlspecialchars($row['nin']) ?></p>
                                    <p><strong>Nama Nasabah:</strong> <?= htmlspecialchars($row['nama_nasabah']) ?></p>
                                    <p><strong>Petugas:</strong> <?= htmlspecialchars($row['nama_admin']) ?></p>
                                    <p><strong>Role:</strong> <?= htmlspecialchars($row['role']) ?></p>
                                    <p><strong>Tanggal Transaksi:</strong> <?= htmlspecialchars($row['tanggal_transaksi']) ?></p>
                                    <p><strong>Total Poin:</strong> <?= htmlspecialchars($row['total_poin']) ?></p>
                                    <p><strong>Saldo:</strong> <?= htmlspecialchars($row['saldo_poin']) ?></p>
                                    <p><strong>Status:</strong>
                                        <?= $row['status_transaksi'] ? '<span class="badge bg-success">Berhasil</span>' : '<span class="badge bg-secondary">Dibatalkan</span>' ?>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>