<?php
require_once 'includes/header.php';

// Get project slug from URL
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>Project Not Found</h1><p>The requested project does not exist.</p>';
    exit;
}

// Get project details
$project = getProjectBySlug($slug);

if (!$project) {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>Project Not Found</h1><p>The requested project does not exist.</p>';
    exit;
}

$pageTitle = $project['title'];
$metaDescription = $project['description'] ? truncateText(strip_tags($project['description']), 160) : 'Discover how Appbuild Tech Company ltd. delivered exceptional results for this client project.';

// Get related projects (same category)
$relatedProjects = getProjects(3, $project['category'] ?? '');
$relatedProjects = array_filter($relatedProjects, function ($proj) use ($project) {
    return $proj['id'] !== $project['id'];
});
$relatedProjects = array_slice($relatedProjects, 0, 3);

// Parse project images if stored as JSON
$projectImages = [];
if ($project['images']) {
    $projectImages = json_decode($project['images'], true) ?? [];
}
?>

<!-- Hero Section -->
<section class="bg-primary-blue text-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Breadcrumbs -->
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-blue-200">
                    <li><a href="<?php echo SITE_URL; ?>" class="hover:text-white transition-colors">Home</a></li>
                    <li><i class="fas fa-chevron-right text-sm"></i></li>
                    <li><a href="<?php echo SITE_URL; ?>/projects" class="hover:text-white transition-colors">Projects</a></li>
                    <li><i class="fas fa-chevron-right text-sm"></i></li>
                    <li class="text-blue-300">
                        <?php echo truncateText($project['title'], 50); ?>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <!-- Project Meta -->
                    <div class="flex flex-wrap items-center space-x-4 mb-6 text-blue-200">
                        <?php if (!empty($project['category'])): ?>
                            <span class="bg-secondary-red text-white px-3 py-1 rounded-full text-sm">
                                <?php echo htmlspecialchars($project['category']); ?>
                            </span>
                        <?php endif; ?>
                        <span class="flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <?php echo formatDate($project['completed_at'] ?? $project['created_at']); ?>
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-user mr-2"></i>
                            <?php echo htmlspecialchars($project['client_name'] ?? 'Client Project'); ?>
                        </span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-6">
                        <?php echo htmlspecialchars($project['title']); ?>
                    </h1>

                    <!-- Short Description -->
                    <div class="text-xl text-gray-600 leading-relaxed">
                        <?php echo $project['description'] ? truncateText(strip_tags($project['description']), 200) : ''; ?>
                    </div>

                    <!-- Project Stats -->
                    <div class="grid grid-cols-2 gap-6 mt-8">
                        <?php if (!empty($project['duration'])): ?>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-secondary-red"><?php echo htmlspecialchars($project['duration']); ?></div>
                                <div class="text-blue-200 text-sm">Project Duration</div>
                            </div>
                        <?php endif; ?>

                        <div class="text-center">
                            <div class="text-3xl font-bold text-secondary-red">100%</div>
                            <div class="text-blue-200 text-sm">Client Satisfaction</div>
                        </div>
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="order-first lg:order-last">
                    <?php if (!empty($project['thumbnail'])): ?>
                        <div class="relative">
                            <img src="<?php echo getUploadedImageUrl('' . $project['thumbnail']); ?>"
                                alt="<?php echo htmlspecialchars($project['title']); ?>"
                                class="w-full h-80 lg:h-96 object-cover rounded-lg shadow-xl">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent rounded-lg"></div>
                        </div>
                    <?php else: ?>
                        <div class="bg-gradient-to-br from-blue-600 to-purple-600 h-80 lg:h-96 rounded-lg shadow-xl flex items-center justify-center">
                            <i class="fas fa-project-diagram text-white text-6xl"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Project Details -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Project Overview -->
                    <div class="mb-12">
                        <h2 class="text-3xl font-bold text-gray-800 mb-6">Project Overview</h2>
                        <div class="project-content prose prose-lg max-w-none">
                            <?php echo $project['description']; ?>
                        </div>
                    </div>

                    <!-- Project Gallery -->
                    <?php if (!empty($projectImages)): ?>
                        <div class="mb-12">
                            <h2 class="text-3xl font-bold text-gray-800 mb-6">Project Gallery</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <?php foreach ($projectImages as $image): ?>
                                    <div class="relative group">
                                        <img src="<?php echo getUploadedImageUrl('uploads/projects/' . $image); ?>"
                                            alt="Project Gallery Image"
                                            class="w-full h-64 object-cover rounded-lg shadow-lg group-hover:shadow-xl transition-shadow cursor-pointer">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-opacity rounded-lg"></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Technologies Used -->
                    <?php if (!empty($project['technologies'])): ?>
                        <div class="mb-12">
                            <h2 class="text-3xl font-bold text-gray-800 mb-6">Technologies Used</h2>
                            <div class="flex flex-wrap gap-3">
                                <?php
                                $technologies = explode(',', $project['technologies']);
                                foreach ($technologies as $tech):
                                ?>
                                    <span class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                                        <?php echo htmlspecialchars(trim($tech)); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Challenges & Solutions -->
                    <?php if (!empty($project['challenges']) || !empty($project['solution'])): ?>
                        <div class="mb-12">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <?php if (!empty($project['challenges'])): ?>
                                    <div class="bg-red-50 p-6 rounded-lg border border-red-100">
                                        <h3 class="text-xl font-bold text-red-800 mb-4 flex items-center">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            Challenges
                                        </h3>
                                        <div class="text-red-700">
                                            <?php echo $project['challenges']; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($project['solution']): ?>
                                    <div class="bg-green-50 p-6 rounded-lg border border-green-100">
                                        <h3 class="text-xl font-bold text-green-800 mb-4 flex items-center">
                                            <i class="fas fa-lightbulb mr-2"></i>
                                            Our Solution
                                        </h3>
                                        <div class="text-green-700">
                                            <?php echo $project['solution']; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Results -->
                    <?php if ($project['results']): ?>
                        <div class="mb-12">
                            <h2 class="text-3xl font-bold text-gray-800 mb-6">Results Achieved</h2>
                            <div class="bg-blue-50 p-6 rounded-lg border border-blue-100">
                                <div class="text-blue-800">
                                    <?php echo $project['results']; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Project Info -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Project Information</h3>
                        <ul class="space-y-3 text-sm">
                            <?php if (!empty($project['category'])): ?>
                                <li class="flex justify-between">
                                    <span class="text-gray-600">Category:</span>
                                    <span class="font-medium text-gray-800"><?php echo htmlspecialchars($project['category']); ?></span>
                                </li>
                            <?php endif; ?>
                            <?php if ($project['client_name']): ?>
                                <li class="flex justify-between">
                                    <span class="text-gray-600">Client:</span>
                                    <span class="font-medium text-gray-800"><?php echo htmlspecialchars($project['client_name']); ?></span>
                                </li>
                            <?php endif; ?>
                            <?php if (!empty($project['duration'])): ?>
                                <li class="flex justify-between">
                                    <span class="text-gray-600">Duration:</span>
                                    <span class="font-medium text-gray-800"><?php echo htmlspecialchars($project['duration']); ?></span>
                                </li>
                            <?php endif; ?>
                            <li class="flex justify-between">
                                <span class="text-gray-600">Completed:</span>
                                <span class="font-medium text-gray-800">
                                    <?php echo formatDate($project['completed_at'] ?? $project['created_at']); ?>
                                </span>
                            </li>
                        </ul>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <a href="<?php echo SITE_URL; ?>/projects"
                                    class="text-gray-600 hover:text-primary-blue transition-colors flex items-center">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Back to Projects
                                </a>
                            </li>
                            <?php if (!empty($project['category'])): ?>
                                <li>
                                    <a href="<?php echo SITE_URL; ?>/projects?category=<?php echo urlencode($project['category']); ?>"
                                        class="text-gray-600 hover:text-primary-blue transition-colors flex items-center">
                                        <i class="fas fa-tags mr-2"></i>
                                        Similar Projects
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li>
                                <a href="<?php echo SITE_URL; ?>/contact"
                                    class="text-gray-600 hover:text-primary-blue transition-colors flex items-center">
                                    <i class="fas fa-envelope mr-2"></i>
                                    Discuss Your Project
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact CTA -->
                    <div class="bg-primary-blue text-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-xl font-bold mb-3">Have a Similar Project?</h3>
                        <p class="text-blue-100 mb-4 text-sm">
                            Let us help you achieve the same level of success with your project.
                        </p>
                        <a href="<?php echo SITE_URL; ?>/contact"
                            class="block bg-secondary-red text-white text-center py-3 px-4 rounded-lg font-semibold hover:bg-red-600 transition-colors mb-3">
                            Get Free Quote
                        </a>
                        <div class="text-center">
                            <a href="tel:<?php echo str_replace(['(', ')', ' ', '-'], '', PHONE_NUMBER); ?>"
                                class="text-blue-200 hover:text-white transition-colors text-sm">
                                <i class="fas fa-phone mr-1"></i>
                                <?php echo PHONE_NUMBER; ?>
                            </a>
                        </div>
                    </div>

                    <!-- Share Project -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Share This Project</h3>
                        <div class="flex space-x-3">
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(SITE_URL . '/projects/' . $project['slug']); ?>&text=<?php echo urlencode('Check out this amazing project: ' . $project['title']); ?>"
                                target="_blank"
                                class="bg-blue-400 text-white p-2 rounded hover:bg-blue-500 transition-colors">
                                <i class="fab fa-twitter text-sm"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(SITE_URL . '/projects/' . $project['slug']); ?>"
                                target="_blank"
                                class="bg-blue-600 text-white p-2 rounded hover:bg-blue-700 transition-colors">
                                <i class="fab fa-facebook-f text-sm"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(SITE_URL . '/projects/' . $project['slug']); ?>"
                                target="_blank"
                                class="bg-blue-800 text-white p-2 rounded hover:bg-blue-900 transition-colors">
                                <i class="fab fa-linkedin-in text-sm"></i>
                            </a>
                            <button onclick="navigator.clipboard.writeText('<?php echo SITE_URL . '/projects/' . $project['slug']; ?>')"
                                class="bg-gray-600 text-white p-2 rounded hover:bg-gray-700 transition-colors">
                                <i class="fas fa-link text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Projects -->
