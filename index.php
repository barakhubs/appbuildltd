<?php
$pageTitle = 'Expert Business Solutions & Development Services';
$metaDescription = 'AppBuild Ltd. provides comprehensive data management, project management, cost management, design management, and application development solutions. Contact us today!';

require_once 'includes/header.php';

// Get featured projects and recent blog posts
$featuredProjects = getProjects(4, null, true);
$recentBlogPosts = getBlogPosts(3);
$serviceCategories = getServiceCategories();
?>

<!-- Hero Section -->
<section class="relative bg-primary-blue text-white overflow-hidden">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('<?php echo SITE_URL; ?>/assets/images/hero.jpeg');"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-primary-blue/90 to-blue-900/80"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="min-h-screen flex items-center justify-center">
            <div class="max-w-4xl text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight animate-fade-in-down">
                    Innovate, Transform, Succeed
                </h1>
                <p class="text-lg md:text-xl mb-10 text-blue-100 leading-relaxed animate-fade-in-up">
                    Driving business transformation with expert data management, project oversight, and custom application development.
                </p>
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6 animate-fade-in-up">
                    <a href="<?php echo SITE_URL; ?>/services"
                        class="bg-secondary-red text-white px-8 py-4 rounded-full text-lg font-bold hover:bg-red-600 transition-all transform hover:scale-105 shadow-lg">
                        Explore Our Services
                    </a>
                    <a href="<?php echo SITE_URL; ?>/contact"
                        class="bg-white text-primary-blue px-8 py-4 rounded-full text-lg font-bold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                        Get a Free Consultation
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="wow-fade-in-left">
                <div class="relative">
                    <img src="<?php echo SITE_URL; ?>/assets/images/about-us.jpeg" alt="Our Team" class="rounded-lg shadow-2xl w-full">
                    <div class="absolute -bottom-4 -right-4 bg-primary-blue text-white p-6 rounded-lg shadow-lg max-w-xs">
                        <p class="text-lg font-semibold">"The only way to do great work is to love what you do."</p>
                        <p class="text-sm text-blue-200 mt-2">- Steve Jobs</p>
                    </div>
                </div>
            </div>
            <div class="wow-fade-in-right">
                <h2 class="text-4xl font-bold text-gray-800 mb-6">Your Partner in Digital Transformation</h2>
                <p class="text-lg text-gray-600 leading-relaxed mb-6">
                    At AppBuild Ltd., we are more than just a service provider; we are your strategic partner. We specialize in turning complex business challenges into streamlined, technology-driven solutions. Our team of experts is dedicated to understanding your unique needs and delivering measurable results.
                </p>
                <ul class="space-y-4 text-gray-700 mb-8">
                    <li class="flex items-start"><i class="fas fa-check-circle text-primary-blue text-xl mr-3 mt-1"></i><span><strong>Client-Centric Approach:</strong> We prioritize your goals and work collaboratively to achieve them.</span></li>
                    <li class="flex items-start"><i class="fas fa-check-circle text-primary-blue text-xl mr-3 mt-1"></i><span><strong>Innovation at Core:</strong> We leverage the latest technologies to provide cutting-edge solutions.</span></li>
                    <li class="flex items-start"><i class="fas fa-check-circle text-primary-blue text-xl mr-3 mt-1"></i><span><strong>Proven Expertise:</strong> Our track record of successful projects speaks for itself.</span></li>
                </ul>
                <a href="<?php echo SITE_URL; ?>/about" class="bg-primary-blue text-white px-8 py-3 rounded-full font-semibold hover:bg-blue-700 transition-colors inline-flex items-center">
                    Learn More About Us <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Our Core Services</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                We provide a comprehensive suite of services to meet your business needs and drive growth.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $serviceIcons = [
                'data-management' => 'fas fa-database',
                'project-management' => 'fas fa-tasks',
                'cost-management' => 'fas fa-chart-pie',
                'design-management' => 'fas fa-palette',
                'application-development' => 'fas fa-code',
                'document-automation' => 'fas fa-file-alt'
            ];
            $serviceDescriptions = [
                'data-management' => 'Organize, secure, and leverage your business data.',
                'project-management' => 'On-time and on-budget project delivery, guaranteed.',
                'cost-management' => 'Optimize budgets and improve ROI with strategic cost control.',
                'design-management' => 'Consistent brand identity and exceptional user experience.',
                'application-development' => 'Custom applications tailored to your specific business needs.',
                'document-automation' => 'Streamline workflows with automated document control.'
            ];
            foreach ($serviceCategories as $key => $name):
            ?>
                <div class="bg-white rounded-lg shadow-lg p-8 text-center transform hover:-translate-y-2 transition-transform duration-300 wow-fade-in-up">
                    <div class="text-primary-blue text-5xl mb-6">
                        <i class="<?php echo $serviceIcons[$key] ?? 'fas fa-cogs'; ?>"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3"><?php echo $name; ?></h3>
                    <p class="text-gray-600 mb-6 h-16"><?php echo $serviceDescriptions[$key] ?? 'Professional solutions tailored to your business needs.'; ?></p>
                    <a href="<?php echo SITE_URL; ?>/services/<?php echo $key; ?>" class="font-semibold text-primary-blue hover:text-secondary-red transition-colors">
                        Learn More <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Why Choose AppBuild Ltd.?</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Our commitment to excellence and innovation sets us apart.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center p-6 wow-fade-in-up" data-wow-delay="0.1s">
                <div class="bg-primary-blue text-white w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Expert Team</h3>
                <p class="text-gray-600">Our certified professionals bring years of industry experience.</p>
            </div>
            <div class="text-center p-6 wow-fade-in-up" data-wow-delay="0.2s">
                <div class="bg-primary-blue text-white w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Result-Oriented</h3>
                <p class="text-gray-600">We focus on delivering measurable results and tangible ROI.</p>
            </div>
            <div class="text-center p-6 wow-fade-in-up" data-wow-delay="0.3s">
                <div class="bg-primary-blue text-white w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">24/7 Support</h3>
                <p class="text-gray-600">Dedicated support to ensure your systems run smoothly.</p>
            </div>
            <div class="text-center p-6 wow-fade-in-up" data-wow-delay="0.4s">
                <div class="bg-primary-blue text-white w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Data Security</h3>
                <p class="text-gray-600">We implement robust security measures to protect your data.</p>
            </div>
        </div>
    </div>
