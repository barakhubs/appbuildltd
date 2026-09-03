# Appbuild Tech Company Ltd. — Website & CMS

A business website and lightweight content management system for a software agency, built in plain PHP with a PostgreSQL backend and Tailwind CSS.

## Features

**Public site**
- Responsive, mobile-first design
- Homepage with hero section, services overview, featured projects, and testimonials
- Detailed service pages
- Filterable project portfolio
- Blog with categories and search
- Validated contact forms with email notifications
- SEO-friendly markup and clean URLs

**Admin panel**
- Login-protected dashboard
- Blog post management (create/edit)
- Project and service management
- Contact submission inbox

## Tech stack

- PHP (no framework — hand-rolled routing/MVC)
- PostgreSQL (schema included in `database/`)
- Tailwind CSS
- Vanilla JavaScript

## Getting started

```bash
cp .env.example .env
# configure database credentials in .env
psql -d your_db -f database/database_schema_postgresql.sql
```
Serve the project root with PHP's built-in server or a standard Apache/Nginx + PHP setup.

## Testing

No automated test suite. `tools/url-test.php` is an ad hoc script for spot-checking routes, not a formal test harness.

## Status

13 commits (Dec 2025–Mar 2026), single author, currently live.
