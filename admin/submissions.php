<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

$db = getDB();

// Handle actions (delete, mark as read/unread)
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? 0;

if ($action === 'delete' && $id > 0) {
    $stmt = $db->prepare("DELETE FROM contact_submissions WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header('Location: submissions.php?deleted=true');
    exit;
}

if ($action === 'toggle_read' && $id > 0) {
    $stmt = $db->prepare("UPDATE contact_submissions SET is_read = NOT is_read WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header('Location: submissions.php?toggled=true');
    exit;
}

// Fetch all submissions
$stmt = $db->query("SELECT * FROM contact_submissions ORDER BY submitted_at DESC");
$submissions = $stmt->fetchAll();

$pageTitle = 'Contact Submissions';
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
                        <a href="submissions.php" class="flex items-center p-3 rounded-lg bg-blue-700 text-white">
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
                    <h2 class="text-3xl font-bold text-gray-800">Contact Submissions</h2>
                    <p class="text-gray-600 mt-2">View and manage customer inquiries.</p>
                </div>
            </div>

            <?php if (isset($_GET['deleted'])): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p><i class="fas fa-check-circle mr-2"></i>Submission deleted successfully.</p>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['toggled'])): ?>
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
                    <p><i class="fas fa-info-circle mr-2"></i>Submission status updated.</p>
                </div>
            <?php endif; ?>

            <!-- Submissions Table -->
            <div class="bg-white rounded-lg shadow">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($submissions)): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-envelope text-4xl mb-4"></i>
                                        <p class="text-lg">No submissions found.</p>
                                        <p class="text-sm mt-2">Customer inquiries will appear here.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($submissions as $submission): ?>
                                    <tr class="hover:bg-gray-50 <?php echo !$submission['is_read'] ? 'bg-yellow-50' : ''; ?>">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($submission['name']); ?>
                                                <?php if (!$submission['is_read']): ?>
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        New
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($submission['email']); ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($submission['subject']); ?></div>
                                            <div class="text-sm text-gray-500 mt-1">
                                                <?php echo truncateText(strip_tags($submission['message']), 60); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        <?php echo !$submission['is_read'] ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'; ?>">
                                                <i class="fas <?php echo !$submission['is_read'] ? 'fa-envelope' : 'fa-envelope-open'; ?> mr-1"></i>
                                                <?php echo !$submission['is_read'] ? 'Unread' : 'Read'; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo formatDate($submission['submitted_at'], 'M j, Y'); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="submission-view.php?id=<?php echo $submission['id']; ?>"
                                                class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button onclick="toggleRead(<?php echo $submission['id']; ?>)"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                            <button onclick="deleteSubmission(<?php echo $submission['id']; ?>)"
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
        function deleteSubmission(submissionId) {
            if (confirm('Are you sure you want to delete this submission? This action cannot be undone.')) {
                window.location.href = 'submissions.php?action=delete&id=' + submissionId;
            }
        }

        function toggleRead(submissionId) {
            window.location.href = 'submissions.php?action=toggle_read&id=' + submissionId;
        }
    </script>
</body>

</html>