# Complete Setup and Testing Guide

## Prerequisites Installation

### Method 1: Using XAMPP (Recommended - All-in-one solution)
1. Download XAMPP from: https://www.apachefriends.org/download.html
2. Install XAMPP (includes PHP 8.2, MySQL, Apache)
3. Start XAMPP Control Panel
4. Start Apache and MySQL services
5. Download Composer from: https://getcomposer.org/download/

### Method 2: Individual Components
```powershell
# Run PowerShell as Administrator
winget install ApacheFriends.Xampp.8.2
# OR install separately:
# winget install PHP.PHP
# winget install Oracle.MySQL
# winget install Apache.HTTPD
```

## Step-by-Step Setup Process

### 1. Initial Setup
```bash
# Navigate to your project directory
cd C:\Users\pulak\Desktop\TF2-express\EventManagementWebsite

# If using XAMPP, copy project to XAMPP htdocs:
# xcopy /E /I . C:\xampp\htdocs\EventManagementWebsite
```

### 2. Install Dependencies
```bash
# Install Composer dependencies
composer install

# If Composer is not found, download and run:
# php composer-setup.php --install-dir=. --filename=composer
# php composer.phar install
```

### 3. Environment Configuration
```bash
# Copy environment template
copy .env.example .env

# Edit .env file with your settings:
# DB_HOST=localhost
# DB_USERNAME=root
# DB_PASSWORD=
# DB_NAME=event_management
```

### 4. Database Setup

#### Option A: Web Installer (Easiest)
1. Start your web server (XAMPP or `php -S localhost:8000`)
2. Visit: http://localhost:8000/install.php
3. Fill in database credentials
4. Click "Install Application"

#### Option B: Manual Database Setup
```bash
# Start MySQL
# For XAMPP: Use XAMPP Control Panel
# For standalone: net start mysql

# Create database
mysql -u root -p
CREATE DATABASE event_management;
exit

# Import schema
mysql -u root -p event_management < database/schema.sql
```

### 5. Start the Application
```bash
# Method 1: Using built-in PHP server
php -S localhost:8000

# Method 2: Using start script
start.bat

# Method 3: Using XAMPP
# Just start Apache in XAMPP Control Panel
```

## Testing Checklist

### ✅ Phase 1: Basic Setup
- [ ] PHP is installed and accessible
- [ ] MySQL is installed and running
- [ ] Composer dependencies are installed
- [ ] .env file is configured
- [ ] Database is created and schema imported
- [ ] Web server is running

### ✅ Phase 2: Application Access
- [ ] Homepage loads: http://localhost:8000/
- [ ] No PHP errors in browser or logs
- [ ] CSS and JavaScript assets load correctly
- [ ] Database connection is successful

### ✅ Phase 3: Web Installer
- [ ] Installer page loads: http://localhost:8000/install.php
- [ ] Database connection test passes
- [ ] Schema installation completes
- [ ] Admin user is created
- [ ] Configuration files are generated

### ✅ Phase 4: User Authentication
- [ ] Login page loads: http://localhost:8000/login.php
- [ ] Admin login works with created credentials
- [ ] User registration works: http://localhost:8000/signup.php
- [ ] Session management functions correctly
- [ ] Dashboard loads after login: http://localhost:8000/dashboard.php

### ✅ Phase 5: Event Management
- [ ] Create event page loads: http://localhost:8000/createevent.php
- [ ] Event creation form works
- [ ] File upload for thumbnails works
- [ ] Events display in dashboard
- [ ] Event editing functions work
- [ ] Event publishing/unpublishing works

### ✅ Phase 6: Registration System
- [ ] Event page loads: http://localhost:8000/eventpage.php?id=1
- [ ] Single participant registration works
- [ ] Team registration works (for team events)
- [ ] Registration data is saved to database

### ✅ Phase 7: Payment System (Optional)
- [ ] Stripe configuration is set up
- [ ] Payment form loads correctly
- [ ] Test payment processing works
- [ ] Payment confirmation pages work

### ✅ Phase 8: Additional Features
- [ ] Gallery upload works: http://localhost:8000/gallery.php
- [ ] Feedback system works
- [ ] Reports generation works
- [ ] Notification system works

## Troubleshooting Common Issues

### 1. "Database connection failed"
**Solution:**
- Check MySQL service is running
- Verify credentials in .env file
- Ensure database exists
- Check firewall/port 3306

### 2. "PHP not found" or "Command not found"
**Solution:**
```bash
# Add PHP to PATH (Windows)
# Add to System Environment Variables:
C:\xampp\php
# OR
C:\Program Files\PHP

# Restart command prompt/PowerShell
```

### 3. "Composer not found"
**Solution:**
```bash
# Download Composer installer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"

# Use local composer
php composer.phar install
```

### 4. "Permission denied" or file upload issues
**Solution:**
```bash
# Windows: Right-click folders → Properties → Security → Edit
# Grant write permissions to:
mkdir images gallery uploads
# Ensure IIS_IUSRS or Everyone has write access
```

### 5. "500 Internal Server Error"
**Solution:**
- Check PHP error logs
- Enable error display in development:
```php
// Add to top of index.php temporarily:
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### 6. CSS/JS not loading
**Solution:**
- Check file paths in HTML
- Verify web server document root
- Clear browser cache
- Check file permissions

## Performance Testing

### Load Testing Commands
```bash
# Test basic page load
curl -I http://localhost:8000/

# Test login endpoint
curl -X POST http://localhost:8000/log_in.php -d "username=admin&password=yourpassword"

# Monitor PHP processes
# Windows: Task Manager → Details → php.exe
# Linux: ps aux | grep php
```

### Database Performance
```sql
-- Check database status
SHOW STATUS LIKE 'Connections';
SHOW STATUS LIKE 'Threads_connected';

-- Check slow queries
SHOW VARIABLES LIKE 'slow_query_log';
SHOW STATUS LIKE 'Slow_queries';
```

## Security Verification

### Security Checklist
- [ ] Database credentials not hardcoded
- [ ] .env file not accessible via web
- [ ] File upload restrictions in place
- [ ] SQL injection protection (prepared statements)
- [ ] Session security configured
- [ ] HTTPS enabled (for production)

### Security Tests
```bash
# Test directory listing (should be disabled)
curl http://localhost:8000/images/

# Test .env access (should be blocked)
curl http://localhost:8000/.env

# Test SQL injection (should be prevented)
# Try malicious inputs in forms
```

## Production Deployment Notes

### Before Going Live:
1. Set `APP_ENV=production` in .env
2. Disable error display
3. Enable HTTPS/SSL
4. Set strong database passwords
5. Configure proper file permissions
6. Set up regular backups
7. Configure monitoring

### Performance Optimization:
1. Enable PHP OpCache
2. Configure MySQL query cache
3. Implement Redis/Memcached
4. Use CDN for static assets
5. Enable Gzip compression

## Maintenance Commands

```bash
# Backup database
mysqldump -u username -p event_management > backup_$(date +%Y%m%d).sql

# Clear logs
# Windows: del /Q logs\*.log
# Linux: rm -f logs/*.log

# Update dependencies
composer update

# Check for security updates
composer audit
```

## Contact and Support

If you encounter issues:
1. Check the troubleshooting section above
2. Review PHP error logs
3. Check MySQL error logs
4. Verify all prerequisites are installed
5. Ensure file permissions are correct

For additional help, refer to:
- PHP Documentation: https://www.php.net/docs.php
- MySQL Documentation: https://dev.mysql.com/doc/
- Composer Documentation: https://getcomposer.org/doc/