<?php
$pageTitle = 'Our Services - Comprehensive Business Solutions';
$metaDescription = 'Discover our comprehensive range of business solutions including data management, project management, cost management, design management, and application development services.';

require_once 'includes/header.php';

$serviceCategories = getServiceCategories();
?>

<!-- Hero Section -->
<section class="bg-primary-blue text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Our Services</h1>
        <p class="text-xl text-blue-100 max-w-3xl mx-auto">
            Comprehensive business solutions designed to optimize your operations,
            reduce costs, and drive sustainable growth.
        </p>
    </div>
</section>

<!-- Services Overview -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-6">Complete Business Solutions</h2>
            <p class="text-lg text-gray-600 max-w-4xl mx-auto">
                From data management to application development, we provide end-to-end solutions
                that address every aspect of your business needs. Our integrated approach ensures
                seamless collaboration across all service areas.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($serviceCategories as $key => $name): ?>
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
                    'data-management' => 'Transform your data into strategic assets with our comprehensive data management solutions. From database design to business intelligence, we help you organize, secure, and leverage your information effectively.',
                    'project-management' => 'Ensure project success with our professional project management services. We deliver projects on time, within budget, and to specification using proven methodologies and expert oversight.',
                    'cost-management' => 'Optimize your financial performance with strategic cost management and control systems. Our solutions help you reduce expenses, improve ROI, and make data-driven financial decisions.',
                    'design-management' => 'Create consistent, compelling brand experiences with our design management services. From brand identity to user experience design, we ensure your visual communications drive results.',
                    'application-development' => 'Bring your vision to life with custom application development tailored to your specific needs. We build scalable, secure, and user-friendly applications that grow with your business.',
                    'document-automation' => 'Streamline your document workflows with automated control systems and digital transformation solutions. Reduce manual processes and improve compliance with smart document management.'
                ];

                $serviceBenefits = [
                    'data-management' => ['Improved Data Security', 'Real-time Analytics', 'Better Decision Making', 'Compliance Management'],
                    'project-management' => ['On-time Delivery', 'Budget Control', 'Risk Mitigation', 'Quality Assurance'],
                    'cost-management' => ['Cost Reduction', 'ROI Optimization', 'Financial Transparency', 'Budget Planning'],
                    'design-management' => ['Brand Consistency', 'User Engagement', 'Professional Image', 'Market Differentiation'],
                    'application-development' => ['Custom Solutions', 'Scalable Architecture', 'Modern Technology', 'Ongoing Support'],
                    'document-automation' => ['Process Efficiency', 'Version Control', 'Automated Workflows', 'Compliance Tracking']
                ];
                ?>

                <div class="bg-gray-50 rounded-lg p-8 shadow-lg card-hover group border border-gray-200">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-primary-blue rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-secondary-red transition-colors">
                            <i class="<?php echo $serviceIcons[$key]; ?> text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3"><?php echo $name; ?></h3>
                    </div>

                    <p class="text-gray-600 mb-6 leading-relaxed">
                        <?php echo $serviceDescriptions[$key]; ?>
                    </p>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-3">Key Benefits:</h4>
                        <ul class="space-y-2">
                            <?php foreach ($serviceBenefits[$key] as $benefit): ?>
                                <li class="flex items-center text-gray-600">
                                    <i class="fas fa-check text-green-500 mr-3 flex-shrink-0"></i>
                                    <?php echo $benefit; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <a href="<?php echo SITE_URL; ?>/service-detail.php?service=<?php echo $key; ?>"
                            class="block w-full bg-primary-blue text-white text-center py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors group-hover:bg-secondary-red">
                            Learn More <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Why Choose Our Services -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl font-bold text-gray-800 mb-6">Why Choose Our Services?</h2>
            <p class="text-lg text-gray-600 mb-12">
                Our integrated approach and proven expertise ensure that you receive comprehensive
                solutions that work together seamlessly to drive your business forward.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-primary-blue rounded-full flex items-center justify-center">
                            <i class="fas fa-cogs text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 ml-4">Integrated Solutions</h3>
                    </div>
                    <p class="text-gray-600">
                        Our services work together as a unified ecosystem, ensuring that your data management,
                        project oversight, cost control, design, and applications all align perfectly.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-primary-blue rounded-full flex items-center justify-center">
                            <i class="fas fa-award text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 ml-4">Proven Expertise</h3>
                    </div>
                    <p class="text-gray-600">
                        Years of experience across multiple industries have given us deep insights into
                        what works and what doesn't, ensuring successful outcomes for every project.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-primary-blue rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 ml-4">Rapid Implementation</h3>
                    </div>
                    <p class="text-gray-600">
                        Our streamlined processes and experienced team enable us to implement solutions
                        quickly without compromising on quality or thoroughness.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-primary-blue rounded-full flex items-center justify-center">
                            <i class="fas fa-headset text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 ml-4">Ongoing Support</h3>
                    </div>
                    <p class="text-gray-600">
                        We don't just deliver and walk away. Our ongoing support ensures your solutions
                        continue to perform optimally and evolve with your business needs.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Service Process -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-6">Our Service Process</h2>
                <p class="text-lg text-gray-600">
                    We follow a proven methodology that ensures successful delivery and maximum value for your investment.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-primary-blue rounded-full flex items-center justify-center mx-auto">
                            <i class="fas fa-search text-white text-2xl"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-secondary-red rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-sm">1</span>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Discovery & Analysis</h3>
                    <p class="text-gray-600">
                        We conduct thorough analysis of your current situation, challenges,
                        and objectives to create a comprehensive understanding of your needs.
                    </p>
                </div>

                <div class="text-center">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-primary-blue rounded-full flex items-center justify-center mx-auto">
                            <i class="fas fa-drafting-compass text-white text-2xl"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-secondary-red rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-sm">2</span>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Strategy & Planning</h3>
                    <p class="text-gray-600">
                        We develop a detailed strategy and implementation plan that aligns with
                        your business goals and maximizes return on investment.
                    </p>
                </div>

                <div class="text-center">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-primary-blue rounded-full flex items-center justify-center mx-auto">
                            <i class="fas fa-hammer text-white text-2xl"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-secondary-red rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-sm">3</span>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Implementation</h3>
                    <p class="text-gray-600">
                        Our expert team executes the plan with precision, maintaining regular
                        communication and adhering to agreed timelines and quality standards.
                    </p>
                </div>

                <div class="text-center">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-primary-blue rounded-full flex items-center justify-center mx-auto">
                            <i class="fas fa-rocket text-white text-2xl"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-secondary-red rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-sm">4</span>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Launch & Support</h3>
                    <p class="text-gray-600">
                        We ensure smooth deployment and provide ongoing support to optimize
                        performance and address any evolving requirements.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-16 bg-primary-blue text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Get Started?</h2>
        <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
            Let's discuss your specific needs and how our comprehensive solutions can help
            transform your business operations and drive sustainable growth.
        </p>

        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="tel:<?php echo str_replace(['(', ')', ' ', '-'], '', PHONE_NUMBER); ?>"
                class="bg-secondary-red text-white px-8 py-4 rounded-lg text-lg font-bold hover:bg-red-600 transition-all transform hover:scale-105 shadow-lg">
                <i class="fas fa-phone mr-2"></i>Call Us Now
            </a>
            <a href="<?php echo SITE_URL; ?>/contact.php"
                class="bg-white text-primary-blue px-8 py-4 rounded-lg text-lg font-bold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                Request Free Consultation
            </a>
        </div>

        <div class="mt-8 text-blue-100">
            <p class="text-lg">
                <i class="fas fa-clock mr-2"></i>
                Free consultation available - No obligation
            </p>
            <p class="text-sm mt-2">Discuss your needs with our experts today</p>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>