<?php
require '../koneksi.php';

$id = $_GET['id'];
$data = $koneksi->query("SELECT * FROM tb_sampah WHERE id = '$id'");
$row = $data->fetch_assoc();

if (isset($_POST['submit'])) {
    $jenis = $_POST['jenis_sampah'];
    $harga = $_POST['harga_per_kg'];
    $deskripsi = $_POST['deskripsi'];
    $status = $_POST['status'] ? 1 : 0;

    $sql = "UPDATE tb_sampah SET 
            jenis_sampah='$jenis',
            harga_per_kg='$harga',
            deskripsi = '$deskripsi',
            status = '$status',
            updated_at = CURRENT_TIMESTAMP() 
            WHERE id='$id'";

    if ($koneksi->query($sql) === TRUE) {
        $_SESSION['pesan'] = 'Data sampah berhasil diperbarui!';
        $_SESSION['tipe'] = 'success';
    } else {
        $_SESSION['pesan'] = 'Data sampah gagal diperbarui!';
        $_SESSION['tipe'] = 'danger';
    }

    header("Location: data_sampah.php");
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
                    <li class="nav-item mb-2"><a class="nav-link" href="../data_petugas.php">Data Petugas</a></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="../data_nasabah/data_nasabah.php">Data Nasabah</a></li>
                    <li class="nav-item mb-2"><a class="nav-link active fw-bold" href="data_sampah/data_sampah.php">Data Sampah</a></li>
                    <li class="nav-item mt-2 mb-1"><span class="text-muted text-uppercase fw-bold small">Transaksi</span></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="../setor_sampah/setor_sampah.php">Transaksi Setor Sampah</a></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="../setor_sampah/riwayat_setor.php">Riwayat Transaksi</a></li>
                    <li class="nav-item mt-2 mb-1"><span class="text-muted text-uppercase fw-bold small">Laporan</span></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="#">Laporan</a></li>
                </ul>
            </div>
        </div>
        <!-- Content -->
        <div class="content pt-5 ms-250 px-3">
            <h2 class="mb-4">Edit Data Petugas</h2>
            <div class="card shadow-sm border-0 p-3">
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="jenis_sampah" class="form-label">Jenis Sampah :</label>
                            <input type="text" class="form-control" name="jenis_sampah" id="jenis_sampah" value="<?= htmlspecialchars($row['jenis_sampah']) ?>" required>
                        </div>
                        <div class=" mb-3">
                            <label for="harga_per_kg" class="form-label">Harga per Kg :</label>
                            <input type="number" class="form-control" name="harga_per_kg" id="harga_per_kg" value="<?= htmlspecialchars($row['harga_per_kg']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi :</label>
                            <input type="text" class="form-control" name="deskripsi" id="deskripsi" value="<?= htmlspecialchars($row['deskripsi']) ?>" required>
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
                            <a href="data_sampah.php" class="btn btn-danger btn-lg">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>