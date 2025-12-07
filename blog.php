<?php
$pageTitle = 'Blog - Latest Insights & Industry News';
$metaDescription = 'Stay updated with our latest insights on business solutions, industry trends, and best practices from AppBuild Ltd.';

require_once 'includes/header.php';

// Get filter parameters
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$page = (int)($_GET['page'] ?? 1);
$perPage = 9;

// Get blog posts (for now, get all - pagination can be added later)
$blogPosts = getBlogPosts(null, $category ? $category : null);
$blogCategories = getBlogCategories();

// Filter by search if provided
if ($search) {
    $blogPosts = array_filter($blogPosts, function ($post) use ($search) {
        return stripos($post['title'], $search) !== false ||
            stripos($post['content'], $search) !== false ||
            stripos($post['excerpt'], $search) !== false;
    });
}
?>

<!-- Hero Section -->
<section class="bg-primary-blue text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Our Blog</h1>
        <p class="text-xl text-blue-100 max-w-3xl mx-auto">
            Stay informed with our latest insights, industry trends, and expert advice on
            business solutions and technology developments.
        </p>
    </div>
</section>

<!-- Filters -->
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row justify-between items-center space-y-4 lg:space-y-0">
            <!-- Category Filter -->
            <div class="flex flex-wrap justify-center lg:justify-start gap-2">
                <a href="<?php echo SITE_URL; ?>/blog"
                    class="category-filter <?php echo empty($category) ? 'active bg-primary-blue text-white' : 'bg-white text-gray-700 hover:bg-primary-blue hover:text-white'; ?> px-4 py-2 rounded-full transition-colors text-sm"
                    data-category="all">
                    All Posts
                </a>
                <?php foreach ($blogCategories as $cat): ?>
                    <a href="<?php echo SITE_URL; ?>/blog?category=<?php echo urlencode($cat); ?>"
                        class="category-filter <?php echo $category === $cat ? 'active bg-primary-blue text-white' : 'bg-white text-gray-700 hover:bg-primary-blue hover:text-white'; ?> px-4 py-2 rounded-full transition-colors text-sm"
                        data-category="<?php echo htmlspecialchars($cat); ?>">
                        <?php echo htmlspecialchars($cat); ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Search -->
            <div class="w-full lg:w-auto">
                <form method="GET" action="" class="flex">
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                    <input type="text" name="search" id="search-input"
                        class="flex-1 lg:w-64 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-primary-blue"
                        placeholder="Search articles..."
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

