<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'appbuild_website');
define('DB_USER', 'postgres');
define('DB_PASS', 'hello');

// Site Configuration
define('SITE_NAME', 'AppBuild Ltd.');
define('SITE_URL', 'http://localhost:9000');
define('ADMIN_EMAIL', 'admin@appbuildltd.com');
define('CONTACT_EMAIL', 'info@appbuildltd.com');
define('PHONE_NUMBER', '+1 (555) 123-4567');
define('BUSINESS_HOURS', 'Monday - Friday: 9:00 AM - 6:00 PM');
define('ADDRESS', '123 Business Street, Suite 100, City, State 12345');
// environment
define('ENVIRONMENT', 'development'); // change to 'production' in live environment

// Error reporting based on environment
if (ENVIRONMENT === 'production') {
    error_reporting(0);
    ini_set('display_errors', '0');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

// Brand Colors
define('PRIMARY_BLUE', '#265E9A');
define('SECONDARY_RED', '#F54927');

// Database Connection
class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        try {
            $this->connection = new PDO(
                "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";options='--client_encoding=UTF8'",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// CSRF Protection
function generateCSRFToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Basic security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
