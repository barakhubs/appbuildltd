<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

$db = getDB();
$pageTitle = 'Edit Service';

$id = $_GET['id'] ?? 0;
$is_new = !$id;

$service = [
    'id' => '',
    'service_key' => '',
    'title' => '',
    'description' => '',
    'benefits' => '',
    'use_cases' => '',
    'featured_image' => ''
];

if (!$is_new) {
    $stmt = $db->prepare("SELECT * FROM service_pages WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $fetchedService = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$fetchedService) {
        header("Location: services.php");
        exit;
    }
    // Merge fetched data with defaults to ensure all keys exist and handle nulls
    $service = array_merge($service, array_filter($fetchedService, function ($value) {
        return $value !== null;
    }));
    $pageTitle = 'Edit Service';
} else {
    $pageTitle = 'Add New Service';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $service_key = !empty($_POST['service_key']) ? createSlug($_POST['service_key']) : createSlug($title);
    $description = $_POST['description'] ?? '';
    $benefits = $_POST['benefits'] ?? '';
    $use_cases = $_POST['use_cases'] ?? '';

    // Image handling
    $featured_image = $_POST['existing_featured_image'] ?? '';
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/services/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $filename = uniqid() . '-' . basename($_FILES['featured_image']['name']);
        $target_file = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $target_file)) {
            $featured_image = 'uploads/services/' . $filename;
        }
    }

    if ($is_new) {
        $sql = "INSERT INTO service_pages (title, service_key, description, benefits, use_cases, featured_image) VALUES (:title, :service_key, :description, :benefits, :use_cases, :featured_image)";
        $stmt = $db->prepare($sql);
    } else {
        $sql = "UPDATE service_pages SET title = :title, service_key = :service_key, description = :description, benefits = :benefits, use_cases = :use_cases, featured_image = :featured_image WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    }

    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':service_key', $service_key);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':benefits', $benefits);
    $stmt->bindParam(':use_cases', $use_cases);
    $stmt->bindParam(':featured_image', $featured_image);

    if ($stmt->execute()) {
        header("Location: services.php?saved=true");
        exit;
    } else {
        $error = "Error saving service.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Quill.js -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editors = {};
            const fields = ['description', 'benefits', 'use_cases'];

            fields.forEach(field => {
                editors[field] = new Quill(`#${field}-editor`, {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            ['bold', 'italic'],
                            [{
                                'list': 'ordered'
                            }, {
                                'list': 'bullet'
                            }],
                            ['link']
                        ]
                    }
                });

                const textarea = document.getElementById(field);
                if (textarea.value) {
                    editors[field].root.innerHTML = textarea.value;
                }
            });

            const form = document.querySelector('form');
            form.addEventListener('submit', function() {
                fields.forEach(field => {
                    document.getElementById(field).value = editors[field].root.innerHTML;
                });
            });
        });
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
                        <a href="services.php" class="flex items-center p-3 rounded-lg bg-blue-700 text-white">
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
                    <h2 class="text-3xl font-bold text-gray-800"><?php echo $pageTitle; ?></h2>
                    <p class="text-gray-600 mt-2">Manage service pages and content.</p>
                </div>
                <a href="services.php" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Services
                </a>
            </div>

            <?php if (isset($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <form action="service-edit.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg p-8">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($service['id']); ?>">

                <div class="mb-6">
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($service['title']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="mb-6">
                    <label for="service_key" class="block text-gray-700 text-sm font-bold mb-2">Service Key</label>
                    <input type="text" id="service_key" name="service_key" value="<?php echo htmlspecialchars($service['service_key']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p class="text-gray-600 text-xs italic mt-2">Leave blank to auto-generate from title.</p>
                </div>

                <div class="mb-6">
                    <label for="featured_image" class="block text-gray-700 text-sm font-bold mb-2">Featured Image</label>
                    <input type="file" id="featured_image" name="featured_image" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <input type="hidden" name="existing_featured_image" value="<?php echo htmlspecialchars($service['featured_image']); ?>">
                    <?php if (!empty($service['featured_image'])): ?>
                        <div class="mt-4">
                            <p class="text-gray-600 text-xs mb-2">Current Image:</p>
                            <img src="../<?php echo htmlspecialchars($service['featured_image']); ?>" alt="Current service image" class="w-48 h-auto rounded">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                    <div id="description-editor" style="height: 200px; background: white;"></div>
                    <textarea id="description" name="description" class="hidden"><?php echo $service['description']; ?></textarea>
                </div>

                <div class="mb-6">
                    <label for="benefits" class="block text-gray-700 text-sm font-bold mb-2">Benefits</label>
                    <div id="benefits-editor" style="height: 250px; background: white;"></div>
                    <textarea id="benefits" name="benefits" class="hidden"><?php echo $service['benefits']; ?></textarea>
                </div>

                <div class="mb-6">
                    <label for="use_cases" class="block text-gray-700 text-sm font-bold mb-2">Use Cases</label>
                    <div id="use_cases-editor" style="height: 250px; background: white;"></div>
                    <textarea id="use_cases" name="use_cases" class="hidden"><?php echo $service['use_cases']; ?></textarea>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-primary-blue hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition duration-300">
                        <i class="fas fa-save mr-2"></i>Save Service
                    </button>
                </div>
            </form>
        </main>
    </div>
</body>

</html>