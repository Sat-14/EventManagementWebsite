<?php
// Load environment variables from .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Load .env file from project root
loadEnv(dirname(__DIR__) . '/.env');

// Load paths configuration
require_once __DIR__ . '/paths.php';

// Database Configuration
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USERNAME', getenv('DB_USERNAME') ?: 'event_user');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'event_management');

// Stripe Configuration
define('STRIPE_PUBLISHABLE_KEY', getenv('STRIPE_PUBLISHABLE_KEY') ?: '');
define('STRIPE_SECRET_KEY', getenv('STRIPE_SECRET_KEY') ?: '');

// Application Configuration
define('APP_URL', getenv('APP_URL') ?: 'http://localhost:8000');
define('APP_ENV', getenv('APP_ENV') ?: 'development');

// Security Configuration
// Note: Session settings should be configured in php.ini for production
// These are commented out to prevent "headers already sent" warnings
// Uncomment only if config.php is loaded BEFORE any output

// if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
//     @ini_set('session.cookie_secure', getenv('SESSION_SECURE') ?: '0');
//     @ini_set('session.cookie_httponly', getenv('SESSION_HTTPONLY') ?: '1');
//     @ini_set('session.cookie_samesite', getenv('SESSION_SAMESITE') ?: 'Strict');
// }
?>
