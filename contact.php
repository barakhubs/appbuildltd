<?php
require_once 'includes/config.php';

$pageTitle = 'Contact Us - Get in Touch Today';
$metaDescription = 'Contact Appbuild Tech Company ltd. for expert business solutions. Call us at ' . PHONE_NUMBER . ' or fill out our contact form to get started.';

require_once 'includes/header.php';

$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid form submission. Please try again.';
    } else {
        // Sanitize and validate form data
        $name = sanitizeInput($_POST['name'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $phone = sanitizeInput($_POST['phone'] ?? '');
        $company = sanitizeInput($_POST['company'] ?? '');
        $serviceInterest = sanitizeInput($_POST['service_interest'] ?? '');
        $message = sanitizeInput($_POST['message'] ?? '');

        // Validation
        if (!validateRequired($name)) {
            $errors[] = 'Name is required.';
        }

        if (!validateRequired($email)) {
            $errors[] = 'Email is required.';
        } elseif (!validateEmail($email)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if (!validateRequired($phone)) {
            $errors[] = 'Phone number is required.';
        } elseif (!validatePhone($phone)) {
            $errors[] = 'Please enter a valid phone number.';
        }

        if (!validateRequired($message)) {
            $errors[] = 'Message is required.';
        } elseif (strlen($message) < 10) {
            $errors[] = 'Message must be at least 10 characters long.';
        }

        // Save to database and send email if no errors
        if (empty($errors)) {
            $contactData = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'company' => $company,
                'service_interest' => $serviceInterest,
                'message' => $message
            ];

            if (saveContactSubmission($contactData)) {
                // Send email notification (basic example)
                $to = ADMIN_EMAIL;
                $subject = 'New Contact Form Submission - ' . SITE_NAME;
                $emailMessage = "New contact form submission:\n\n";
                $emailMessage .= "Name: $name\n";
                $emailMessage .= "Email: $email\n";
                $emailMessage .= "Phone: $phone\n";
                $emailMessage .= "Company: $company\n";
                $emailMessage .= "Service Interest: $serviceInterest\n";
                $emailMessage .= "Message:\n$message\n";

                $headers = "From: $email\r\n";
                $headers .= "Reply-To: $email\r\n";

                // Attempt to send email
                @mail($to, $subject, $emailMessage, $headers);

                $success = 'Thank you for your message! We will get back to you within 24 hours.';

                // Clear form data
                $name = $email = $phone = $company = $serviceInterest = $message = '';
            } else {
                $errors[] = 'An error occurred while submitting your message. Please try again.';
            }
        }
    }
}

$serviceCategories = getServiceCategories();
?>

<!-- Hero Section -->
<section class="bg-primary-blue text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Contact Us</h1>
        <p class="text-xl text-blue-100 max-w-3xl mx-auto">
            Ready to transform your business? Get in touch with our expert team today.
            We're here to help you succeed.
        </p>
    </div>
</section>

<!-- Contact Information -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Info -->
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-8">Get In Touch</h2>

                <div class="space-y-6">
                    <!-- Phone -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-primary-blue rounded-full flex items-center justify-center">
                            <i class="fas fa-phone text-white"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">Phone</h3>
                            <p class="text-gray-600 mb-2">Call us for immediate assistance</p>
                            <a href="tel:<?php echo str_replace(['(', ')', ' ', '-'], '', PHONE_NUMBER); ?>"
                                class="text-2xl font-bold text-primary-blue hover:text-blue-700 transition-colors">
                                <?php echo PHONE_NUMBER; ?>
                            </a>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-primary-blue rounded-full flex items-center justify-center">
                            <i class="fas fa-envelope text-white"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">Email</h3>
                            <p class="text-gray-600 mb-2">Send us a message anytime</p>
                            <a href="mailto:<?php echo CONTACT_EMAIL; ?>"
                                class="text-lg font-semibold text-primary-blue hover:text-blue-700 transition-colors">
                                <?php echo CONTACT_EMAIL; ?>
                            </a>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-primary-blue rounded-full flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-white"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">Address</h3>
                            <p class="text-gray-600 mb-2">Visit our office</p>
                            <p class="text-gray-700"><?php echo ADDRESS; ?></p>
                        </div>
                    </div>

                    <!-- Business Hours -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-primary-blue rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">Business Hours</h3>
                            <p class="text-gray-600 mb-2">We're available during</p>
                            <p class="text-gray-700"><?php echo BUSINESS_HOURS; ?></p>
                            <p class="text-gray-700">Saturday - Sunday: Closed</p>
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="mt-8 p-6 bg-primary-blue text-white rounded-lg">
                    <h3 class="text-xl font-bold mb-2">Need Immediate Help?</h3>
                    <p class="text-blue-100 mb-4">Call us now for urgent inquiries or time-sensitive projects.</p>
                    <a href="tel:<?php echo str_replace(['(', ')', ' ', '-'], '', PHONE_NUMBER); ?>"
                        class="bg-secondary-red text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-600 transition-colors inline-block">
                        <i class="fas fa-phone mr-2"></i>Call Now
                    </a>
                </div>
            </div>

            <!-- Contact Form -->
            <div>
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Send Us a Message</h2>

                    <?php if ($success): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            <i class="fas fa-check-circle mr-2"></i><?php echo $success; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <ul class="list-disc list-inside mt-2">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" id="contact-form" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-blue focus:border-transparent"
                                    placeholder="Your full name"
                                    value="<?php echo htmlspecialchars($name ?? ''); ?>">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-blue focus:border-transparent"
                                    placeholder="your.email@example.com"
                                    value="<?php echo htmlspecialchars($email ?? ''); ?>">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Phone <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" id="phone" name="phone" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-blue focus:border-transparent"
                                    placeholder="(555) 123-4567"
                                    value="<?php echo htmlspecialchars($phone ?? ''); ?>"
                                    onkeyup="formatPhoneNumber(this)">
                            </div>

                            <div>
                                <label for="company" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Company
                                </label>
                                <input type="text" id="company" name="company"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-blue focus:border-transparent"
                                    placeholder="Your company name"
                                    value="<?php echo htmlspecialchars($company ?? ''); ?>">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="service_interest" class="block text-sm font-semibold text-gray-700 mb-2">
                                Service Interest
                            </label>
                            <select id="service_interest" name="service_interest"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-blue focus:border-transparent">
                                <option value="">Select a service (optional)</option>
                                <?php foreach ($serviceCategories as $key => $name): ?>
                                    <option value="<?php echo $name; ?>" <?php echo ($serviceInterest ?? '') === $name ? 'selected' : ''; ?>>
                                        <?php echo $name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                                Message <span class="text-red-500">*</span>
                            </label>
                            <textarea id="message" name="message" rows="5" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-blue focus:border-transparent"
                                placeholder="Tell us about your project or how we can help you..."><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-primary-blue text-white font-bold py-4 px-6 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i>Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Our Location</h2>
            <p class="text-lg text-gray-600">Find us at our main office location.</p>
        </div>

        <!-- Google Maps Embed (placeholder) -->
        <div class="bg-gray-200 h-96 rounded-lg flex items-center justify-center">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63835.82688512447!2d32.71378124459197!3d0.36087755670366384!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177dc7b71409b0a5%3A0xdddaf82b549ec570!2sMukono!5e0!3m2!1sen!2sug!4v1765114280771!5m2!1sen!2sug" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>