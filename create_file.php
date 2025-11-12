<?php
require_once 'includes/functions.php';
ensureDirs();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filename = $_POST['filename'];
    $path = 'pages/' . $filename;
    if (!file_exists($path)) {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($path, '');
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'File exists']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>