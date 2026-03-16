<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Get service parameter
$serviceKey = $_GET['service'] ?? '';

// Validate service key
$validServices = array_keys(getServiceCategories());
if (!in_array($serviceKey, $validServices)) {
    http_response_code(404);
    $pageTitle = '404 - Service Not Found';
    $metaDescription = '';
    require_once 'includes/header.php';
    echo '<div class="container mx-auto px-4 py-20 text-center"><h1 class="text-3xl font-bold">Service Not Found</h1><p class="mt-4"><a href="/services" class="text-primary-blue">View All Services</a></p></div>';
    require_once 'includes/footer.php';
    exit;
}

// Get service data from database
$serviceData = getServicePage($serviceKey);
$serviceName = getServiceCategories()[$serviceKey];

if (!$serviceData) {
    // Fallback data if not found in database
    $serviceData = [
        'title' => $serviceName,
        'description' => 'Professional ' . strtolower($serviceName) . ' solutions.',
        'benefits' => 'Professional Service|Expert Team|Quality Results|Ongoing Support',
        'use_cases' => 'Various business applications and use cases.',
        'featured_image' => $serviceKey . '.jpg'
    ];
}

$pageTitle = $serviceData['title'] . ' - Professional Solutions';
$metaDescription = 'Expert ' . strtolower($serviceName) . ' services. ' . truncateText(strip_tags($serviceData['description']), 150);

require_once 'includes/header.php';

// Get related projects
$relatedProjects = getProjects(3, $serviceName);
?>

<!-- Hero Section -->
<section class="bg-primary-blue text-white py-16 relative">
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4"><?php echo htmlspecialchars($serviceData['title']); ?></h1>
            <div class="text-xl text-blue-100 leading-relaxed">
                <?php echo $serviceData['description']; ?>
            </div>
        </div>
    </div>
</section>

