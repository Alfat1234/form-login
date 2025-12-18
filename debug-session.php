<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/config.php';
require_once 'includes/auth.php';

echo "<h2>Session Debug</h2>";
echo "<pre>";
echo "Session Status: " . (session_status() === PHP_SESSION_ACTIVE ? "ACTIVE" : "INACTIVE") . "\n\n";

echo "Session Variables:\n";
echo json_encode($_SESSION, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

echo "Auth Functions:\n";
echo "isLoggedIn(): " . (isLoggedIn() ? "TRUE" : "FALSE") . "\n";
echo "getCurrentUser(): " . getCurrentUser() . "\n";
echo "getCurrentRole(): " . getCurrentRole() . "\n";
echo "hasRole(ROLE_ADMIN): " . (hasRole(ROLE_ADMIN) ? "TRUE" : "FALSE") . "\n";
echo "hasRole(ROLE_MAHASISWA): " . (hasRole(ROLE_MAHASISWA) ? "TRUE" : "FALSE") . "\n";

echo "</pre>";
echo "<hr>";
echo "<a href='" . APP_URL . "/index.php'>Go to Dashboard</a>";
?>
