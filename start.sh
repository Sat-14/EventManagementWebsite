#!/bin/bash

echo "Event Management Website - Quick Start Script"
echo "============================================"
echo ""

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "PHP is not installed. Please install PHP first:"
    echo "Ubuntu/Debian: sudo apt install php8.1"
    echo "CentOS/RHEL: sudo yum install php"
    echo "macOS: brew install php"
    echo ""
    exit 1
fi

# Check if MySQL is running
if ! pgrep -x "mysqld" > /dev/null; then
    echo "MySQL is not running. Please start MySQL service first:"
    echo "Ubuntu/Debian: sudo systemctl start mysql"
    echo "CentOS/RHEL: sudo systemctl start mysqld"
    echo "macOS: brew services start mysql"
    echo ""
    exit 1
fi

# Check if .env file exists
if [ ! -f ".env" ]; then
    echo ".env file not found. Creating from template..."
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo ".env file created. Please edit it with your database credentials."
    else
        echo ".env.example not found. Please create .env file manually."
    fi
    echo ""
    read -p "Press Enter to continue..."
fi

# Check if composer dependencies are installed
if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install
    if [ $? -ne 0 ]; then
        echo "Failed to install dependencies. Please run 'composer install' manually."
        exit 1
    fi
fi

# Create necessary directories
mkdir -p images gallery uploads

# Set permissions (Linux/macOS)
chmod 755 images gallery uploads

echo ""
echo "Starting PHP development server..."
echo "Application will be available at: http://localhost:8000"
echo ""
echo "Web installer available at: http://localhost:8000/install.php"
echo "Admin login at: http://localhost:8000/login.php"
echo ""
echo "Press Ctrl+C to stop the server"
echo ""

# Start PHP built-in server
php -S localhost:8000