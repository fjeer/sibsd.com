<?php
session_start();
require '../koneksi.php';

if (isset($_POST['submit'])) {
    $nasabah = $_POST['id_nasabah'];
    $jenis = $_POST['id_sampah'];
    $berat = $_POST['berat_sampah'];
    $tanggal = $_POST['tanggal'];

    // Ambil harga dari tabel sampah
    $query = $koneksi->query("SELECT harga_per_kg FROM tb_sampah WHERE id = '$jenis'");
    $data = $query->fetch_assoc();
    $harga = $data['harga_per_kg'];

    // Hitung total poin
    $total_poin = $berat * $harga;

    $sql = "INSERT INTO tb_setor_sampah (id_nasabah, id_sampah, berat_sampah, total_poin, tanggal_transaksi) 
        VALUES ('$nasabah', '$jenis', '$berat', '$total_poin', '$tanggal')";
    if ($koneksi->query($sql) === TRUE) {
        $_SESSION['pesan'] = 'Transaksi Setor sampah berhasil!';
        $_SESSION['tipe'] = 'success';

        // Update saldo_poin
        $query = $koneksi->query("SELECT SUM(total_poin) AS total FROM tb_setor_sampah WHERE id_nasabah = '$nasabah'");
        $row = $query->fetch_assoc();
        $total_poin = $row['total'] ?? 0;

        $koneksi->query("UPDATE tb_nasabah 
            SET saldo_poin = '$total_poin', 
            updated_at = CURRENT_TIMESTAMP()  
            WHERE id = '$nasabah'");
    } else {
        $_SESSION['pesan'] = 'Transaksi Setor sampah gagal!';
        $_SESSION['tipe'] = 'danger';
    }

    header("Location: riwayat_setor.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bank Sampah Digital</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anaheim:wght@400..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg shadow-sm navbar-custom fixed-top">
            <div class="container">
                <a class="navbar-brand" href="../index.php">
                    <img src="../img/bsd-logo.png" alt="">
                </a>
                <div class="ms-auto">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="../img/pp wa kosong sad.jpg" alt="Profile" class="profile-img rounded-circle" width="50" height="50">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li><a class="dropdown-item" href="#">Akun Saya</a></li>
                                <li><a class="dropdown-item text-danger" href="#">Keluar</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


        <!-- Sidebar -->
        <div class="sidebar">
            <div class="container">
                <ul class="nav nav-pills flex-column mt-3">
                    <li class="nav-link"><a class="nav-link" href="../index.php">Dashboard</a></li>
                    <li class="nav-item mt-1 mb-1"><span class="text-muted text-uppercase fw-bold small">Data Master</span></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="../data_admin/data_admin.php">Data Admin</a></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="../data_petugas/data_petugas.php">Data Petugas</a></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="../data_nasabah/data_nasabah.php">Data Nasabah</a></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="../data_sampah/data_sampah.php">Data Sampah</a></li>
                    <li class="nav-item mt-2 mb-1"><span class="text-muted text-uppercase fw-bold small">Transaksi</span></li>
                    <li class="nav-item mb-2"><a class="nav-link active" href="setor_sampah">Transaksi Setor Sampah</a></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="riwayat_setor.php">Riwayat Transaksi</a></li>
                    <li class="nav-item mt-2 mb-1"><span class="text-muted text-uppercase fw-bold small">Laporan</span></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="#">Laporan</a></li>
                </ul>
            </div>
        </div>
        <!-- Content -->
        <div class="content pt-5 ms-250 px-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Setor Sampah</h2>
                </div>
                <div class="card-body">
                    <form action="" method="POST">

                        <div class="mb-3">
                            <label for="nasabah" class="form-label">Nama Nasabah</label>
                            <select class="form-select" name="id_nasabah" id="nasabah" required>
                                <option value="">-- Pilih Nasabah --</option>
                                <?php
                                $data = $koneksi->query("SELECT id, nama FROM tb_nasabah WHERE status = 1");
                                while ($nasabah = $data->fetch_assoc()) {
                                    echo "<option value='{$nasabah['id']}'>{$nasabah['nama']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="jenis_sampah" class="form-label">Jenis Sampah</label>
                            <select class="form-select" name="id_sampah" id="jenis_sampah" required>
                                <option value="">-- Pilih Jenis Sampah --</option>
                                <?php
                                $data = $koneksi->query("SELECT id, jenis_sampah FROM tb_sampah WHERE status = 1");
                                while ($sampah = $data->fetch_assoc()) {
                                    echo "<option value='{$sampah['id']}'>{$sampah['jenis_sampah']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="berat" class="form-label">Berat Sampah (kg)</label>
                            <input type="number" step="0.1" class="form-control" name="berat_sampah" id="berat" required>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal Setor</label>
                            <input type="date" class="form-control" name="tanggal" id="tanggal" value="<?= date('d-m-Y') ?>" required>
                        </div>

                        <div class="mt-4">
                            <button type="submit" name="submit" class="btn btn-success btn-lg">Simpan Setor</button>
                            <a href="../index.php" class="btn btn-secondary btn-lg">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>