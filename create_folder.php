<?php
require_once 'includes/functions.php';
ensureDirs();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $foldername = $_POST['foldername'];
    $path = 'pages/' . $foldername;
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Folder exists']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>