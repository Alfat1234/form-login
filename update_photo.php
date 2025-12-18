<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

requireLogin();

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!empty($_FILES['photo']['name'])) {

        $filename = time() . "_" . basename($_FILES['photo']['name']);
        $target = "uploads/" . $filename;

        // buat folder jika belum ada
        if (!is_dir("uploads")) {
            mkdir("uploads", 0777, true);
        }

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {

            // update ke database
            $query = "UPDATE users SET photo = ? WHERE id = ?";
            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, "si", $filename, $user_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            header("Location: profil.php?success=1");
            exit;
        } else {
            die("Gagal upload foto.");
        }
    }
}

header("Location: profil.php?error=1");
exit;
?>
