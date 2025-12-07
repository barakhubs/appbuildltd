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
    'title' => '',
    'slug' => '',
    'excerpt' => '',
    'content' => '',
    'icon' => 'fas fa-cogs'
];

if (!$is_new) {
    $stmt = $db->prepare("SELECT * FROM services WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$service) {
        header("Location: services.php");
        exit;
    }
    $pageTitle = 'Edit Service';
} else {
    $pageTitle = 'Add New Service';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $slug = !empty($_POST['slug']) ? createSlug($_POST['slug']) : createSlug($title);
    $excerpt = $_POST['excerpt'] ?? '';
    $content = $_POST['content'] ?? '';
    $icon = $_POST['icon'] ?? 'fas fa-cogs';

    if ($is_new) {
        $sql = "INSERT INTO services (title, slug, excerpt, content, icon) VALUES (:title, :slug, :excerpt, :content, :icon)";
        $stmt = $db->prepare($sql);
    } else {
        $sql = "UPDATE services SET title = :title, slug = :slug, excerpt = :excerpt, content = :content, icon = :icon WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    }

    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':slug', $slug);
    $stmt->bindParam(':excerpt', $excerpt);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':icon', $icon);

    if ($stmt->execute()) {
        header("Location: services.php?saved=true");
        exit;
    } else {
        $error = "Error saving service.";
    }
}

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800"><?php echo $pageTitle; ?></h1>
        <a href="services.php" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition duration-300">
            <i class="fas fa-arrow-left mr-2"></i>Back to Services
        </a>
    </div>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p><?php echo $error; ?></p>
        </div>
    <?php endif; ?>

    <form action="service-edit.php?id=<?php echo $id; ?>" method="POST" class="bg-white shadow-lg rounded-lg p-8">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($service['id']); ?>">

        <div class="mb-6">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($service['title']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-6">
            <label for="slug" class="block text-gray-700 text-sm font-bold mb-2">Slug</label>
            <input type="text" id="slug" name="slug" value="<?php echo htmlspecialchars($service['slug']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <p class="text-gray-600 text-xs italic mt-2">Leave blank to auto-generate from title.</p>
        </div>

        <div class="mb-6">
            <label for="icon" class="block text-gray-700 text-sm font-bold mb-2">Font Awesome Icon Class</label>
            <input type="text" id="icon" name="icon" value="<?php echo htmlspecialchars($service['icon']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <p class="text-gray-600 text-xs italic mt-2">e.g., 'fas fa-cogs'. Find icons on <a href="https://fontawesome.com/icons" target="_blank" class="text-blue-500">Font Awesome</a>.</p>
        </div>

        <div class="mb-6">
            <label for="excerpt" class="block text-gray-700 text-sm font-bold mb-2">Excerpt</label>
            <textarea id="excerpt" name="excerpt" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo htmlspecialchars($service['excerpt']); ?></textarea>
        </div>

        <div class="mb-6">
            <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Content</label>
            <textarea id="content" name="content" rows="10" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo htmlspecialchars($service['content']); ?></textarea>
        </div>

        <div class="flex items-center justify-end">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition duration-300">
                <i class="fas fa-save mr-2"></i>Save Service
            </button>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>