<!-- Service Details -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Service Image -->
                <div class="mb-8">
                    <div class="h-64 md:h-80 bg-gradient-to-br from-primary-blue to-blue-600 rounded-lg flex items-center justify-center">
                        <?php if ($serviceData['featured_image']): ?>
                            <img src="<?php echo getUploadedImageUrl('' . $serviceData['featured_image']); ?>"
                                alt="<?php echo htmlspecialchars($serviceData['title']); ?>"
                                class="w-full h-full object-cover rounded-lg">
                        <?php else: ?>
                            <?php
                            $serviceIcons = [
                                'data-management' => 'fas fa-database',
                                'project-management' => 'fas fa-tasks',
                                'cost-management' => 'fas fa-chart-pie',
                                'design-management' => 'fas fa-palette',
                                'application-development' => 'fas fa-code',
                                'document-automation' => 'fas fa-file-alt'
                            ];
                            ?>
                            <i class="<?php echo $serviceIcons[$serviceKey] ?? 'fas fa-cogs'; ?> text-white text-8xl"></i>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Detailed Description -->
                <div class="prose max-w-none mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Comprehensive <?php echo $serviceName; ?> Solutions</h2>

                    <?php
                    // Detailed service descriptions
                    $detailedDescriptions = [
                        'data-management' => '<p>In today\'s data-driven business environment, effective data management is crucial for success. Our comprehensive data management solutions help you organize, secure, and leverage your business data to make informed decisions and drive growth.</p><p>We provide end-to-end data management services including database design and optimization, data integration, business intelligence implementation, and data governance frameworks. Our team of experts ensures that your data infrastructure is scalable, secure, and aligned with your business objectives.</p><p>Whether you\'re dealing with legacy data migration, real-time analytics requirements, or compliance challenges, we have the expertise and tools to transform your data into a strategic asset that drives competitive advantage.</p>',

                        'project-management' => '<p>Successful project delivery requires a combination of proven methodologies, expert oversight, and clear communication. Our professional project management services ensure that your projects are completed on time, within budget, and to the highest quality standards.</p><p>We employ industry-standard project management frameworks including Agile, Waterfall, and hybrid approaches tailored to your specific project requirements. Our certified project managers bring years of experience across various industries and project types.</p><p>From initial planning and resource allocation to risk management and stakeholder communication, we handle every aspect of project management so you can focus on your core business activities while ensuring successful project outcomes.</p>',

                        'cost-management' => '<p>Effective cost management is essential for maintaining profitability and ensuring sustainable business growth. Our strategic cost management solutions help you optimize expenses, improve ROI, and make data-driven financial decisions.</p><p>We provide comprehensive cost analysis, budget planning, and financial control systems that give you complete visibility into your business expenses. Our solutions include cost tracking, variance analysis, and predictive modeling to help you anticipate and manage financial risks.</p><p>Our approach combines proven financial management practices with modern technology solutions to deliver cost management systems that are both effective and easy to use, helping you maintain financial control while supporting business growth.</p>',

                        'design-management' => '<p>Consistent, compelling design is crucial for building brand recognition and engaging your target audience. Our design management services help you create and maintain a cohesive visual identity across all touchpoints.</p><p>We provide comprehensive design management including brand identity development, user experience design, marketing materials creation, and design system implementation. Our team ensures that all design elements work together to create a powerful and memorable brand experience.</p><p>From initial concept development to final implementation and ongoing brand management, we help you create design solutions that not only look great but also drive business results and support your marketing objectives.</p>',

                        'application-development' => '<p>Custom application development allows you to create solutions that perfectly match your unique business requirements. Our application development services deliver scalable, secure, and user-friendly applications that grow with your business.</p><p>We specialize in developing web applications, mobile apps, desktop software, and API integrations using the latest technologies and development practices. Our development process emphasizes security, performance, and maintainability to ensure long-term success.</p><p>Whether you need a simple business application or a complex enterprise system, our experienced development team works closely with you to understand your requirements and deliver solutions that exceed your expectations while providing excellent user experiences.</p>',

                        'document-automation' => '<p>Manual document processes are time-consuming, error-prone, and often create compliance challenges. Our document control and automation solutions streamline your document workflows and improve operational efficiency.</p><p>We implement comprehensive document management systems that automate creation, approval, distribution, and archival processes. Our solutions include version control, automated workflows, digital signatures, and compliance tracking features.</p><p>Transform your document-heavy processes with intelligent automation that reduces manual effort, improves accuracy, and ensures compliance with industry regulations while providing complete audit trails and reporting capabilities.</p>'
                    ];

                    echo $detailedDescriptions[$serviceKey] ?? '<div>' . $serviceData['description'] . '</div>';
                    ?>
                </div>

                <!-- Key Benefits -->
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Key Benefits</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php
                        $benefits = explode('|', $serviceData['benefits']);
                        foreach ($benefits as $benefit):
                        ?>
                            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-check-circle text-green-500 text-xl mr-3 flex-shrink-0"></i>
                                <span class="text-gray-700 font-medium"><?php echo $benefit; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Use Cases -->
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Common Use Cases</h3>
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="text-gray-700 leading-relaxed prose max-w-none">
                            <?php echo $serviceData['use_cases']; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Contact Card -->
                <div class="bg-primary-blue text-white p-6 rounded-lg mb-8">
                    <h3 class="text-xl font-bold mb-4">Ready to Get Started?</h3>
                    <p class="text-blue-100 mb-6">
                        Contact our experts today to discuss your <?php echo strtolower($serviceName); ?> needs and learn how we can help.
                    </p>

                    <div class="space-y-4">
                        <a href="tel:<?php echo str_replace(['(', ')', ' ', '-'], '', PHONE_NUMBER); ?>"
                            class="block bg-secondary-red text-white text-center py-3 px-4 rounded-lg font-semibold hover:bg-red-600 transition-colors">
                            <i class="fas fa-phone mr-2"></i>Call Us Now
                        </a>
                        <a href="<?php echo SITE_URL; ?>/contact.php?service=<?php echo urlencode($serviceName); ?>"
                            class="block bg-white text-primary-blue text-center py-3 px-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            <i class="fas fa-envelope mr-2"></i>Send Message
                        </a>
                    </div>

                    <div class="mt-6 text-center text-blue-100">
                        <p class="font-semibold"><?php echo PHONE_NUMBER; ?></p>
                        <p class="text-sm"><?php echo BUSINESS_HOURS; ?></p>
                    </div>
                </div>

                <!-- Other Services -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Other Services</h3>
                    <ul class="space-y-2">
                        <?php foreach (getServiceCategories() as $key => $name): ?>
                            <?php if ($key !== $serviceKey): ?>
                                <li>
                                    <a href="<?php echo SITE_URL; ?>/service-detail.php?service=<?php echo $key; ?>"
                                        class="text-gray-600 hover:text-primary-blue transition-colors flex items-center">
                                        <i class="fas fa-arrow-right mr-2 text-xs"></i>
                                        <?php echo $name; ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Quick Facts -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Quick Facts</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-clock text-primary-blue mr-3"></i>
                            <span class="text-gray-700">Rapid Implementation</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-primary-blue mr-3"></i>
                            <span class="text-gray-700">Secure & Reliable</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-headset text-primary-blue mr-3"></i>
                            <span class="text-gray-700">24/7 Support Available</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-award text-primary-blue mr-3"></i>
                            <span class="text-gray-700">Industry Certified</span>
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
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Related Projects</h2>
                <p class="text-lg text-gray-600">
                    See how we've helped other clients with similar <?php echo strtolower($serviceName); ?> needs.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-<?php echo min(count($relatedProjects), 3); ?> gap-8">
                <?php foreach ($relatedProjects as $project): ?>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden card-hover">
                        <div class="h-48 bg-gradient-to-br from-primary-blue to-blue-600 flex items-center justify-center">
                            <?php if ($project['thumbnail']): ?>
                                <img src="<?php echo getUploadedImageUrl('uploads/projects/' . $project['thumbnail']); ?>"
                                    alt="<?php echo htmlspecialchars($project['title']); ?>"
                                    class="w-full h-full object-cover">
                            <?php else: ?>
                                <i class="fas fa-project-diagram text-white text-4xl"></i>
                            <?php endif; ?>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-3">
                                <?php echo htmlspecialchars($project['title']); ?>
                            </h3>
                            <p class="text-gray-600 mb-4">
                                <?php echo truncateText(strip_tags($project['description']), 120); ?>
                            </p>
                            <a href="<?php echo SITE_URL; ?>/project-detail.php?slug=<?php echo $project['slug']; ?>"
                                class="inline-flex items-center text-primary-blue font-semibold hover:text-blue-700 transition-colors">
                                View Project <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Call to Action -->
<section class="py-16 bg-primary-blue text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Transform Your <?php echo $serviceName; ?>?</h2>
        <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
            Let's discuss how our expert <?php echo strtolower($serviceName); ?> solutions can help
            optimize your operations and drive business growth.
        </p>

        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="tel:<?php echo str_replace(['(', ')', ' ', '-'], '', PHONE_NUMBER); ?>"
                class="bg-secondary-red text-white px-8 py-4 rounded-lg text-lg font-bold hover:bg-red-600 transition-all transform hover:scale-105 shadow-lg">
                <i class="fas fa-phone mr-2"></i>Call Us Today
            </a>
            <a href="<?php echo SITE_URL; ?>/contact.php?service=<?php echo urlencode($serviceName); ?>"
                class="bg-white text-primary-blue px-8 py-4 rounded-lg text-lg font-bold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                Get Free Quote
            </a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>