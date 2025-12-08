<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

$db = getDB();
$pageTitle = 'Edit Project';

$id = $_GET['id'] ?? 0;
$is_new = !$id;

$project = [
    'id' => '',
    'title' => '',
    'slug' => '',
    'thumbnail' => '',
    'service_category' => '',
    'client_name' => '',
    'description' => '',
    'challenge' => '',
    'solution' => '',
    'results' => '',
    'images' => '',
    'featured' => false
];

$serviceCategories = getServiceCategories();

if (!$is_new) {
    $stmt = $db->prepare("SELECT * FROM projects WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $fetchedProject = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$fetchedProject) {
        header("Location: projects.php");
        exit;
    }
    // Merge fetched data with defaults to ensure all keys exist
    $project = array_merge($project, $fetchedProject);
    $pageTitle = 'Edit Project';
} else {
    $pageTitle = 'Add New Project';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $slug = !empty($_POST['slug']) ? createSlug($_POST['slug']) : createSlug($title);
    $description = $_POST['description'] ?? '';
    $challenge = $_POST['challenge'] ?? '';
    $solution = $_POST['solution'] ?? '';
    $results = $_POST['results'] ?? '';
    $client_name = $_POST['client_name'] ?? '';
    $service_category = $_POST['service_category'] ?? '';
    $featured = isset($_POST['featured']) ? 1 : 0;

    // Image handling
    $thumbnail = $_POST['existing_thumbnail'] ?? '';
    if (isset($_FILES['thumbnail_file']) && $_FILES['thumbnail_file']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/projects/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $filename = uniqid() . '-' . basename($_FILES['thumbnail_file']['name']);
        $target_file = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['thumbnail_file']['tmp_name'], $target_file)) {
            $thumbnail = 'uploads/projects/' . $filename;
        }
    }

    if ($is_new) {
        $sql = "INSERT INTO projects (title, slug, description, challenge, solution, results, thumbnail, client_name, service_category, featured) VALUES (:title, :slug, :description, :challenge, :solution, :results, :thumbnail, :client_name, :service_category, :featured)";
        $stmt = $db->prepare($sql);
    } else {
        $sql = "UPDATE projects SET title = :title, slug = :slug, description = :description, challenge = :challenge, solution = :solution, results = :results, thumbnail = :thumbnail, client_name = :client_name, service_category = :service_category, featured = :featured WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    }

    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':slug', $slug);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':challenge', $challenge);
    $stmt->bindParam(':solution', $solution);
    $stmt->bindParam(':results', $results);
    $stmt->bindParam(':thumbnail', $thumbnail);
    $stmt->bindParam(':client_name', $client_name);
    $stmt->bindParam(':service_category', $service_category);
    $stmt->bindParam(':featured', $featured, PDO::PARAM_BOOL);

    if ($stmt->execute()) {
        header("Location: projects.php?saved=true");
        exit;
    } else {
        $error = "Error saving project.";
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
            const fields = ['description', 'challenge', 'solution', 'results'];

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
                        <a href="projects.php" class="flex items-center p-3 rounded-lg bg-blue-700 text-white">
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
                    <h2 class="text-3xl font-bold text-gray-800"><?php echo $pageTitle; ?></h2>
                    <p class="text-gray-600 mt-2">Manage project portfolio and details.</p>
                </div>
                <a href="projects.php" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Projects
                </a>
            </div>

            <?php if (isset($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <form action="project-edit.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg p-8">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($project['id']); ?>">

                <div class="mb-6">
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="mb-6">
                    <label for="slug" class="block text-gray-700 text-sm font-bold mb-2">Slug</label>
                    <input type="text" id="slug" name="slug" value="<?php echo htmlspecialchars($project['slug']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p class="text-gray-600 text-xs italic mt-2">Leave blank to auto-generate from title.</p>
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                    <div id="description-editor" style="height: 200px; background: white;"></div>
                    <textarea id="description" name="description" class="hidden"><?php echo htmlspecialchars($project['description']); ?></textarea>
                </div>

                <div class="mb-6">
                    <label for="challenge" class="block text-gray-700 text-sm font-bold mb-2">Challenge</label>
                    <div id="challenge-editor" style="height: 250px; background: white;"></div>
                    <textarea id="challenge" name="challenge" class="hidden"><?php echo htmlspecialchars($project['challenge']); ?></textarea>
                </div>

                <div class="mb-6">
                    <label for="solution" class="block text-gray-700 text-sm font-bold mb-2">Solution</label>
                    <div id="solution-editor" style="height: 250px; background: white;"></div>
                    <textarea id="solution" name="solution" class="hidden"><?php echo htmlspecialchars($project['solution']); ?></textarea>
                </div>

                <div class="mb-6">
                    <label for="results" class="block text-gray-700 text-sm font-bold mb-2">Results</label>
                    <div id="results-editor" style="height: 250px; background: white;"></div>
                    <textarea id="results" name="results" class="hidden"><?php echo htmlspecialchars($project['results']); ?></textarea>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="mb-6">
                        <label for="client_name" class="block text-gray-700 text-sm font-bold mb-2">Client Name</label>
                        <input type="text" id="client_name" name="client_name" value="<?php echo htmlspecialchars($project['client_name']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-6">
                        <label for="service_category" class="block text-gray-700 text-sm font-bold mb-2">Service Category</label>
                        <select id="service_category" name="service_category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select a category</option>
                            <?php foreach ($serviceCategories as $key => $label): ?>
                                <option value="<?php echo htmlspecialchars($key); ?>" <?php echo ($project['service_category'] === $key) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($label); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="thumbnail_file" class="block text-gray-700 text-sm font-bold mb-2">Thumbnail Image</label>
                    <input type="file" id="thumbnail_file" name="thumbnail_file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <input type="hidden" name="existing_thumbnail" value="<?php echo htmlspecialchars($project['thumbnail']); ?>">
                    <?php if (!empty($project['thumbnail'])): ?>
                        <div class="mt-4">
                            <p class="text-gray-600">Current Thumbnail:</p>
                            <img src="../<?php echo htmlspecialchars($project['thumbnail']); ?>" alt="Current project thumbnail" class="w-48 h-auto rounded mt-2">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="featured" value="1" <?php echo !empty($project['featured']) ? 'checked' : ''; ?> class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Feature this project on the homepage</span>
                    </label>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-primary-blue hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition duration-300">
                        <i class="fas fa-save mr-2"></i>Save Project
                    </button>
                </div>
            </form>
        </main>
    </div>
</body>

</html>