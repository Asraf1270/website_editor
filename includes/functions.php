<?php
// Start session
session_start();

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Ensure directories exist
function ensureDirs() {
    $dirs = ['pages', 'backups'];
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }
}

// Get list of pages and folders (recursive, any file type, folders end with /)
function getPages($dir = 'pages') {
    ensureDirs();
    $items = [];
    $iterator = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $iterator = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
    foreach ($iterator as $item) {
        $relPath = substr($item->getPathname(), strlen($dir) + 1);
        if ($item->isDir()) {
            $relPath .= '/';
        }
        $items[] = $relPath;
    }
    sort($items);
    return $items;
}

// Get backups for a file (flat, with path flattened)
function getBackups($filename) {
    ensureDirs();
    $safeFilename = str_replace('/', '_', pathinfo($filename, PATHINFO_FILENAME) . '.' . pathinfo($filename, PATHINFO_EXTENSION));
    $base = pathinfo($safeFilename, PATHINFO_FILENAME);
    $ext = pathinfo($safeFilename, PATHINFO_EXTENSION);
    $pattern = 'backups/' . $base . '_*.' . $ext;
    $backups = glob($pattern);
    usort($backups, function($a, $b) {
        return filemtime($b) - filemtime($a); // Newest first
    });
    return array_map('basename', $backups);
}

// Format timestamp from backup name
function formatTimestamp($backupName) {
    preg_match('/_(\d{8})_(\d{6})\..+$/', $backupName, $matches);
    if (isset($matches[1]) && isset($matches[2])) {
        $date = DateTime::createFromFormat('Ymd His', $matches[1] . ' ' . $matches[2]);
        return $date->format('Y-m-d H:i:s');
    }
    return 'Unknown';
}

// Recursive delete folder
function deleteFolder($dirPath) {
    if (!is_dir($dirPath)) {
        return false;
    }
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dirPath, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($iterator as $file) {
        if ($file->isDir()) {
            rmdir($file->getPathname());
        } else {
            unlink($file->getPathname());
        }
    }
    rmdir($dirPath);
    return true;
}

// Protect pages: redirect if not logged in
if (!isLoggedIn() && basename($_SERVER['PHP_SELF']) !== 'index.php') {
    header('Location: index.php');
    exit;
}
?>