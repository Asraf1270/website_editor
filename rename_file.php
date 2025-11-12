<?php
require_once 'includes/functions.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldName = $_POST['oldName'];
    $newName = $_POST['newName']; // Full new path or just basename? Here, new basename in same dir
    $oldPath = 'pages/' . $oldName;
    $dir = dirname($oldPath);
    $newPath = $dir . '/' . $newName;

    if (file_exists($oldPath) && !file_exists($newPath)) {
        rename($oldPath, $newPath);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error renaming']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>