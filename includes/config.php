<?php
// Load environment variables from .env file
function loadEnv($path)
{
    if (!file_exists($path)) {
        echo __DIR__ . '/../.env';
        die('.env file not found. Please copy .env.example to .env and configure your settings.');
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Parse key=value
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Remove quotes if present
            $value = trim($value, '"\'');

            // Set environment variable
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}

// Load .env file
loadEnv(__DIR__ . '/../.env');

// Database Configuration
define('DB_TYPE', $_ENV['DB_TYPE'] ?? 'pgsql'); // pgsql or mysql
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_PORT', $_ENV['DB_PORT'] ?? '5432');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'appbuild_website');
define('DB_USER', $_ENV['DB_USER'] ?? 'postgres');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');

// Site Configuration
define('SITE_NAME', $_ENV['SITE_NAME'] ?? 'AppBuild Ltd.');
define('SITE_URL', $_ENV['SITE_URL'] ?? 'http://localhost:9000');
define('ADMIN_EMAIL', $_ENV['ADMIN_EMAIL'] ?? 'admin@appbuildltd.com');
define('CONTACT_EMAIL', $_ENV['CONTACT_EMAIL'] ?? 'info@appbuildltd.com');
define('PHONE_NUMBER', $_ENV['PHONE_NUMBER'] ?? '+256-783-879681');
define('BUSINESS_HOURS', $_ENV['BUSINESS_HOURS'] ?? 'Monday - Friday: 9:00 AM - 6:00 PM');
define('ADDRESS', $_ENV['ADDRESS'] ?? '22th Streets, Kampala');

// Environment
define('ENVIRONMENT', $_ENV['ENVIRONMENT'] ?? 'development');

// Error reporting based on environment
if (ENVIRONMENT === 'production') {
    error_reporting(0);
    ini_set('display_errors', '0');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

// Brand Colors
define('PRIMARY_BLUE', $_ENV['PRIMARY_BLUE'] ?? '#265E9A');
define('SECONDARY_RED', $_ENV['SECONDARY_RED'] ?? '#F54927');

// Database Connection
class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        try {
            // Build DSN based on database type
            if (DB_TYPE === 'mysql') {
                $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            } else {
                // Default to PostgreSQL
                $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";options='--client_encoding=UTF8'";
            }

            $this->connection = new PDO(
                $dsn,
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
