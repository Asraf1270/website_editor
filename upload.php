<?php
require_once 'includes/functions.php';
ensureDirs();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $filename = $_POST['filename'];
    $target = 'pages/' . $filename;
    $dir = dirname($target);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    if (file_exists($target)) {
        // Backup current (flat, path to _)
        $timestamp = date('Ymd_His');
        $safeFilename = str_replace('/', '_', $filename);
        $base = pathinfo($safeFilename, PATHINFO_FILENAME);
        $ext = pathinfo($safeFilename, PATHINFO_EXTENSION);
        $backupName = $base . '_' . $timestamp . '.' . $ext;
        $backupPath = 'backups/' . $backupName;
        copy($target, $backupPath);
    }

    // Upload
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
        echo json_encode(['success' => true, 'message' => 'File uploaded!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Upload failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>