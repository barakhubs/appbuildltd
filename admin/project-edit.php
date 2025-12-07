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
    'excerpt' => '',
    'content' => '',
    'image_url' => '',
    'client' => '',
    'project_date' => '',
    'project_url' => '',
    'category' => '',
    'is_featured' => false
];

if (!$is_new) {
    $stmt = $db->prepare("SELECT * FROM projects WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$project) {
        header("Location: projects.php");
        exit;
    }
    $pageTitle = 'Edit Project';
} else {
    $pageTitle = 'Add New Project';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $slug = !empty($_POST['slug']) ? createSlug($_POST['slug']) : createSlug($title);
    $excerpt = $_POST['excerpt'] ?? '';
    $content = $_POST['content'] ?? '';
    $client = $_POST['client'] ?? '';
    $project_date = $_POST['project_date'] ?? '';
    $project_url = $_POST['project_url'] ?? '';
    $category = $_POST['category'] ?? '';
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    // Image handling
    $image_url = $_POST['existing_image_url'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/projects/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $filename = uniqid() . '-' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_url = 'uploads/projects/' . $filename;
        }
    }

    if ($is_new) {
        $sql = "INSERT INTO projects (title, slug, excerpt, content, image_url, client, project_date, project_url, category, is_featured) VALUES (:title, :slug, :excerpt, :content, :image_url, :client, :project_date, :project_url, :category, :is_featured)";
        $stmt = $db->prepare($sql);
    } else {
        $sql = "UPDATE projects SET title = :title, slug = :slug, excerpt = :excerpt, content = :content, image_url = :image_url, client = :client, project_date = :project_date, project_url = :project_url, category = :category, is_featured = :is_featured WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    }

    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':slug', $slug);
    $stmt->bindParam(':excerpt', $excerpt);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':image_url', $image_url);
    $stmt->bindParam(':client', $client);
    $stmt->bindParam(':project_date', $project_date);
    $stmt->bindParam(':project_url', $project_url);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':is_featured', $is_featured, PDO::PARAM_BOOL);

    if ($stmt->execute()) {
        header("Location: projects.php?saved=true");
        exit;
    } else {
        $error = "Error saving project.";
    }
}

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800"><?php echo $pageTitle; ?></h1>
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
            <label for="excerpt" class="block text-gray-700 text-sm font-bold mb-2">Excerpt</label>
            <textarea id="excerpt" name="excerpt" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo htmlspecialchars($project['excerpt']); ?></textarea>
        </div>

        <div class="mb-6">
            <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Content</label>
            <textarea id="content" name="content" rows="10" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo htmlspecialchars($project['content']); ?></textarea>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="mb-6">
                <label for="client" class="block text-gray-700 text-sm font-bold mb-2">Client</label>
                <input type="text" id="client" name="client" value="<?php echo htmlspecialchars($project['client']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-6">
                <label for="project_date" class="block text-gray-700 text-sm font-bold mb-2">Project Date</label>
                <input type="date" id="project_date" name="project_date" value="<?php echo htmlspecialchars($project['project_date']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="mb-6">
                <label for="project_url" class="block text-gray-700 text-sm font-bold mb-2">Project URL</label>
                <input type="url" id="project_url" name="project_url" value="<?php echo htmlspecialchars($project['project_url']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-6">
                <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($project['category']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
        </div>

        <div class="mb-6">
            <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Featured Image</label>
            <input type="file" id="image" name="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <input type="hidden" name="existing_image_url" value="<?php echo htmlspecialchars($project['image_url']); ?>">
            <?php if ($project['image_url']): ?>
                <div class="mt-4">
                    <p class="text-gray-600">Current Image:</p>
                    <img src="../<?php echo htmlspecialchars($project['image_url']); ?>" alt="Current project image" class="w-48 h-auto rounded mt-2">
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_featured" value="1" <?php echo !empty($project['is_featured']) ? 'checked' : ''; ?> class="form-checkbox h-5 w-5 text-blue-600">
                <span class="ml-2 text-gray-700">Feature this project on the homepage</span>
            </label>
        </div>

        <div class="flex items-center justify-end">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition duration-300">
                <i class="fas fa-save mr-2"></i>Save Project
            </button>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>