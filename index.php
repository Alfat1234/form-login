<?php
// Konfigurasi
require_once 'includes/config.php';
require_once 'includes/auth.php';

// Cek login
requireLogin();

// Tentukan role user
$user_role = getCurrentRole();
$page_title = 'Dashboard';

// Jika mahasiswa, tampilkan profile mereka saja
if ($user_role === ROLE_MAHASISWA) {
    include 'includes/header.php';
    ?>
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard Mahasiswa</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-user mr-2"></i> Profil Anda
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Nama:</strong> <?php echo htmlspecialchars(getCurrentUser()); ?></p>
                                            <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email'] ?? '-'); ?></p>
                                            <p><strong>Role:</strong> <?php echo htmlspecialchars(getCurrentRole()); ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Status:</strong> <span class="badge badge-success"><?php echo htmlspecialchars($_SESSION['status']); ?></span></p>
                                            <a href="<?php echo APP_URL; ?>/profile.php" class="btn btn-sm btn-primary mt-3">
                                                <i class="fas fa-edit"></i> Edit Profil
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    <?php
    include 'includes/footer.php';
    exit;
}

// Hanya admin yang bisa lihat data semua mahasiswa
if (!hasRole(ROLE_ADMIN)) {
    header("Location: " . APP_URL . "/unauthorized.php");
    exit;
}

$page_title = 'Data Mahasiswa';
include 'includes/header.php';

// Get statistics untuk dashboard
$total_mahasiswa = mysqli_query($koneksi, "SELECT COUNT(*) as count FROM mahasiswa");
$total_mhs = mysqli_fetch_assoc($total_mahasiswa)['count'];

$total_users = mysqli_query($koneksi, "SELECT COUNT(*) as count FROM users");
$total_usr = mysqli_fetch_assoc($total_users)['count'];

$active_users = mysqli_query($koneksi, "SELECT COUNT(*) as count FROM users WHERE status = 'active'");
$active_usr = mysqli_fetch_assoc($active_users)['count'];

// Get data untuk grafik role
$admin_count = mysqli_query($koneksi, "SELECT COUNT(*) as count FROM users WHERE role = 'admin'");
$admin_count_val = mysqli_fetch_assoc($admin_count)['count'];

$mahasiswa_count = mysqli_query($koneksi, "SELECT COUNT(*) as count FROM users WHERE role = 'mahasiswa'");
$mahasiswa_count_val = mysqli_fetch_assoc($mahasiswa_count)['count'];

// Get data untuk grafik mahasiswa per prodi
$prodi_data = mysqli_query($koneksi, "SELECT prodi, COUNT(*) as count FROM mahasiswa GROUP BY prodi ORDER BY count DESC");
$prodi_labels = [];
$prodi_counts = [];
while($row = mysqli_fetch_assoc($prodi_data)){
    $prodi_labels[] = $row['prodi'];
    $prodi_counts[] = $row['count'];
}
?>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
                        </a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Total Mahasiswa Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Mahasiswa</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_mhs; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Users Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total User</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_usr; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Active Users Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                User Aktif
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $active_usr; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Percentage Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Data Lengkap</div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                        <?php 
                                                        $mhs_foto = mysqli_query($koneksi, "SELECT COUNT(*) as count FROM mahasiswa WHERE gambar IS NOT NULL AND gambar != ''");
                                                        $mhs_foto_count = mysqli_fetch_assoc($mhs_foto)['count'];
                                                        $percentage = ($total_mhs > 0) ? round(($mhs_foto_count / $total_mhs) * 100) : 0;
                                                        echo $percentage . '%';
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-warning" role="progressbar"
                                                            style="width: <?php echo $percentage; ?>%" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-image fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Pie Chart - Distribusi Role User -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-pie-chart mr-2"></i> Distribusi Role User
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="roleChart" height="80"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Bar Chart - Mahasiswa per Prodi -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-chart-bar mr-2"></i> Mahasiswa per Program Studi
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="prodiChart" height="80"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-12">
                            <!-- Tombol Aksi -->
                           
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
$admin_count_val_json = json_encode($admin_count_val);
$mahasiswa_count_val_json = json_encode($mahasiswa_count_val);
$prodi_labels_json = json_encode($prodi_labels);
$prodi_counts_json = json_encode($prodi_counts);

$additional_js = '<script src="' . APP_URL . '/assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="' . APP_URL . '/assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(document).ready(function() {
    
    // Role Chart (Pie)
    const roleCtx = document.getElementById("roleChart").getContext("2d");
    new Chart(roleCtx, {
        type: "pie",
        data: {
            labels: ["Admin", "Mahasiswa"],
            datasets: [{
                data: [' . $admin_count_val_json . ', ' . $mahasiswa_count_val_json . '],
                backgroundColor: [
                    "#667eea",
                    "#764ba2"
                ],
                borderColor: "#fff",
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: "bottom"
                }
            }
        }
    });
    
    // Prodi Chart (Bar)
    const prodiCtx = document.getElementById("prodiChart").getContext("2d");
    new Chart(prodiCtx, {
        type: "bar",
        data: {
            labels: ' . $prodi_labels_json . ',
            datasets: [{
                label: "Jumlah Mahasiswa",
                data: ' . $prodi_counts_json . ',
                backgroundColor: [
                    "#667eea",
                    "#764ba2",
                    "#f093fb",
                    "#4facfe",
                    "#00f2fe"
                ],
                borderColor: "#667eea",
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Handle file input label dan validasi
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        
        // Validasi ukuran file
        if (this.files && this.files[0]) {
            var fileSize = this.files[0].size; // dalam bytes
            var maxSize = 5 * 1024 * 1024; // 5MB
            
            if (fileSize > maxSize) {
                alert("Ukuran file terlalu besar! Maksimal 5MB");
                $(this).val("").siblings(".custom-file-label").html("Pilih file...");
            }
        }
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
