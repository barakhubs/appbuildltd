<?php
require_once 'includes/header.php';

// Get blog post slug from URL
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>Blog Post Not Found</h1><p>The requested blog post does not exist.</p>';
    exit;
}

// Get blog post
$blogPost = getBlogPostBySlug($slug);

if (!$blogPost) {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>Blog Post Not Found</h1><p>The requested blog post does not exist.</p>';
    exit;
}

$pageTitle = $blogPost['title'];
$metaDescription = $blogPost['excerpt'] ? truncateText(strip_tags($blogPost['excerpt']), 160) : truncateText(strip_tags($blogPost['content']), 160);

// Get related posts (same category)
$relatedPosts = getBlogPosts(3, $blogPost['category']);
$relatedPosts = array_filter($relatedPosts, function ($post) use ($blogPost) {
    return $post['id'] !== $blogPost['id'];
});
$relatedPosts = array_slice($relatedPosts, 0, 3);
?>

<!-- Hero Section -->
<section class="bg-primary-blue text-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Breadcrumbs -->
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-blue-200">
                    <li><a href="<?php echo SITE_URL; ?>" class="hover:text-white transition-colors">Home</a></li>
                    <li><i class="fas fa-chevron-right text-sm"></i></li>
                    <li><a href="<?php echo SITE_URL; ?>/blog" class="hover:text-white transition-colors">Blog</a></li>
                    <li><i class="fas fa-chevron-right text-sm"></i></li>
                    <li class="text-blue-300">
                        <?php echo truncateText($blogPost['title'], 50); ?>
                    </li>
                </ol>
            </nav>

            <!-- Post Meta -->
            <div class="flex flex-wrap items-center space-x-4 mb-6 text-blue-200">
                <span class="bg-secondary-red text-white px-3 py-1 rounded-full text-sm">
                    <?php echo htmlspecialchars($blogPost['category']); ?>
                </span>
                <time class="flex items-center">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    <?php echo formatDate($blogPost['created_at']); ?>
                </time>
                <span class="flex items-center">
                    <i class="fas fa-user mr-2"></i>
                    <?php echo htmlspecialchars($blogPost['author']); ?>
                </span>
                <span class="flex items-center">
                    <i class="fas fa-clock mr-2"></i>
                    <?php echo ceil(str_word_count(strip_tags($blogPost['content'])) / 200); ?> min read
                </span>
            </div>

            <!-- Title -->
            <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                <?php echo htmlspecialchars($blogPost['title']); ?>
            </h1>

            <!-- Excerpt -->
            <?php if ($blogPost['excerpt']): ?>
                <p class="text-xl text-blue-100 mt-6 leading-relaxed">
                    <?php echo htmlspecialchars($blogPost['excerpt']); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Article Content -->
