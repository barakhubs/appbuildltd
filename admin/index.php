<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

// Get dashboard statistics
$db = getDB();

// Count published blog posts
$stmt = $db->query("SELECT COUNT(*) as count FROM blog_posts WHERE status = 'published'");
$blogCount = $stmt->fetch()['count'];

// Count published projects
$stmt = $db->query("SELECT COUNT(*) as count FROM projects WHERE status = 'published'");
$projectCount = $stmt->fetch()['count'];

// Count unread contact submissions
$stmt = $db->query("SELECT COUNT(*) as count FROM contact_submissions WHERE is_read = false");
$contactCount = $stmt->fetch()['count'];

// Get recent activities
$stmt = $db->query("
    (SELECT 'blog' as type, title, created_at FROM blog_posts ORDER BY created_at DESC LIMIT 5)
    UNION ALL
    (SELECT 'project' as type, title, created_at FROM projects ORDER BY created_at DESC LIMIT 5)
    UNION ALL
    (SELECT 'contact' as type, name || ' - ' || LEFT(message, 50) || '...' as title, submitted_at as created_at FROM contact_submissions ORDER BY submitted_at DESC LIMIT 5)
    ORDER BY created_at DESC LIMIT 10
");
$recentActivities = $stmt->fetchAll();

$pageTitle = 'Admin Dashboard';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-blue': '#265E9A',
                        'secondary-red': '#F54927',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="flex items-center justify-between px-6 py-4">
            <h1 class="text-2xl font-bold text-primary-blue"><?php echo SITE_NAME; ?> - Admin</h1>
            <div class="flex items-center space-x-4">
                <span class="text-gray-600">Welcome, <?php echo $_SESSION['admin_username']; ?></span>
                <a href="<?php echo SITE_URL; ?>" target="_blank"
                    class="text-primary-blue hover:text-blue-700 transition-colors">
                    <i class="fas fa-external-link-alt mr-1"></i>View Site
                </a>
                <a href="logout.php" class="text-red-600 hover:text-red-700 transition-colors">
                    <i class="fas fa-sign-out-alt mr-1"></i>Logout
                </a>
            </div>
        </div>
    </header>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="bg-primary-blue text-white w-64 min-h-screen">
            <nav class="p-6">
                <ul class="space-y-2">
                    <li>
                        <a href="index.php" class="flex items-center p-3 rounded-lg bg-blue-700 text-white">
                            <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="blog-manage.php" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-blog mr-3"></i>Blog Posts
                        </a>
                    </li>
                    <li>
                        <a href="projects.php" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-project-diagram mr-3"></i>Projects
                        </a>
                    </li>
                    <li>
                        <a href="services.php" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-cogs mr-3"></i>Services
                        </a>
                    </li>
                    <li>
                        <a href="submissions.php" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-envelope mr-3"></i>Contact Submissions
                            <?php if ($contactCount > 0): ?>
                                <span class="ml-auto bg-secondary-red text-white text-xs px-2 py-1 rounded-full">
                                    <?php echo $contactCount; ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Page Title -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Dashboard</h2>
                <p class="text-gray-600 mt-2">Welcome to your admin panel. Here's an overview of your website.</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-primary-blue">
                            <i class="fas fa-blog text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">Blog Posts</h3>
                            <p class="text-3xl font-bold text-primary-blue"><?php echo $blogCount; ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="blog-manage.php" class="text-primary-blue hover:text-blue-700 font-medium">
                            Manage Posts <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-project-diagram text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">Projects</h3>
                            <p class="text-3xl font-bold text-green-600"><?php echo $projectCount; ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="projects.php" class="text-primary-blue hover:text-blue-700 font-medium">
                            Manage Projects <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-600">
                            <i class="fas fa-envelope text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">New Messages</h3>
                            <p class="text-3xl font-bold text-red-600"><?php echo $contactCount; ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="submissions.php" class="text-primary-blue hover:text-blue-700 font-medium">
                            View Messages <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="blog-manage.php?action=add"
                        class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-primary-blue hover:bg-blue-50 transition-colors">
                        <i class="fas fa-plus text-primary-blue mr-3"></i>
                        <span class="font-medium">New Blog Post</span>
                    </a>
                    <a href="project-edit.php"
                        class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-primary-blue hover:bg-blue-50 transition-colors">
                        <i class="fas fa-plus text-primary-blue mr-3"></i>
                        <span class="font-medium">New Project</span>
                    </a>
                    <a href="services.php"
                        class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-primary-blue hover:bg-blue-50 transition-colors">
                        <i class="fas fa-edit text-primary-blue mr-3"></i>
                        <span class="font-medium">Edit Services</span>
                    </a>
                    <a href="submissions.php"
                        class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-primary-blue hover:bg-blue-50 transition-colors">
                        <i class="fas fa-envelope text-primary-blue mr-3"></i>
                        <span class="font-medium">View Messages</span>
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Recent Activity</h3>

                <?php if (empty($recentActivities)): ?>
                    <p class="text-gray-500 text-center py-8">No recent activity to display.</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($recentActivities as $activity): ?>
                            <div class="flex items-center p-4 border-l-4 border-primary-blue bg-blue-50">
                                <div class="flex-shrink-0">
                                    <?php
                                    $iconClass = '';
                                    $bgColor = '';
                                    switch ($activity['type']) {
                                        case 'blog':
                                            $iconClass = 'fas fa-blog';
                                            $bgColor = 'bg-blue-100 text-blue-600';
                                            break;
                                        case 'project':
                                            $iconClass = 'fas fa-project-diagram';
                                            $bgColor = 'bg-green-100 text-green-600';
                                            break;
                                        case 'contact':
                                            $iconClass = 'fas fa-envelope';
                                            $bgColor = 'bg-red-100 text-red-600';
                                            break;
                                    }
                                    ?>
                                    <div class="p-2 rounded-full <?php echo $bgColor; ?>">
                                        <i class="<?php echo $iconClass; ?>"></i>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="font-medium text-gray-800"><?php echo htmlspecialchars($activity['title']); ?></p>
                                    <p class="text-sm text-gray-600">
                                        <?php echo ucfirst($activity['type']); ?> • <?php echo formatDate($activity['created_at'], 'M j, Y g:i A'); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>