<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Structure</title>
    <?php include "../xtras/link.php"; ?>
    <style>
        body {
            background-color: #f1f5f9;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #334155;
            padding: 40px 20px;
        }

        .structure-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            max-width: 900px;
            margin: 0 auto;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .card-header {
            background: #f8fafc;
            padding: 20px 30px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.25rem;
            color: #0f172a;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-body {
            padding: 30px;
        }

        /* Tree Styles */
        .tree-root {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .tree-item {
            position: relative;
            line-height: 2;
        }

        .tree-content {
            display: flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 6px;
            transition: background-color 0.15s ease;
            cursor: pointer;
            user-select: none;
        }

        .tree-content:hover {
            background-color: #f1f5f9;
        }

        .tree-item ul {
            list-style: none;
            padding-left: 24px;
            position: relative;
            display: none; /* Collapsed by default */
        }

        .tree-item ul.expanded {
            display: block;
            animation: slideDown 0.2s ease-out;
        }

        /* Connecting Lines */
        .tree-item ul::before {
            content: '';
            position: absolute;
            top: 0;
            left: 12px;
            bottom: 12px;
            width: 1px;
            background-color: #e2e8f0;
        }

        /* Icons */
        .toggle-icon {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            color: #94a3b8;
            transition: transform 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }

        .toggle-icon.rotated {
            transform: rotate(90deg);
        }

        .item-icon {
            margin-right: 10px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .item-icon i {
            display: inline-flex;
            width: 26px;
            height: 26px;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 0.9em;
            color: white !important;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .item-name {
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 0.95rem;
        }

        .meta-info {
           margin-left: auto;
           font-size: 0.75rem;
           color: #94a3b8;
           font-family: sans-serif;
        }

        /* Icon Background Colors - Strong & Distinct */
        .bi-folder-fill, .bi-folder2-open { background-color: #f59e0b; } /* Amber-500 */
        
        .bi-filetype-php { background-color: #4f46e5; } /* Indigo-600 */
        .bi-filetype-html { background-color: #ea580c; } /* Orange-600 */
        .bi-filetype-css { background-color: #0034a5ff; } /* Blue-600 */
        .bi-filetype-js { background-color: #ca8a04; } /* Yellow-600 */
        .bi-filetype-json { background-color: #059669; } /* Emerald-600 */
        .bi-filetype-sql { background-color: #e11d48; } /* Rose-600 */
        .bi-filetype-xml { background-color: #0891b2; } /* Cyan-600 */
        .bi-filetype-py { background-color: #3776ab; } /* Python Blue */
        .bi-filetype-java { background-color: #b07219; } /* Java Brown */
        
        /* Media */
        .bi-file-earmark-play-fill { background-color: #dc2626; } /* Red-600 (Video) */
        .bi-file-earmark-music-fill { background-color: #9333ea; } /* Purple-600 (Audio) */
        .bi-file-image { background-color: #7c3aed; } /* Violet-600 */
        
        /* Docs */
        .bi-file-earmark-word-fill { background-color: #1d4ed8; } /* Blue-700 */
        .bi-file-earmark-excel-fill { background-color: #15803d; } /* Green-700 */
        .bi-file-earmark-ppt-fill { background-color: #c2410c; } /* Orange-700 */
        .bi-file-pdf-fill { background-color: #b91c1c; } /* Red-700 */
        .bi-file-text-fill { background-color: #475569; } /* Slate-600 */
        .bi-filetype-md { background-color: #0f172a; } /* Slate-900 */
        
        /* System/Config */
        .bi-git { background-color: #be123c; } /* Rose-700 */
        .bi-gear-wide-connected { background-color: #52525b; } /* Zinc-600 */
        .bi-file-zip-fill { background-color: #d97706; } /* Amber-600 */
        .bi-file-code-fill { background-color: #0284c7; } /* Sky-600 */
        .bi-file-earmark-fill { background-color: #94a3b8; } /* Slate-400 (Default) */
        .bi-hdd-network-fill { background-color: #3b82f6; } /* Root Blue */


        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
    </style>
</head>
<body>

<div class="structure-card">
    <div class="card-header">
        <h2><i class="bi bi-diagram-3-fill"></i> Project Structure</h2>
        <button class="btn btn-sm btn-outline-primary" onclick="expandAll()">Expand All</button>
    </div>
    <div class="card-body">
        <ul class="tree-root">
            <?php
            function getFileIcon($filename) {
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $icons = [
                    // Code
                    'php' => 'bi-filetype-php', 
                    'html' => 'bi-filetype-html', 'htm' => 'bi-filetype-html',
                    'css' => 'bi-filetype-css', 'scss' => 'bi-filetype-css', 'sass' => 'bi-filetype-css',
                    'js' => 'bi-filetype-js', 'ts' => 'bi-filetype-js',
                    'json' => 'bi-filetype-json', 
                    'sql' => 'bi-filetype-sql',
                    'xml' => 'bi-filetype-xml',
                    'py' => 'bi-filetype-py',
                    'java' => 'bi-filetype-java',
                    
                    // Config/System
                    'env' => 'bi-gear-wide-connected',
                    'htaccess' => 'bi-gear-wide-connected',
                    'gitignore' => 'bi-git',
                    'md' => 'bi-filetype-md',
                    'txt' => 'bi-file-text-fill',
                    'log' => 'bi-file-text-fill',
                    
                    // Images
                    'png' => 'bi-file-image', 'jpg' => 'bi-file-image', 'jpeg' => 'bi-file-image', 
                    'gif' => 'bi-file-image', 'svg' => 'bi-file-image', 'webp' => 'bi-file-image', 'ico' => 'bi-file-image',
                    
                    // Media
                    'mp4' => 'bi-file-earmark-play-fill', 'mkv' => 'bi-file-earmark-play-fill', 'mov' => 'bi-file-earmark-play-fill', 'avi' => 'bi-file-earmark-play-fill',
                    'mp3' => 'bi-file-earmark-music-fill', 'wav' => 'bi-file-earmark-music-fill',
                    
                    // Documents
                    'pdf' => 'bi-file-pdf-fill',
                    'doc' => 'bi-file-earmark-word-fill', 'docx' => 'bi-file-earmark-word-fill',
                    'xls' => 'bi-file-earmark-excel-fill', 'xlsx' => 'bi-file-earmark-excel-fill', 'csv' => 'bi-file-earmark-excel-fill',
                    'ppt' => 'bi-file-earmark-ppt-fill', 'pptx' => 'bi-file-earmark-ppt-fill',
                    
                    // Archives
                    'zip' => 'bi-file-zip-fill', 'rar' => 'bi-file-zip-fill', '7z' => 'bi-file-zip-fill', 'tar' => 'bi-file-zip-fill', 'gz' => 'bi-file-zip-fill'
                ];
                
                // Fallback for known extensions without specific 'filetype-' icons but mapped to generic code
                if (!isset($icons[$ext]) && in_array($ext, ['sh', 'bat', 'yml', 'yaml'])) {
                    return 'bi-file-code-fill';
                }

                return $icons[$ext] ?? 'bi-file-earmark-fill';
            }

            function renderTree($dir) {
                $files = scandir($dir);
                $dirs = [];
                $nonDirs = [];

                foreach ($files as $file) {
                    if ($file === '.' || $file === '..' || $file === '.git' || $file === 'vendor') continue;
                    if (is_dir($dir . '/' . $file)) {
                        $dirs[] = $file;
                    } else {
                        $nonDirs[] = $file;
                    }
                }
                sort($dirs);
                sort($nonDirs);

                // Render Directories
                foreach ($dirs as $folder) {
                    $itemCount = count(scandir($dir . '/' . $folder)) - 2; // Approximate count
                    echo '<li class="tree-item">';
                    echo '<div class="tree-content" onclick="toggleFolder(this)">';
                    echo '<span class="toggle-icon"><i class="bi bi-chevron-right"></i></span>';
                    echo '<span class="item-icon"><i class="bi bi-folder-fill"></i></span>';
                    echo '<span class="item-name">' . htmlspecialchars($folder) . '</span>';
                    echo '<span class="meta-info">' . $itemCount . ' items</span>';
                    echo '</div>';
                    echo '<ul>';
                    renderTree($dir . '/' . $folder);
                    echo '</ul>';
                    echo '</li>';
                }

                // Render Files
                foreach ($nonDirs as $file) {
                    $icon = getFileIcon($file);
                    $size = filesize($dir . '/' . $file);
                    $sizeStr = ($size > 1024) ? round($size/1024, 1) . ' KB' : $size . ' B';
                    
                    echo '<li class="tree-item">';
                    echo '<div class="tree-content file-item">';
                    echo '<span class="toggle-icon"></span>'; // Spacer
                    echo '<span class="item-icon"><i class="bi ' . $icon . '"></i></span>';
                    echo '<span class="item-name">' . htmlspecialchars($file) . '</span>';
                     echo '<span class="meta-info">' . $sizeStr . '</span>';
                    echo '</div>';
                    echo '</li>';
                }
            }
            
            // Root Node
            echo '<li class="tree-item">';
            echo '<div class="tree-content" onclick="toggleFolder(this)">';
            echo '<span class="toggle-icon rotated"><i class="bi bi-chevron-right"></i></span>';
            echo '<span class="item-icon"><i class="bi bi-hdd-network-fill text-primary"></i></span>';
            echo '<span class="item-name">root</span>';
            echo '</div>';
            echo '<ul class="expanded" style="display:block;">'; // Open by default
            renderTree(__DIR__);
            echo '</ul>';
            echo '</li>';
            ?>
        </ul>
    </div>
    <div class="card-footer text-center text-muted" style="background:#f8fafc; font-size:0.8rem; padding:10px;">
        Generated automatically via structure.php
    </div>
</div>

<script>
    function toggleFolder(element) {
        // Find the next sibling UL
        const nextUl = element.parentElement.querySelector('ul');
        if (!nextUl) return; // It's a file or empty folder logic handled differently

        const toggleIcon = element.querySelector('.toggle-icon');
        const folderIcon = element.querySelector('.item-icon i');

        if (nextUl.classList.contains('expanded')) {
            // Collapse
            nextUl.classList.remove('expanded');
            setTimeout(() => { nextUl.style.display = 'none'; }, 200); // Wait for animation if using CSS transitions, else immediately
             nextUl.style.display = 'none'; // Simple toggle for now
            
            if(toggleIcon) toggleIcon.classList.remove('rotated');
            if(folderIcon) {
                folderIcon.classList.remove('bi-folder2-open');
                folderIcon.classList.add('bi-folder-fill');
            }
        } else {
            // Expand
            nextUl.style.display = 'block';
            // Small delay to allow display:block to render so transition can trigger
            requestAnimationFrame(() => {
                nextUl.classList.add('expanded');
            });
            
            if(toggleIcon) toggleIcon.classList.add('rotated');
            if(folderIcon) {
                folderIcon.classList.remove('bi-folder-fill');
                folderIcon.classList.add('bi-folder2-open');
            }
        }
    }

    function expandAll() {
        const uls = document.querySelectorAll('.tree-item ul');
        const icons = document.querySelectorAll('.toggle-icon');
        const folders = document.querySelectorAll('.bi-folder-fill');

        uls.forEach(ul => {
            ul.style.display = 'block';
            ul.classList.add('expanded');
        });
        icons.forEach(icon => icon.classList.add('rotated'));
        folders.forEach(icon => {
             icon.classList.remove('bi-folder-fill');
             icon.classList.add('bi-folder2-open');
        });
    }
</script>

</body>
</html>
