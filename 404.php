<?php
http_response_code(404);
$pageTitle = '404 - Page Not Found';
$metaDescription = 'The page you are looking for could not be found.';
require_once 'includes/header.php';
?>

<section class="bg-gray-50 min-h-screen flex items-center justify-center py-20">
    <div class="container mx-auto px-4 text-center">
        <div class="max-w-md mx-auto">
            <div class="text-9xl font-bold text-primary-blue mb-6">404</div>
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Page Not Found</h1>
            <p class="text-gray-600 mb-8">
                Sorry, the page you are looking for doesn't exist or has been moved.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo SITE_URL; ?>"
                    class="bg-primary-blue text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    <i class="fas fa-home mr-2"></i>Go Home
                </a>
                <a href="<?php echo SITE_URL; ?>/contact"
                    class="border border-primary-blue text-primary-blue px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                    <i class="fas fa-envelope mr-2"></i>Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>