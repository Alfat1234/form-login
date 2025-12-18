<?php
// Konfigurasi
require_once 'includes/config.php';
require_once 'includes/auth.php';

// Cek login dan role
requireLogin();
requireRole(ROLE_ADMIN);

$page_title = 'Data Mahasiswa';
include 'includes/header.php';
?>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Data Mahasiswa</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-12">
                            <!-- DataTables Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Daftar Data Mahasiswa</h6>
                                    <button type="button" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#tambahMahasiswaModal">
                                        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Mahasiswa
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>NIM</th>
                                                    <th>Nama</th>
                                                    <th>Program Studi</th>
                                                    <th>Alamat</th>
                                                    <th>Foto</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $data = mysqli_query($koneksi, "SELECT * FROM mahasiswa ORDER BY nim ASC");
                                                while($d = mysqli_fetch_array($data)){
                                                ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($d['nim']) ?></td>
                                                    <td><?= htmlspecialchars($d['nama']) ?></td>
                                                    <td><?= htmlspecialchars($d['prodi']) ?></td>
                                                    <td><?= htmlspecialchars($d['alamat']) ?></td>
                                                    <td>
                                                        <?php if (!empty($d['gambar'])): ?>
                                                            <img src="<?php echo APP_URL; ?>/upload/<?= htmlspecialchars($d['gambar']) ?>" width="50" class="img-thumbnail" alt="<?= htmlspecialchars($d['nama']) ?>">
                                                        <?php else: ?>
                                                            <span class="text-muted">Tidak ada foto</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo APP_URL; ?>/edit.php?nim=<?= htmlspecialchars($d['nim']) ?>" class="btn btn-sm btn-warning">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <a href="<?php echo APP_URL; ?>/hapus.php?nim=<?= htmlspecialchars($d['nim']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Modal Tambah Mahasiswa -->
                    <div class="modal fade" id="tambahMahasiswaModal" tabindex="-1" role="dialog" aria-labelledby="tambahMahasiswaLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="tambahMahasiswaLabel">Tambah Data Mahasiswa</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="formTambahMahasiswa" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div id="alertMessage"></div>
                                        
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
                                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required placeholder="Masukkan alamat"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="gambar">Foto Mahasiswa</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="gambar" name="gambar" accept="image/*">
                                                <label class="custom-file-label" for="gambar">Pilih file...</label>
                                            </div>
                                            <small class="form-text text-muted">Format: JPG, JPEG, PNG, GIF. Max 5MB</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary" id="submitBtn">
                                            <i class="fas fa-save"></i> Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

<?php
$additional_js = '<script src="' . APP_URL . '/assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="' . APP_URL . '/assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    const dataTable = $("#dataTable").DataTable();

    // Handle file input label
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    // Handle form submit untuk tambah mahasiswa
    $("#formTambahMahasiswa").on("submit", function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = $("#submitBtn");
        const alertDiv = $("#alertMessage");
        
        submitBtn.prop("disabled", true).html("<i class=\"fas fa-spinner fa-spin\"></i> Loading...");

        $.ajax({
            url: "' . APP_URL . '/process/tambah_mahasiswa.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                const result = JSON.parse(response);
                
                if (result.success) {
                    alertDiv.html("<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\"><i class=\"fas fa-check-circle\"></i> " + result.message + "<button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span>&times;</span></button></div>");
                    
                    // Reset form
                    $("#formTambahMahasiswa")[0].reset();
                    $(".custom-file-label").html("Pilih file...");
                    
                    // Reload data table
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    alertDiv.html("<div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\"><i class=\"fas fa-exclamation-circle\"></i> " + result.message + "<button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span>&times;</span></button></div>");
                }
            },
            error: function() {
                alertDiv.html("<div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\"><i class=\"fas fa-exclamation-circle\"></i> Terjadi kesalahan saat mengirim data!<button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span>&times;</span></button></div>");
            },
            complete: function() {
                submitBtn.prop("disabled", false).html("<i class=\"fas fa-save\"></i> Simpan");
            }
        });
    });
});
</script>';
include 'includes/footer.php';
?>
