<?php
session_start();
require '../koneksi.php';

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $no_telephone = $_POST['no_telephone'];
    $role = $_POST['role'];

    // --- Validasi Username ---
    // Memastikan username hanya huruf kecil dan maksimal 12 karakter
    if (!preg_match("/^[a-z]{1,12}$/", $username)) {
        $_SESSION['pesan'] = 'Username hanya boleh huruf kecil dan maksimal 12 karakter!';
        $_SESSION['tipe'] = 'warning';
    }

    // --- Validasi Password ---
    // Memastikan password 6-12 karakter, kombinasi alfanumerik
    if (!preg_match("/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{6,12}$/", $password)) {
        $_SESSION['pesan'] = 'Password harus 6-12 karakter, kombinasi alfanumerik!';
        $_SESSION['tipe'] = 'warning';
    }

    // --- Validasi Konfirmasi Password ---
    if ($password !== $confirm_password) {
        $_SESSION['pesan'] = 'Konfirmasi password tidak cocok dengan password!';
        $_SESSION['tipe'] = 'danger';
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $sql = "INSERT INTO tb_admin (nama, email, username, password, no_telephone, role) VALUES (?, ?, ?, ?, ?, ?)";

    // Membuat prepared statement
    $stmt = $koneksi->prepare($sql);

    // Memeriksa jika prepared statement gagal
    if ($stmt === FALSE) {
        $_SESSION['pesan'] = 'Error saat menyiapkan statement: ' . $koneksi->error;
        $_SESSION['tipe'] = 'warning';
    }

    $stmt->bind_param("ssssss", $nama, $email, $username, $hashed_password, $no_telephone, $role);

    // Mengeksekusi statement
    if ($stmt->execute()) {
        $_SESSION['pesan'] = 'Data petugas berhasil ditambahkan!';
        $_SESSION['tipe'] = 'success';
    } else {
        $_SESSION['pesan'] = 'Data petugas gagal ditambahkan! Error: ' . $stmt->error;
        $_SESSION['tipe'] = 'danger';
    }

    // Menutup statement
    $stmt->close();

    header("Location: data_petugas.php");
    exit();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                    <li class="nav-item mb-2"><a class="nav-link active fw-bold" href="data_petugas.php">Data Petugas</a></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="../data_nasabah/data_nasabah.php">Data Nasabah</a></li>
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
            <h2 class="mb-4">Tambah Data Petugas</h2>

            <?php
            // --- Tampilkan Alert ---
            if (isset($_SESSION['pesan'])): ?>
                <div class="alert alert-<?= $_SESSION['tipe'] ?> alert-dismissible fade show mb-3" role="alert">
                    <strong><?= $_SESSION['pesan'] ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php
                unset($_SESSION['pesan']);
                unset($_SESSION['tipe']);
            endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama :</label>
                    <input type="text" class="form-control" name="nama" id="nama" placeholder="contoh : Budie Arie" required autocomplete="off">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email :</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="contoh : Budiegaming@gamil.kom" required autocomplete="off">
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username :</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="contoh : budie" maxlength="12" pattern="[a-z]{1,12}" title="Username hanya boleh huruf kecil dan maksimal 12 karakter" aria-describedby="usernameHelp" required autocomplete="off">
                    <div id="usernameHelp" class="form-text">Username hanya boleh huruf kecil dan maksimal 12 karakter.</div>
                </div>
                <div class="mb-3 password-input-group">
                    <label for="password" class="form-label">Password :</label>
                    <input type="password" class="form-control" name="password" id="password" minlength="6" maxlength="12" pattern="^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{6,12}$" title="Password harus 6-12 karakter, kombinasi huruf dan angka" aria-describedby="passwordHelp" required autocomplete="off">
                    <span class="password-toggle" onclick="togglePasswordVisibility('password')">
                        <i class="fas fa-eye-slash" id="togglePasswordIcon"></i>
                    </span>
                    <div id="passwordHelp" class="form-text">Password harus 6-12 karakter, kombinasi alfanumerik.</div>
                </div>
                <div class="mb-3 password-input-group">
                    <label for="confirm_password" class="form-label">Konfirmasi Password :</label>
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" aria-describedby="confirmpasswordHelp" required autocomplete="off">
                    <span class="password-toggle" onclick="togglePasswordVisibility('confirm_password')">
                        <i class="fas fa-eye-slash" id="toggleConfirmPasswordIcon"></i>
                    </span>
                    <div id="confirmpasswordHelp" class="form-text">Konfirmasi password yang anda inputkan sebelumnya.</div>
                </div>
                <div class="mb-3">
                    <label for="no_telephone" class="form-label">No Telephone :</label>
                    <input type="number" class="form-control" name="no_telephone" id="no_telephone" placeholder="contoh : 08262xxxxxxx" required autocomplete="off">
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role :</label>
                    <input type="text" class="form-control" name="role" id="role" value="petugas" readonly>
                </div>
                <button type="submit" name="submit" class="btn btn-success btn-lg">Simpan</button>
                <a href="data_petugas.php" class="btn btn-danger btn-lg">Kembali</a>
            </form>
        </div>

    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/script2.js"></script>
</body>

</html>