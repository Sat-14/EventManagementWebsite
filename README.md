# Event Management Website

A comprehensive PHP-based Event Management System that allows users to create, manage, and register for events with integrated payment processing through Stripe.

## Features

- **User Management**: Registration, authentication, and role-based access control
- **Event Management**: Create, edit, delete, and publish events
- **Registration System**: Support for both individual and team registrations
- **Payment Processing**: Integrated Stripe payment gateway
- **Gallery Management**: Upload and manage event photos
- **Feedback System**: Event reviews and ratings
- **Dashboard**: Comprehensive admin and user dashboards
- **Responsive Design**: Mobile-friendly interface using Bootstrap

## Requirements

- **PHP**: 7.4 or higher (recommended: 8.0+)
- **MySQL**: 5.7 or higher (or MariaDB 10.2+)
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Composer**: For dependency management
- **Extensions**: mysqli, gd, curl, mbstring, zip

## Installation

### Windows Installation

#### Step 1: Install Required Software

**Option A: Using Chocolatey (Recommended)**
```bash
# Open PowerShell as Administrator and run:
choco install php --version=8.2.12 -y
choco install mysql -y
choco install composer -y
choco install apache-httpd -y
```

**Option B: Using winget**
```bash
# Open PowerShell as Administrator and run:
winget install PHP.PHP
winget install Oracle.MySQL
winget install Composer.Composer
winget install Apache.HTTPD
```

