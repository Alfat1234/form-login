<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

requireLogin();
requireRole(ROLE_ADMIN);

echo "Upload Directory: " . UPLOAD_DIR . "<br>";
echo "Directory Exists: " . (is_dir(UPLOAD_DIR) ? 'YES' : 'NO') . "<br>";
echo "Is Writable: " . (is_writable(UPLOAD_DIR) ? 'YES' : 'NO') . "<br>";
echo "Permissions: " . substr(sprintf('%o', fileperms(UPLOAD_DIR)), -4) . "<br>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<hr>";
    echo "File Upload Test:<br>";
    
    if (isset($_FILES['test_file'])) {
        $file = $_FILES['test_file'];
        echo "File Name: " . $file['name'] . "<br>";
        echo "File Size: " . $file['size'] . "<br>";
        echo "File Type: " . $file['type'] . "<br>";
        echo "File Error: " . $file['error'] . "<br>";
        echo "File Tmp: " . $file['tmp_name'] . "<br>";
        
        if ($file['error'] === UPLOAD_ERR_OK) {
            $new_name = uniqid() . '.png';
            $target_path = UPLOAD_DIR . $new_name;
            echo "Target Path: " . $target_path . "<br>";
            
            if (move_uploaded_file($file['tmp_name'], $target_path)) {
                echo "<span style='color:green;'>✓ Upload SUCCESS!</span>";
            } else {
                echo "<span style='color:red;'>✗ Move Upload Failed!</span>";
            }
        } else {
            echo "<span style='color:red;'>✗ File Error: " . $file['error'] . "</span>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Test</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        code { background: #f0f0f0; padding: 2px 5px; }
    </style>
</head>
<body>
    <h2>Upload Directory Test</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="test_file" required>
        <button type="submit">Test Upload</button>
    </form>
</body>
</html>
