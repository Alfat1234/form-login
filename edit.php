<?php
// Konfigurasi
require_once 'includes/config.php';
require_once 'includes/auth.php';

// Cek login dan role
requireLogin();
requireRole(ROLE_ADMIN);

$page_title = 'Edit Data Mahasiswa';

// Ambil NIM dari URL
$nim = isset($_GET['nim']) ? trim($_GET['nim']) : '';

if (empty($nim)) {
    header("Location: " . APP_URL . "/index.php");
    exit;
}

// Query data mahasiswa
$query = "SELECT * FROM mahasiswa WHERE nim = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "s", $nim);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$mahasiswa = mysqli_fetch_assoc($result);

if (!$mahasiswa) {
    header("Location: " . APP_URL . "/index.php?error=Data tidak ditemukan");
    exit;
}

include 'includes/header.php';

$error = '';
$success = false;

// Proses form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $prodi = trim($_POST['prodi'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    
    // Validasi input
    if (empty($nama) || empty($prodi) || empty($alamat)) {
        $error = 'Semua field harus diisi!';
    } else {
        $gambar = $mahasiswa['gambar'];
        
        // Upload gambar baru jika ada
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['gambar']['tmp_name'];
            $file_name = basename($_FILES['gambar']['name']);
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array(strtolower($file_ext), $allowed_ext)) {
                $new_filename = uniqid() . '.' . $file_ext;
                if (move_uploaded_file($file_tmp, UPLOAD_DIR . $new_filename)) {
                    // Hapus gambar lama jika ada
                    if (!empty($mahasiswa['gambar']) && file_exists(UPLOAD_DIR . $mahasiswa['gambar'])) {
                        unlink(UPLOAD_DIR . $mahasiswa['gambar']);
                    }
                    $gambar = $new_filename;
                }
            } else {
                $error = 'Format file tidak didukung!';
            }
        }
        
        if (empty($error)) {
            $update_query = "UPDATE mahasiswa SET nama = ?, prodi = ?, alamat = ?, gambar = ? WHERE nim = ?";
            $update_stmt = mysqli_prepare($koneksi, $update_query);
            mysqli_stmt_bind_param($update_stmt, "sssss", $nama, $prodi, $alamat, $gambar, $nim);
            
            if (mysqli_stmt_execute($update_stmt)) {
                $success = true;
                // Update data lokal untuk ditampilkan
                $mahasiswa['nama'] = $nama;
                $mahasiswa['prodi'] = $prodi;
                $mahasiswa['alamat'] = $alamat;
                $mahasiswa['gambar'] = $gambar;
                
                echo '<script>
                    setTimeout(function() {
                        window.location.href = "' . APP_URL . '/index.php";
                    }, 1000);
                </script>';
            } else {
                $error = 'Gagal mengupdate data: ' . mysqli_error($koneksi);
            }
        }
    }
}
?>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Edit Data Mahasiswa</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Edit Data - <?= htmlspecialchars($mahasiswa['nama']) ?></h6>
                                </div>
                                <div class="card-body">
                                    <?php if ($error): ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($success): ?>
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <i class="fas fa-check-circle"></i> Data berhasil diupdate! Mengalihkan...
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>

                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="nim">NIM</label>
                                            <input type="text" class="form-control" id="nim" value="<?= htmlspecialchars($mahasiswa['nim']) ?>" disabled>
                                            <small class="form-text text-muted">NIM tidak dapat diubah</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($mahasiswa['nama']) ?>" required placeholder="Masukkan nama lengkap">
                                        </div>

                                        <div class="form-group">
                                            <label for="prodi">Program Studi <span class="text-danger">*</span></label>
                                            <select class="form-control" id="prodi" name="prodi" required>
                                                <option value="">-- Pilih Program Studi --</option>
                                                <option value="Teknik Informatika" <?= ($mahasiswa['prodi'] === 'Teknik Informatika') ? 'selected' : '' ?>>Teknik Informatika</option>
                                                <option value="Sistem Informasi" <?= ($mahasiswa['prodi'] === 'Sistem Informasi') ? 'selected' : '' ?>>Sistem Informasi</option>
                                                <option value="Manajemen Informatika" <?= ($mahasiswa['prodi'] === 'Manajemen Informatika') ? 'selected' : '' ?>>Manajemen Informatika</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="alamat">Alamat <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="alamat" name="alamat" rows="4" required placeholder="Masukkan alamat"><?= htmlspecialchars($mahasiswa['alamat']) ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Foto Mahasiswa Saat Ini</label>
                                            <?php if (!empty($mahasiswa['gambar']) && file_exists(UPLOAD_DIR . $mahasiswa['gambar'])): ?>
                                                <div class="mb-2">
                                                    <img src="<?php echo APP_URL; ?>/upload/<?= htmlspecialchars($mahasiswa['gambar']) ?>" class="img-thumbnail" alt="<?= htmlspecialchars($mahasiswa['nama']) ?>" width="150">
                                                </div>
                                            <?php else: ?>
                                                <div class="alert alert-info" role="alert">
                                                    <i class="fas fa-info-circle"></i> Tidak ada foto
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="gambar">Ganti Foto Mahasiswa</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="gambar" name="gambar" accept="image/*">
                                                <label class="custom-file-label" for="gambar">Pilih file...</label>
                                            </div>
                                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti. Format: JPG, JPEG, PNG, GIF. Max 5MB</small>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Update
                                            </button>
                                            <a href="<?php echo APP_URL; ?>/index.php" class="btn btn-secondary">
                                                <i class="fas fa-arrow-left"></i> Kembali
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


<?php
$additional_js = '<script>
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>';
include 'includes/footer.php';
?>