**Option C: Manual Installation**
1. Download and install [XAMPP](https://www.apachefriends.org/download.html) (includes PHP, MySQL, Apache)
2. Download and install [Composer](https://getcomposer.org/download/)

#### Step 2: Clone the Repository
```bash
cd C:\xampp\htdocs  # or your web root directory
git clone https://github.com/yourusername/EventManagementWebsite.git
cd EventManagementWebsite
```

#### Step 3: Install Dependencies
```bash
composer install
```

#### Step 4: Configure Environment
1. Copy the environment example file:
   ```bash
   copy .env.example .env
   ```
2. Edit `.env` file with your configuration:
   ```env
   DB_HOST=localhost
   DB_USERNAME=your_db_username
   DB_PASSWORD=your_db_password
   DB_NAME=event_management

   STRIPE_PUBLISHABLE_KEY=pk_test_your_publishable_key
   STRIPE_SECRET_KEY=sk_test_your_secret_key
   ```

#### Step 5: Database Setup

**Option A: Using the Web Installer (Recommended)**
1. Start your web server and MySQL
2. Open browser and navigate to: `http://localhost/EventManagementWebsite/install.php`
3. Fill in the installation form with your database credentials
4. Click "Install Application"

**Option B: Manual Database Setup**
1. Start MySQL service
2. Create database:
   ```sql
   CREATE DATABASE event_management;
   ```
3. Import the schema:
   ```bash
   mysql -u your_username -p event_management < database/schema.sql
   ```

#### Step 6: Set Directory Permissions
```bash
# For Windows, ensure these directories are writable:
mkdir images gallery uploads
# Right-click each folder → Properties → Security → Edit → Add write permissions
```

#### Step 7: Configure Web Server

**For XAMPP:**
1. Start Apache and MySQL from XAMPP Control Panel
2. Access the application at: `http://localhost/EventManagementWebsite`

**For Standalone Apache:**
1. Edit `httpd.conf` to point to your project directory
2. Restart Apache service
3. Access the application at: `http://localhost`

### Linux Installation

#### Step 1: Install Required Software

**Ubuntu/Debian:**
```bash
sudo apt update
sudo apt install php8.1 php8.1-mysql php8.1-gd php8.1-curl php8.1-mbstring php8.1-zip
sudo apt install mysql-server apache2
sudo apt install composer
```

**CentOS/RHEL:**
```bash
sudo yum install php php-mysql php-gd php-curl php-mbstring
sudo yum install mysql-server httpd
sudo yum install composer
```

#### Step 2: Clone and Setup
```bash
cd /var/www/html
sudo git clone https://github.com/yourusername/EventManagementWebsite.git
cd EventManagementWebsite
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
composer install
```

#### Step 3: Configure Environment
```bash
cp .env.example .env
nano .env  # Edit with your configuration
```

#### Step 4: Database Setup
```bash
sudo mysql
CREATE DATABASE event_management;
CREATE USER 'event_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON event_management.* TO 'event_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

mysql -u event_user -p event_management < database/schema.sql
```

#### Step 5: Set Permissions
```bash
sudo mkdir -p images gallery uploads
sudo chown -R www-data:www-data images gallery uploads
sudo chmod -R 755 images gallery uploads
```

## Quick Start Guide

### 1. Access the Application
- Open your browser and go to: `http://localhost/EventManagementWebsite`
- Or if using the web installer: `http://localhost/EventManagementWebsite/install.php`

### 2. Initial Login
- **Admin Username**: admin (or as configured during installation)
- **Admin Password**: As set during installation
- **Login URL**: `http://localhost/EventManagementWebsite/login.php`

### 3. Create Your First Event
1. Login as admin
2. Navigate to Dashboard
3. Click "Create Event"
4. Fill in event details
5. Upload thumbnail image
6. Set registration parameters
7. Publish the event

### 4. Configure Stripe (For Payments)
1. Sign up at [Stripe](https://stripe.com)
2. Get your API keys from Stripe Dashboard
3. Update `.env` file with your Stripe keys
4. Test payment functionality

## Configuration

### Environment Variables

| Variable | Description | Example |
|----------|-------------|---------|
| `DB_HOST` | Database host | `localhost` |
| `DB_USERNAME` | Database username | `event_user` |
| `DB_PASSWORD` | Database password | `your_password` |
| `DB_NAME` | Database name | `event_management` |
| `STRIPE_PUBLISHABLE_KEY` | Stripe public key | `pk_test_...` |
| `STRIPE_SECRET_KEY` | Stripe secret key | `sk_test_...` |
| `APP_URL` | Application URL | `http://localhost:8000` |
| `APP_ENV` | Environment | `development` |

### Web Server Configuration

**Apache Virtual Host Example:**
```apache
<VirtualHost *:80>
    ServerName eventmanagement.local
    DocumentRoot "C:/xampp/htdocs/EventManagementWebsite"
    <Directory "C:/xampp/htdocs/EventManagementWebsite">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Nginx Configuration Example:**
```nginx
server {
    listen 80;
    server_name eventmanagement.local;
    root /var/www/html/EventManagementWebsite;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## Troubleshooting

### Common Issues

**1. Database Connection Failed**
- Check MySQL service is running
- Verify credentials in `.env` file
- Ensure database exists and user has proper permissions

**2. File Upload Issues**
- Check directory permissions for `images/`, `gallery/`, `uploads/`
- Verify PHP upload limits in `php.ini`:
  ```ini
  upload_max_filesize = 20M
  post_max_size = 20M
  ```

**3. Stripe Payment Not Working**
- Verify Stripe keys are correctly set in `.env`
- Check Stripe webhook configuration
- Ensure SSL is enabled for production

**4. Sessions Not Working**
- Check PHP session configuration
- Verify session directory permissions
- Clear browser cookies

### Error Logs
- **Apache**: Check `error.log` in Apache directory
- **PHP**: Enable error reporting in development:
  ```php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  ```
- **MySQL**: Check MySQL error logs

## Development

### Running in Development Mode
```bash
# Using PHP built-in server
php -S localhost:8000

# Or using XAMPP/WAMP
# Access via http://localhost/EventManagementWebsite
```

### File Structure
```
EventManagementWebsite/
├── assets/                 # Frontend assets (CSS, JS, images)
├── database/              # Database schema and migrations
├── images/                # Event thumbnail uploads
├── gallery/               # Gallery image uploads
├── vendor/                # Composer dependencies
├── config.php             # Application configuration
├── dbconnect.php          # Database connection
├── install.php            # Web-based installer
├── index.php              # Homepage
├── login.php              # User authentication
├── dashboard.php          # User dashboard
├── createevent.php        # Event creation
├── eventpage.php          # Event details and registration
└── README.md              # This file
```

### API Endpoints
The application uses traditional PHP pages rather than REST APIs:

- `POST /login.php` - User authentication
- `POST /sign_up.php` - User registration
- `POST /createeventsave.php` - Create new event
- `POST /eventregistration.php` - Event registration
- `POST /stripepayment.php` - Process payments

## Production Deployment

### Security Checklist
- [ ] Change default admin credentials
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Enable HTTPS/SSL
- [ ] Configure proper file permissions
- [ ] Enable PHP opcache
- [ ] Set up regular database backups
- [ ] Configure firewall rules
- [ ] Update Stripe to live keys

### Performance Optimization
- Enable PHP opcache
- Configure MySQL query cache
- Use CDN for static assets
- Implement caching strategies
- Optimize images

## Support and Contributing

### Getting Help
- Check the troubleshooting section above
- Review PHP and MySQL error logs
- Ensure all requirements are met

### Contributing
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is licensed under the MIT License. See the LICENSE file for details.

## Changelog

### Version 1.0.0
- Initial release
- Basic event management functionality
- User registration and authentication
- Stripe payment integration
- Gallery management
- Feedback system

---

For additional support or questions, please refer to the documentation or create an issue in the project repository.