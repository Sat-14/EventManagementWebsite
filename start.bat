@echo off
echo Event Management Website - Quick Start Script
echo ============================================
echo.

REM Set PHP path from XAMPP
set PHP_PATH=C:\xampp\php\php.exe
set MYSQL_PATH=C:\xampp\mysql\bin\mysql.exe

REM Check if XAMPP PHP exists
if exist "%PHP_PATH%" (
    echo Found PHP in XAMPP
    set PHP_CMD=%PHP_PATH%
) else (
    REM Try to find PHP in PATH
    php --version >nul 2>&1
    if errorlevel 1 (
        echo PHP is not installed or not in PATH
        echo Please install XAMPP or PHP first
        echo.
        pause
        exit /b 1
    )
    set PHP_CMD=php
)

REM Check if MySQL is running
tasklist /fi "imagename eq mysqld.exe" 2>NUL | find /i /n "mysqld.exe">NUL
if "%ERRORLEVEL%"=="1" (
    echo MySQL is not running. Please start MySQL service first.
    echo You can start it from:
    echo - XAMPP Control Panel if using XAMPP
    echo - Services.msc if using standalone MySQL
    echo - Or run: net start mysql
    echo.
    pause
    exit /b 1
)

REM Check if .env file exists
if not exist ".env" (
    echo .env file not found. Creating from template...
    if exist ".env.example" (
        copy .env.example .env
        echo .env file created. Please edit it with your database credentials.
    ) else (
        echo .env.example not found. Please create .env file manually.
    )
    echo.
    pause
)

REM Check if composer dependencies are installed
if not exist "vendor" (
    echo Installing Composer dependencies...
    composer install
    if errorlevel 1 (
        echo Failed to install dependencies. Please run 'composer install' manually.
        pause
        exit /b 1
    )
)

REM Create necessary directories
if not exist "images" mkdir images
if not exist "gallery" mkdir gallery
if not exist "uploads" mkdir uploads

echo.
echo Starting PHP development server...
echo Application will be available at: http://localhost:8000
echo.
echo Web installer available at: http://localhost:8000/install.php
echo Admin login at: http://localhost:8000/login.php
echo.
echo Press Ctrl+C to stop the server
echo.

REM Start PHP built-in server
%PHP_CMD% -S localhost:8000