<?php require_once 'includes/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Local File Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"> <!-- Font Awesome for icons -->
    <link href="assets/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/custom.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <h4>Items</h4>
                <input type="text" id="searchFiles" class="form-control mb-3" placeholder="Search items...">
                <ul id="fileList" class="list-group"></ul>
                <button id="createFile" class="btn btn-success mt-2">Create New File</button>
                <button id="uploadNew" class="btn btn-info mt-2">Upload New File</button>
                <button id="createFolder" class="btn btn-primary mt-2">Create Folder</button>
                <button id="renameFile" class="btn btn-warning mt-2" disabled>Rename</button>
                <button id="deleteItem" class="btn btn-danger mt-2" disabled>Delete</button>
                <hr>
                <button id="themeToggle" class="btn btn-secondary">Toggle Theme</button>
                <a href="logout.php" class="btn btn-outline-danger mt-2">Logout</a>
            </div>
            <!-- Main Editor -->
            <div class="col-md-9 main-content">
                <h2 id="currentFile">Select an item</h2>
                <textarea id="editor" class="form-control" rows="15"></textarea>
                <button id="saveBtn" class="btn btn-primary mt-3" disabled>Save</button>
                <button id="viewBtn" class="btn btn-info mt-3" disabled>View</button>
                <button id="replaceFile" class="btn btn-warning mt-3" disabled>Replace with Upload</button>
                <div id="message" class="mt-3"><div class="alert alert-info">Tip: Drag and drop files anywhere on the page to upload.</div></div>
                <h4 class="mt-4">Backups (for files only)</h4>
                <ul id="backupList" class="list-group"></ul>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="social-icons">
            <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
            <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
            <a href="https://linkedin.com" target="_blank"><i class="fab fa-linkedin-in"></i></a>
            <a href="https://github.com" target="_blank"><i class="fab fa-github"></i></a>
        </div>
        <p>&copy; 2025 Local File Manager. All rights reserved.</p>
    </footer>
</body>
</html>