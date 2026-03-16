<?php
http_response_code(500);
// Avoid loading config/functions in case the error is DB-related
$siteName = 'Appbuild Tech Company Ltd.';
$siteUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error | <?php echo htmlspecialchars($siteName); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-blue': '#265E9A',
                        'secondary-red': '#F54927'
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 text-center max-w-md">
        <div class="text-9xl font-bold text-secondary-red mb-6">500</div>
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Internal Server Error</h1>
        <p class="text-gray-600 mb-8">
            Something went wrong on our end. We're working to fix it. Please try again shortly.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo htmlspecialchars($siteUrl); ?>"
                class="bg-primary-blue text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                <i class="fas fa-home mr-2"></i>Go Home
            </a>
            <a href="<?php echo htmlspecialchars($siteUrl); ?>/contact"
                class="border border-primary-blue text-primary-blue px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                <i class="fas fa-envelope mr-2"></i>Contact Us
            </a>
        </div>
    </div>
</body>

</html>