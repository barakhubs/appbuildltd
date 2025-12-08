<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$status = $_GET['status'] ?? 'all';

// Build query
$whereConditions = [];
$params = [];

if (!empty($search)) {
    $whereConditions[] = "(title LIKE :search OR content LIKE :search)";
    $params['search'] = "%$search%";
}

if (!empty($category)) {
    $whereConditions[] = "category = :category";
    $params['category'] = $category;
}

if ($status !== 'all') {
    $whereConditions[] = "status = :status";
    $params['status'] = $status;
}

$whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

$db = getDB();
$stmt = $db->prepare("SELECT * FROM blog_posts $whereClause ORDER BY created_at DESC");
$stmt->execute($params);
$blogPosts = $stmt->fetchAll();

$pageTitle = 'Manage Blog Posts';
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
                        <a href="index.php" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="blog-manage.php" class="flex items-center p-3 rounded-lg bg-blue-700 text-white">
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
                        </a>
                    </li>
                    <li class="pt-4 border-t border-blue-600">
                        <a href="account-settings.php" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-user-cog mr-3"></i>Account Settings
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Page Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Blog Posts</h2>
                    <p class="text-gray-600 mt-2">Manage your blog posts and content.</p>
                </div>
                <a href="blog-edit.php"
                    class="bg-primary-blue text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    <i class="fas fa-plus mr-2"></i>New Blog Post
                </a>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" id="search" name="search"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-blue"
                            placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select id="category" name="category"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-blue">
                            <option value="">All Categories</option>
                            <?php foreach (getBlogCategories() as $cat): ?>
                                <option value="<?php echo $cat; ?>" <?php echo $category === $cat ? 'selected' : ''; ?>>
                                    <?php echo $cat; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-blue">
                            <option value="all" <?php echo $status === 'all' ? 'selected' : ''; ?>>All Status</option>
                            <option value="published" <?php echo $status === 'published' ? 'selected' : ''; ?>>Published</option>
                            <option value="draft" <?php echo $status === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-primary-blue text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Blog Posts Table -->
            <div class="bg-white rounded-lg shadow">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($blogPosts)): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-blog text-4xl mb-4"></i>
                                        <p class="text-lg">No blog posts found.</p>
                                        <a href="blog-edit.php" class="text-primary-blue hover:text-blue-700 font-medium mt-2 inline-block">
                                            Create your first blog post
                                        </a>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($blogPosts as $post): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div>
                                                <h3 class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($post['title']); ?>
                                                </h3>
                                                <?php if ($post['excerpt']): ?>
                                                    <p class="text-sm text-gray-500 mt-1">
                                                        <?php echo truncateText(strip_tags($post['excerpt']), 100); ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <?php echo htmlspecialchars($post['category']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        <?php echo $post['status'] === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                                <i class="fas <?php echo $post['status'] === 'published' ? 'fa-check-circle' : 'fa-clock'; ?> mr-1"></i>
                                                <?php echo ucfirst($post['status']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo formatDate($post['created_at'], 'M j, Y'); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <?php if ($post['status'] === 'published'): ?>
                                                <a href="<?php echo SITE_URL; ?>/blog-post.php?slug=<?php echo $post['slug']; ?>"
                                                    target="_blank" class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="blog-edit.php?id=<?php echo $post['id']; ?>"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="deleteBlogPost(<?php echo $post['id']; ?>)"
                                                class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        function deleteBlogPost(postId) {
            if (confirm('Are you sure you want to delete this blog post? This action cannot be undone.')) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'blog-delete.php';

                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'id';
                idInput.value = postId;

                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = 'csrf_token';
                tokenInput.value = '<?php echo generateCSRFToken(); ?>';

                form.appendChild(idInput);
                form.appendChild(tokenInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>

</html>