<?php
session_start();
require '../koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['pesan'] = 'ID petugas tidak ditemukan!';
    $_SESSION['tipe'] = 'danger';
    header("Location: data_petugas.php");
    exit();
}

$stmt_select = $koneksi->prepare("SELECT id, nama, email, username, password, no_telephone, role, status FROM tb_admin WHERE id = ?");
if ($stmt_select === FALSE) {
    // Handle error preparing statement
    $_SESSION['pesan'] = 'Error menyiapkan query data admin: ' . $koneksi->error;
    $_SESSION['tipe'] = 'danger';
    header("Location: data_petugas.php");
    exit();
}
$stmt_select->bind_param("i", $id);
$stmt_select->execute();
$result_select = $stmt_select->get_result();
$row = $result_select->fetch_assoc();
$stmt_select->close();

if (!$row) {
    $_SESSION['pesan'] = 'Data petugas tidak ditemukan!';
    $_SESSION['tipe'] = 'danger';
    header("Location: data_petugas.php");
    exit();
}

// Cek apakah form disubmit
if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $no_telephone = $_POST['no_telephone'];
    $role = $_POST['role'];
    $status = isset($_POST['status']) && $_POST['status'] === 'true' ? 1 : 0;

    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $hashed_password_to_update = $row['password']; // Default: gunakan password lama

    // --- Validasi Password Baru (jika diisi) ---
    if (!empty($new_password)) {
        // Validasi panjang dan format password
        if (!preg_match("/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{6,12}$/", $new_password)) {
            $_SESSION['pesan'] = 'Password baru harus 6-12 karakter, kombinasi alfanumerik!';
            $_SESSION['tipe'] = 'danger';
        }

        // Validasi konfirmasi password
        if ($new_password !== $confirm_password) {
            $_SESSION['pesan'] = 'Konfirmasi password tidak cocok dengan password baru!';
            $_SESSION['tipe'] = 'danger';
        }

        // Hash password baru jika valid
        $hashed_password_to_update = password_hash($new_password, PASSWORD_DEFAULT);
    } else {
        // Jika password baru tidak diisi, pastikan confirm_password juga kosong
        if (!empty($confirm_password)) {
            $_SESSION['pesan'] = 'Jika tidak ingin mengubah password, kosongkan juga konfirmasi password!';
            $_SESSION['tipe'] = 'danger';
        }
    }

    $sql_update = "UPDATE tb_admin SET 
                   nama = ?,
                   email = ?,
                   username = ?,
                   password = ?,
                   no_telephone = ?,
                   role = ?,
                   status = ?,
                   updated_at = CURRENT_TIMESTAMP() 
                   WHERE id = ?";

    $stmt_update = $koneksi->prepare($sql_update);

    if ($stmt_update === FALSE) {
        $_SESSION['pesan'] = 'Error saat menyiapkan statement update: ' . $koneksi->error;
        $_SESSION['tipe'] = 'danger';
    }

    $stmt_update->bind_param("ssssssii", $nama, $email, $username, $hashed_password_to_update, $no_telephone, $role, $status, $id);

    if ($stmt_update->execute()) {
        $_SESSION['pesan'] = 'Data petugas berhasil diperbarui!';
        $_SESSION['tipe'] = 'success';
    } else {
        $_SESSION['pesan'] = 'Data petugas gagal diperbarui! Error: ' . $stmt_update->error;
        $_SESSION['tipe'] = 'danger';
    }

    $stmt_update->close();
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
            <h2 class="mb-4">Edit Data Petugas</h2>

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
            <div class="card shadow-sm border-0 p-3">
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama :</label>
                            <input type="text" class="form-control" name="nama" id="nama" value="<?= htmlspecialchars($row['nama']) ?>" required autocomplete="off">
                        </div>
                        <div class=" mb-3">
                            <label for="email" class="form-label">Email :</label>
                            <input type="email" class="form-control" name="email" id="email" value="<?= htmlspecialchars($row['email']) ?>" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username :</label>
                            <input type="text" class="form-control" name="username" id="username" value="<?= htmlspecialchars($row['username']) ?>" readonly autocomplete="off">
                        </div>
                        <div class="mb-3 password-input-group">
                            <label for="password" class="form-label">Password Baru (kosongkan jika tidak diubah):</label>
                            <input type="password" class="form-control" name="password" id="password" minlength="6" maxlength="12" pattern="^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{6,12}$" placeholder="******" title="Password harus 6-12 karakter, kombinasi huruf dan angka" aria-describedby="passwordHelp" autocomplete="off">
                            <span class="password-toggle" onclick="togglePasswordVisibility('password')">
                                <i class="fas fa-eye-slash"></i>
                            </span>
                            <div id="passwordHelp" class="form-text">Password harus 6-12 karakter, kombinasi alfanumerik.</div>
                        </div>
                        <div class="mb-3 password-input-group">
                            <label for="confirm_password" class="form-label">Konfirmasi Password Baru :</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" aria-describedby="confirmpasswordHelp" autocomplete="off">
                            <span class="password-toggle" onclick="togglePasswordVisibility('confirm_password')">
                                <i class="fas fa-eye-slash"></i>
                            </span>
                            <div id="confirmpasswordHelp" class="form-text">Konfirmasi password yang anda inputkan sebelumnya.</div>
                        </div>
                        <div class="mb-3">
                            <label for="no_telephone" class="form-label">No Telephone :</label>
                            <input type="number" class="form-control" name="no_telephone" id="no_telephone" value="<?= htmlspecialchars($row['no_telephone']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role :</label>
                            <input type="text" class="form-control" name="role" id="role" value="<?= htmlspecialchars($row['role']) ?>" required readonly>
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
                            <a href="data_petugas.php" class="btn btn-danger btn-lg">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/script2.js"></script>
</body>

</html>