<?php
require 'config/koneksi.php';

if (isset($_POST['submit'])) {
    $jenis = $_POST['jenis_sampah'];
    $harga = $_POST['harga_per_kg'];
    $deskripsi = $_POST['deskripsi'];

    $sql = "INSERT INTO tb_sampah (jenis_sampah, harga_per_kg, deskripsi) 
        VALUES ('$jenis', '$harga', '$deskripsi')";
    if ($koneksi->query($sql) === TRUE) {
        $_SESSION['pesan'] = 'Data sampah berhasil ditambahkan!';
        $_SESSION['tipe'] = 'success';
    } else {
        $_SESSION['pesan'] = 'Data sampah gagal ditambahkan!';
        $_SESSION['tipe'] = 'danger';
    }

    header("Location: data_sampah.php");
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
                    <li class="nav-item mb-2"><a class="nav-link" href="#">Laporan</a></li>
                </ul>
            </div>
        </div>
        <!-- Content -->
        <div class="content pt-5 ms-250 px-3">
            <h2 class="mb-4">Tambah Data Sampah</h2>
            <div class="card shadow-sm border-0 p-3">
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="jenis_sampah" class="form-label">Jenis Sampah :</label>
                            <input type="text" class="form-control" name="jenis_sampah" id="jenis_sampah" placeholder="contoh : Plastik" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga_per_kg" class="form-label">Harga per Kg :</label>
                            <input type="number" class="form-control" name="harga_per_kg" id="harga_per_kg" placeholder="contoh : 2000" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi :</label>
                            <input type="text" class="form-control" name="deskripsi" id="deskripsi" placeholder="contoh : Botol, Sedotan, Styrofoam dll">
                        </div>
                        <button type="submit" name="submit" class="btn btn-success btn-lg">Simpan</button>
                        <a href="data_sampah.php" class="btn btn-danger btn-lg">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>