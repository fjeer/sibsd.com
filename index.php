<?php
require_once 'middleware/auth.php';
require 'dashboard.php';
?>
<?php require_once 'template/header.php'; ?>

        <!-- Sidebar -->
        <div class="sidebar">
            <div class="container">
                <ul class="nav nav-pills flex-column mt-3">
                    <li class="nav-link active"><a class="nav-link text-white fw-bold" href="index.php">Dashboard</a></li>
                    <li class="nav-item mt-1 mb-1"><span class="text-muted text-uppercase fw-bold small">Data Master</span></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="data_admin.php">Data Admin</a></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="data_petugas.php">Data Petugas</a></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="data_nasabah.php">Data Nasabah</a></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="data_sampah.php">Data Sampah</a></li>
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
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h1>Selamat Datang di Aplikasi Sistem Bank Sampah Digital</h1>
                    <p>Pilih menu di sebelah kiri untuk mulai mengelola data.</p>
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0 card-nasabah">
                                <div class="card-body">
                                    <h5 class="card-title">Nasabah Aktif</h5>
                                    <p class="display-6 fw-bold text-primary"><?= $jumlah_nasabah ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card shadow-sm border-0 card-petugas">
                                <div class="card-body">
                                    <h5 class="card-title">Petugas Aktif</h5>
                                    <p class="display-6 fw-bold text-success"><?= $jumlah_petugas ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card shadow-sm border-0 card-transaksi">
                                <div class="card-body">
                                    <h5 class="card-title">Riwayat Transaksi</h5>
                                    <p class="display-6 fw-bold text-warning"><?= $jumlah_transaksi ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-md border-secondary mb-5 bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Grafik Setoran Sampah Bulanan</h5>
                            <canvas id="sampahChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.bulan_labels = <?= json_encode($bulan_labels) ?>;
        window.jumlah_kg = <?= json_encode($jumlah_kg) ?>;
    </script>
    <script src="js/script.js"></script>
</body>

</html>