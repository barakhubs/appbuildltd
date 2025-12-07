<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

$db = getDB();
$pageTitle = 'Edit Blog Post';

$id = $_GET['id'] ?? 0;
$is_new = !$id;

$post = [
    'id' => '',
    'title' => '',
    'slug' => '',
    'excerpt' => '',
    'content' => '',
    'image_url' => '',
    'category' => '',
    'author' => $_SESSION['admin_username'],
    'status' => 'draft',
    'meta_description' => '',
    'tags' => ''
];

if (!$is_new) {
    $stmt = $db->prepare("SELECT * FROM blog_posts WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$post) {
        header("Location: blog-manage.php");
        exit;
    }
    $pageTitle = 'Edit Blog Post';
} else {
    $pageTitle = 'Add New Blog Post';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $slug = !empty($_POST['slug']) ? createSlug($_POST['slug']) : createSlug($title);
    $excerpt = $_POST['excerpt'] ?? '';
    $content = $_POST['content'] ?? '';
    $category = $_POST['category'] ?? '';
    $author = $_POST['author'] ?? $_SESSION['admin_username'];
    $status = $_POST['status'] ?? 'draft';
    $meta_description = $_POST['meta_description'] ?? '';
    $tags = $_POST['tags'] ?? '';

    // Image handling
    $image_url = $_POST['existing_image_url'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/blog/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $filename = uniqid() . '-' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_url = 'uploads/blog/' . $filename;
        }
    }

    if ($is_new) {
        $sql = "INSERT INTO blog_posts (title, slug, excerpt, content, image_url, category, author, status, meta_description, tags) VALUES (:title, :slug, :excerpt, :content, :image_url, :category, :author, :status, :meta_description, :tags)";
        $stmt = $db->prepare($sql);
    } else {
        $sql = "UPDATE blog_posts SET title = :title, slug = :slug, excerpt = :excerpt, content = :content, image_url = :image_url, category = :category, author = :author, status = :status, meta_description = :meta_description, tags = :tags, updated_at = NOW() WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    }

    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':slug', $slug);
    $stmt->bindParam(':excerpt', $excerpt);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':image_url', $image_url);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':meta_description', $meta_description);
    $stmt->bindParam(':tags', $tags);

    if ($stmt->execute()) {
        header("Location: blog-manage.php?saved=true");
        exit;
    } else {
        $error = "Error saving blog post.";
    }
}

$categories = getBlogCategories();
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
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800"><?php echo $pageTitle; ?></h2>
                    <p class="text-gray-600 mt-2">Create or update your blog content.</p>
                </div>
                <a href="blog-manage.php" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Posts
                </a>
            </div>

            <?php if (isset($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <form action="blog-edit.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg p-8">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($post['id']); ?>">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Content Area -->
                    <div class="lg:col-span-2 space-y-6">
                        <div>
                            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title *</label>
                            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div>
                            <label for="slug" class="block text-gray-700 text-sm font-bold mb-2">Slug</label>
                            <input type="text" id="slug" name="slug" value="<?php echo htmlspecialchars($post['slug']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <p class="text-gray-600 text-xs italic mt-2">Leave blank to auto-generate from title.</p>
                        </div>

                        <div>
                            <label for="excerpt" class="block text-gray-700 text-sm font-bold mb-2">Excerpt</label>
                            <textarea id="excerpt" name="excerpt" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo htmlspecialchars($post['excerpt']); ?></textarea>
                            <p class="text-gray-600 text-xs italic mt-2">A brief summary of your post.</p>
                        </div>

                        <div>
                            <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Content *</label>
                            <textarea id="content" name="content" rows="15" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                        </div>

                        <div>
                            <label for="meta_description" class="block text-gray-700 text-sm font-bold mb-2">Meta Description</label>
                            <textarea id="meta_description" name="meta_description" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo htmlspecialchars($post['meta_description'] ?? ''); ?></textarea>
                            <p class="text-gray-600 text-xs italic mt-2">For SEO purposes (155 characters max).</p>
                        </div>
                    </div>

                    <!-- Sidebar Settings -->
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-bold text-gray-700 mb-4">Publish Settings</h3>

                            <div class="mb-4">
                                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status *</label>
                                <select id="status" name="status" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    <option value="draft" <?php echo $post['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                    <option value="published" <?php echo $post['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="author" class="block text-gray-700 text-sm font-bold mb-2">Author</label>
                                <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($post['author']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-bold text-gray-700 mb-4">Categories & Tags</h3>

                            <div class="mb-4">
                                <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category *</label>
                                <select id="category" name="category" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $post['category'] === $cat ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label for="tags" class="block text-gray-700 text-sm font-bold mb-2">Tags</label>
                                <input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars($post['tags']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <p class="text-gray-600 text-xs italic mt-2">Comma separated.</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-bold text-gray-700 mb-4">Featured Image</h3>

                            <input type="file" id="image" name="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <input type="hidden" name="existing_image_url" value="<?php echo htmlspecialchars($post['image_url']); ?>">

                            <?php if ($post['image_url']): ?>
                                <div class="mt-4">
                                    <p class="text-gray-600 text-xs mb-2">Current Image:</p>
                                    <img src="../<?php echo htmlspecialchars($post['image_url']); ?>" alt="Current post image" class="w-full h-auto rounded">
                                </div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="w-full bg-primary-blue hover:bg-blue-700 text-white font-bold py-3 px-6 rounded focus:outline-none focus:shadow-outline transition duration-300">
                            <i class="fas fa-save mr-2"></i><?php echo $is_new ? 'Publish Post' : 'Update Post'; ?>
                        </button>
                    </div>
                </div>
            </form>
        </main>
    </div>
</body>

</html>