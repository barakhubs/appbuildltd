<?php
$pageTitle = 'Our Projects - Portfolio & Case Studies';
$metaDescription = 'Explore our portfolio of successful projects and case studies. See how Appbuild Tech Company ltd. has helped clients achieve their business goals.';

require_once 'includes/header.php';

// Get filter parameters
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

// Get projects
$projects = getProjects(null, $category ? $category : null);
$serviceCategories = getServiceCategories();
?>

<!-- Hero Section -->
<section class="bg-primary-blue text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Our Projects</h1>
        <p class="text-xl text-blue-100 max-w-3xl mx-auto">
            Discover how we've helped businesses transform their operations and achieve remarkable results
            through our comprehensive solutions and expert services.
        </p>
    </div>
</section>

<!-- Filters -->
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <!-- Category Filter -->
            <div class="flex flex-wrap justify-center md:justify-start space-x-2 space-y-2">
                <a href="<?php echo SITE_URL; ?>/projects"
                    class="category-filter <?php echo empty($category) ? 'active bg-primary-blue text-white' : 'bg-white text-gray-700 hover:bg-primary-blue hover:text-white'; ?> px-4 py-2 rounded-full transition-colors"
                    data-category="all">
                    All Projects
                </a>
                <?php foreach ($serviceCategories as $key => $name): ?>
                    <a href="<?php echo SITE_URL; ?>/projects?category=<?php echo urlencode($name); ?>"
                        class="category-filter <?php echo $category === $name ? 'active bg-primary-blue text-white' : 'bg-white text-gray-700 hover:bg-primary-blue hover:text-white'; ?> px-4 py-2 rounded-full transition-colors"
                        data-category="<?php echo htmlspecialchars($name); ?>">
                        <?php echo htmlspecialchars($name); ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Search -->
            <div class="w-full md:w-auto">
                <form method="GET" action="" class="flex">
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                    <input type="text" name="search"
                        class="flex-1 md:w-64 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-primary-blue"
                        placeholder="Search projects..."
                        value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit"
                        class="bg-primary-blue text-white px-6 py-2 rounded-r-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Projects Grid -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <?php if (empty($projects)): ?>
            <div class="text-center py-16">
                <i class="fas fa-project-diagram text-6xl text-gray-300 mb-6"></i>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">No Projects Found</h3>
                <p class="text-gray-600 mb-8">
                    <?php if ($search || $category): ?>
                        No projects match your current filters. Try adjusting your search criteria.
                    <?php else: ?>
                        We're working on showcasing our latest projects. Check back soon!
                    <?php endif; ?>
                </p>
                <?php if ($search || $category): ?>
                    <a href="<?php echo SITE_URL; ?>/projects"
                        class="bg-primary-blue text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                        View All Projects
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($projects as $project): ?>
                    <div class="filterable-item searchable-item bg-white rounded-lg shadow-lg overflow-hidden card-hover"
                        data-category="<?php echo htmlspecialchars($project['service_category']); ?>">
                        <div class="h-56 bg-gradient-to-br from-primary-blue to-blue-600 flex items-center justify-center">
                            <?php if ($project['thumbnail']): ?>
                                <img src="<?php echo getUploadedImageUrl('uploads/projects/' . $project['thumbnail']); ?>"
                                    alt="<?php echo htmlspecialchars($project['title']); ?>"
                                    class="w-full h-full object-cover">
                            <?php else: ?>
                                <i class="fas fa-project-diagram text-white text-4xl"></i>
                            <?php endif; ?>
                        </div>

                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <span class="bg-primary-blue text-white text-xs px-3 py-1 rounded-full">
                                    <?php echo htmlspecialchars($project['service_category']); ?>
                                </span>
                                <?php if ($project['featured']): ?>
                                    <span class="bg-secondary-red text-white text-xs px-3 py-1 rounded-full">
                                        <i class="fas fa-star mr-1"></i>Featured
                                    </span>
                                <?php endif; ?>
                            </div>

                            <h3 class="item-title text-xl font-bold text-gray-800 mb-3 hover:text-primary-blue transition-colors">
                                <a href="<?php echo SITE_URL; ?>/projects/<?php echo $project['slug']; ?>">
                                    <?php echo htmlspecialchars($project['title']); ?>
                                </a>
                            </h3>

                            <p class="item-description text-gray-600 mb-4 leading-relaxed">
                                <?php echo truncateText(strip_tags($project['description']), 120); ?>
                            </p>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">
                                    <i class="fas fa-building mr-1"></i>
                                    <?php echo htmlspecialchars($project['client_name']); ?>
                                </span>
                                <a href="<?php echo SITE_URL; ?>/projects/<?php echo $project['slug']; ?>"
                                    class="bg-primary-blue text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">
                                    View Details <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- No Results Message -->
            <div id="no-results" class="text-center py-16 hidden">
                <i class="fas fa-search text-6xl text-gray-300 mb-6"></i>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">No Projects Found</h3>
                <p class="text-gray-600">No projects match your search criteria. Try a different search term.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Call to Action -->
<section class="py-16 bg-primary-blue text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Start Your Project?</h2>
        <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
            Join our list of satisfied clients and let us help you achieve similar remarkable results
            with our expert solutions and dedicated support.
        </p>

        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="tel:<?php echo str_replace(['(', ')', ' ', '-'], '', PHONE_NUMBER); ?>"
                class="bg-secondary-red text-white px-8 py-4 rounded-lg text-lg font-bold hover:bg-red-600 transition-all transform hover:scale-105 shadow-lg">
                <i class="fas fa-phone mr-2"></i>Call Us Today
            </a>
            <a href="<?php echo SITE_URL; ?>/contact"
                class="bg-white text-primary-blue px-8 py-4 rounded-lg text-lg font-bold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                Discuss Your Project
            </a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>