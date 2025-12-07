# PostgreSQL Setup Guide for AppBuild Website

## Quick Setup Instructions

### 1. Install PostgreSQL

- Download and install PostgreSQL from https://www.postgresql.org/download/
- During installation, remember the password you set for the `postgres` user
- Default port is 5432

### 2. Create Database

Open PostgreSQL command line (psql) or pgAdmin and run:

```sql
-- Connect as postgres user
CREATE DATABASE appbuild_website;
```

### 3. Import Schema

```bash
# Using psql command line
psql -U postgres -d appbuild_website -f database_schema_postgresql.sql

# Or using pgAdmin:
# 1. Right-click on appbuild_website database
# 2. Select "Query Tool"
# 3. Open database_schema_postgresql.sql file
# 4. Execute the script
```

### 4. Update Configuration

Edit `includes/config.php`:

```php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'appbuild_website');
define('DB_USER', 'postgres');
define('DB_PASS', 'your_postgres_password');
```

### 5. Install PHP PostgreSQL Extension

#### Windows (XAMPP/WAMP)

- Uncomment `extension=pdo_pgsql` in php.ini
- Restart Apache

#### Linux (Ubuntu/Debian)

```bash
sudo apt-get install php-pgsql
sudo systemctl restart apache2
```

#### macOS (Homebrew)

```bash
brew install php
# pdo_pgsql should be included by default
```

### 6. Test Connection

Create a test file to verify the connection:

```php
<?php
try {
    $pdo = new PDO("pgsql:host=localhost;port=5432;dbname=appbuild_website", "postgres", "your_password");
    echo "Successfully connected to PostgreSQL!";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
```

## Key Differences from MySQL

### 1. Data Types

- `AUTO_INCREMENT` → `SERIAL`
- `DATETIME` → `TIMESTAMP`
- `ENUM` → `CHECK` constraints

### 2. String Functions

- `CONCAT()` → `||` operator
- `LEFT()` → `LEFT()` or `SUBSTRING()`

### 3. Case Sensitivity

- PostgreSQL is case-sensitive for identifiers
- Use double quotes for case-sensitive names

### 4. Sequences

- PostgreSQL uses sequences for auto-incrementing fields
- Created automatically with `SERIAL` type

## Backup and Restore

### Backup Database

```bash
pg_dump -U postgres -d appbuild_website -f backup.sql
```

### Restore Database

```bash
psql -U postgres -d appbuild_website -f backup.sql
```

## Performance Tips

1. **Indexing**: Indexes are automatically created for PRIMARY KEY and UNIQUE constraints
2. **VACUUM**: Run periodic VACUUM commands to optimize performance
3. **Connection Pooling**: Consider using pgBouncer for production environments
4. **Configuration**: Tune postgresql.conf for your hardware

## Troubleshooting

### Common Issues

1. **Connection Refused**

   - Check if PostgreSQL service is running
   - Verify pg_hba.conf allows connections
   - Check firewall settings

2. **Authentication Failed**

   - Verify username and password
   - Check pg_hba.conf authentication method

3. **Extension Not Found**

   - Install php-pgsql package
   - Restart web server after installation

4. **Permission Denied**
   - Grant proper permissions to database user
   - Check file permissions for uploads directory

### Log Locations

- **Windows**: `C:\Program Files\PostgreSQL\{version}\data\log\`
- **Linux**: `/var/log/postgresql/`
- **macOS**: `/usr/local/var/log/postgres.log`

## Production Considerations

1. **Create dedicated user** instead of using postgres superuser
2. **Configure pg_hba.conf** for secure connections
3. **Enable SSL/TLS** for remote connections
4. **Set up regular backups** with pg_dump
5. **Monitor performance** with pg_stat_statements
6. **Configure connection limits** in postgresql.conf
