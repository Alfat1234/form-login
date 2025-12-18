<?php
// Konfigurasi
require_once 'includes/config.php';
require_once 'includes/auth.php';

// Cek login dan role
requireLogin();
requireRole(ROLE_ADMIN);

$page_title = 'Tambah Data Mahasiswa';
include 'includes/header.php';

$error = '';
$success = false;

// Proses form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = trim($_POST['nim'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $prodi = trim($_POST['prodi'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    
    // Validasi input
    if (empty($nim) || empty($nama) || empty($prodi) || empty($alamat)) {
        $error = 'Semua field harus diisi!';
    } else {
        // Cek NIM sudah ada
        $check_query = "SELECT nim FROM mahasiswa WHERE nim = ?";
        $check_stmt = mysqli_prepare($koneksi, $check_query);
        mysqli_stmt_bind_param($check_stmt, "s", $nim);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = 'NIM sudah terdaftar!';
        } else {
            // Upload gambar
            $gambar = '';
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $file_tmp = $_FILES['gambar']['tmp_name'];
                $file_name = basename($_FILES['gambar']['name']);
                $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (in_array(strtolower($file_ext), $allowed_ext)) {
                    $new_filename = uniqid() . '.' . $file_ext;
                    if (move_uploaded_file($file_tmp, UPLOAD_DIR . $new_filename)) {
                        $gambar = $new_filename;
                    }
                } else {
                    $error = 'Format file tidak didukung!';
                }
            }
            
            if (empty($error)) {
                $query = "INSERT INTO mahasiswa (nim, nama, prodi, alamat, gambar, user_id, created_by) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)";

                $stmt = mysqli_prepare($koneksi, $query);

                $user_id = $_SESSION['user_id'];
                $created_by = $_SESSION['user_id'];

                // 5 string + 2 integer = sssssii
                mysqli_stmt_bind_param(
                    $stmt,
                    "sssssii",
                    $nim,
                    $nama,
                    $prodi,
                    $alamat,
                    $gambar,
                    $user_id,
                    $created_by
                );

                
                if (mysqli_stmt_execute($stmt)) {
                    $success = true;
                    echo '<script>
                        setTimeout(function() {
                            window.location.href = "' . APP_URL . '/index.php";
                        }, 1000);
                    </script>';
                } else {
                    $error = 'Gagal menyimpan data: ' . mysqli_error($koneksi);
                }
            }
        }
    }
}
?>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Tambah Data Mahasiswa</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Form Tambah Mahasiswa</h6>
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
                                            <i class="fas fa-check-circle"></i> Data berhasil ditambahkan! Mengalihkan...
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>

                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="nim">NIM <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nim" name="nim" required placeholder="Masukkan NIM">
                                        </div>

                                        <div class="form-group">
                                            <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nama" name="nama" required placeholder="Masukkan nama lengkap">
                                        </div>

                                        <div class="form-group">
                                            <label for="prodi">Program Studi <span class="text-danger">*</span></label>
                                            <select class="form-control" id="prodi" name="prodi" required>
                                                <option value="">-- Pilih Program Studi --</option>
                                                <option value="Teknik Informatika">Teknik Informatika</option>
                                                <option value="Sistem Informasi">Sistem Informasi</option>
                                                <option value="Manajemen Informatika">Manajemen Informatika</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="alamat">Alamat <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="alamat" name="alamat" rows="4" required placeholder="Masukkan alamat"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="gambar">Foto Mahasiswa</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="gambar" name="gambar" accept="image/*">
                                                <label class="custom-file-label" for="gambar">Pilih file...</label>
                                            </div>
                                            <small class="form-text text-muted">Format: JPG, JPEG, PNG, GIF. Max 5MB</small>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Simpan
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
