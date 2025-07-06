<?php
require 'config/koneksi.php';

$id = $_GET['id'];
$data = $koneksi->query("SELECT * FROM tb_nasabah WHERE id = '$id'");
$row = $data->fetch_assoc();

if (isset($_POST['submit'])) {
    $nin = $_POST['nin'];
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $no_telephone = $_POST['no_telephone'];
    $tanggal_daftar = $_POST['tanggal_daftar'];
    $status = $_POST['status'] ? 1 : 0;

    $sql = "UPDATE tb_nasabah SET 
            nama='$nama',
            jenis_kelamin='$jenis_kelamin',
            email='$email',
            no_telephone = '$no_telephone',
            status = '$status',
            updated_at = CURRENT_TIMESTAMP() 
            WHERE id='$id'";

    if ($koneksi->query($sql) === TRUE) {
        $_SESSION['pesan'] = 'Data nasabah berhasil diperbarui!';
        $_SESSION['tipe'] = 'success';
    } else {
        $_SESSION['pesan'] = 'Data nasabah gagal diperbarui!';
        $_SESSION['tipe'] = 'danger';
    }

    header("Location: data_nasabah.php");
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
                    <li class="nav-item mb-2"><a class="nav-link active fw-bold" href="data_nasabah.php">Data Nasabah</a></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="data_sampah.php">Data Sampah</a></li>
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
            <h2 class="mb-4">Edit Data Nasabah</h2>
            <div class="card shadow-sm border-0 p-3">
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="nin" class="form-label">Nin :</label>
                            <input type="text" class="form-control" name="nin" id="nin" value="<?= htmlspecialchars($row['nin']) ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama :</label>
                            <input type="text" class="form-control" name="nama" id="nama" value="<?= htmlspecialchars($row['nama']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Jenis Kelamin :</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="jenis_kelaminL" value="l" <?= $row['jenis_kelamin'] == 'l' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="jenis_kelaminL">Laki-laki</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="jenis_kelaminP" value="p" <?= $row['jenis_kelamin'] == 'p' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="jenis_kelaminP">Perempuan</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block" for="alamat">Alamat :</label>
                            <div class="form-floating">
                                <textarea class="form-control" name="alamat" id="alamat" required><?= htmlspecialchars($row['alamat']) ?></textarea>
                                <label for="alamat">isi alamat disini</label>
                            </div>
                        </div>
                        <div class=" mb-3">
                            <label for="email" class="form-label">Email :</label>
                            <input type="email" class="form-control" name="email" id="email" value="<?= htmlspecialchars($row['email']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="no_telephone" class="form-label">No Telephone :</label>
                            <input type="number" class="form-control" name="no_telephone" id="no_telephone" value="<?= htmlspecialchars($row['no_telephone']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_daftar" class="form-label">Tanggal daftar :</label>
                            <input type="date" class="form-control" name="tanggal_daftar" id="tanggal_daftar" value="<?= htmlspecialchars($row['tanggal_daftar']) ?>" readonly>
                        </div>
                        <label class="form-label d-block">Status :</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="statusAktif" value="true"
                                <?= $row['status'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="statusAktif">Aktif</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="statusNonAktif" value="false"
                                <?= !$row['status'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="statusNonAktif">Non Aktif</label>
                        </div>

                        <div class="mt-3">
                            <button type="submit" name="submit" class="btn btn-success btn-lg">Edit Data</button>
                            <a href="data_nasabah.php" class="btn btn-danger btn-lg">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>