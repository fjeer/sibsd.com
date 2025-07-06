<?php
require 'config/koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['pesan'] = 'ID admin tidak ditemukan!';
    $_SESSION['tipe'] = 'danger';
    header("Location: data_admin.php");
    exit();
}

$stmt_select = $koneksi->prepare("SELECT id, nama, email, username, password, no_telephone, role, status FROM tb_admin WHERE id = ?");
if ($stmt_select === FALSE) {
    // Handle error preparing statement
    $_SESSION['pesan'] = 'Error menyiapkan query data admin: ' . $koneksi->error;
    $_SESSION['tipe'] = 'danger';
    header("Location: data_admin.php");
    exit();
}
$stmt_select->bind_param("i", $id);
$stmt_select->execute();
$result_select = $stmt_select->get_result();
$row = $result_select->fetch_assoc();
$stmt_select->close();

if (!$row) {
    $_SESSION['pesan'] = 'Data admin tidak ditemukan!';
    $_SESSION['tipe'] = 'danger';
    header("Location: data_admin.php");
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
        $_SESSION['pesan'] = 'Data admin berhasil diperbarui!';
        $_SESSION['tipe'] = 'success';
    } else {
        $_SESSION['pesan'] = 'Data admin gagal diperbarui! Error: ' . $stmt_update->error;
        $_SESSION['tipe'] = 'danger';
    }

    $stmt_update->close();
    header("Location: data_admin.php");
    exit();
}
?>

<?php require_once 'template/header.php'; ?>

        <div class="sidebar">
            <div class="container">
                <ul class="nav nav-pills flex-column mt-3">
                    <li class="nav-link"><a class="nav-link" href="index.php">Dashboard</a></li>
                    <li class="nav-item mt-1 mb-1"><span class="text-muted text-uppercase fw-bold small">Data Master</span></li>
                    <li class="nav-item mb-2"><a class="nav-link active fw-bold" href="data_admin.php">Data Admin</a></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="data_petugas.php">Data Petugas</a></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="data_nasabah.php">Data Nasabah</a></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="data_sampah.php">Data Sampah</a></li>
                    <li class="nav-item mt-2 mb-1"><span class="text-muted text-uppercase fw-bold small">Transaksi</span></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="setor_sampah.php">Transaksi Setor Sampah</a></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="riwayat_setor.php">Riwayat Transaksi</a></li>
                    <li class="nav-item mt-2 mb-1"><span class="text-muted text-uppercase fw-bold small">Laporan</span></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="#">Laporan</a></li>
                </ul>
            </div>
        </div>

        <div class="content pt-5 ms-250 px-3">
            <h2 class="mb-4">Edit Data Admin</h2>
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
                        <div class="mb-3">
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
                            <input type="number" class="form-control" name="no_telephone" id="no_telephone" value="<?= htmlspecialchars($row['no_telephone']) ?>" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role :</label>
                            <select class="form-select" name="role" required>
                                <option value="" disabled>-- Pilih Role --</option>
                                <option value="superadmin" <?= $row['role'] == 'superadmin' ? 'selected' : '' ?>>Superadmin</option>
                                <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>
                        <label class="form-label d-block">Status :</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="statusAktif" value="true" <?= $row['status'] == 1 ? 'checked' : '' ?>>
                            <label class="form-check-label" for="statusAktif">Aktif</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="statusNonAktif" value="false" <?= $row['status'] == 0 ? 'checked' : '' ?>>
                            <label class="form-check-label" for="statusNonAktif">Non Aktif</label>
                        </div>

                        <div class="mt-3">
                            <button type="submit" name="submit" class="btn btn-success btn-lg">Edit Data</button>
                            <a href="data_admin.php" class="btn btn-danger btn-lg">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script2.js"></script>
</body>

</html>