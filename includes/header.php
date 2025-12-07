<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$pageTitle = isset($pageTitle) ? $pageTitle : '';
$metaDescription = isset($metaDescription) ? $metaDescription : '';
$currentPage = getCurrentPage();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getPageTitle($pageTitle); ?></title>
    <meta name="description" content="<?php echo getMetaDescription($metaDescription); ?>">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/styles.css">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo SITE_URL; ?>/assets/images/favicon.ico">

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
</head>

<body class="font-sans bg-gray-50">
    <!-- Top Bar -->
    <div class="bg-primary-blue text-white">
        <div class="container mx-auto px-4 py-2 flex justify-between items-center text-sm">
            <div class="flex items-center space-x-4">
                <a href="mailto:<?php echo CONTACT_EMAIL; ?>" class="hover:text-secondary-red transition-colors">
                    <i class="fas fa-envelope mr-1"></i> <?php echo CONTACT_EMAIL; ?>
                </a>
                <span class="hidden md:block">|</span>
                <span class="hidden md:block">
                    <i class="fas fa-map-marker-alt mr-1"></i> <?php echo ADDRESS; ?>
                </span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="#" class="hover:text-secondary-red transition-colors"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="hover:text-secondary-red transition-colors"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:text-secondary-red transition-colors"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" class="hover:text-secondary-red transition-colors"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>

    <!-- Sticky Header -->
    <header id="main-header" class="bg-white shadow-md sticky top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="<?php echo SITE_URL; ?>" class="text-2xl font-bold text-primary-blue">
                        <?php echo SITE_NAME; ?>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center space-x-8">
                    <a href="<?php echo SITE_URL; ?>"
                        class="text-gray-700 hover:text-primary-blue transition-colors <?php echo $currentPage === 'index' ? 'text-primary-blue font-semibold' : ''; ?>">
                        Home
                    </a>
                    <a href="<?php echo SITE_URL; ?>/services"
                        class="text-gray-700 hover:text-primary-blue transition-colors <?php echo $currentPage === 'services' || $currentPage === 'service-detail' ? 'text-primary-blue font-semibold' : ''; ?>">
                        Services
                    </a>
                    <a href="<?php echo SITE_URL; ?>/projects"
                        class="text-gray-700 hover:text-primary-blue transition-colors <?php echo $currentPage === 'projects' || $currentPage === 'project-detail' ? 'text-primary-blue font-semibold' : ''; ?>">
                        Projects
                    </a>
                    <a href="<?php echo SITE_URL; ?>/blog"
                        class="text-gray-700 hover:text-primary-blue transition-colors <?php echo $currentPage === 'blog' || $currentPage === 'blog-post' ? 'text-primary-blue font-semibold' : ''; ?>">
                        Blog
                    </a>
                    <a href="<?php echo SITE_URL; ?>/about"
                        class="text-gray-700 hover:text-primary-blue transition-colors <?php echo $currentPage === 'about' ? 'text-primary-blue font-semibold' : ''; ?>">
                        About
                    </a>
                    <a href="<?php echo SITE_URL; ?>/contact"
                        class="text-gray-700 hover:text-primary-blue transition-colors <?php echo $currentPage === 'contact' ? 'text-primary-blue font-semibold' : ''; ?>">
                        Contact
                    </a>
                </nav>

                <!-- Phone Number & CTA -->
                <div class="hidden lg:flex items-center space-x-4">
                    <a href="tel:<?php echo str_replace(['(', ')', ' ', '-'], '', PHONE_NUMBER); ?>"
                        class="text-primary-blue font-semibold text-lg hover:text-secondary-red transition-colors">
                        <i class="fas fa-phone mr-2"></i><?php echo PHONE_NUMBER; ?>
                    </a>
                    <a href="<?php echo SITE_URL; ?>/contact"
                        class="bg-secondary-red text-white px-6 py-2 rounded-lg hover:bg-red-600 transition-colors font-semibold">
                        Get a Quote
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button class="lg:hidden text-gray-700 hover:text-primary-blue" id="mobile-menu-button">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <nav class="lg:hidden bg-white border-t hidden" id="mobile-menu">
            <div class="container mx-auto px-4 py-4">
                <div class="flex flex-col space-y-4">
                    <a href="<?php echo SITE_URL; ?>"
                        class="text-gray-700 hover:text-primary-blue transition-colors py-2 <?php echo $currentPage === 'index' ? 'text-primary-blue font-semibold' : ''; ?>">
                        Home
                    </a>
                    <a href="<?php echo SITE_URL; ?>/services"
                        class="text-gray-700 hover:text-primary-blue transition-colors py-2 <?php echo $currentPage === 'services' || $currentPage === 'service-detail' ? 'text-primary-blue font-semibold' : ''; ?>">
                        Services
                    </a>
                    <a href="<?php echo SITE_URL; ?>/projects"
                        class="text-gray-700 hover:text-primary-blue transition-colors py-2 <?php echo $currentPage === 'projects' || $currentPage === 'project-detail' ? 'text-primary-blue font-semibold' : ''; ?>">
                        Projects
                    </a>
                    <a href="<?php echo SITE_URL; ?>/blog"
                        class="text-gray-700 hover:text-primary-blue transition-colors py-2 <?php echo $currentPage === 'blog' || $currentPage === 'blog-post' ? 'text-primary-blue font-semibold' : ''; ?>">
                        Blog
                    </a>
                    <a href="<?php echo SITE_URL; ?>/about"
                        class="text-gray-700 hover:text-primary-blue transition-colors py-2 <?php echo $currentPage === 'about' ? 'text-primary-blue font-semibold' : ''; ?>">
                        About
                    </a>
                    <a href="<?php echo SITE_URL; ?>/contact"
                        class="text-gray-700 hover:text-primary-blue transition-colors py-2 <?php echo $currentPage === 'contact' ? 'text-primary-blue font-semibold' : ''; ?>">
                        Contact
                    </a>
                    <div class="pt-4 border-t border-gray-200">
                        <a href="tel:<?php echo str_replace(['(', ')', ' ', '-'], '', PHONE_NUMBER); ?>"
                            class="block text-primary-blue font-semibold text-lg mb-3">
                            <i class="fas fa-phone mr-2"></i><?php echo PHONE_NUMBER; ?>
                        </a>
                        <a href="<?php echo SITE_URL; ?>/contact"
                            class="block bg-secondary-red text-white text-center px-6 py-3 rounded-lg hover:bg-red-600 transition-colors font-semibold">
                            Get a Quote
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });

            const header = document.getElementById('main-header');
            if (window.location.pathname === '/' || window.location.pathname === '/index.php') {
                header.classList.add('bg-transparent', 'shadow-none');
                header.classList.remove('bg-white', 'shadow-md');

                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        header.classList.remove('bg-transparent', 'shadow-none');
                        header.classList.add('bg-white', 'shadow-md');
                    } else {
                        header.classList.add('bg-transparent', 'shadow-none');
                        header.classList.remove('bg-white', 'shadow-md');
                    }
                });
            }
        });
    </script>

    <!-- Main Content -->