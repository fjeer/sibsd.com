<?php
session_start();
require '../koneksi.php';

// Generate Nomor Induk Nasabah
// Tanggal hari ini
$tanggal = date('d');
$bulan = date('m');
$tahun = date('y'); // 2 digit tahun

$prefix = $tahun . $tanggal . $bulan; // contoh: 250518 (2025-18-05)

// Ambil nomor urut terakhir untuk hari ini
$query = $koneksi->query("SELECT nin FROM tb_nasabah WHERE nin LIKE '{$prefix}%' ORDER BY nin DESC LIMIT 1");
$data = $query->fetch_assoc();

if ($data) {
    $last_number = (int)substr($data['nin'], -4) + 1;
} else {
    $last_number = 1;
}

$nomor_urut = str_pad($last_number, 4, '0', STR_PAD_LEFT);
$nin = $prefix . $nomor_urut;

if (isset($_POST['submit'])) {
    $ninn = $_POST['nin'];
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $no_telephone = $_POST['no_telephone'];
    $tanggal_daftar = $_POST['tanggal_daftar'];

    $sql = "INSERT INTO tb_nasabah (nin, nama, jenis_kelamin, alamat, email, no_telephone, tanggal_daftar) 
        VALUES ('$ninn','$nama', '$jenis_kelamin', '$alamat', '$email', '$no_telephone', '$tanggal_daftar')";
    if ($koneksi->query($sql) === TRUE) {
        $_SESSION['pesan'] = 'Data nasabah berhasil ditambahkan!';
        $_SESSION['tipe'] = 'success';
    } else {
        $_SESSION['pesan'] = 'Data nasabah gagal ditambahkan!';
        $_SESSION['tipe'] = 'danger';
    }

    header("Location: data_nasabah.php");
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
                    <li class="nav-item mb-2"><a class="nav-link active fw-bold" href="data_nasabah.php">Data Nasabah</a></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="../data_sampah/data_sampah.php">Data Sampah</a></li>
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
            <h2 class="mb-4">Tambah Data Nasabah</h2>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="nin" class="form-label">Nin :</label>
                    <input type="text" class="form-control" name="nin" id="nin" placeholder="Nomor induk Nasabah" value="<?= $nin ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama :</label>
                    <input type="text" class="form-control" name="nama" id="nama" placeholder="contoh : Budie Arie" required>
                </div>
                <div class="mb-3">
                    <label class="form-label d-block">Jenis Kelamin :</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jenis_kelaminL" value="l" checked>
                        <label class="form-check-label" for="jenis_kelaminL">Laki-laki</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jenis_kelaminP" value="p">
                        <label class="form-check-label" for="jenis_kelaminP">Perempuan</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label d-block">Alamat :</label>
                    <div class="form-floating">
                        <textarea class="form-control" name="alamat" placeholder="jl.Malang Raya, Dusun Kaliwates Kec.BungurAsih Kab.Pangkalpinang" id="alamat" required></textarea>
                        <label for="alamat">isi alamat disini</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email :</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="contoh : Budiegaming@gamil.kom" required>
                </div>
                <div class="mb-3">
                    <label for="no_telephone" class="form-label">No Telephone :</label>
                    <input type="number" class="form-control" name="no_telephone" id="no_telephone" placeholder="contoh : 08262xxxxxxx" required>
                </div>
                <div class="mb-3">
                    <label for="tanggal_daftar" class="form-label">Tanggal daftar :</label>
                    <input type="date" class="form-control" name="tanggal_daftar" id="tanggal_daftar" required>
                </div>
                <button type="submit" name="submit" class="btn btn-success btn-lg">Simpan</button>
                <a href="data_nasabah.php" class="btn btn-danger btn-lg">Kembali</a>
            </form>
        </div>

    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>