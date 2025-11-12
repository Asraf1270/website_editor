<?php
require_once 'includes/functions.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $foldername = $_POST['foldername'];
    $path = 'pages/' . rtrim($foldername, '/');
    if (is_dir($path)) {
        if (deleteFolder($path)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete folder']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Folder not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>