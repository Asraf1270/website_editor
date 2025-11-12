<?php
require_once 'includes/functions.php';
ensureDirs();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filename = $_POST['filename'];
    $content = $_POST['content'];
    $mainPath = 'pages/' . $filename;

    if (file_exists($mainPath)) {
        // Backup current (flat, path to _)
        $timestamp = date('Ymd_His');
        $safeFilename = str_replace('/', '_', $filename);
        $base = pathinfo($safeFilename, PATHINFO_FILENAME);
        $ext = pathinfo($safeFilename, PATHINFO_EXTENSION);
        $backupName = $base . '_' . $timestamp . '.' . $ext;
        $backupPath = 'backups/' . $backupName;
        copy($mainPath, $backupPath);
    }

    // Save new content
    file_put_contents($mainPath, $content);
    echo json_encode(['success' => true, 'message' => 'File saved!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>