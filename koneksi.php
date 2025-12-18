<?php
$koneksi = mysqli_connect("localhost", "root", "", "sika");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
