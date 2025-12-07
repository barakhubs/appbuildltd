<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

$id = $_GET['id'] ?? 0;
if (!$id) {
    header('Location: submissions.php');
    exit;
}

$db = getDB();

// Mark as read
$stmt = $db->prepare("UPDATE contact_submissions SET is_read = true WHERE id = :id");
$stmt->execute(['id' => $id]);

// Fetch submission
$stmt = $db->prepare("SELECT * FROM contact_submissions WHERE id = :id");
$stmt->execute(['id' => $id]);
$submission = $stmt->fetch();

if (!$submission) {
    header('Location: submissions.php');
    exit;
}

$pageTitle = 'View Submission';
include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">View Submission</h1>
        <a href="submissions.php" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition duration-300">
            <i class="fas fa-arrow-left mr-2"></i>Back to Submissions
        </a>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-gray-800 text-white py-4 px-6">
            <h2 class="text-xl font-bold">Submission from <?php echo htmlspecialchars($submission['name']); ?></h2>
        </div>
        <div class="p-6 text-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="font-semibold text-gray-600">Name</p>
                    <p><?php echo htmlspecialchars($submission['name']); ?></p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="font-semibold text-gray-600">Email</p>
                    <p><a href="mailto:<?php echo htmlspecialchars($submission['email']); ?>" class="text-blue-500 hover:underline"><?php echo htmlspecialchars($submission['email']); ?></a></p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="font-semibold text-gray-600">Submitted At</p>
                    <p><?php echo formatDate($submission['submitted_at'], true); ?></p>
                </div>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <p class="font-semibold text-gray-600">Subject</p>
                <p><?php echo htmlspecialchars($submission['subject']); ?></p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="font-semibold text-gray-600">Message</p>
                <p class="whitespace-pre-wrap"><?php echo htmlspecialchars($submission['message']); ?></p>
            </div>
        </div>
        <div class="bg-gray-100 py-4 px-6 border-t border-gray-200">
            <a href="submissions.php?action=delete&id=<?php echo $submission['id']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this submission?');">
                <i class="fas fa-trash mr-2"></i>Delete Submission
            </a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>