<?php
require_once 'includes/functions.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filename = $_POST['filename'];
    $path = 'pages/' . $filename;
    if (file_exists($path)) {
        unlink($path);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'File not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>