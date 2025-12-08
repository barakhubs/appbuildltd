<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

$db = getDB();
$message = '';
$messageType = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        // Update username and email
        $newUsername = trim($_POST['username']);
        $newEmail = trim($_POST['email']);

        if (empty($newUsername)) {
            $message = 'Username cannot be empty.';
            $messageType = 'error';
        } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $message = 'Please enter a valid email address.';
            $messageType = 'error';
        } else {
            $stmt = $db->prepare("UPDATE admin_users SET username = ?, email = ? WHERE id = ?");
            $stmt->execute([$newUsername, $newEmail, $_SESSION['admin_user_id']]);
            $_SESSION['admin_username'] = $newUsername;
            $message = 'Profile updated successfully!';
            $messageType = 'success';
        }
    } elseif (isset($_POST['change_password'])) {
        // Change password
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        // Verify current password
        $stmt = $db->prepare("SELECT password_hash FROM admin_users WHERE id = ?");
        $stmt->execute([$_SESSION['admin_user_id']]);
        $admin = $stmt->fetch();

        if (!password_verify($currentPassword, $admin['password_hash'])) {
            $message = 'Current password is incorrect.';
            $messageType = 'error';
        } elseif (strlen($newPassword) < 8) {
            $message = 'New password must be at least 8 characters long.';
            $messageType = 'error';
        } elseif ($newPassword !== $confirmPassword) {
            $message = 'New password and confirmation do not match.';
            $messageType = 'error';
        } else {
            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE admin_users SET password_hash = ? WHERE id = ?");
            $stmt->execute([$passwordHash, $_SESSION['admin_user_id']]);
            $message = 'Password changed successfully!';
            $messageType = 'success';
        }
    }
}

// Get current admin user data
$stmt = $db->prepare("SELECT username, email FROM admin_users WHERE id = ?");
$stmt->execute([$_SESSION['admin_user_id']]);
$adminData = $stmt->fetch();

// Count unread contact submissions for badge
$stmt = $db->query("SELECT COUNT(*) as count FROM contact_submissions WHERE is_read = false");
$contactCount = $stmt->fetch()['count'];

$pageTitle = 'Account Settings';
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
                        <a href="submissions.php" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-envelope mr-3"></i>Contact Submissions
                            <?php if ($contactCount > 0): ?>
                                <span class="ml-auto bg-secondary-red text-white text-xs px-2 py-1 rounded-full">
                                    <?php echo $contactCount; ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="pt-4 border-t border-blue-600">
                        <a href="account-settings.php" class="flex items-center p-3 rounded-lg bg-blue-700 text-white">
                            <i class="fas fa-user-cog mr-3"></i>Account Settings
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Page Title -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Account Settings</h2>
                <p class="text-gray-600 mt-2">Manage your account information and security settings</p>
            </div>

            <!-- Success/Error Messages -->
            <?php if ($message): ?>
                <div class="mb-6 p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200'; ?>">
                    <div class="flex items-center">
                        <i class="fas <?php echo $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-2"></i>
                        <?php echo $message; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Profile Information Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-user-circle text-primary-blue mr-2"></i>
                            Profile Information
                        </h3>
                    </div>
                    <form method="POST" class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                                    Username
                                </label>
                                <input type="text"
                                    id="username"
                                    name="username"
                                    value="<?php echo htmlspecialchars($adminData['username']); ?>"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-blue">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email Address
                                </label>
                                <input type="email"
                                    id="email"
                                    name="email"
                                    value="<?php echo htmlspecialchars($adminData['email'] ?? ''); ?>"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-blue">
                            </div>
                            <div class="pt-4">
                                <button type="submit"
                                    name="update_profile"
                                    class="w-full bg-primary-blue text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                    <i class="fas fa-save mr-2"></i>Update Profile
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Change Password Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-lock text-primary-blue mr-2"></i>
                            Change Password
                        </h3>
                    </div>
                    <form method="POST" class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                                    Current Password
                                </label>
                                <input type="password"
                                    id="current_password"
                                    name="current_password"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-blue">
                            </div>
                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">
                                    New Password
                                </label>
                                <input type="password"
                                    id="new_password"
                                    name="new_password"
                                    required
                                    minlength="8"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-blue">
                                <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters</p>
                            </div>
                            <div>
                                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">
                                    Confirm New Password
                                </label>
                                <input type="password"
                                    id="confirm_password"
                                    name="confirm_password"
                                    required
                                    minlength="8"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-blue">
                            </div>
                            <div class="pt-4">
                                <button type="submit"
                                    name="change_password"
                                    class="w-full bg-primary-blue text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                    <i class="fas fa-key mr-2"></i>Change Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Information Card -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-primary-blue mr-2"></i>
                    Account Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Account ID:</span>
                        <span class="text-gray-600 ml-2"><?php echo $_SESSION['admin_user_id']; ?></span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Username:</span>
                        <span class="text-gray-600 ml-2"><?php echo htmlspecialchars($adminData['username']); ?></span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Email:</span>
                        <span class="text-gray-600 ml-2"><?php echo htmlspecialchars($adminData['email'] ?? 'Not set'); ?></span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Role:</span>
                        <span class="text-gray-600 ml-2">Administrator</span>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Password confirmation validation
        document.querySelector('form[name="change_password"]')?.addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('New password and confirmation do not match.');
            }
        });
    </script>
</body>

</html>