    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="container mx-auto px-4 pt-16 pb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                <!-- Company Info -->
                <div class="md:col-span-2 lg:col-span-1">
                    <h3 class="text-2xl font-bold text-white mb-4"><?php echo SITE_NAME; ?></h3>
                    <p class="text-gray-400 mb-6">
                        Driving business transformation through innovative technology and expert management solutions.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="bg-gray-700 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-primary-blue transition-colors"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="bg-gray-700 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-primary-blue transition-colors"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="bg-gray-700 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-primary-blue transition-colors"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="bg-gray-700 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-primary-blue transition-colors"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-6 text-white">Quick Links</h4>
                    <ul class="space-y-3">
                        <li><a href="<?php echo SITE_URL; ?>/about" class="text-gray-400 hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>About Us</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/services" class="text-gray-400 hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Services</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/projects" class="text-gray-400 hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Projects</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/blog" class="text-gray-400 hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Blog</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/contact" class="text-gray-400 hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Contact</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="text-lg font-semibold mb-6 text-white">Our Services</h4>
                    <ul class="space-y-3">
                        <?php
                        $serviceCategories = array_slice(getServiceCategories(), 0, 5);
                        foreach ($serviceCategories as $key => $name):
                        ?>
                            <li>
                                <a href="<?php echo SITE_URL; ?>/services/<?php echo $key; ?>"
                                    class="text-gray-400 hover:text-white transition-colors flex items-center">
                                    <i class="fas fa-chevron-right text-xs mr-2"></i><?php echo $name; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Newsletter Signup -->
                <div>
                    <h4 class="text-lg font-semibold mb-6 text-white">Stay Updated</h4>
                    <p class="text-gray-400 mb-4">Subscribe to our newsletter for the latest insights.</p>
                    <form class="flex">
                        <input type="email" placeholder="Your email"
                            class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-l-md focus:outline-none focus:ring-2 focus:ring-primary-blue">
                        <button type="submit"
                            class="bg-primary-blue text-white px-4 py-2 rounded-r-md hover:bg-blue-700 transition-colors font-semibold">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="bg-gray-900 py-4 border-t border-gray-700">
            <div class="container mx-auto px-4">
                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm mb-2 sm:mb-0">
                        &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All Rights Reserved.
                    </p>
                    <div class="flex space-x-6 text-sm">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Terms of Service</a>
                        <a href="<?php echo SITE_URL; ?>/admin" class="text-gray-400 hover:text-white transition-colors">Admin</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
    </body>

    </html>