<?php if (!empty($relatedProjects)): ?>
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center">Related Projects</h2>

                <div class="grid grid-cols-1 md:grid-cols-<?php echo min(count($relatedProjects), 3); ?> gap-8">
                    <?php foreach ($relatedProjects as $relatedProject): ?>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden card-hover">
                            <div class="h-48 bg-gradient-to-br from-primary-blue to-blue-600 flex items-center justify-center">
                                <?php if (!empty($relatedProject['thumbnail'])): ?>
                                    <img src="<?php echo getUploadedImageUrl('' . $relatedProject['thumbnail']); ?>"
                                        alt="<?php echo htmlspecialchars($relatedProject['title']); ?>"
                                        class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i class="fas fa-project-diagram text-white text-3xl"></i>
                                <?php endif; ?>
                            </div>
                            <div class="p-6">
                                <?php if (!empty($relatedProject['category'])): ?>
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="bg-primary-blue text-white text-xs px-2 py-1 rounded-full">
                                            <?php echo htmlspecialchars($relatedProject['category']); ?>
                                        </span>
                                        <span class="text-gray-500 text-sm">
                                            <?php echo formatDate($relatedProject['completed_at'] ?? $relatedProject['created_at']); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <h3 class="text-lg font-bold text-gray-800 mb-3 hover:text-primary-blue transition-colors">
                                    <a href="<?php echo SITE_URL; ?>/projects/<?php echo $relatedProject['slug']; ?>">
                                        <?php echo htmlspecialchars($relatedProject['title']); ?>
                                    </a>
                                </h3>
                                <p class="text-gray-600 mb-4 text-sm">
                                    <?php echo !empty($relatedProject['short_description']) ? truncateText(strip_tags($relatedProject['short_description']), 100) : truncateText(strip_tags($relatedProject['description'] ?? ''), 100); ?>
                                </p>
                                <a href="<?php echo SITE_URL; ?>/projects/<?php echo $relatedProject['slug']; ?>"
                                    class="text-primary-blue font-semibold hover:text-blue-700 transition-colors text-sm">
                                    View Project <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Call to Action -->
<section class="py-16 bg-primary-blue text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready for Your Next Project?</h2>
        <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
            Let's discuss how we can bring your vision to life with the same level of expertise and dedication.
        </p>

        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="tel:<?php echo str_replace(['(', ')', ' ', '-'], '', PHONE_NUMBER); ?>"
                class="bg-secondary-red text-white px-8 py-4 rounded-lg text-lg font-bold hover:bg-red-600 transition-all transform hover:scale-105 shadow-lg">
                <i class="fas fa-phone mr-2"></i>Call Us Now
            </a>
            <a href="<?php echo SITE_URL; ?>/contact"
                class="bg-white text-primary-blue px-8 py-4 rounded-lg text-lg font-bold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                Start Your Project
            </a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>