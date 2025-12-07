-- AppBuild Ltd. Website Database Schema
-- Create database first: CREATE DATABASE appbuild_website;
-- Use database: USE appbuild_website;
-- Admin users table
CREATE TABLE admin_users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(100),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
-- Blog posts table
CREATE TABLE blog_posts (
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) UNIQUE NOT NULL,
  featured_image VARCHAR(255),
  excerpt TEXT,
  content TEXT NOT NULL,
  category VARCHAR(100) DEFAULT 'General',
  author VARCHAR(100) DEFAULT 'AppBuild Team',
  status ENUM('draft', 'published') DEFAULT 'draft',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_slug (slug),
  INDEX idx_status (status),
  INDEX idx_category (category)
);
-- Projects table
CREATE TABLE projects (
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) UNIQUE NOT NULL,
  thumbnail VARCHAR(255),
  service_category VARCHAR(100) NOT NULL,
  client_name VARCHAR(255) DEFAULT 'Confidential Client',
  description TEXT NOT NULL,
  challenge TEXT,
  solution TEXT,
  results TEXT,
  images TEXT,
  -- JSON array of image paths
  status ENUM('draft', 'published') DEFAULT 'draft',
  featured BOOLEAN DEFAULT FALSE,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_slug (slug),
  INDEX idx_status (status),
  INDEX idx_category (service_category),
  INDEX idx_featured (featured)
);
-- Contact form submissions table
CREATE TABLE contact_submissions (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  company VARCHAR(255),
  service_interest VARCHAR(100),
  message TEXT NOT NULL,
  is_read BOOLEAN DEFAULT FALSE,
  submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_submitted (submitted_at),
  INDEX idx_read (is_read)
);
-- Service pages content (for easy editing via admin)
CREATE TABLE service_pages (
  id INT PRIMARY KEY AUTO_INCREMENT,
  service_key VARCHAR(50) UNIQUE NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  benefits TEXT,
  -- JSON array or pipe-separated
  use_cases TEXT,
  featured_image VARCHAR(255),
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
-- Insert default admin user (password: admin123 - change this!)
INSERT INTO
  admin_users (username, password, email)
VALUES
  (
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin@appbuildltd.com'
  );
-- Insert default service pages
INSERT INTO
  service_pages (
    service_key,
    title,
    description,
    benefits,
    use_cases,
    featured_image
  )
VALUES
  (
    'data-management',
    'Data Management Solutions',
    'Comprehensive data management services to help you organize, secure, and leverage your business data effectively.',
    'Data Security|Performance Optimization|Compliance Management|Real-time Analytics',
    'Database Design|Data Migration|Business Intelligence|Data Warehousing',
    'data-management.jpg'
  ),
  (
    'project-management',
    'Project Management Services',
    'Professional project management solutions to ensure your projects are delivered on time, within budget, and to specification.',
    'Timeline Management|Resource Optimization|Risk Mitigation|Quality Assurance',
    'Software Development Projects|Infrastructure Upgrades|Business Process Improvement',
    'project-management.jpg'
  ),
  (
    'cost-management',
    'Cost Management & Control',
    'Strategic cost management solutions to optimize your budget allocation and improve ROI across all business operations.',
    'Budget Optimization|Cost Tracking|Financial Planning|ROI Analysis',
    'Budget Planning|Expense Management|Financial Reporting|Cost Analysis',
    'cost-management.jpg'
  ),
  (
    'design-management',
    'Design Management',
    'Creative design management services to ensure consistent brand identity and user experience across all touchpoints.',
    'Brand Consistency|User Experience|Design Standards|Creative Workflow',
    'Brand Development|UI/UX Design|Marketing Materials|Design Systems',
    'design-management.jpg'
  ),
  (
    'application-development',
    'Application Development',
    'Custom application development services tailored to your specific business needs and requirements.',
    'Custom Solutions|Scalable Architecture|Modern Technologies|Ongoing Support',
    'Web Applications|Mobile Apps|Desktop Software|API Development',
    'application-development.jpg'
  ),
  (
    'document-automation',
    'Document Control & Automation',
    'Streamline your document workflows with automated control systems and digital transformation solutions.',
    'Process Automation|Version Control|Compliance Tracking|Digital Workflows',
    'Document Management Systems|Workflow Automation|Digital Transformation|Compliance Management',
    'document-automation.jpg'
  );
-- Insert sample blog posts
INSERT INTO
  blog_posts (
    title,
    slug,
    featured_image,
    excerpt,
    content,
    category,
    status
  )
VALUES
  (
    'The Future of Data Management in 2025',
    'future-data-management-2025',
    'blog-data-future.jpg',
    'Exploring the latest trends and technologies shaping data management strategies for modern businesses.',
    '<p>Data management continues to evolve rapidly as businesses generate more data than ever before. In this comprehensive guide, we explore the key trends and technologies that will define data management strategies in 2025.</p><h3>Key Trends to Watch</h3><p>Cloud-first approaches, AI-powered analytics, and enhanced security measures are becoming standard requirements for effective data management solutions.</p>',
    'Data Management',
    'published'
  ),
  (
    '5 Essential Project Management Best Practices',
    'project-management-best-practices',
    'blog-project-tips.jpg',
    'Discover proven strategies to improve project success rates and team productivity.',
    '<p>Successful project management requires a combination of methodology, tools, and leadership skills. Here are five essential best practices that can transform your project outcomes.</p><h3>1. Clear Communication</h3><p>Establishing clear communication channels and expectations from the start is crucial for project success.</p>',
    'Project Management',
    'published'
  );
-- Insert sample projects
INSERT INTO
  projects (
    title,
    slug,
    thumbnail,
    service_category,
    client_name,
    description,
    challenge,
    solution,
    results,
    status,
    featured
  )
VALUES
  (
    'Enterprise Data Warehouse Implementation',
    'enterprise-data-warehouse',
    'project-warehouse.jpg',
    'Data Management',
    'Fortune 500 Manufacturing Company',
    'Complete implementation of a modern data warehouse solution to centralize and analyze business data from multiple sources.',
    'The client had data scattered across 15+ different systems with no unified view for decision-making.',
    'We designed and implemented a comprehensive data warehouse using cloud technologies, creating automated ETL pipelines and real-time dashboards.',
    '95% reduction in report generation time, 40% improvement in data accuracy, and enhanced decision-making capabilities across all departments.',
    'published',
    TRUE
  ),
  (
    'Mobile Application Development',
    'mobile-app-development',
    'project-mobile.jpg',
    'Application Development',
    'Tech Startup',
    'Development of a cross-platform mobile application for inventory management and real-time tracking.',
    'The client needed a mobile solution that could work offline and sync data when connected.',
    'We built a React Native application with offline capabilities, real-time synchronization, and intuitive user interface.',
    'Successful launch with 10,000+ downloads in first month, 4.8-star rating, and 60% increase in operational efficiency.',
    'published',
    TRUE
  ),
  (
    'Digital Document Management System',
    'digital-document-management',
    'project-documents.jpg',
    'Document Control & Automation',
    'Legal Firm',
    'Implementation of a comprehensive digital document management and automation system.',
    'Manual document processing was causing delays and compliance issues.',
    'We implemented an automated document workflow system with version control, approval processes, and compliance tracking.',
    '75% reduction in document processing time, improved compliance score, and enhanced client satisfaction.',
    'published',
    FALSE
  );