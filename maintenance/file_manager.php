<?php
// Simple file manager for uploads directory
$uploadDir = '../uploads/';
$directories = ['users', 'products', 'backups' , 'reports'];

// Handle file deletion
if (isset($_GET['delete'])) {
    $dir = $_GET['dir'] ?? '';
    if (in_array($dir, $directories)) {
        $fileToDelete = $uploadDir . $dir . '/' . basename($_GET['delete']);
        if (file_exists($fileToDelete) && unlink($fileToDelete)) {
            $message = "✓ File deleted successfully";
        }
    }
}

// Handle bulk deletion
if (isset($_POST['bulk_delete']) && isset($_POST['selected_files'])) {
    $deletedCount = 0;
    foreach ($_POST['selected_files'] as $fileData) {
        $parts = explode('|', $fileData);
        if (count($parts) === 2) {
            $dir = $parts[0];
            $filename = basename($parts[1]);
            if (in_array($dir, $directories)) {
                $fileToDelete = $uploadDir . $dir . '/' . $filename;
                if (file_exists($fileToDelete) && unlink($fileToDelete)) {
                    $deletedCount++;
                }
            }
        }
    }
    if ($deletedCount > 0) {
        $message = "✓ $deletedCount files deleted successfully";
    }
}

// Get file lists
$filesByDir = [];
$totalSize = 0;

