<?php
require_once 'config.php';

// Common utility functions
function sanitizeInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function createSlug($string)
{
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    return $slug;
}

function formatDate($date, $format = 'F j, Y')
{
    return date($format, strtotime($date));
}

function truncateText($text, $limit = 150)
{
    if (strlen($text) <= $limit) {
        return $text;
    }
    return substr($text, 0, $limit) . '...';
}

function getCurrentPage()
{
    return basename($_SERVER['PHP_SELF'], '.php');
}

function isCurrentPage($page)
{
    return getCurrentPage() === $page;
}

function getServiceCategories()
{
    return [
        'data-management' => 'Data Management',
        'project-management' => 'Project Management',
        'cost-management' => 'Cost Management',
        'design-management' => 'Design Management',
        'application-development' => 'Application Development',
        'document-automation' => 'Document Control & Automation'
    ];
}

function getBlogCategories()
{
    return [
        'Data Management',
        'Project Management',
        'Cost Management',
        'Design Management',
        'Application Development',
        'Document Control',
        'Industry News',
        'Best Practices',
        'Case Studies'
    ];
}

// Database helper functions
function getDB()
{
    return Database::getInstance()->getConnection();
}

function getBlogPosts($limit = null, $category = null, $status = 'published')
{
    $db = getDB();
    $sql = "SELECT * FROM blog_posts WHERE status = :status";
    $params = ['status' => $status];

    if ($category) {
        $sql .= " AND category = :category";
        $params['category'] = $category;
    }

    $sql .= " ORDER BY created_at DESC";

    if ($limit) {
        $sql .= " LIMIT :limit";
    }

    $stmt = $db->prepare($sql);

    if ($limit) {
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    }

    foreach ($params as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }

    $stmt->execute();
    return $stmt->fetchAll();
}

function getBlogPostBySlug($slug)
{
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM blog_posts WHERE slug = :slug AND status = 'published'");
    $stmt->execute(['slug' => $slug]);
    return $stmt->fetch();
}

function getProjects($limit = null, $category = null, $featured = null, $status = 'published')
{
    $db = getDB();
    $sql = "SELECT * FROM projects WHERE status = :status";
    $params = ['status' => $status];

    if ($category) {
        $sql .= " AND service_category = :category";
        $params['category'] = $category;
    }

    if ($featured !== null) {
        $sql .= " AND featured = :featured";
        $params['featured'] = $featured ? 1 : 0;
    }

    $sql .= " ORDER BY featured DESC, created_at DESC";

    if ($limit) {
        $sql .= " LIMIT :limit";
    }

    $stmt = $db->prepare($sql);

    if ($limit) {
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    }

    foreach ($params as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }

    $stmt->execute();
    return $stmt->fetchAll();
}

function getProjectBySlug($slug)
{
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM projects WHERE slug = :slug AND status = 'published'");
    $stmt->execute(['slug' => $slug]);
    return $stmt->fetch();
}

function getServicePage($serviceKey)
{
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM service_pages WHERE service_key = :service_key");
    $stmt->execute(['service_key' => $serviceKey]);
    return $stmt->fetch();
}

function saveContactSubmission($data)
{
    $db = getDB();
    $stmt = $db->prepare("
        INSERT INTO contact_submissions (name, email, phone, company, service_interest, message, submitted_at) 
        VALUES (:name, :email, :phone, :company, :service_interest, :message, CURRENT_TIMESTAMP)
    ");

    return $stmt->execute([
        'name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'company' => $data['company'] ?? '',
        'service_interest' => $data['service_interest'] ?? '',
        'message' => $data['message']
    ]);
}

// Admin authentication functions
function isAdminLoggedIn()
{
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireAdminLogin()
{
    if (!isAdminLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

function loginAdmin($username, $password)
{
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        return true;
    }

    return false;
}

function logoutAdmin()
{
    unset($_SESSION['admin_logged_in']);
    unset($_SESSION['admin_user_id']);
    unset($_SESSION['admin_username']);
    session_destroy();
}

// SEO and Meta functions
function getPageTitle($title = '')
{
    $siteTitle = SITE_NAME;
    return $title ? "$title - $siteTitle" : $siteTitle;
}

function getMetaDescription($description = '')
{
    $default = "AppBuild Ltd. specializes in data management, project management, cost management, design management, and application development solutions. Contact us today!";
    return $description ?: $default;
}

// Image helper functions
function getImageUrl($imagePath, $default = 'placeholder.jpg')
{
    if (empty($imagePath)) {
        return SITE_URL . '/assets/images/' . $default;
    }

    // If it's already a full URL, return as is
    if (strpos($imagePath, 'http') === 0) {
        return $imagePath;
    }

    return SITE_URL . '/assets/images/' . $imagePath;
}

// Validation functions
function validateEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePhone($phone)
{
    return preg_match('/^[\+]?[0-9\s\-\(\)]{10,}$/', $phone);
}

function validateRequired($value)
{
    return !empty(trim($value));
}
