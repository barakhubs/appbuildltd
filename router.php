<?php
// router.php - Custom router for PHP built-in server
// This file handles URL routing since .htaccess doesn't work with PHP built-in server

$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$query = parse_url($request, PHP_URL_QUERY);

// Remove leading slash
$path = ltrim($path, '/');

// Handle static files - let PHP serve them normally
if (preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$/', $path)) {
    return false; // Let PHP serve the file normally
}

// Handle admin directory
if (strpos($path, 'admin/') === 0) {
    $file = $path;
    if (is_file($file)) {
        return false; // Let PHP serve the file normally
    }
    // Handle admin clean URLs
    if ($path === 'admin' || $path === 'admin/') {
        $_SERVER['REQUEST_URI'] = '/admin/index.php';
        include 'admin/index.php';
        return true;
    }
}

// Handle specific service routes
if (preg_match('#^services/([a-z-]+)/?$#', $path, $matches)) {
    $_GET['service'] = $matches[1];
    $_SERVER['REQUEST_URI'] = '/service-detail.php?service=' . $matches[1];
    include 'service-detail.php';
    return true;
}

// Handle specific project detail routes
if (preg_match('#^projects/([a-z0-9-]+)/?$#', $path, $matches)) {
    $_GET['slug'] = $matches[1];
    $_SERVER['REQUEST_URI'] = '/project-detail.php?slug=' . $matches[1];
    include 'project-detail.php';
    return true;
}

// Handle specific blog post routes
if (preg_match('#^blog/([a-z0-9-]+)/?$#', $path, $matches)) {
    $_GET['slug'] = $matches[1];
    $_SERVER['REQUEST_URI'] = '/blog-post.php?slug=' . $matches[1];
    include 'blog-post.php';
    return true;
}

// Handle clean URLs for main pages
$cleanUrlMap = [
    '' => 'index.php',
    'home' => 'index.php',
    'about' => 'about.php',
    'services' => 'services.php',
    'projects' => 'projects.php',
    'blog' => 'blog.php',
    'contact' => 'contact.php',
    'url-test' => 'url-test.php',
];

// Remove trailing slash and check clean URL map
$cleanPath = rtrim($path, '/');
if (isset($cleanUrlMap[$cleanPath])) {
    $file = $cleanUrlMap[$cleanPath];
    if (is_file($file)) {
        include $file;
        return true;
    }
}

// Try appending .php extension
if (!empty($path) && !strpos($path, '.')) {
    $phpFile = $path . '.php';
    if (is_file($phpFile)) {
        include $phpFile;
        return true;
    }
}

// Check if file exists as requested
if (is_file($path)) {
    return false; // Let PHP serve the file normally
}

// Handle directories
if (is_dir($path)) {
    $indexFile = rtrim($path, '/') . '/index.php';
    if (is_file($indexFile)) {
        include $indexFile;
        return true;
    }
}

// Return 404 for unmatched routes
http_response_code(404);
echo "<h1>404 - Page Not Found</h1>";
echo "<p>The requested URL '$request' was not found on this server.</p>";
echo "<p><a href='/'>Return to Home</a></p>";
return true;
