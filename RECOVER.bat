@echo off
REM Database Recovery Script for SIKA
REM Run this if you get "MySQL server gone away" error

echo.
echo ================================
echo   SIKA Database Recovery
echo ================================
echo.

REM Check if PHP exists
if not exist "C:\xampp\php\php.exe" (
    echo ERROR: PHP not found at C:\xampp\php\php.exe
    echo Please install XAMPP properly
    pause
    exit /b 1
)

REM Create temporary PHP script
(
echo ^<?php
echo require_once 'includes/config.php';
echo ^$host = 'localhost'; $user = 'root'; $pass = '';
echo ^$conn = @mysqli_connect($host, $user, $pass);
echo if (!$conn) {
echo     echo "ERROR: Cannot connect to MySQL\n";
echo     echo "Please start MySQL from XAMPP Control Panel\n";
echo     exit(1);
echo }
echo echo "Creating database...\n";
echo mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS sika");
echo mysqli_select_db($conn, 'sika');
echo 
echo $users_sql = "CREATE TABLE IF NOT EXISTS users (
echo   id int(11) NOT NULL AUTO_INCREMENT,
echo   username varchar(50) NOT NULL UNIQUE,
echo   email varchar(100) NOT NULL UNIQUE,
echo   name varchar(100) NOT NULL,
echo   password varchar(255) NOT NULL,
echo   role enum('admin','mahasiswa') NOT NULL DEFAULT 'mahasiswa',
echo   status enum('active','inactive') NOT NULL DEFAULT 'active',
echo   created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
echo   updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
echo   last_login datetime,
echo   PRIMARY KEY (id)
echo ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
echo mysqli_query($conn, $users_sql);
echo 
echo $mhs_sql = "CREATE TABLE IF NOT EXISTS mahasiswa (
echo   id int(11) NOT NULL AUTO_INCREMENT,
echo   nim varchar(20) NOT NULL UNIQUE,
echo   nama varchar(100) NOT NULL,
echo   prodi varchar(100) NOT NULL,
echo   alamat text,
echo   gambar varchar(255),
echo   user_id int(11),
echo   created_by int(11),
echo   created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
echo   updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
echo   PRIMARY KEY (id),
echo   FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
echo   FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
echo ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
echo mysqli_query($conn, $mhs_sql);
echo echo "Inserting data...\n";
echo 
echo $pass_hash = password_hash('password123', PASSWORD_DEFAULT);
echo mysqli_query($conn, "TRUNCATE TABLE mahasiswa");
echo mysqli_query($conn, "TRUNCATE TABLE users");
echo 
echo $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, name, password, role) VALUES (?, ?, ?, ?, ?)");
echo mysqli_stmt_bind_param($stmt, "sssss", $u, $e, $n, $p, $r);
echo 
echo $users = array(
echo     array('admin', 'admin@sika.local', 'Administrator', $pass_hash, 'admin'),
echo     array('mahasiswa1', 'mahasiswa1@sika.local', 'Mahasiswa Satu', $pass_hash, 'mahasiswa'),
echo     array('mahasiswa2', 'mahasiswa2@sika.local', 'Mahasiswa Dua', $pass_hash, 'mahasiswa'),
echo     array('mahasiswa3', 'mahasiswa3@sika.local', 'Mahasiswa Tiga', $pass_hash, 'mahasiswa'),
echo );
echo 
echo foreach ($users as $user) {
echo     $u = $user[0]; $e = $user[1]; $n = $user[2]; $p = $user[3]; $r = $user[4];
echo     mysqli_stmt_execute($stmt);
echo }
echo mysqli_stmt_close($stmt);
echo 
echo $result = mysqli_query($conn, "SELECT id FROM users WHERE username='admin' LIMIT 1");
echo $admin = mysqli_fetch_assoc($result);
echo $admin_id = $admin['id'] ?? 1;
echo 
echo $mahasiswa = array(
echo     array('1234567890', 'Budi Santoso', 'Teknik Informatika', 'Jl. Merdeka No. 123'),
echo     array('1234567891', 'Siti Nurhaliza', 'Sistem Informasi', 'Jl. Ahmad Yani No. 45'),
echo     array('1234567892', 'Ahmad Wijaya', 'Teknik Informatika', 'Jl. Diponegoro No. 67'),
echo     array('1234567893', 'Nur Azizah', 'Sistem Informasi', 'Jl. Sudirman No. 89'),
echo     array('1234567894', 'Rudi Hermanto', 'Teknik Informatika', 'Jl. Gatot Subroto No. 101'),
echo     array('1234567895', 'Lina Marlina', 'Sistem Informasi', 'Jl. Imam Bonjol No. 123'),
echo );
echo 
echo $stmt = mysqli_prepare($conn, "INSERT INTO mahasiswa (nim, nama, prodi, alamat, created_by) VALUES (?, ?, ?, ?, ?)");
echo mysqli_stmt_bind_param($stmt, "ssssi", $nim, $nama, $prodi, $alamat, $created_by);
echo $created_by = $admin_id;
echo 
echo foreach ($mahasiswa as $m) {
echo     $nim = $m[0]; $nama = $m[1]; $prodi = $m[2]; $alamat = $m[3];
echo     mysqli_stmt_execute($stmt);
echo }
echo mysqli_stmt_close($stmt);
echo 
echo echo "\n=== Recovery Complete ===\n";
echo echo "Login: admin / password123\n";
echo mysqli_close($conn);
echo ^?>
) > recover.php

REM Run recovery script
echo Running recovery script...
"C:\xampp\php\php.exe" recover.php

REM Cleanup
del recover.php

echo.
echo ================================
echo   Done! Login at:
echo   http://localhost/sika/login.php
echo.
echo   Admin: admin / password123
echo ================================
echo.
pause
