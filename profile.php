<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

// Cek login
requireLogin();

$page_title = 'Profil Saya';
include 'includes/header.php';

// Get current user data
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-circle mr-2"></i>Data Profil
                    </h6>
                </div>
                <div class="card-body">
                    <?php
                    $error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
                    $success = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : '';
                    
                    if ($error): ?>
                        <div class="alert alert-danger mb-3" role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
                        </div>
                    <?php endif; 
                    
                    if ($success): ?>
                        <div class="alert alert-success mb-3" role="alert">
                            <i class="fas fa-check-circle mr-2"></i><?php echo $success; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($user): ?>
                    <!-- Foto Profil Section -->
                    <div class="row mb-4">
                        <div class="col-md-3 text-center">
                            <div style="width: 150px; height: 150px; margin: 0 auto; border-radius: 50%; overflow: hidden; border: 3px solid #667eea; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                <?php if (!empty($user['profile_photo']) && file_exists('upload/' . $user['profile_photo'])): ?>
                                    <img src="<?php echo APP_URL; ?>/upload/<?php echo htmlspecialchars($user['profile_photo']); ?>" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <i class="fas fa-user-circle" style="font-size: 80px; color: #999;"></i>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary mt-3" data-toggle="modal" data-target="#uploadPhotoModal">
                                <i class="fas fa-camera mr-2"></i>Ubah Foto
                            </button>
                        </div>
                        <div class="col-md-9">
                            <h5 class="mb-3"><?php echo htmlspecialchars($user['name']); ?></h5>
                            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                            <p><strong>Role:</strong> <span class="badge badge-info"><?php echo ucfirst($user['role']); ?></span></p>
                            <p><strong>Status:</strong> <span class="badge badge-<?php echo ($user['status'] == 'active') ? 'success' : 'danger'; ?>"><?php echo ucfirst($user['status']); ?></span></p>
                        </div>
                    </div>
                    <hr>
                    
                    <form method="POST" action="<?php echo APP_URL; ?>/process/update_profile.php">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-600">Nama</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name'] ?? 'N/A'); ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-600">Username</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username'] ?? 'N/A'); ?>" disabled>
                                <small class="form-text text-muted">Username tidak dapat diubah</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-600">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-600">Password Baru</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                                <small class="form-text text-muted">Minimal 6 karakter</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-600">Role</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="<?php echo ucfirst($user['role']); ?>" disabled>
                                <small class="form-text text-muted">Role tidak dapat diubah</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-600">Status</label>
                            <div class="col-sm-9">
                                <span class="badge badge-<?php echo ($user['status'] == 'active') ? 'success' : 'danger'; ?>">
                                    <?php echo ucfirst($user['status']); ?>
                                </span>
                                <small class="form-text text-muted">Status tidak dapat diubah</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-600">Terdaftar Sejak</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="<?php echo date('d-m-Y H:i', strtotime($user['created_at'] ?? '')); ?>" disabled>
                            </div>
                        </div>
                        <?php if ($user['last_login']): ?>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-600">Login Terakhir</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="<?php echo date('d-m-Y H:i', strtotime($user['last_login'])); ?>" disabled>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="form-group row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                                </button>
                                <a href="<?php echo APP_URL; ?>/index.php" class="btn btn-secondary">
                                    <i class="fas fa-times mr-2"></i>Batal
                                </a>
                            </div>
                        </div>
                    </form>
                    <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-warning mr-2"></i>Tidak dapat memuat data profil
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Foto Profil -->
<div class="modal fade" id="uploadPhotoModal" tabindex="-1" role="dialog" aria-labelledby="uploadPhotoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadPhotoLabel">Upload Foto Profil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formUploadPhoto" enctype="multipart/form-data">
                <div class="modal-body">
                    <div id="alertMessage"></div>
                    <div class="form-group">
                        <label for="profilePhoto">Pilih Foto <span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="profilePhoto" name="profile_photo" accept="image/*" required>
                            <label class="custom-file-label" for="profilePhoto">Pilih file...</label>
                        </div>
                        <small class="form-text text-muted">Format: JPG, JPEG, PNG, GIF. Max 5MB</small>
                    </div>
                    <div id="previewContainer" style="display: none;">
                        <label>Preview:</label>
                        <img id="previewImage" src="" alt="Preview" style="max-width: 100%; border-radius: 5px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitPhotoBtn">
                        <i class="fas fa-save"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script>
$(document).ready(function() {
    // Handle file input label
    $("#profilePhoto").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        
        // Preview image
        if (this.files && this.files[0]) {
            var fileSize = this.files[0].size;
            var maxSize = 5 * 1024 * 1024;
            
            if (fileSize > maxSize) {
                alert("Ukuran file terlalu besar! Maksimal 5MB");
                $(this).val("").siblings(".custom-file-label").html("Pilih file...");
                $("#previewContainer").hide();
            } else {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $("#previewImage").attr("src", e.target.result);
                    $("#previewContainer").show();
                };
                reader.readAsDataURL(this.files[0]);
            }
        }
    });
    
    // Handle form submit
    $("#formUploadPhoto").on("submit", function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = $("#submitPhotoBtn");
        const alertDiv = $("#alertMessage");
        
        submitBtn.prop("disabled", true).html("<i class=\"fas fa-spinner fa-spin\"></i> Loading...");
        
        $.ajax({
            url: "<?php echo APP_URL; ?>/process/upload_profile_photo.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                const result = JSON.parse(response);
                
                if (result.success) {
                    alertDiv.html("<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\"><i class=\"fas fa-check-circle\"></i> " + result.message + "<button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span>&times;</span></button></div>");
                    
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    alertDiv.html("<div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\"><i class=\"fas fa-exclamation-circle\"></i> " + result.message + "<button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span>&times;</span></button></div>");
                }
            },
            error: function() {
                alertDiv.html("<div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\"><i class=\"fas fa-exclamation-circle\"></i> Terjadi kesalahan saat upload!<button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span>&times;</span></button></div>");
            },
            complete: function() {
                submitBtn.prop("disabled", false).html("<i class=\"fas fa-save\"></i> Upload");
            }
        });
    });
});
</script>
