<?php
require_once 'includes/functions.php';
header('Content-Type: application/json');

$filename = $_GET['filename'] ?? '';
$safeFilename = str_replace('/', '_', $filename);
$base = pathinfo($safeFilename, PATHINFO_FILENAME);
$ext = pathinfo($safeFilename, PATHINFO_EXTENSION);
$backups = getBackups($safeFilename); // Pass safe for consistency
$formatted = [];
foreach ($backups as $backup) {
    $formatted[] = [
        'name' => $backup,
        'timestamp' => formatTimestamp($backup)
    ];
}
echo json_encode($formatted);
?>