foreach ($directories as $dir) {
    $dirPath = $uploadDir . $dir;
    if (is_dir($dirPath)) {
        $files = glob($dirPath . '/*');
        $filesByDir[$dir] = [];
        foreach ($files as $file) {
            if (is_file($file)) {
                $size = filesize($file);
                $totalSize += $size;
                $filesByDir[$dir][] = [
                    'name' => basename($file),
                    'size' => $size,
                    'modified' => filemtime($file),
                    'path' => $file
                ];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>File Manager</title>
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --secondary-color: #f8fafc;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.1);
            --border-radius: 8px;
            --transition: all 0.2s ease-out;
            --pma-bg: #f0f4f8;
            --pma-bg-hover: #e6f2f9;
            --pma-border: #e2e8f0;
            --pma-success: #10b981;
            --pma-warning: #f59e0b;
            --pma-danger: #ef4444;
            --pma-primary: #6366f1;
            --pma-primary-dark: #4f46e5;
            --pma-text: #1e293b;
            --pma-text-light: #64748b;
        }
        * {margin: 0; padding: 0; box-sizing: border-box;}
        body {font-family: 'Segoe UI', system-ui, sans-serif; font-size: 13px; color: var(--pma-text); background: var(--pma-bg); padding: 20px;}
        .container {max-width: 1400px; margin: 0 auto; background: white; border: 1px solid var(--pma-border); border-radius: 4px; overflow: hidden;}
        h1 {font-size: 20px; font-weight: 600; color: white; background: linear-gradient(to bottom, var(--pma-primary), var(--pma-primary-dark)); padding: 15px 20px; display: flex; align-items: center; gap: 10px;}
        h1:before {content: "📁"; font-size: 22px;}
        
        .info-bar {padding: 15px 20px; background: #E7F4FD; border-bottom: 1px solid var(--pma-border); display: flex; justify-content: space-between; align-items: center;}
        .message {background: #D4EDDA; color: #155724; padding: 10px 20px; border-bottom: 1px solid #C3E6CB;}
        
        .directory-section {padding: 0 0 30px 0;}
        h2 {font-size: 16px; padding: 12px 20px; background: #F9F9F9; border-bottom: 1px solid var(--pma-border); margin-bottom: 15px; display: flex; align-items: center; gap: 10px;}
        
        /* Grid Layout */
        .file-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            padding: 0 20px;
        }
        
        .file-card {
            border: 1px solid var(--pma-border);
            border-radius: 6px;
            background: white;
            padding: 10px;
            position: relative;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .file-card:hover { border-color: var(--pma-primary); box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        
        .file-card.selected { background: #F0F7FD; border-color: var(--pma-primary); }

        .file-preview {
            width: 100%;
            height: 120px;
            background: #eee;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        .file-preview img { width: 100%; height: 100%; object-fit: cover; }
        .file-preview .icon { font-size: 40px; color: #999; }
        
        .file-info { flex-grow: 1; overflow: hidden; }
        .file-name { font-weight: 600; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--pma-text); }
        .file-meta { font-size: 11px; color: #999; line-height: 1.4; }
        
        .file-actions { display: flex; justify-content: space-between; align-items: center; margin-top: 5px; border-top: 1px solid #eee; pt: 8px; }
        
        .checkbox-container { position: absolute; top: 10px; left: 10px; z-index: 10; width: 20px; height: 20px; cursor: pointer; }
        
        .btn { padding: 5px 10px; border-radius: 3px; font-weight: 600; text-decoration: none; font-size: 11px; border: none; cursor: pointer; }
        .btn-danger { background: var(--danger); color: white; }
        .btn-bulk-delete { background: var(--danger); color: white; padding: 8px 20px; font-size: 13px; position: sticky; bottom: 20px; right: 20px; z-index: 100; box-shadow: 0 4px 12px rgba(217, 83, 79, 0.4); }
        
        .select-all-btn { font-size: 11px; background: #eee; border: 1px solid #ccc; padding: 2px 8px; border-radius: 3px; cursor: pointer; }

        .sticky-footer { position: sticky; bottom: 0; background: rgba(255,255,255,0.9); backdrop-filter: blur(5px); padding: 15px 20px; border-top: 1px solid var(--pma-border); text-align: right; }
    </style>
</head>
<body>
    <div class="container">
        <h1>File Manager <a href="index.php" style="text-decoration: none; display: inline-block; padding: 6px 12px; font-size: 13px; font-weight: 600; border-radius: 3px; cursor: pointer; margin-left: auto; background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">Home</a></h1>
        
        <?php if (isset($message)): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>

        <div class="info-bar">
            <span><strong>Total Disk Usage:</strong> <?= round($totalSize / 1024 / 1024, 2) ?> MB</span>
        </div>
        
        <form method="post" id="bulkForm">
            <?php foreach ($filesByDir as $dir => $files): ?>
            <div class="directory-section">
                <h2>
                    📁 /uploads/<?= $dir ?> (<?= count($files) ?> files)
                    <button type="button" class="select-all-btn" onclick="toggleSelection('<?= $dir ?>')">Select All</button>
                </h2>
                
                <?php if (count($files) > 0): ?>
                <div class="file-grid" id="grid-<?= $dir ?>">
                    <?php foreach ($files as $file): ?>
                    <?php 
                        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                        $displayPath = 'uploads/' . $dir . '/' . $file['name'];
                    ?>
                    <div class="file-card" id="card-<?= md5($file['path']) ?>">
                        <input type="checkbox" name="selected_files[]" value="<?= $dir ?>|<?= $file['name'] ?>" class="checkbox-container cb-<?= $dir ?>" onclick="updateCardStyle(this)">
                        
                        <div class="file-preview">
                            <?php if ($isImage): ?>
                                <img src="../<?= $displayPath ?>" alt="Preview">
                            <?php else: ?>
                                <div class="icon"><?= ($ext == 'sql' ? '🗄️' : ($ext == 'zip' ? '📦' : '📄')) ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="file-info">
                            <div class="file-name" title="<?= htmlspecialchars($file['name']) ?>"><?= htmlspecialchars($file['name']) ?></div>
                            <div class="file-meta">
                                <strong>Size:</strong> <?= round($file['size'] / 1024, 2) ?> KB<br>
                                <strong>Modified:</strong> <?= date('M j, Y', $file['modified']) ?>
                            </div>
                        </div>
                        
                        <div class="file-actions">
                            <a href="?delete=<?= urlencode($file['name']) ?>&dir=<?= $dir ?>" 
                               class="btn btn-danger" 
                               onclick="return confirm('Delete this file?')">Delete</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <p style="padding: 20px; text-align: center; color: #999;">No files in this directory</p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>

            <div class="sticky-footer">
                <button type="submit" name="bulk_delete" class="btn btn-bulk-delete" onclick="return confirm('Delete all selected files?')">🗑️ Delete Selected Files</button>
            </div>
        </form>
    </div>

    <script>
        function updateCardStyle(cb) {
            const card = cb.closest('.file-card');
            if (cb.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        }

        function toggleSelection(dir) {
            const checkboxes = document.querySelectorAll('.cb-' + dir);
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(cb => {
                cb.checked = !allChecked;
                updateCardStyle(cb);
            });
        }
    </script>
</body>
</html>
