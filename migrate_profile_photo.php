<?php
require 'includes/config.php';

$result = mysqli_query($koneksi, 'ALTER TABLE users ADD COLUMN profile_photo VARCHAR(255) NULL AFTER password');
echo $result ? 'Kolom profile_photo berhasil ditambahkan' : 'Error: ' . mysqli_error($koneksi);
?>