</section>

<!-- Latest Projects -->
<?php if (!empty($featuredProjects)): ?>
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Our Latest Projects</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    A glimpse into our portfolio of successful project implementations.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php foreach ($featuredProjects as $project): ?>
                    <div class="group wow-fade-in-up">
                        <div class="relative overflow-hidden rounded-lg shadow-lg">
                            <img src="<?php echo getUploadedImageUrl('' . ($project['thumbnail'] ?? 'placeholder.jpg')); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" class="w-full h-64 object-cover transform group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-end p-6">
                                <div>
                                    <span class="bg-secondary-red text-white text-xs px-3 py-1 rounded-full mb-2 inline-block"><?php echo htmlspecialchars($project['service_category']); ?></span>
                                    <h3 class="text-xl font-bold text-white"><?php echo htmlspecialchars($project['title']); ?></h3>
                                </div>
                            </div>
                            <div class="absolute inset-0 bg-primary-blue bg-opacity-90 p-6 flex flex-col justify-center items-center text-center opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                <h3 class="text-xl font-bold text-white mb-2"><?php echo htmlspecialchars($project['title']); ?></h3>
                                <p class="text-blue-200 mb-4 text-sm"><?php echo truncateText(strip_tags($project['description']), 80); ?></p>
                                <a href="<?php echo SITE_URL; ?>/projects/<?php echo $project['slug']; ?>" class="bg-white text-primary-blue px-4 py-2 rounded-full font-semibold text-sm hover:bg-gray-200 transition-colors">View Project</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-16">
                <a href="<?php echo SITE_URL; ?>/projects" class="bg-primary-blue text-white px-8 py-3 rounded-full font-semibold hover:bg-blue-700 transition-colors inline-flex items-center">
                    View All Projects <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Latest Blog Posts -->