<!-- Blog Posts Grid -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <?php if (empty($blogPosts)): ?>
            <div class="text-center py-16">
                <i class="fas fa-newspaper text-6xl text-gray-300 mb-6"></i>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">No Articles Found</h3>
                <p class="text-gray-600 mb-8">
                    <?php if ($search || $category): ?>
                        No articles match your current filters. Try adjusting your search criteria.
                    <?php else: ?>
                        We're working on creating valuable content for you. Check back soon!
                    <?php endif; ?>
                </p>
                <?php if ($search || $category): ?>
                    <a href="<?php echo SITE_URL; ?>/blog"
                        class="bg-primary-blue text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                        View All Articles
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($blogPosts as $post): ?>
                    <article class="filterable-item searchable-item bg-white rounded-lg shadow-lg overflow-hidden card-hover"
                        data-category="<?php echo htmlspecialchars($post['category']); ?>">
                        <div class="h-48 bg-gradient-to-br from-primary-blue to-blue-600 flex items-center justify-center">
                            <?php if ($post['featured_image']): ?>
                                <img src="<?php echo getImageUrl('blog/' . $post['featured_image']); ?>"
                                    alt="<?php echo htmlspecialchars($post['title']); ?>"
                                    class="w-full h-full object-cover">
                            <?php else: ?>
                                <i class="fas fa-newspaper text-white text-4xl"></i>
                            <?php endif; ?>
                        </div>

                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <span class="bg-primary-blue text-white text-xs px-3 py-1 rounded-full">
                                    <?php echo htmlspecialchars($post['category']); ?>
                                </span>
                                <time class="text-gray-500 text-sm">
                                    <?php echo formatDate($post['created_at']); ?>
                                </time>
                            </div>

                            <h2 class="item-title text-xl font-bold text-gray-800 mb-3 hover:text-primary-blue transition-colors">
                                <a href="<?php echo SITE_URL; ?>/blog/<?php echo $post['slug']; ?>">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </a>
                            </h2>

                            <p class="item-description text-gray-600 mb-4 leading-relaxed">
                                <?php echo $post['excerpt'] ? truncateText(strip_tags($post['excerpt']), 120) : truncateText(strip_tags($post['content']), 120); ?>
                            </p>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-primary-blue rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        <?php echo strtoupper(substr($post['author'], 0, 1)); ?>
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600">
                                        <?php echo htmlspecialchars($post['author']); ?>
                                    </span>
                                </div>
                                <a href="<?php echo SITE_URL; ?>/blog/<?php echo $post['slug']; ?>"
                                    class="text-primary-blue font-semibold hover:text-blue-700 transition-colors text-sm">
                                    Read More <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- No Results Message -->
            <div id="no-results" class="text-center py-16 hidden">
                <i class="fas fa-search text-6xl text-gray-300 mb-6"></i>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">No Articles Found</h3>
                <p class="text-gray-600">No articles match your search criteria. Try a different search term.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Featured Topics -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Popular Topics</h2>
            <p class="text-lg text-gray-600">
                Explore our most popular articles and insights on key business topics.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-md text-center card-hover">
                <div class="w-16 h-16 bg-primary-blue rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-database text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Data Management</h3>
                <p class="text-gray-600 mb-4">
                    Best practices for organizing, securing, and leveraging business data effectively.
                </p>
                <a href="<?php echo SITE_URL; ?>/blog?category=Data%20Management"
                    class="text-primary-blue font-semibold hover:text-blue-700 transition-colors">
                    Explore Articles <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md text-center card-hover">
                <div class="w-16 h-16 bg-primary-blue rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-tasks text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Project Management</h3>
                <p class="text-gray-600 mb-4">
                    Proven strategies and methodologies for successful project delivery.
                </p>
                <a href="<?php echo SITE_URL; ?>/blog?category=Project%20Management"
                    class="text-primary-blue font-semibold hover:text-blue-700 transition-colors">
                    Explore Articles <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md text-center card-hover">
                <div class="w-16 h-16 bg-primary-blue rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lightbulb text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Best Practices</h3>
                <p class="text-gray-600 mb-4">
                    Industry insights and best practices to optimize your business operations.
                </p>
                <a href="<?php echo SITE_URL; ?>/blog?category=Best%20Practices"
                    class="text-primary-blue font-semibold hover:text-blue-700 transition-colors">
                    Explore Articles <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Signup -->
<section class="py-16 bg-primary-blue text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Stay Updated</h2>
        <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
            Subscribe to our newsletter and get the latest insights, tips, and industry news
            delivered directly to your inbox.
        </p>

        <form class="flex flex-col sm:flex-row justify-center items-center max-w-lg mx-auto space-y-4 sm:space-y-0">
            <input type="email"
                class="flex-1 px-6 py-4 rounded-l-lg sm:rounded-r-none text-gray-900 focus:outline-none focus:ring-2 focus:ring-secondary-red"
                placeholder="Enter your email address" required>
            <button type="submit"
                class="bg-secondary-red text-white px-8 py-4 rounded-r-lg sm:rounded-l-none hover:bg-red-600 transition-colors font-semibold whitespace-nowrap">
                Subscribe Now
            </button>
        </form>

        <p class="text-blue-200 text-sm mt-4">
            No spam, unsubscribe at any time. Read our <a href="#" class="underline">Privacy Policy</a>.
        </p>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>