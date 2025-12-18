<?php
// Test login process dengan error reporting lengkap
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/config.php';

echo "<h2>Test Login Process</h2>";
echo "<pre>";

// Cek koneksi
echo "1. Database Connection:\n";
if ($koneksi) {
    echo "   ✓ Connected\n";
} else {
    echo "   ✗ FAILED: " . mysqli_connect_error() . "\n";
    exit;
}

// Cek tabel users
echo "\n2. Checking users table:\n";
$check_table = mysqli_query($koneksi, "SELECT COUNT(*) as count FROM users");
if ($check_table) {
    $result = mysqli_fetch_assoc($check_table);
    echo "   ✓ Found " . $result['count'] . " users\n";
} else {
    echo "   ✗ ERROR: " . mysqli_error($koneksi) . "\n";
}

// Cek user admin
echo "\n3. Checking admin user:\n";
$admin_query = "SELECT id, username, email, role FROM users WHERE username = 'admin'";
$admin_result = mysqli_query($koneksi, $admin_query);
if ($admin_result) {
    $admin = mysqli_fetch_assoc($admin_result);
    if ($admin) {
        echo "   ✓ Admin user found\n";
        echo "   - ID: " . $admin['id'] . "\n";
        echo "   - Username: " . $admin['username'] . "\n";
        echo "   - Email: " . $admin['email'] . "\n";
        echo "   - Role: " . $admin['role'] . "\n";
    } else {
        echo "   ✗ Admin user NOT found\n";
    }
} else {
    echo "   ✗ Query error: " . mysqli_error($koneksi) . "\n";
}

// Test password verification
echo "\n4. Testing password verification:\n";
$test_password = "password123";
$admin_full_query = "SELECT password FROM users WHERE username = 'admin'";
$admin_full_result = mysqli_query($koneksi, $admin_full_query);
if ($admin_full_result) {
    $admin_full = mysqli_fetch_assoc($admin_full_result);
    if ($admin_full) {
        echo "   - Stored hash: " . substr($admin_full['password'], 0, 20) . "...\n";
        $verify = password_verify($test_password, $admin_full['password']);
        if ($verify) {
            echo "   ✓ Password verification PASSED\n";
        } else {
            echo "   ✗ Password verification FAILED\n";
        }
    }
} else {
    echo "   ✗ Query error: " . mysqli_error($koneksi) . "\n";
}

// Test prepared statement
echo "\n5. Testing prepared statement:\n";
$stmt = mysqli_prepare($koneksi, "SELECT * FROM users WHERE username = ?");
if ($stmt) {
    echo "   ✓ Statement prepared\n";
    $username = "admin";
    $bind_result = mysqli_stmt_bind_param($stmt, "s", $username);
    if ($bind_result) {
        echo "   ✓ Parameters bound\n";
        $exec_result = mysqli_stmt_execute($stmt);
        if ($exec_result) {
            echo "   ✓ Statement executed\n";
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
            if ($user) {
                echo "   ✓ User fetched\n";
                echo "   - Username: " . $user['username'] . "\n";
                echo "   - Name: " . $user['name'] . "\n";
            } else {
                echo "   ✗ No user returned\n";
            }
        } else {
            echo "   ✗ Execution failed: " . mysqli_stmt_error($stmt) . "\n";
        }
    } else {
        echo "   ✗ Bind failed: " . mysqli_stmt_error($stmt) . "\n";
    }
    mysqli_stmt_close($stmt);
} else {
    echo "   ✗ Prepare failed: " . mysqli_error($koneksi) . "\n";
}

echo "</pre>";

mysqli_close($koneksi);
?>
