<?php
require_once __DIR__ . '/includes/config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Login - SIKA">
    <meta name="author" content="SIKA Team">
    <title>Login - SIKA</title>

    <!-- Bootstrap CSS (Local) -->
    <link href="<?php echo APP_URL; ?>/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome CSS (Local) -->
    <link href="<?php echo APP_URL; ?>/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- SB Admin 2 CSS -->
    <link href="<?php echo APP_URL; ?>/assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?php echo APP_URL; ?>/assets/css/sb-admin-2-custom.css" rel="stylesheet">
</head>
<body style="background:linear-gradient(135deg,#667eea,#764ba2);min-height:100vh;">
    <div class="login-container">
        <div class="login-card">
            <div class="card">
                <div class="login-header">
                    <h2>SIKA Login</h2>
                    <p style="margin:0;font-size:.95rem;opacity:.95;">Sistem Informasi Kemahasiswaan</p>
                </div>
                <div class="login-body">
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

                    <form method="POST" action="<?php echo APP_URL; ?>/process/login_process.php">
                        <div class="form-group">
                            <label for="username" class="font-weight-600">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required placeholder="Masukkan username">
                        </div>

                        <div class="form-group">
                            <label for="password" class="font-weight-600">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="Masukkan password">
                        </div>

                        <button type="submit" class="btn-login mt-2">Login</button>

                        <div style="text-align:center;margin-top:1rem;">
                            <p style="color:#666;margin:0 0 .5rem 0;font-size:.9rem;">Belum punya akun?</p>
                            <a href="<?php echo APP_URL; ?>/register.php" style="color:#667eea;text-decoration:none;font-weight:600;">Daftar di sini</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript (Local) -->
    <script src="<?php echo APP_URL; ?>/assets/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo APP_URL; ?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
