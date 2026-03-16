# Appbuild Tech Company ltd. Website

A professional business website built with PHP, PostgreSQL, and Tailwind CSS featuring comprehensive business solutions and services.

## Features

### Public Website

- **Responsive Design**: Mobile-first approach with Tailwind CSS
- **Professional Homepage**: Hero section, services overview, featured projects, testimonials
- **Services Pages**: Detailed service information with clean navigation
- **Projects Portfolio**: Showcase of completed projects with filtering
- **Blog System**: Content management with categories and search
- **Contact Forms**: Validated contact forms with email notifications
- **SEO Optimized**: Proper meta tags, semantic HTML, clean URLs

### Admin Panel

- **Secure Authentication**: PHP session-based login system
- **Content Management**: CRUD operations for blog posts and projects
- **Service Management**: Edit service page content
- **Contact Management**: View and manage form submissions
- **Dashboard Analytics**: Overview of content and activity

### Technical Features

- **Database**: PostgreSQL with prepared statements for security
- **Security**: CSRF protection, input sanitization, secure headers
- **Performance**: Image optimization, caching headers, compression
- **Clean URLs**: SEO-friendly URLs with .htaccess rewriting

## Installation

### Prerequisites

- PHP 7.4+ (with PDO PostgreSQL extension)
- PostgreSQL 12+ (with contrib modules)
- Apache web server (with mod_rewrite enabled)
- Composer (optional, for additional dependencies)

### Step 1: Database Setup

1. Create a new PostgreSQL database:

```sql
CREATE DATABASE appbuild_website;
```

2. Import the database schema:

```bash
psql -U postgres -d appbuild_website -f database_schema_postgresql.sql
```

### Step 2: Configuration

1. Edit `includes/config.php` and update the database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'appbuild_website');
define('DB_USER', 'postgres');
define('DB_PASS', 'your_password');
```

2. Update site configuration:

```php
define('SITE_URL', 'http://yourdomain.com');
define('ADMIN_EMAIL', 'admin@yourdomain.com');
define('CONTACT_EMAIL', 'info@yourdomain.com');
define('PHONE_NUMBER', '+1 (555) 123-4567');
```

### Step 3: File Permissions

Set appropriate permissions for file uploads:

```bash
chmod 755 assets/images/
chmod 755 assets/images/blog/
chmod 755 assets/images/projects/
chmod 755 assets/images/services/
```

### Step 4: Admin Access

Default admin credentials (change immediately after first login):

- Username: `admin`
- Password: `admin123`

Access the admin panel at: `http://yourdomain.com/admin/`

### Step 5: Email Configuration

For contact form email functionality, ensure your server has mail() function enabled or configure SMTP settings in the contact form processing code.

## File Structure

```
/appbuild-website/
├── admin/                  # Admin panel files
│   ├── index.php          # Dashboard
│   ├── login.php          # Admin login
│   ├── blog-manage.php    # Blog management
│   ├── project-manage.php # Project management
│   └── submissions.php    # Contact submissions
├── assets/                # Static assets
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   └── images/           # Image uploads
├── includes/             # PHP includes
│   ├── config.php        # Database & site configuration
│   ├── functions.php     # Utility functions
│   ├── header.php        # Site header
│   └── footer.php        # Site footer
├── index.php             # Homepage
├── services.php          # Services overview
├── service-detail.php    # Individual service pages
├── about.php             # About page
├── projects.php          # Projects overview
├── project-detail.php    # Individual project pages
├── blog.php              # Blog listing
├── blog-post.php         # Individual blog posts
├── contact.php           # Contact page
├── .htaccess             # Apache configuration
└── README.md             # This file
```

## Customization

### Brand Colors

Update brand colors in `includes/config.php`:

```php
define('PRIMARY_BLUE', '#265E9A');
define('SECONDARY_RED', '#F54927');
```

Also update the Tailwind configuration in header.php:

```javascript
tailwind.config = {
  theme: {
    extend: {
      colors: {
        "primary-blue": "#265E9A",
        "secondary-red": "#F54927",
      },
    },
  },
};
```

### Content Management

- **Services**: Edit service content through admin panel or directly in database
- **Blog Posts**: Create and manage blog posts through admin panel
- **Projects**: Add project portfolio items through admin panel
- **Contact Info**: Update contact information in `includes/config.php`

### Adding New Pages

1. Create new PHP file in root directory
2. Include header: `require_once 'includes/header.php';`
3. Add your content
4. Include footer: `require_once 'includes/footer.php';`
5. Add navigation link in `includes/header.php`

## Security Considerations

### Production Deployment

1. **Change Default Passwords**: Update admin password immediately
2. **Remove Development Info**: Remove the default credentials info from login page
3. **Enable HTTPS**: Uncomment HTTPS redirect in .htaccess
4. **Secure File Permissions**: Set restrictive permissions on sensitive files
5. **Regular Updates**: Keep PHP and PostgreSQL updated
6. **Backup Strategy**: Implement regular database and file backups

### Security Features

- CSRF token protection on forms
- Prepared statements for database queries
- Input sanitization and validation
- Secure session handling
- SQL injection prevention
- XSS protection headers

## Performance Optimization

### Image Optimization

- Compress images before uploading
- Use appropriate image formats (WebP when possible)
- Implement lazy loading for images below the fold

### Caching

- Browser caching configured via .htaccess
- Consider implementing Redis/Memcached for database query caching
- Use CDN for static assets in production

### Database Optimization

- Regular OPTIMIZE TABLE commands
- Monitor slow queries
- Add appropriate indexes for frequently queried columns

## Maintenance

### Regular Tasks

- Monitor contact form submissions
- Update blog content regularly
- Review and update project portfolio
- Check for broken links and images
- Monitor website analytics
- Backup database and files

### Monitoring

- Set up website uptime monitoring
- Monitor website speed and performance
- Track contact form conversions
- Review error logs regularly

## Support

### Common Issues

1. **Database Connection Error**: Check config.php credentials
2. **Email Not Sending**: Verify server mail configuration
3. **Images Not Loading**: Check file permissions and paths
4. **Admin Login Issues**: Verify session configuration

### Getting Help

- Check error logs for detailed error information
- Verify file permissions and ownership
- Test database connectivity separately
- Review .htaccess syntax if URL rewriting fails

## License

This project is proprietary software developed for Appbuild Tech Company ltd. All rights reserved.

## Development Notes

### Technologies Used

- **Backend**: PHP 7.4+
- **Database**: PostgreSQL
- **Frontend**: HTML5, Tailwind CSS 3.0
- **JavaScript**: Vanilla ES6+
- **Icons**: Font Awesome 6.0
- **Server**: Apache with mod_rewrite

### Development Environment

For local development:

1. Use local PostgreSQL installation or Docker for database
2. Enable mod_rewrite in Apache configuration
3. Set display_errors = On in PHP for debugging
4. Use a local domain (e.g., appbuild.local) via hosts file

### Future Enhancements

- Newsletter subscription functionality
- Advanced search capabilities
- User role management
- API endpoints for mobile app
- Multi-language support
- Advanced analytics integration
