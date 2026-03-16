<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Test - Appbuild Tech Company ltd.</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">URL Testing Page</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Main Pages -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4 text-blue-600">Main Pages</h2>
                <ul class="space-y-2">
                    <li><a href="<?php echo 'http://localhost:9000'; ?>" class="text-blue-500 hover:underline" target="_blank">Home</a></li>
                    <li><a href="<?php echo 'http://localhost:9000'; ?>/about" class="text-blue-500 hover:underline" target="_blank">About</a></li>
                    <li><a href="<?php echo 'http://localhost:9000'; ?>/services" class="text-blue-500 hover:underline" target="_blank">Services</a></li>
                    <li><a href="<?php echo 'http://localhost:9000'; ?>/projects" class="text-blue-500 hover:underline" target="_blank">Projects</a></li>
                    <li><a href="<?php echo 'http://localhost:9000'; ?>/blog" class="text-blue-500 hover:underline" target="_blank">Blog</a></li>
                    <li><a href="<?php echo 'http://localhost:9000'; ?>/contact" class="text-blue-500 hover:underline" target="_blank">Contact</a></li>
                </ul>
            </div>

            <!-- Service Pages -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4 text-green-600">Service Pages</h2>
                <ul class="space-y-2">
                    <li><a href="<?php echo 'http://localhost:9000'; ?>/services/data-management" class="text-blue-500 hover:underline" target="_blank">Data Management</a></li>
                    <li><a href="<?php echo 'http://localhost:9000'; ?>/services/project-management" class="text-blue-500 hover:underline" target="_blank">Project Management</a></li>
                    <li><a href="<?php echo 'http://localhost:9000'; ?>/services/cost-management" class="text-blue-500 hover:underline" target="_blank">Cost Management</a></li>
                    <li><a href="<?php echo 'http://localhost:9000'; ?>/services/design-management" class="text-blue-500 hover:underline" target="_blank">Design Management</a></li>
                    <li><a href="<?php echo 'http://localhost:9000'; ?>/services/application-development" class="text-blue-500 hover:underline" target="_blank">Application Development</a></li>
                    <li><a href="<?php echo 'http://localhost:9000'; ?>/services/document-automation" class="text-blue-500 hover:underline" target="_blank">Document Automation</a></li>
                </ul>
            </div>

            <!-- Example Dynamic URLs -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4 text-purple-600">Example Dynamic URLs</h2>
                <ul class="space-y-2">
                    <li><a href="<?php echo 'http://localhost:9000'; ?>/projects/sample-project" class="text-blue-500 hover:underline" target="_blank">Sample Project Detail</a></li>
                    <li><a href="<?php echo 'http://localhost:9000'; ?>/blog/sample-blog-post" class="text-blue-500 hover:underline" target="_blank">Sample Blog Post</a></li>
                    <li><a href="<?php echo 'http://localhost:9000'; ?>/projects?category=data-management" class="text-blue-500 hover:underline" target="_blank">Projects by Category</a></li>
                    <li><a href="<?php echo 'http://localhost:9000'; ?>/blog?category=technology" class="text-blue-500 hover:underline" target="_blank">Blog by Category</a></li>
                </ul>
                <p class="text-sm text-gray-600 mt-4">
                    <i class="fas fa-info-circle mr-1"></i>
                    These URLs will show 404 unless you have sample data in your database.
                </p>
            </div>

            <!-- Admin URLs -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4 text-red-600">Admin Pages</h2>
                <ul class="space-y-2">
                    <li><a href="<?php echo 'http://localhost:9000'; ?>/admin" class="text-blue-500 hover:underline" target="_blank">Admin Dashboard</a></li>
                    <li><a href="<?php echo 'http://localhost:9000'; ?>/admin/login.php" class="text-blue-500 hover:underline" target="_blank">Admin Login</a></li>
                </ul>
                <p class="text-sm text-gray-600 mt-4">
                    <i class="fas fa-lock mr-1"></i>
                    Admin pages may require authentication.
                </p>
            </div>
        </div>

        <div class="mt-8 bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Testing Instructions</h2>
            <ol class="list-decimal list-inside space-y-2 text-gray-700">
                <li>Click on each link above to test if the URLs work correctly</li>
                <li>Check that there are no .php extensions in the URLs</li>
                <li>Verify that all pages load without 404 errors</li>
                <li>Test the navigation menu on each page</li>
                <li>Ensure proper redirects from old .php URLs to clean URLs</li>
            </ol>
        </div>

        <div class="mt-8 bg-green-50 p-6 rounded-lg border-l-4 border-green-400">
            <h3 class="text-lg font-bold text-green-800 mb-2">
                <i class="fas fa-check-circle mr-2"></i>
                Routing Fixed!
            </h3>
            <p class="text-green-700 mb-3">
                The URL routing issue has been resolved. Here's what was done:
            </p>
            <ul class="space-y-1 text-green-700 mb-4">
                <li>✅ Fixed .htaccess rewrite rule order</li>
                <li>✅ Created custom router.php for PHP development server</li>
                <li>✅ Server now runs with: <code class="bg-green-100 px-2 py-1 rounded">php -S localhost:9000 router.php</code></li>
                <li>✅ All clean URLs now work correctly</li>
                <li>✅ Dynamic routes (blog posts, projects, services) working</li>
            </ul>
            <div class="bg-green-100 p-3 rounded">
                <p class="text-sm font-medium text-green-800 mb-1">Important Note:</p>
                <p class="text-sm text-green-700">
                    The PHP built-in server doesn't use .htaccess files. For production (Apache/Nginx),
                    use the updated .htaccess. For development, use the router.php file.
                </p>
            </div>
        </div>

        <div class="mt-8 bg-blue-50 p-6 rounded-lg border-l-4 border-blue-400">
            <h3 class="text-lg font-bold text-blue-800 mb-2">
                <i class="fas fa-info-circle mr-2"></i>
                Production Deployment
            </h3>
            <p class="text-blue-700 mb-3">
                When deploying to a real web server (Apache/Nginx):
            </p>
            <ul class="space-y-1 text-blue-700">
                <li>📁 Upload all files except router.php</li>
                <li>⚙️ The .htaccess file will handle clean URLs automatically</li>
                <li>🔧 Ensure mod_rewrite is enabled on Apache</li>
                <li>🎯 All URLs will work exactly as tested here</li>
            </ul>
        </div>
    </div>
</body>

</html>