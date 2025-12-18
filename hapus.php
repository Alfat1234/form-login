<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

// Cek login dan role
requireLogin();
requireRole(ROLE_ADMIN);

$nim = isset($_GET['nim']) ? trim($_GET['nim']) : '';

if (!empty($nim)) {
    // Delete mahasiswa
    $query = "DELETE FROM mahasiswa WHERE nim = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $nim);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: " . APP_URL . "/index.php?success=Data berhasil dihapus");
    } else {
        header("Location: " . APP_URL . "/index.php?error=Gagal menghapus data");
    }
} else {
    header("Location: " . APP_URL . "/index.php");
}
exit;
?>
