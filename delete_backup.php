<?php
require_once 'includes/functions.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $backupName = $_POST['backupName'];
    $backupPath = 'backups/' . $backupName;
    if (file_exists($backupPath)) {
        unlink($backupPath);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Backup not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>