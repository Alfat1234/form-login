<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

// Hanya admin
requireLogin();
requireRole(ROLE_ADMIN);

$page_title = 'Manajemen User';
include 'includes/header.php';

?>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen User</h1>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar User</h6>
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#tambahUserModal">
                        <i class="fas fa-user-plus"></i> Tambah User
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Foto</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = mysqli_query($koneksi, "SELECT id, username, name, email, role, status, profile_photo FROM users ORDER BY id ASC");
                                while($row = mysqli_fetch_assoc($res)){
                                ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['role']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td>
                                        <?php if (!empty($row['profile_photo']) && file_exists('upload/' . $row['profile_photo'])): ?>
                                            <img src="<?php echo APP_URL; ?>/upload/<?php echo htmlspecialchars($row['profile_photo']); ?>" width="40" class="img-thumbnail">
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo APP_URL; ?>/edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="<?php echo APP_URL; ?>/process/delete_user.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus user ini?')">Hapus</a>
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

    <!-- Modal Tambah User -->
    <div class="modal fade" id="tambahUserModal" tabindex="-1" role="dialog" aria-labelledby="tambahUserLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahUserLabel">Tambah User Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formTambahUser">
                    <div class="modal-body">
                        <div id="alertUser"></div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control">
                                <option value="admin">Admin</option>
                                <option value="mahasiswa">Mahasiswa</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="active">active</option>
                                <option value="inactive">inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitUserBtn">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
$additional_js = <<<JS
<script src="{APP_URL}/assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{APP_URL}/assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function(){
    $("#usersTable").DataTable();

    $("#formTambahUser").on("submit", function(e){
        e.preventDefault();
        var btn = $("#submitUserBtn");
        var alertDiv = $("#alertUser");
        btn.prop("disabled", true).text("Menyimpan...");
        $.post("{APP_URL}/process/add_user.php", $(this).serialize(), function(resp){
            var r = typeof resp === 'string' ? JSON.parse(resp) : resp;
            if (r.success) {
                alertDiv.html("<div class=\"alert alert-success\">"+r.message+"</div>");
                setTimeout(function(){ location.reload(); }, 1200);
            } else {
                alertDiv.html("<div class=\"alert alert-danger\">"+r.message+"</div>");
            }
            btn.prop("disabled", false).text("Simpan");
        }).fail(function(){
            alertDiv.html("<div class=\"alert alert-danger\">Terjadi kesalahan.</div>");
            btn.prop("disabled", false).text("Simpan");
        });
    });
});
</script>
JS;

$additional_js = str_replace('{APP_URL}', APP_URL, $additional_js);
include 'includes/footer.php';
?>