<?php if (!empty($recentBlogPosts)): ?>
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">From Our Blog</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Stay updated with the latest industry trends, insights, and company news.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($recentBlogPosts as $post): ?>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:-translate-y-2 transition-transform duration-300 wow-fade-in-up">
                        <a href="<?php echo SITE_URL; ?>/blog/<?php echo $post['slug']; ?>">
                            <img src="<?php echo getUploadedImageUrl('' . ($post['featured_image'] ?? 'placeholder.jpg')); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="w-full h-56 object-cover">
                        </a>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3 text-sm text-gray-500">
                                <span><i class="fas fa-calendar-alt mr-1"></i> <?php echo formatDate($post['created_at']); ?></span>
                                <span class="bg-primary-blue text-white text-xs px-2 py-1 rounded-full"><?php echo htmlspecialchars($post['category']); ?></span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3 h-20">
                                <a href="<?php echo SITE_URL; ?>/blog/<?php echo $post['slug']; ?>" class="hover:text-primary-blue transition-colors">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </a>
                            </h3>
                            <p class="text-gray-600 mb-4 h-24"><?php echo truncateText(strip_tags($post['content']), 100); ?></p>
                            <a href="<?php echo SITE_URL; ?>/blog/<?php echo $post['slug']; ?>" class="font-semibold text-primary-blue hover:text-secondary-red transition-colors">
                                Read More <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- CTA Section -->
<section class="py-20 bg-primary-blue text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-4 wow-fade-in-down">Ready to Transform Your Business?</h2>
        <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto wow-fade-in-up">
            Let's discuss how our expert solutions can help you achieve your business goals.
        </p>
        <div class="wow-fade-in-up">
            <a href="<?php echo SITE_URL; ?>/contact" class="bg-secondary-red text-white px-6 sm:px-10 py-3 sm:py-4 rounded-full text-base sm:text-lg font-bold hover:bg-red-600 transition-all transform hover:scale-105 shadow-lg inline-block">
                <span class="hidden sm:inline">Get Your Free Consultation Today</span>
                <span class="sm:hidden">Get Free Consultation</span>
            </a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">What Our Clients Say</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Don't just take our word for it - hear from some of our satisfied clients.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl shadow-xl p-8 transform hover:-translate-y-2 transition-all duration-300 wow-fade-in-up relative">
                <div class="absolute -top-4 -left-4 bg-primary-blue text-white w-12 h-12 rounded-full flex items-center justify-center text-2xl">
                    <i class="fas fa-quote-left"></i>
                </div>
                <div class="flex text-yellow-400 text-lg mb-4">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="text-gray-700 mb-6 text-lg leading-relaxed italic">
                    "AppBuild Ltd. transformed our data management processes completely. The efficiency gains
                    have been remarkable, and their team's expertise is unmatched."
                </p>
                <div class="flex items-center pt-4 border-t border-gray-200">
                    <div class="w-14 h-14 bg-gradient-to-br from-primary-blue to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                        JS
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-gray-800 text-lg">John Smith</h4>
                        <p class="text-gray-500 text-sm">CTO, Tech Solutions Inc.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-xl p-8 transform hover:-translate-y-2 transition-all duration-300 wow-fade-in-up relative" data-wow-delay="0.1s">
                <div class="absolute -top-4 -left-4 bg-primary-blue text-white w-12 h-12 rounded-full flex items-center justify-center text-2xl">
                    <i class="fas fa-quote-left"></i>
                </div>
                <div class="flex text-yellow-400 text-lg mb-4">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="text-gray-700 mb-6 text-lg leading-relaxed italic">
                    "Exceptional project management and attention to detail. They delivered our custom
                    application on time and exceeded our expectations."
                </p>
                <div class="flex items-center pt-4 border-t border-gray-200">
                    <div class="w-14 h-14 bg-gradient-to-br from-primary-blue to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                        MJ
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-gray-800 text-lg">Maria Johnson</h4>
                        <p class="text-gray-500 text-sm">Operations Manager, Global Corp</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-xl p-8 transform hover:-translate-y-2 transition-all duration-300 wow-fade-in-up relative" data-wow-delay="0.2s">
                <div class="absolute -top-4 -left-4 bg-primary-blue text-white w-12 h-12 rounded-full flex items-center justify-center text-2xl">
                    <i class="fas fa-quote-left"></i>
                </div>
                <div class="flex text-yellow-400 text-lg mb-4">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="text-gray-700 mb-6 text-lg leading-relaxed italic">
                    "Their cost management solutions helped us reduce operational expenses by 30%
                    while improving overall efficiency. Outstanding results!"
                </p>
                <div class="flex items-center pt-4 border-t border-gray-200">
                    <div class="w-14 h-14 bg-gradient-to-br from-primary-blue to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                        RB
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-gray-800 text-lg">Robert Brown</h4>
                        <p class="text-gray-500 text-sm">Finance Director, Enterprise Ltd</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</section>

