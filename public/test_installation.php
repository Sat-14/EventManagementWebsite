<?php
/**
 * Installation Test Script
 * Run this to verify your Event Management Website setup
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Event Management Website - Installation Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test { margin: 10px 0; padding: 10px; border-radius: 5px; }
        .pass { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .fail { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .warning { background-color: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
        .info { background-color: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
        h1, h2 { color: #333; }
        .summary { margin-top: 20px; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>";

echo "<h1>Event Management Website - Installation Test</h1>";

$tests = [];
$errors = [];

// Test 1: PHP Version
echo "<h2>1. PHP Environment Tests</h2>";
$phpVersion = PHP_VERSION;
if (version_compare($phpVersion, '7.4.0', '>=')) {
    echo "<div class='test pass'>✓ PHP Version: $phpVersion (Compatible)</div>";
    $tests['php_version'] = true;
} else {
    echo "<div class='test fail'>✗ PHP Version: $phpVersion (Requires 7.4+)</div>";
    $tests['php_version'] = false;
    $errors[] = "PHP version too old";
}

// Test 2: Required Extensions
$extensions = ['mysqli', 'gd', 'curl', 'mbstring', 'json'];
foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<div class='test pass'>✓ Extension '$ext' is loaded</div>";
        $tests["ext_$ext"] = true;
    } else {
        echo "<div class='test fail'>✗ Extension '$ext' is missing</div>";
        $tests["ext_$ext"] = false;
        $errors[] = "Missing extension: $ext";
    }
}

// Test 3: File Permissions
echo "<h2>2. File System Tests</h2>";
$directories = ['images', 'gallery', 'uploads'];
foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    if (is_writable($dir)) {
        echo "<div class='test pass'>✓ Directory '$dir' is writable</div>";
        $tests["dir_$dir"] = true;
    } else {
        echo "<div class='test fail'>✗ Directory '$dir' is not writable</div>";
        $tests["dir_$dir"] = false;
        $errors[] = "Directory '$dir' not writable";
    }
}

// Test 4: Configuration Files
echo "<h2>3. Configuration Tests</h2>";
if (file_exists('.env')) {
    echo "<div class='test pass'>✓ .env file exists</div>";
    $tests['env_file'] = true;
} else {
    echo "<div class='test warning'>⚠ .env file missing (will use defaults)</div>";
    $tests['env_file'] = false;
}

if (file_exists('config.php')) {
    echo "<div class='test pass'>✓ config.php file exists</div>";
    $tests['config_file'] = true;
} else {
    echo "<div class='test fail'>✗ config.php file missing</div>";
    $tests['config_file'] = false;
    $errors[] = "config.php file missing";
}

// Test 5: Database Connection
echo "<h2>4. Database Tests</h2>";
try {
    if (file_exists('config.php')) {
        include __DIR__ . '/../config/config.php';
    }

    $db_host = defined('DB_HOST') ? DB_HOST : 'localhost';
    $db_username = defined('DB_USERNAME') ? DB_USERNAME : 'root';
    $db_password = defined('DB_PASSWORD') ? DB_PASSWORD : '';
    $db_name = defined('DB_NAME') ? DB_NAME : 'event_management';

    $conn = new mysqli($db_host, $db_username, $db_password);

    if ($conn->connect_error) {
        echo "<div class='test fail'>✗ Database connection failed: " . $conn->connect_error . "</div>";
        $tests['db_connection'] = false;
        $errors[] = "Database connection failed";
    } else {
        echo "<div class='test pass'>✓ Database connection successful</div>";
        $tests['db_connection'] = true;

        // Test database selection
        if ($conn->select_db($db_name)) {
            echo "<div class='test pass'>✓ Database '$db_name' exists and accessible</div>";
            $tests['db_exists'] = true;

            // Test tables
            $tables = ['sign_up', 'create_event', 'singleevent_registration', 'feedback'];
            $table_count = 0;
            foreach ($tables as $table) {
                $result = $conn->query("SHOW TABLES LIKE '$table'");
                if ($result && $result->num_rows > 0) {
                    $table_count++;
                }
            }

            if ($table_count == count($tables)) {
                echo "<div class='test pass'>✓ All required database tables exist</div>";
                $tests['db_tables'] = true;
            } else {
                echo "<div class='test warning'>⚠ Some database tables missing ($table_count/" . count($tables) . " found)</div>";
                $tests['db_tables'] = false;
            }
        } else {
            echo "<div class='test warning'>⚠ Database '$db_name' does not exist</div>";
            $tests['db_exists'] = false;
        }
        $conn->close();
    }
} catch (Exception $e) {
    echo "<div class='test fail'>✗ Database test failed: " . $e->getMessage() . "</div>";
    $tests['db_connection'] = false;
    $errors[] = "Database test failed: " . $e->getMessage();
}

// Test 6: Composer Dependencies
echo "<h2>5. Dependency Tests</h2>";
if (file_exists('vendor/autoload.php')) {
    echo "<div class='test pass'>✓ Composer dependencies installed</div>";
    $tests['composer'] = true;
} else {
    echo "<div class='test warning'>⚠ Composer dependencies not installed (run 'composer install')</div>";
    $tests['composer'] = false;
}

// Test 7: Stripe Configuration
echo "<h2>6. Payment System Tests</h2>";
$stripe_pub = defined('STRIPE_PUBLISHABLE_KEY') ? STRIPE_PUBLISHABLE_KEY : '';
$stripe_secret = defined('STRIPE_SECRET_KEY') ? STRIPE_SECRET_KEY : '';

if (!empty($stripe_pub) && !empty($stripe_secret)) {
    echo "<div class='test pass'>✓ Stripe configuration found</div>";
    $tests['stripe_config'] = true;
} else {
    echo "<div class='test info'>ℹ Stripe not configured (optional for testing)</div>";
    $tests['stripe_config'] = null;
}

// Test 8: Web Server
echo "<h2>7. Web Server Tests</h2>";
if (isset($_SERVER['SERVER_SOFTWARE'])) {
    echo "<div class='test pass'>✓ Web server: " . $_SERVER['SERVER_SOFTWARE'] . "</div>";
    $tests['web_server'] = true;
} else {
    echo "<div class='test pass'>✓ PHP built-in server detected</div>";
    $tests['web_server'] = true;
}

if (isset($_SERVER['HTTP_HOST'])) {
    $url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
    echo "<div class='test info'>ℹ Application URL: <a href='$url'>$url</a></div>";
}

// Summary
echo "<h2>Test Summary</h2>";
$passed = count(array_filter($tests, function($v) { return $v === true; }));
$failed = count(array_filter($tests, function($v) { return $v === false; }));
$total = count(array_filter($tests, function($v) { return $v !== null; }));

if ($failed == 0) {
    echo "<div class='summary pass'>";
    echo "<h3>✓ All Tests Passed ($passed/$total)</h3>";
    echo "<p>Your Event Management Website is ready to use!</p>";
    echo "<p><strong>Next Steps:</strong></p>";
    echo "<ul>";
    echo "<li><a href='install.php'>Run Web Installer</a> (if database tables are missing)</li>";
    echo "<li><a href='index.php'>Visit Homepage</a></li>";
    echo "<li><a href='login.php'>Admin Login</a></li>";
    echo "<li><a href='dashboard.php'>Dashboard</a> (after login)</li>";
    echo "</ul>";
    echo "</div>";
} else {
    echo "<div class='summary fail'>";
    echo "<h3>✗ Some Tests Failed ($passed passed, $failed failed)</h3>";
    echo "<p><strong>Issues to fix:</strong></p>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
    echo "<p><strong>Recommended Actions:</strong></p>";
    echo "<ul>";
    echo "<li>Install missing PHP extensions</li>";
    echo "<li>Set up database and import schema</li>";
    echo "<li>Fix file permissions</li>";
    echo "<li>Run 'composer install' for dependencies</li>";
    echo "</ul>";
    echo "</div>";
}

// Additional Information
echo "<h2>System Information</h2>";
echo "<div class='test info'>";
echo "<strong>PHP Version:</strong> " . PHP_VERSION . "<br>";
echo "<strong>Operating System:</strong> " . PHP_OS . "<br>";
echo "<strong>Server API:</strong> " . php_sapi_name() . "<br>";
echo "<strong>Memory Limit:</strong> " . ini_get('memory_limit') . "<br>";
echo "<strong>Upload Max Size:</strong> " . ini_get('upload_max_filesize') . "<br>";
echo "<strong>Post Max Size:</strong> " . ini_get('post_max_size') . "<br>";
echo "<strong>Max Execution Time:</strong> " . ini_get('max_execution_time') . "s<br>";
echo "</div>";

echo "</body></html>";
?>