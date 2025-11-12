**Local File Manager**

A simple, PHP-based file manager that runs locally using XAMPP. It allows users to create, edit, upload, delete, and manage files and folders with automatic backups, version restoration, and a modern web interface.

## Description

This application provides a browser-based interface for managing files on your local server. It's designed for developers or users who need a quick way to handle files without leaving the browser. Features include text editing for non-binary files, binary file handling via upload/replace, folder management, and backup versioning.

The app uses PHP sessions for authentication (JSON-based user storage) and AJAX for seamless operations. It supports light/dark themes and is responsive for desktop/mobile.

## Features

- **File Management**: Create, rename, delete, and view files of any type.
- **Folder Management**: Create and delete folders (recursive deletion for non-empty folders).
- **Text Editing**: Edit text-based files in a modern textarea with syntax-friendly styling.
- **Binary File Support**: View/download binary files; replace via upload with auto-backup.
- **Auto Backups**: Creates timestamped backups before saves, replaces, or restores.
- **Backup Management**: List, view, restore, or delete backups for each file.
- **Upload Support**: Upload new files or replace existing ones; supports any file type.
- **Drag and Drop**: Upload files by dragging them onto the page (prompts for path).
- **Search**: Filter files/folders in the sidebar.
- **Theme Toggle**: Switch between light and dark modes (persists via localStorage).
- **Authentication**: Simple login system using JSON file (default: admin/password).
- **Responsive UI**: Works on desktop and mobile with Bootstrap.

## Tech Stack

- **Backend**: PHP 8+ (file operations, sessions, AJAX handlers).
- **Frontend**: HTML, CSS (custom modern stylesheet), JavaScript (jQuery for AJAX/DOM).
- **UI Framework**: Bootstrap 5 (CDN) for layout and components.
- **Icons**: Font Awesome 6 (CDN) for social icons in footer.
- **Server**: XAMPP (Apache + PHP; no MySQL needed).
- **Storage**: File system for files/backups; JSON for users.

## Setup Instructions

1. **Install XAMPP**: Download and install XAMPP from [apachefriends.org](https://www.apachefriends.org/). Start Apache.

2. **Place the Project**: Copy the `website_editor` folder (rename if desired) into XAMPP's `htdocs` directory (e.g., `C:\xampp\htdocs\website_editor`).

3. **Create Directories**: Inside `website_editor`, ensure these folders exist (created automatically on first run):
   - `pages/` (stores managed files and folders).
   - `backups/` (stores backup versions).
   - `assets/` (CSS and JS files).
   - `includes/` (functions and users.json).

4. **Users File**: The `includes/users.json` should contain:
   ```json
   [
       {
           "id": 1,
           "username": "admin",
           "password_hash": "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi"
       }
   ]
   ```
   - Default login: Username `admin`, Password `password`.

5. **Access the App**: Open `http://localhost/website_editor/index.php` in your browser. Log in to access the editor.

## Usage

- **Login**: Use default credentials or add users to `users.json` (hash passwords with `password_hash()`).
- **Sidebar**: Lists files/folders (folders bolded with `/`). Search to filter.
- **Create**: Use buttons for new files/folders/uploads. Prompts for paths/names.
- **Edit**: Select a text file to load in editor; save via button.
- **Binary Files**: Editor disabled; use "Replace with Upload".
- **Backups**: Shown below editor; view in new tab, restore (backs up current), or delete.
- **Delete**: Confirms; recursive for folders.
- **Drag & Drop**: Drop files anywhere; prompt for path (defaults to selected folder).
- **Theme**: Toggle button switches modes.
- **Logout**: Redirects to login.

## Folder Structure

```
website_editor/
├── assets/
│   ├── custom.js       # JavaScript logic (AJAX, events, drag-drop)
│   └── style.css       # Custom CSS (modern, responsive)
├── backups/            # Auto-created for backups
├── includes/
│   ├── functions.php   # Core functions (auth, file ops)
│   └── users.json      # User credentials
├── pages/              # Managed files/folders go here
├── create_file.php     # AJAX: Create empty file
├── create_folder.php   # AJAX: Create folder
├── delete_backup.php   # AJAX: Delete backup
├── delete_file.php     # AJAX: Delete file
├── delete_folder.php   # AJAX: Delete folder (recursive)
├── editor.php          # Main interface
├── index.php           # Login page
├── list_backups.php    # AJAX: List backups
├── list_files.php      # AJAX: List items
├── load_file.php       # AJAX: Load file content
├── logout.php          # Logout handler
├── rename_file.php     # AJAX: Rename file
├── restore_backup.php  # AJAX: Restore from backup
├── save.php            # AJAX: Save text file
├── upload.php          # AJAX: Upload/replace file
└── README.md           # This file
```

## Bonus Features

- **Responsive Design**: Adjusts for mobile (sidebar stacks).
- **Hover Effects**: Buttons/lists scale and shadow on hover.
- **Footer**: Social icons (placeholders) and copyright.
- **Visual Feedback**: Drag-over highlight; alerts/messages for actions.

## Possible Enhancements

Here are some ideas for features you could add to extend the functionality of this Local File Manager:

- **User Registration and Multi-User Support**: Implement a registration form to add new users dynamically to `users.json`. Add role-based access (e.g., admin vs. viewer) and per-user folders for isolation.
- **Folder Renaming and Moving**: Extend the rename functionality to folders and add drag-and-drop or buttons to move files/folders between directories.
- **File Previews**: Add inline previews for images, videos, or PDFs (using HTML5 elements or libraries like PDF.js).
- **Search Within Files**: Implement full-text search across file contents using PHP's file reading capabilities or integrate a lightweight search library.
- **Backup Diff Viewer**: When viewing backups, show differences between versions (e.g., using a simple diff algorithm in JS or PHP).
- **File Sharing/Download Links**: Generate temporary download links for files or integrate with local sharing tools.
- **Keyboard Shortcuts**: Add hotkeys for common actions (e.g., Ctrl+S to save, Del to delete) using JS event listeners.
- **Undo/Redo Stack**: Track recent operations (e.g., deletes, renames) in session storage for quick reversal.
- **External Integration**: Connect to cloud storage (e.g., Google Drive API) or Git for version control.
- **Advanced Security**: Add file permissions, encryption for sensitive files, or CAPTCHA on login.
- **Export/Import**: Allow zipping and downloading entire folders or importing ZIP archives.
- **Syntax Highlighting**: Integrate a JS library like Prism.js or CodeMirror for better code editing in the textarea.
- **Notifications**: Use browser notifications for long operations (e.g., large uploads) or errors.
- **Analytics**: Log user actions (e.g., file accesses) to a local file for auditing.

These features can be implemented incrementally, starting with client-side JS enhancements or additional PHP endpoints.

## Limitations

- No multi-user support (single JSON file).
- Backups are flat (paths flattened with `_`).
- No folder renaming/moving (only files rename in place).
- Runs locally only; not for production without security enhancements.

## License

MIT License. Feel free to modify and use for personal projects.
