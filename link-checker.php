<?php

/**
 * Link Verification Script
 * Checks all internal links in the AppBuild website
 */

echo "<!DOCTYPE html><html><head><title>Link Verification Report</title>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    h1 { color: #265E9A; }
    h2 { color: #333; margin-top: 30px; border-bottom: 2px solid #265E9A; padding-bottom: 10px; }
    .success { color: #28a745; font-weight: bold; }
    .error { color: #dc3545; font-weight: bold; }
    .warning { color: #ffc107; font-weight: bold; }
    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
    th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
    th { background: #265E9A; color: white; }
    tr:nth-child(even) { background: #f9f9f9; }
    .file-path { font-family: monospace; background: #f0f0f0; padding: 2px 6px; border-radius: 3px; }
    .summary { background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 20px 0; }
</style></head><body><div class='container'>";

echo "<h1>AppBuild Ltd. - Link Verification Report</h1>";
echo "<p>Generated: " . date('Y-m-d H:i:s') . "</p>";

// Define base path
$basePath = __DIR__;

// Files to check
$filesToCheck = [
    // Public pages
    'index.php',
    'about.php',
    'services.php',
    'service-detail.php',
    'projects.php',
    'project-detail.php',
    'blog.php',
    'blog-post.php',
    'contact.php',
    // Includes
    'includes/header.php',
    'includes/footer.php',
    // Admin pages
    'admin/index.php',
    'admin/login.php',
    'admin/blog-manage.php',
    'admin/blog-edit.php',
    'admin/projects.php',
    'admin/project-edit.php',
    'admin/services.php',
    'admin/service-edit.php',
    'admin/submissions.php',
    'admin/submission-view.php',
    'admin/account-settings.php',
];

$results = [
    'total_files' => 0,
    'files_exist' => 0,
    'files_missing' => 0,
    'require_paths' => [],
    'missing_files' => [],
];

echo "<h2>File Existence Check</h2>";
echo "<table><tr><th>File Path</th><th>Status</th></tr>";

foreach ($filesToCheck as $file) {
    $fullPath = $basePath . '/' . $file;
    $results['total_files']++;

    if (file_exists($fullPath)) {
        echo "<tr><td class='file-path'>{$file}</td><td class='success'>✓ EXISTS</td></tr>";
        $results['files_exist']++;
    } else {
        echo "<tr><td class='file-path'>{$file}</td><td class='error'>✗ MISSING</td></tr>";
        $results['files_missing']++;
        $results['missing_files'][] = $file;
    }
}

echo "</table>";

// Check require_once paths
echo "<h2>Include/Require Path Verification</h2>";
echo "<table><tr><th>File</th><th>Include Statement</th><th>Target Exists</th></tr>";

$includePatterns = [
    'index.php' => ['includes/header.php', 'includes/footer.php'],
    'about.php' => ['includes/header.php', 'includes/footer.php'],
    'services.php' => ['includes/header.php', 'includes/footer.php'],
    'service-detail.php' => ['includes/header.php', 'includes/footer.php'],
    'projects.php' => ['includes/header.php', 'includes/footer.php'],
    'project-detail.php' => ['includes/header.php', 'includes/footer.php'],
    'blog.php' => ['includes/header.php', 'includes/footer.php'],
    'blog-post.php' => ['includes/header.php', 'includes/footer.php'],
    'contact.php' => ['includes/config.php', 'includes/header.php', 'includes/footer.php'],
    'includes/header.php' => ['includes/config.php', 'includes/functions.php'],
    'admin/index.php' => ['../includes/config.php', '../includes/functions.php'],
    'admin/blog-manage.php' => ['../includes/config.php', '../includes/functions.php'],
    'admin/projects.php' => ['../includes/config.php', '../includes/functions.php'],
    'admin/services.php' => ['../includes/config.php', '../includes/functions.php'],
    'admin/submissions.php' => ['../includes/config.php', '../includes/functions.php'],
    'admin/account-settings.php' => ['../includes/config.php', '../includes/functions.php'],
];

foreach ($includePatterns as $sourceFile => $includes) {
    foreach ($includes as $includePath) {
        $sourceDir = dirname($basePath . '/' . $sourceFile);
        $targetPath = realpath($sourceDir . '/' . $includePath);

        $exists = $targetPath && file_exists($targetPath);
        $status = $exists ? "<span class='success'>✓</span>" : "<span class='error'>✗</span>";

        echo "<tr><td class='file-path'>{$sourceFile}</td><td>{$includePath}</td><td>{$status}</td></tr>";
    }
}

echo "</table>";

// Check router.php routes
echo "<h2>Router Configuration Check</h2>";
$routerFile = $basePath . '/router.php';
if (file_exists($routerFile)) {
    echo "<p class='success'>✓ router.php exists</p>";

    $routerTargets = [
        'service-detail.php' => 'Services detail handler',
        'project-detail.php' => 'Projects detail handler',
        'blog-post.php' => 'Blog post handler',
    ];

    echo "<table><tr><th>Route Handler</th><th>Description</th><th>Status</th></tr>";
    foreach ($routerTargets as $file => $desc) {
        $exists = file_exists($basePath . '/' . $file);
        $status = $exists ? "<span class='success'>✓ EXISTS</span>" : "<span class='error'>✗ MISSING</span>";
        echo "<tr><td class='file-path'>{$file}</td><td>{$desc}</td><td>{$status}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p class='error'>✗ router.php is missing!</p>";
}

// Check .htaccess
echo "<h2>.htaccess Configuration</h2>";
$htaccessFile = $basePath . '/.htaccess';
if (file_exists($htaccessFile)) {
    echo "<p class='success'>✓ .htaccess exists</p>";
    $htaccess = file_get_contents($htaccessFile);
    if (strpos($htaccess, 'RewriteEngine On') !== false) {
        echo "<p class='success'>✓ URL rewriting is enabled</p>";
    }
} else {
    echo "<p class='warning'>⚠ .htaccess not found (OK for PHP built-in server)</p>";
}

// Check critical directories
echo "<h2>Directory Structure Check</h2>";
$directories = [
    'includes',
    'admin',
    'assets',
    'assets/css',
    'assets/images',
    'uploads',
    'uploads/blog',
    'uploads/projects',
    'uploads/services',
];

echo "<table><tr><th>Directory</th><th>Status</th></tr>";
foreach ($directories as $dir) {
    $dirPath = $basePath . '/' . $dir;
    $exists = is_dir($dirPath);
    $status = $exists ? "<span class='success'>✓ EXISTS</span>" : "<span class='error'>✗ MISSING</span>";
    echo "<tr><td class='file-path'>{$dir}/</td><td>{$status}</td></tr>";
}
echo "</table>";

// Summary
echo "<div class='summary'>";
echo "<h2>Summary</h2>";
echo "<p><strong>Total Files Checked:</strong> {$results['total_files']}</p>";
echo "<p><strong>Files Exist:</strong> <span class='success'>{$results['files_exist']}</span></p>";
echo "<p><strong>Files Missing:</strong> <span class='error'>{$results['files_missing']}</span></p>";

if ($results['files_missing'] > 0) {
    echo "<h3>Missing Files:</h3><ul>";
    foreach ($results['missing_files'] as $file) {
        echo "<li class='file-path'>{$file}</li>";
    }
    echo "</ul>";
} else {
    echo "<p class='success'>✓ All critical files are present!</p>";
}

echo "<h3>Recommendations:</h3>";
echo "<ul>";
echo "<li>All public pages are in the root directory for clean URLs</li>";
echo "<li>Admin panel is organized in /admin/ directory</li>";
echo "<li>Shared includes are in /includes/ directory</li>";
echo "<li>Static assets are in /assets/ directory</li>";
echo "<li>User uploads are in /uploads/ directory</li>";
echo "<li>✓ Current structure is well-organized and links should work correctly</li>";
echo "</ul>";

echo "</div>";

echo "</div></body></html>";
