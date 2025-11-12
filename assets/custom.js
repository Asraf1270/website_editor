$(document).ready(function() {
    let currentItem = '';
    let isFolder = false;

    // Load items
    function loadFiles() {
        $.get('list_files.php', function(data) {
            $('#fileList').empty();
            data.forEach(item => {
                const display = item.endsWith('/') ? `<strong>${item}</strong>` : item;
                $('#fileList').append(`<li class="list-group-item file-item" data-item="${item}">${display}</li>`);
            });
        });
    }

    loadFiles();

    // Select item
    $(document).on('click', '.file-item', function() {
        $('.file-item').removeClass('active');
        $(this).addClass('active');
        currentItem = $(this).data('item');
        isFolder = currentItem.endsWith('/');
        $('#currentFile').text(currentItem);
        $('#deleteItem').prop('disabled', false);
        $('#renameFile').prop('disabled', isFolder); // No rename for folders
        $('#viewBtn').prop('disabled', isFolder);
        $('#replaceFile').prop('disabled', isFolder);
        $('#backupList').empty();

        if (isFolder) {
            $('#editor').val('This is a folder. No editing available.').prop('disabled', true);
            $('#saveBtn').prop('disabled', true);
            $('#message').html('<div class="alert alert-info">Folder selected. You can delete it (recursive if not empty). Drag and drop files here to upload into this folder.</div>');
        } else {
            // Load content for files
            $.get('load_file.php?filename=' + currentItem, function(res) {
                if (res.success) {
                    if (res.isBinary) {
                        $('#editor').val('').prop('disabled', true);
                        $('#saveBtn').prop('disabled', true);
                        $('#replaceFile').prop('disabled', false);
                        $('#message').html(`<div class="alert alert-info">${res.message}</div>`);
                    } else {
                        $('#editor').val(res.content).prop('disabled', false);
                        $('#saveBtn').prop('disabled', false);
                        $('#replaceFile').prop('disabled', true);
                        $('#message').empty();
                    }
                }
            });

            // Load backups for files
            loadBackups();
        }
    });

    // Load backups (files only)
    function loadBackups() {
        $.get('list_backups.php?filename=' + currentItem, function(data) {
            $('#backupList').empty();
            data.forEach(backup => {
                $('#backupList').append(`
                    <li class="list-group-item">
                        ${backup.name} (${backup.timestamp})
                        <a href="backups/${backup.name}" target="_blank" class="btn btn-sm btn-info">View</a>
                        <button class="btn btn-sm btn-warning restore" data-backup="${backup.name}">Restore</button>
                        <button class="btn btn-sm btn-danger delete-backup" data-backup="${backup.name}">Delete</button>
                    </li>
                `);
            });
        });
    }

    // Save (text only)
    $('#saveBtn').click(function() {
        const content = $('#editor').val();
        $.post('save.php', { filename: currentItem, content: content }, function(res) {
            $('#message').html(`<div class="alert alert-success">${res.message}</div>`);
            loadBackups();
        });
    });

    // View file
    $('#viewBtn').click(function() {
        window.open('pages/' + currentItem, '_blank');
    });

    // Create file (empty, any path/ext)
    $('#createFile').click(function() {
        const filename = prompt('Enter file path and name (e.g., folder/subfile.txt)');
        if (filename) {
            $.post('create_file.php', { filename: filename }, function(res) {
                if (res.success) {
                    loadFiles();
                } else {
                    alert(res.message);
                }
            });
        }
    });

    // Upload new file (any type, path)
    $('#uploadNew').click(function() {
        const filename = prompt('Enter file path and name (e.g., folder/image.jpg)');
        if (filename) {
            const input = document.createElement('input');
            input.type = 'file';
            input.onchange = e => {
                const formData = new FormData();
                formData.append('file', e.target.files[0]);
                formData.append('filename', filename);
                $.ajax({
                    url: 'upload.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: res => {
                        if (res.success) {
                            loadFiles();
                            alert(res.message);
                        } else {
                            alert(res.message);
                        }
                    }
                });
            };
            input.click();
        }
    });

    // Replace with upload (backs up old)
    $('#replaceFile').click(function() {
        const input = document.createElement('input');
        input.type = 'file';
        input.onchange = e => {
            const formData = new FormData();
            formData.append('file', e.target.files[0]);
            formData.append('filename', currentItem);
            $.ajax({
                url: 'upload.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: res => {
                    if (res.success) {
                        loadBackups();
                        $('#message').html(`<div class="alert alert-success">${res.message}</div>`);
                    } else {
                        alert(res.message);
                    }
                }
            });
        };
        input.click();
    });

    // Create folder
    $('#createFolder').click(function() {
        const foldername = prompt('Enter folder path (e.g., folder/subfolder)');
        if (foldername) {
            $.post('create_folder.php', { foldername: foldername }, function(res) {
                if (res.success) {
                    loadFiles();
                } else {
                    alert(res.message);
                }
            });
        }
    });

    // Rename file (basename only, same dir, files only)
    $('#renameFile').click(function() {
        if (!currentItem || isFolder) return;
        const oldBase = currentItem.split('/').pop();
        const newBase = prompt('Enter new file name (without path)', oldBase);
        if (newBase && newBase !== oldBase) {
            $.post('rename_file.php', { oldName: currentItem, newName: newBase }, function(res) {
                if (res.success) {
                    loadFiles();
                    currentItem = currentItem.replace(oldBase, newBase);
                    $('#currentFile').text(currentItem);
                } else {
                    alert(res.message);
                }
            });
        }
    });

    // Delete item (file or folder)
    $('#deleteItem').click(function() {
        if (!currentItem) return;
        const type = isFolder ? 'folder (and all contents)' : 'file';
        if (confirm(`Delete ${type}: ${currentItem}?`)) {
            const url = isFolder ? 'delete_folder.php' : 'delete_file.php';
            const param = isFolder ? 'foldername' : 'filename';
            $.post(url, { [param]: currentItem }, function(res) {
                if (res.success) {
                    loadFiles();
                    $('#editor').val('').prop('disabled', false);
                    $('#currentFile').text('Select an item');
                    $('#saveBtn, #renameFile, #deleteItem, #viewBtn, #replaceFile').prop('disabled', true);
                    $('#backupList').empty();
                    $('#message').empty();
                } else {
                    alert(res.message);
                }
            });
        }
    });

    // Restore backup (files only)
    $(document).on('click', '.restore', function() {
        const backup = $(this).data('backup');
        if (confirm('Restore from ' + backup + '?')) {
            $.post('restore_backup.php', { backupName: backup, filename: currentItem }, function(res) {
                if (res.success) {
                    // Reload content
                    $.get('load_file.php?filename=' + currentItem, function(res) {
                        if (res.isBinary) {
                            $('#editor').val('').prop('disabled', true);
                            $('#saveBtn').prop('disabled', true);
                            $('#replaceFile').prop('disabled', false);
                            $('#message').html(`<div class="alert alert-info">${res.message}</div>`);
                        } else {
                            $('#editor').val(res.content).prop('disabled', false);
                            $('#saveBtn').prop('disabled', false);
                            $('#replaceFile').prop('disabled', true);
                            $('#message').empty();
                        }
                    });
                    loadBackups();
                    $('#message').html(`<div class="alert alert-success">${res.message}</div>`);
                }
            });
        }
    });

    // Delete backup
    $(document).on('click', '.delete-backup', function() {
        const backup = $(this).data('backup');
        if (confirm('Delete ' + backup + '?')) {
            $.post('delete_backup.php', { backupName: backup }, function(res) {
                if (res.success) {
                    loadBackups();
                }
            });
        }
    });

    // Search items
    $('#searchFiles').on('input', function() {
        const search = $(this).val().toLowerCase();
        $('.file-item').each(function() {
            $(this).toggle($(this).text().toLowerCase().includes(search));
        });
    });

    // Theme toggle
    $('#themeToggle').click(function() {
        $('body').toggleClass('dark-mode');
        localStorage.setItem('theme', $('body').hasClass('dark-mode') ? 'dark' : 'light');
    });

    // Load theme
    if (localStorage.getItem('theme') === 'dark') {
        $('body').addClass('dark-mode');
    }

    // Drag and drop upload (anywhere on page)
    $(document).on('dragover', function(e) {
        e.preventDefault();
        $('body').addClass('drag-over');
    });

    $(document).on('dragleave', function(e) {
        e.preventDefault();
        $('body').removeClass('drag-over');
    });

    $(document).on('drop', function(e) {
        e.preventDefault();
        $('body').removeClass('drag-over');
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            // Prompt for path, default to current folder if selected
            let defaultPath = '';
            if (isFolder) {
                defaultPath = currentItem;
            }
            const path = prompt('Enter folder path for upload (e.g., folder/subfolder/)', defaultPath);
            if (path !== null) { // Proceed if not canceled
                const normalizedPath = path ? path.replace(/\/*$/, '/') : ''; // Ensure ends with /
                Array.from(files).forEach(file => {
                    const filename = normalizedPath + file.name;
                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('filename', filename);
                    $.ajax({
                        url: 'upload.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: res => {
                            if (res.success) {
                                loadFiles();
                                $('#message').html(`<div class="alert alert-success">${res.message} (${file.name})</div>`);
                            } else {
                                alert(res.message);
                            }
                        }
                    });
                });
            }
        }
    });
});