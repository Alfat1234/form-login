<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

requireLogin();

$page_title = 'Akses Ditolak';
include 'includes/header.php';
?>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Akses Ditolak</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow mb-4 border-left-danger">
                                <div class="card-body">
                                    <div class="text-center">
                                        <i class="fas fa-lock fa-5x text-danger mb-4"></i>
                                        <h2>Akses Ditolak</h2>
                                        <p class="text-muted mb-4">
                                            Anda tidak memiliki izin untuk mengakses halaman ini. 
                                            Hubungi administrator jika Anda merasa ini adalah kesalahan.
                                        </p>
                                        <a href="<?php echo APP_URL; ?>/index.php" class="btn btn-primary">
                                            <i class="fas fa-home"></i> Kembali ke Dashboard
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

<?php
include 'includes/footer.php';
?>
