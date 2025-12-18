<?php
// Simulasi session mahasiswa
session_status() === PHP_SESSION_ACTIVE || session_start();

// Simulasi user mahasiswa login
$_SESSION['user_id'] = 2;
$_SESSION['username'] = 'mahasiswa1';
$_SESSION['name'] = 'Mahasiswa Satu';
$_SESSION['role'] = 'mahasiswa';
$_SESSION['email'] = 'mahasiswa1@sika.local';
$_SESSION['status'] = 'active';

require_once 'includes/config.php';
require_once 'includes/auth.php';

echo "<h2>Test: Mahasiswa Role Access to index.php</h2>";
echo "<pre>";

echo "Session Simulation:\n";
echo "- user_id: " . $_SESSION['user_id'] . "\n";
echo "- username: " . $_SESSION['username'] . "\n";
echo "- name: " . $_SESSION['name'] . "\n";
echo "- role: " . $_SESSION['role'] . "\n";

echo "\nAuth Functions:\n";
echo "- isLoggedIn(): " . (isLoggedIn() ? "TRUE" : "FALSE") . "\n";
echo "- getCurrentUser(): " . getCurrentUser() . "\n";
echo "- getCurrentRole(): " . getCurrentRole() . "\n";
echo "- hasRole(ROLE_ADMIN): " . (hasRole(ROLE_ADMIN) ? "TRUE" : "FALSE") . "\n";
echo "- hasRole(ROLE_MAHASISWA): " . (hasRole(ROLE_MAHASISWA) ? "TRUE" : "FALSE") . "\n";

echo "\nLogic Check:\n";
$user_role = getCurrentRole();
if ($user_role === ROLE_MAHASISWA) {
    echo "✓ User is mahasiswa - should show mahasiswa dashboard\n";
} else if ($user_role === ROLE_ADMIN) {
    echo "✓ User is admin - should show admin dashboard\n";
} else {
    echo "✗ Unknown role: " . $user_role . "\n";
}

echo "\nROLE_MAHASISWA constant value: '" . ROLE_MAHASISWA . "'\n";
echo "User role value: '" . $_SESSION['role'] . "'\n";
echo "Comparison result: " . (($_SESSION['role'] === ROLE_MAHASISWA) ? "MATCH" : "NO MATCH") . "\n";

echo "</pre>";
?>
