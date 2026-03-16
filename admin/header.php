<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' . SITE_NAME : SITE_NAME . ' Admin'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f3f4f6;
        }

        .nav-link {
            @apply px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white transition-colors;
        }

        .nav-link.active {
            @apply bg-gray-900 text-white;
        }
    </style>
</head>

<body class="flex h-screen bg-gray-100">
    <div class="w-64 bg-gray-800 text-white flex flex-col">
        <div class="px-8 py-6">
            <a href="index.php" class="text-2xl font-bold"><?php echo SITE_NAME; ?> Admin</a>
        </div>
        <nav class="flex-1 px-4">
            <a href="index.php" class="nav-link <?php echo $current_page === 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt w-6 mr-2"></i> Dashboard
            </a>
            <a href="projects.php" class="nav-link <?php echo in_array($current_page, ['projects.php', 'project-edit.php']) ? 'active' : ''; ?>">
                <i class="fas fa-project-diagram w-6 mr-2"></i> Projects
            </a>
            <a href="services.php" class="nav-link <?php echo in_array($current_page, ['services.php', 'service-edit.php']) ? 'active' : ''; ?>">
                <i class="fas fa-concierge-bell w-6 mr-2"></i> Services
            </a>
            <a href="submissions.php" class="nav-link <?php echo in_array($current_page, ['submissions.php', 'submission-view.php']) ? 'active' : ''; ?>">
                <i class="fas fa-envelope-open-text w-6 mr-2"></i> Submissions
            </a>
        </nav>
        <div class="px-4 py-4">
            <a href="logout.php" class="nav-link">
                <i class="fas fa-sign-out-alt w-6 mr-2"></i> Logout
            </a>
        </div>
    </div>

    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">