<?php
require_once 'includes/functions.php';
header('Content-Type: application/json');

$filename = $_GET['filename'] ?? '';
$path = 'pages/' . $filename;
if (file_exists($path)) {
    $content = file_get_contents($path);
    $isBinary = strpos($content, "\0") !== false;
    if ($isBinary) {
        echo json_encode(['success' => true, 'isBinary' => true, 'message' => 'Binary file, cannot edit in text editor.']);
    } else {
        echo json_encode(['success' => true, 'isBinary' => false, 'content' => $content]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'File not found']);
}
?>