<?php
require_once 'includes/functions.php';
ensureDirs();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $backupName = $_POST['backupName'];
    $filename = $_POST['filename'];
    $backupPath = 'backups/' . $backupName;
    $mainPath = 'pages/' . $filename;

    if (file_exists($backupPath)) {
        // Backup current before restore (flat, path to _)
        if (file_exists($mainPath)) {
            $timestamp = date('Ymd_His');
            $safeFilename = str_replace('/', '_', $filename);
            $base = pathinfo($safeFilename, PATHINFO_FILENAME);
            $ext = pathinfo($safeFilename, PATHINFO_EXTENSION);
            $newBackupName = $base . '_' . $timestamp . '.' . $ext;
            $newBackupPath = 'backups/' . $newBackupName;
            copy($mainPath, $newBackupPath);
        }

        // Restore
        copy($backupPath, $mainPath);
        echo json_encode(['success' => true, 'message' => 'Restored!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Backup not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>