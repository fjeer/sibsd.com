<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$id_admin = $_SESSION['user_id'];

if (isset($_POST['submit'])) {
    $id_nasabah = $_POST['id_nasabah'];
    $tanggal_transaksi = $_POST['tanggal'];

    $id_sampah_isi = $_POST['id_sampah'] ?? [];
    $berat_sampah_isi = $_POST['berat_sampah'] ?? [];

    if (empty($id_nasabah) || empty($tanggal_transaksi) || empty($id_sampah_isi)) {
        $_SESSION['pesan'] = 'Semua field harus diisi, dan minimal ada satu jenis sampah!';
        $_SESSION['tipe'] = 'danger';
    }

    $koneksi->begin_transaction();

    try {
        // 1. Insert ke tb_setoran
        $stmt_setoran = $koneksi->prepare("INSERT INTO tb_setoran (id_nasabah, id_admin, tanggal_transaksi) VALUES (?, ?, ?)");
        if (!$stmt_setoran) {
            throw new Exception("Prepare statement for tb_setoran gagal: " . $koneksi->error);
        }
        $stmt_setoran->bind_param("iis", $id_nasabah, $id_admin, $tanggal_transaksi);
        $stmt_setoran->execute();

        $id_setoran_baru = $koneksi->insert_id;
        $total_poin = 0;

        // 2. Loop dan Insert ke tb_detail_setoran
        for ($i = 0; $i < count($id_sampah_isi); $i++) {
            $id_sampah = $id_sampah_isi[$i];
            $berat_kg = (float)$berat_sampah_isi[$i];

            // Ambil harga_per_kg dari tb_sampah untuk setiap jenis sampah
            $stmt_harga = $koneksi->prepare("SELECT harga_per_kg FROM tb_sampah WHERE id = ?");
            if (!$stmt_harga) {
                throw new Exception("Prepare statement for tb_sampah gagal: " . $koneksi->error);
            }
            $stmt_harga->bind_param("i", $id_sampah);
            $stmt_harga->execute();
            $result_harga = $stmt_harga->get_result();
            $data_sampah = $result_harga->fetch_assoc();
            $stmt_harga->close();

            if (!$data_sampah) {
                throw new Exception("Jenis sampah dengan ID {$id_sampah} tidak ditemukan.");
            }

            $harga_per_kg = $data_sampah['harga_per_kg'];
            $poin = $berat_kg * $harga_per_kg;

            // Insert ke tb_detail_setoran
            $stmt_detail = $koneksi->prepare("INSERT INTO tb_detail_setoran (id_setoran, id_sampah, berat_kg, poin) VALUES (?, ?, ?, ?)");
            if (!$stmt_detail) {
                throw new Exception("Prepare statement for tb_detail_setoran gagal: " . $koneksi->error);
            }
            $stmt_detail->bind_param("iidi", $id_setoran_baru, $id_sampah, $berat_kg, $poin);
            $stmt_detail->execute();

            $total_poin += $poin;
        }

        // 3. Update total_poin di tb_setoran
        $stmt_update_setoran = $koneksi->prepare("UPDATE tb_setoran SET total_poin = ? WHERE id = ?");
        if (!$stmt_update_setoran) {
            throw new Exception("Prepare statement for update tb_setoran gagal: " . $koneksi->error);
        }
        $stmt_update_setoran->bind_param("ii", $total_poin, $id_setoran_baru);
        $stmt_update_setoran->execute();

        // 4. Update saldo_poin di tb_nasabah
        $stmt_get_saldo = $koneksi->prepare("SELECT saldo_poin FROM tb_nasabah WHERE id = ?");
        if (!$stmt_get_saldo) {
            throw new Exception("Prepare statement for get saldo_poin gagal: " . $koneksi->error);
        }
        $stmt_get_saldo->bind_param("i", $id_nasabah);
        $stmt_get_saldo->execute();
        $result_get_saldo = $stmt_get_saldo->get_result();
        $row_saldo = $result_get_saldo->fetch_assoc();
        $saldo_poin_sekarang = $row_saldo['saldo_poin'] ?? 0;
        $stmt_get_saldo->close();

        // Tambahkan total_poin_keseluruhan dari transaksi ini ke saldo yang sudah ada
        $saldo_poin_baru = $saldo_poin_sekarang + $total_poin;

        $stmt_update_nasabah = $koneksi->prepare("UPDATE tb_nasabah SET saldo_poin = ?, updated_at = CURRENT_TIMESTAMP() WHERE id = ?");
        if (!$stmt_update_nasabah) {
            throw new Exception("Prepare statement for update tb_nasabah gagal: " . $koneksi->error);
        }
        $stmt_update_nasabah->bind_param("di", $saldo_poin_baru, $id_nasabah);
        $stmt_update_nasabah->execute();

        // Commit transaksi jika semua berhasil
        $koneksi->commit();
        $_SESSION['pesan'] = "Setoran sampah berhasil dicatat.";
        $_SESSION['tipe'] = "success";
    } catch (Exception $e) {
        $koneksi->rollback();
        $_SESSION['pesan'] = 'Transaksi Setor sampah gagal! ' . $e->getMessage();
        $_SESSION['tipe'] = 'danger';
        error_log("Setor Sampah Error: " . $e->getMessage());
    } finally {
        if (isset($stmt_setoran) && $stmt_setoran !== false) $stmt_setoran->close();
        if (isset($stmt_detail) && $stmt_detail !== false) $stmt_detail->close();
        if (isset($stmt_update_setoran) && $stmt_update_setoran !== false) $stmt_update_setoran->close();
        if (isset($stmt_update_nasabah) && $stmt_update_nasabah !== false) $stmt_update_nasabah->close();
    }

    header("Location: riwayat_setor.php");
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
    <link rel="stylesheet" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anaheim:wght@400..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <div class="container">
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
                                <li><a class="dropdown-item text-danger" href="../admin/logout.php">Keluar</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


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
                    <li class="nav-item mb-2"><a class="nav-link active" href="setor_sampah.php">Transaksi Setor Sampah</a></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="riwayat_setor.php">Riwayat Transaksi</a></li>
                    <li class="nav-item mt-2 mb-1"><span class="text-muted text-uppercase fw-bold small">Laporan</span></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="#">Laporan</a></li>
                </ul>
            </div>
        </div>
        <div class="content pt-5 ms-250 px-3">
            <h2 class="mb-3">Setor Sampah</h2>
            <?php
            if (isset($_SESSION['pesan']) && isset($_SESSION['tipe'])) {
                echo '<div class="alert alert-' . $_SESSION['tipe'] . ' alert-dismissible fade show" role="alert">';
                echo $_SESSION['pesan'];
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '</div>';
                unset($_SESSION['pesan']);
                unset($_SESSION['tipe']);
            }
            ?>
            <div class="card shadow-sm border-0 p-3">
                <div class="card-body">
                    <form action="" method="POST">

                        <div class="mb-3">
                            <label for="nasabah" class="form-label">Nama Nasabah</label>
                            <select class="form-select" name="id_nasabah" id="nasabah" required>
                                <option value="">-- Pilih Nasabah --</option>
                                <?php
                                $data_nasabah = $koneksi->query("SELECT id, nama FROM tb_nasabah WHERE status = 1");
                                while ($nasabah = $data_nasabah->fetch_assoc()) {
                                    echo "<option value='{$nasabah['id']}'>{$nasabah['nama']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal Setor</label>
                            <input type="date" class="form-control" name="tanggal" id="tanggal" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <hr>
                        <h4>Detail Sampah</h4>
                        <div id="item_list">
                            <div class="row item-row mb-3 align-items-end">
                                <div class="col-md-5">
                                    <label for="jenis_sampah_0" class="form-label">Jenis Sampah</label>
                                    <select class="form-select" name="id_sampah[]" id="jenis_sampah_0" required>
                                        <option value="">-- Pilih Jenis Sampah --</option>
                                        <?php
                                        $data_sampah_html = $koneksi->query("SELECT id, jenis_sampah FROM tb_sampah WHERE status = 1");
                                        while ($sampah_html = $data_sampah_html->fetch_assoc()) {
                                            echo "<option value='{$sampah_html['id']}'>{$sampah_html['jenis_sampah']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label for="berat_0" class="form-label">Berat Sampah (kg)</label>
                                    <input type="number" step="0.01" min="0" class="form-control" name="berat_sampah[]" id="berat_0" required>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-item" style="display:none;">Hapus</button>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-primary btn-sm mt-3" id="add_item_btn"> + Tambah Sampah</button>

                        <div class="mt-4">
                            <button type="submit" name="submit" class="btn btn-success btn-lg">Simpan Setor</button>
                            <a href="../index.php" class="btn btn-danger btn-lg">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/script3.js"></script>
</body>

</html>