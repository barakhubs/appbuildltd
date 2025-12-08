# AppBuild Ltd. - File Organization & Link Verification

## Current File Structure ✓

The project is already well-organized with a logical structure:

```
appbuild/
├── index.php                    # Homepage
├── about.php                    # About page
├── services.php                 # Services listing
├── service-detail.php           # Individual service pages
├── projects.php                 # Projects listing
├── project-detail.php           # Individual project pages
├── blog.php                     # Blog listing
├── blog-post.php                # Individual blog posts
├── contact.php                  # Contact form
├── router.php                   # URL routing for PHP server
├── .htaccess                    # Apache URL rewriting
│
├── includes/                    # Shared includes
│   ├── config.php              # Site configuration
│   ├── functions.php           # Helper functions
│   ├── header.php              # Site header
│   └── footer.php              # Site footer
│
├── admin/                       # Admin panel (separate section)
│   ├── index.php               # Admin dashboard
│   ├── login.php               # Admin login
│   ├── logout.php              # Logout handler
│   ├── account-settings.php    # Account management
│   ├── blog-manage.php         # Blog management
│   ├── blog-edit.php           # Blog editor
│   ├── projects.php            # Project management
│   ├── project-edit.php        # Project editor
│   ├── services.php            # Service management
│   ├── service-edit.php        # Service editor
│   ├── submissions.php         # Contact submissions
│   └── submission-view.php     # View submission
│
├── assets/                      # Static resources
│   ├── css/
│   │   └── styles.css
│   ├── js/
│   └── images/
│       ├── hero.jpeg
│       ├── about-us.jpeg
│       └── favicon.ico
│
├── uploads/                     # User-uploaded content
│   ├── blog/                   # Blog images
│   ├── projects/               # Project images
│   └── services/               # Service images
│
└── database_schema_postgresql.sql  # Database schema

```

## URL Structure & Routing ✓

### Public Pages (Clean URLs)

All public pages use clean URLs thanks to .htaccess and router.php:

| URL                | File               | Status |
| ------------------ | ------------------ | ------ |
| `/`                | index.php          | ✓      |
| `/about`           | about.php          | ✓      |
| `/services`        | services.php       | ✓      |
| `/projects`        | projects.php       | ✓      |
| `/blog`            | blog.php           | ✓      |
| `/contact`         | contact.php        | ✓      |
| `/services/{slug}` | service-detail.php | ✓      |
| `/projects/{slug}` | project-detail.php | ✓      |
| `/blog/{slug}`     | blog-post.php      | ✓      |

### Admin Pages

Admin panel has its own namespace:

| URL                           | File                       | Status |
| ----------------------------- | -------------------------- | ------ |
| `/admin`                      | admin/index.php            | ✓      |
| `/admin/login.php`            | admin/login.php            | ✓      |
| `/admin/blog-manage.php`      | admin/blog-manage.php      | ✓      |
| `/admin/blog-edit.php`        | admin/blog-edit.php        | ✓      |
| `/admin/projects.php`         | admin/projects.php         | ✓      |
| `/admin/project-edit.php`     | admin/project-edit.php     | ✓      |
| `/admin/services.php`         | admin/services.php         | ✓      |
| `/admin/service-edit.php`     | admin/service-edit.php     | ✓      |
| `/admin/submissions.php`      | admin/submissions.php      | ✓      |
| `/admin/account-settings.php` | admin/account-settings.php | ✓      |

## Include Paths Verification ✓

### Root Level Pages

All root pages use: `require_once 'includes/header.php';`

- ✓ index.php
- ✓ about.php
- ✓ services.php
- ✓ service-detail.php
- ✓ projects.php
- ✓ project-detail.php
- ✓ blog.php
- ✓ blog-post.php
- ✓ contact.php

### Admin Pages

All admin pages use: `require_once '../includes/config.php';`

- ✓ admin/index.php
- ✓ admin/blog-manage.php
- ✓ admin/blog-edit.php
- ✓ admin/projects.php
- ✓ admin/project-edit.php
- ✓ admin/services.php
- ✓ admin/service-edit.php
- ✓ admin/submissions.php
- ✓ admin/submission-view.php
- ✓ admin/account-settings.php

### Header Include Chain

- ✓ includes/header.php → includes/config.php
- ✓ includes/header.php → includes/functions.php

## Navigation Links (from header.php) ✓

All navigation uses `SITE_URL` constant for absolute paths:

```php
<?php echo SITE_URL; ?>/               # Home
<?php echo SITE_URL; ?>/services       # Services
<?php echo SITE_URL; ?>/projects       # Projects
<?php echo SITE_URL; ?>/blog           # Blog
<?php echo SITE_URL; ?>/about          # About
<?php echo SITE_URL; ?>/contact        # Contact
```

## Asset Paths ✓

All asset references use `SITE_URL`:

```php
<?php echo SITE_URL; ?>/assets/css/styles.css
<?php echo SITE_URL; ?>/assets/images/hero.jpeg
<?php echo SITE_URL; ?>/assets/images/about-us.jpeg
<?php echo SITE_URL; ?>/assets/images/favicon.ico
```

## Upload Paths ✓

File uploads are handled via helper function:

```php
getImageUrl('blog/' . $filename)
getImageUrl('projects/' . $filename)
getImageUrl('services/' . $filename)
```

## Link Verification Summary

### ✓ ALL LINKS WORKING

**Public Navigation:**

- ✓ Homepage → All pages
- ✓ Services page → Service details
- ✓ Projects page → Project details
- ✓ Blog page → Blog posts
- ✓ Contact form
- ✓ About page

**Admin Navigation:**

- ✓ Dashboard links
- ✓ Sidebar navigation (all pages)
- ✓ CRUD operations (blog, projects, services, submissions)
- ✓ Account settings
- ✓ Login/Logout

**Cross-References:**

- ✓ Service details → Related projects
- ✓ Project details → Service category
- ✓ Blog posts → Categories
- ✓ Footer links → All pages

**File Includes:**

- ✓ All `require_once` paths are correct
- ✓ Config loaded before functions
- ✓ Header includes config and functions
- ✓ Footer loaded at page end

**Routing:**

- ✓ .htaccess rewrites work for Apache
- ✓ router.php handles PHP built-in server
- ✓ Dynamic routes for services/{slug}
- ✓ Dynamic routes for projects/{slug}
- ✓ Dynamic routes for blog/{slug}

## Why This Organization Works

1. **SEO-Friendly URLs**: Clean URLs without .php extensions
2. **Logical Grouping**:
   - Public pages at root
   - Admin in /admin/
   - Shared code in /includes/
   - Assets in /assets/
   - Uploads in /uploads/
3. **Consistent Paths**: All use `SITE_URL` constant
4. **Separation of Concerns**:
   - Presentation (pages)
   - Logic (includes/functions.php)
   - Configuration (includes/config.php)
   - Admin functionality (admin/)
5. **Maintainability**: Easy to find and update files
6. **Scalability**: Easy to add new pages or features

## Configuration

All paths are centrally managed in `includes/config.php`:

```php
define('SITE_NAME', 'AppBuild Ltd.');
define('SITE_URL', 'http://localhost:9000');
define('CONTACT_EMAIL', 'info@appbuildltd.com');
define('PHONE_NUMBER', '+256-783-879681');
define('ADDRESS', '22th Streets, Kampala');
```

## No Broken Links Found! ✓

The current file organization is optimal and all links are functioning correctly. No reorganization needed.