<!-- Recent Blog Posts -->
<?php if (!empty($recentBlogPosts)): ?>
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Latest Insights</h2>
                <p class="text-lg text-gray-600">
                    Stay updated with our latest thoughts on business solutions and industry trends.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($recentBlogPosts as $post): ?>
                    <article class="bg-gray-50 rounded-lg overflow-hidden shadow-md card-hover">
                        <div class="h-48 bg-gradient-to-br from-primary-blue to-blue-600 flex items-center justify-center">
                            <?php if ($post['featured_image']): ?>
                                <img src="<?php echo getUploadedImageUrl('uploads/blog/' . $post['featured_image']); ?>"
                                    alt="<?php echo htmlspecialchars($post['title']); ?>"
                                    class="w-full h-full object-cover">
                            <?php else: ?>
                                <i class="fas fa-newspaper text-white text-4xl"></i>
                            <?php endif; ?>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center mb-3">
                                <span class="bg-primary-blue text-white text-xs px-2 py-1 rounded-full">
                                    <?php echo htmlspecialchars($post['category']); ?>
                                </span>
                                <span class="text-gray-500 text-sm ml-3">
                                    <?php echo formatDate($post['created_at']); ?>
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3 hover:text-primary-blue transition-colors">
                                <a href="<?php echo SITE_URL; ?>/blog-post.php?slug=<?php echo $post['slug']; ?>">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </a>
                            </h3>
                            <p class="text-gray-600 mb-4">
                                <?php echo $post['excerpt'] ? truncateText(strip_tags($post['excerpt']), 120) : truncateText(strip_tags($post['content']), 120); ?>
                            </p>
                            <a href="<?php echo SITE_URL; ?>/blog-post.php?slug=<?php echo $post['slug']; ?>"
                                class="inline-flex items-center text-primary-blue font-semibold hover:text-blue-700 transition-colors">
                                Read More <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-12">
                <a href="<?php echo SITE_URL; ?>/blog.php"
                    class="bg-primary-blue text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors inline-flex items-center">
                    View All Posts <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Trust Indicators -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Why Choose AppBuild Ltd.?</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-4xl font-bold text-primary-blue mb-2">10+</div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Years Experience</h3>
                <p class="text-gray-600">Proven track record in delivering successful business solutions.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-4xl font-bold text-primary-blue mb-2">500+</div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Projects Completed</h3>
                <p class="text-gray-600">Successfully delivered projects across various industries.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-4xl font-bold text-primary-blue mb-2">100+</div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Happy Clients</h3>
                <p class="text-gray-600">Satisfied clients who trust our expertise and solutions.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-4xl font-bold text-primary-blue mb-2">24/7</div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Support Available</h3>
                <p class="text-gray-600">Round-the-clock support to ensure your success.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>