<article class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <!-- Featured Image -->
                    <?php if ($blogPost['featured_image']): ?>
                        <div class="mb-8">
                            <img src="<?php echo getImageUrl('blog/' . $blogPost['featured_image']); ?>"
                                alt="<?php echo htmlspecialchars($blogPost['title']); ?>"
                                class="w-full h-64 md:h-80 object-cover rounded-lg shadow-lg">
                        </div>
                    <?php endif; ?>

                    <!-- Article Content -->
                    <div class="blog-content prose prose-lg max-w-none">
                        <?php echo $blogPost['content']; ?>
                    </div>

                    <!-- Tags/Categories -->
                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <div class="flex flex-wrap items-center gap-4">
                            <span class="text-gray-600 font-semibold">Filed under:</span>
                            <span class="bg-primary-blue text-white px-3 py-1 rounded-full text-sm">
                                <?php echo htmlspecialchars($blogPost['category']); ?>
                            </span>
                        </div>
                    </div>

                    <!-- Share Buttons -->
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Share this article</h3>
                        <div class="flex space-x-4">
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(SITE_URL . '/blog/' . $blogPost['slug']); ?>&text=<?php echo urlencode($blogPost['title']); ?>"
                                target="_blank"
                                class="bg-blue-400 text-white p-3 rounded-full hover:bg-blue-500 transition-colors">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(SITE_URL . '/blog/' . $blogPost['slug']); ?>"
                                target="_blank"
                                class="bg-blue-600 text-white p-3 rounded-full hover:bg-blue-700 transition-colors">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(SITE_URL . '/blog/' . $blogPost['slug']); ?>"
                                target="_blank"
                                class="bg-blue-800 text-white p-3 rounded-full hover:bg-blue-900 transition-colors">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <button onclick="navigator.clipboard.writeText('<?php echo SITE_URL . '/blog/' . $blogPost['slug']; ?>')"
                                class="bg-gray-600 text-white p-3 rounded-full hover:bg-gray-700 transition-colors">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Author Info -->
                    <div class="bg-gray-50 p-6 rounded-lg mb-8">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-primary-blue rounded-full flex items-center justify-center text-white text-xl font-bold">
                                <?php echo strtoupper(substr($blogPost['author'], 0, 1)); ?>
                            </div>
                            <div class="ml-3">
                                <h4 class="font-semibold text-gray-800"><?php echo htmlspecialchars($blogPost['author']); ?></h4>
                                <p class="text-gray-600 text-sm">Author</p>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm">
                            Expert in business solutions and industry best practices.
                        </p>
                    </div>

                    <!-- Table of Contents (if content has headings) -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Navigation</h3>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <a href="<?php echo SITE_URL; ?>/blog"
                                    class="text-gray-600 hover:text-primary-blue transition-colors flex items-center">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Back to Blog
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo SITE_URL; ?>/blog?category=<?php echo urlencode($blogPost['category']); ?>"
                                    class="text-gray-600 hover:text-primary-blue transition-colors flex items-center">
                                    <i class="fas fa-tags mr-2"></i>
                                    More in <?php echo htmlspecialchars($blogPost['category']); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo SITE_URL; ?>/contact"
                                    class="text-gray-600 hover:text-primary-blue transition-colors flex items-center">
                                    <i class="fas fa-envelope mr-2"></i>
                                    Contact Us
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact CTA -->
                    <div class="bg-primary-blue text-white p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-3">Need Help?</h3>
                        <p class="text-blue-100 mb-4 text-sm">
                            Have questions about this topic? Our experts are here to help.
                        </p>
                        <a href="<?php echo SITE_URL; ?>/contact"
                            class="block bg-secondary-red text-white text-center py-3 px-4 rounded-lg font-semibold hover:bg-red-600 transition-colors">
                            Get Expert Advice
                        </a>
                        <div class="mt-4 text-center">
                            <a href="tel:<?php echo str_replace(['(', ')', ' ', '-'], '', PHONE_NUMBER); ?>"
                                class="text-blue-200 hover:text-white transition-colors text-sm">
                                <i class="fas fa-phone mr-1"></i>
                                <?php echo PHONE_NUMBER; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>

<!-- Related Posts -->
<?php if (!empty($relatedPosts)): ?>
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center">Related Articles</h2>

                <div class="grid grid-cols-1 md:grid-cols-<?php echo min(count($relatedPosts), 3); ?> gap-8">
                    <?php foreach ($relatedPosts as $post): ?>
                        <article class="bg-white rounded-lg shadow-lg overflow-hidden card-hover">
                            <div class="h-48 bg-gradient-to-br from-primary-blue to-blue-600 flex items-center justify-center">
                                <?php if ($post['featured_image']): ?>
                                    <img src="<?php echo getImageUrl('blog/' . $post['featured_image']); ?>"
                                        alt="<?php echo htmlspecialchars($post['title']); ?>"
                                        class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i class="fas fa-newspaper text-white text-3xl"></i>
                                <?php endif; ?>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="bg-primary-blue text-white text-xs px-2 py-1 rounded-full">
                                        <?php echo htmlspecialchars($post['category']); ?>
                                    </span>
                                    <time class="text-gray-500 text-sm">
                                        <?php echo formatDate($post['created_at']); ?>
                                    </time>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800 mb-3 hover:text-primary-blue transition-colors">
                                    <a href="<?php echo SITE_URL; ?>/blog/<?php echo $post['slug']; ?>">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </a>
                                </h3>
                                <p class="text-gray-600 mb-4 text-sm">
                                    <?php echo $post['excerpt'] ? truncateText(strip_tags($post['excerpt']), 100) : truncateText(strip_tags($post['content']), 100); ?>
                                </p>
                                <a href="<?php echo SITE_URL; ?>/blog/<?php echo $post['slug']; ?>"
                                    class="text-primary-blue font-semibold hover:text-blue-700 transition-colors text-sm">
                                    Read More <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Call to Action -->
<section class="py-16 bg-primary-blue text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Get Started?</h2>
        <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
            Apply these insights to your business and see the difference expert solutions can make.
        </p>

        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="tel:<?php echo str_replace(['(', ')', ' ', '-'], '', PHONE_NUMBER); ?>"
                class="bg-secondary-red text-white px-8 py-4 rounded-lg text-lg font-bold hover:bg-red-600 transition-all transform hover:scale-105 shadow-lg">
                <i class="fas fa-phone mr-2"></i>Call Us Today
            </a>
            <a href="<?php echo SITE_URL; ?>/contact"
                class="bg-white text-primary-blue px-8 py-4 rounded-lg text-lg font-bold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                Get Free Consultation
            </a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>