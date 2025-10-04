<?php
/**
 * Installation Script for Event Management Website
 * This script helps setup the database and initial configuration
 */

// Prevent access if already installed
if (file_exists('.env') && file_exists('installed.lock')) {
    die('Application is already installed. Delete the installed.lock file to reinstall.');
}

$errors = [];
$success = [];

if ($_POST) {
    $db_host = $_POST['db_host'] ?? 'localhost';
    $db_username = $_POST['db_username'] ?? '';
    $db_password = $_POST['db_password'] ?? '';
    $db_name = $_POST['db_name'] ?? '';
    $admin_username = $_POST['admin_username'] ?? 'admin';
    $admin_email = $_POST['admin_email'] ?? '';
    $admin_password = $_POST['admin_password'] ?? '';
    $stripe_publishable = $_POST['stripe_publishable'] ?? '';
    $stripe_secret = $_POST['stripe_secret'] ?? '';

    // Validate inputs
    if (empty($db_username)) $errors[] = 'Database username is required';
    if (empty($db_name)) $errors[] = 'Database name is required';
    if (empty($admin_email)) $errors[] = 'Admin email is required';
    if (empty($admin_password)) $errors[] = 'Admin password is required';

    if (empty($errors)) {
        try {
            // Test database connection
            $conn = new mysqli($db_host, $db_username, $db_password);

            if ($conn->connect_error) {
                $errors[] = "Database connection failed: " . $conn->connect_error;
            } else {
                // Create database if it doesn't exist
                $conn->query("CREATE DATABASE IF NOT EXISTS `$db_name`");
                $conn->select_db($db_name);

                // Read and execute schema
                $schema = file_get_contents('database/schema.sql');
                if ($schema) {
                    // Split queries and execute them
                    $queries = explode(';', $schema);
                    foreach ($queries as $query) {
                        $query = trim($query);
                        if (!empty($query)) {
                            $conn->query($query);
                        }
                    }
                }

                // Create admin user
                $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO sign_up (role, username, full_name, email, password) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE password = ?");
                $stmt->bind_param("ssssss", $role, $admin_username, $admin_username, $admin_email, $hashed_password, $hashed_password);
                $role = 'admin';
                $stmt->execute();

                // Create .env file
                $env_content = "# Database Configuration
DB_HOST=$db_host
DB_USERNAME=$db_username
DB_PASSWORD=$db_password
DB_NAME=$db_name

# Stripe Payment Configuration
STRIPE_PUBLISHABLE_KEY=$stripe_publishable
STRIPE_SECRET_KEY=$stripe_secret

# Application Configuration
APP_URL=http://localhost:8000
APP_ENV=development

# Security
SESSION_SECURE=false
SESSION_HTTPONLY=true
SESSION_SAMESITE=Strict";

                file_put_contents('.env', $env_content);

                // Create directories if they don't exist
                if (!is_dir('images')) mkdir('images', 0755, true);
                if (!is_dir('gallery')) mkdir('gallery', 0755, true);
                if (!is_dir('uploads')) mkdir('uploads', 0755, true);

                // Set permissions
                chmod('images', 0755);
                chmod('gallery', 0755);
                chmod('uploads', 0755);

                // Create installation lock file
                file_put_contents('installed.lock', date('Y-m-d H:i:s'));

                $success[] = 'Installation completed successfully!';
                $success[] = 'Admin username: ' . $admin_username;
                $success[] = 'Admin email: ' . $admin_email;
                $success[] = 'You can now access the application.';

                $conn->close();
            }
        } catch (Exception $e) {
            $errors[] = 'Installation failed: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management Website - Installation</title>
    <link href="public/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .install-container { max-width: 600px; margin: 50px auto; }
        .card { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
    </style>
</head>
<body>
    <div class="container install-container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Event Management Website - Installation</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        <ul class="mb-0">
                            <?php foreach ($success as $msg): ?>
                                <li><?= htmlspecialchars($msg) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <hr>
                        <a href="index.php" class="btn btn-success">Go to Homepage</a>
                        <a href="login.php" class="btn btn-primary">Login as Admin</a>
                    </div>
                <?php else: ?>
                    <form method="POST">
                        <h5>Database Configuration</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="db_host" class="form-label">Database Host</label>
                                    <input type="text" class="form-control" id="db_host" name="db_host" value="localhost" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="db_name" class="form-label">Database Name</label>
                                    <input type="text" class="form-control" id="db_name" name="db_name" value="event_management" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="db_username" class="form-label">Database Username</label>
                                    <input type="text" class="form-control" id="db_username" name="db_username" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="db_password" class="form-label">Database Password</label>
                                    <input type="password" class="form-control" id="db_password" name="db_password">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5>Admin Account</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="admin_username" class="form-label">Admin Username</label>
                                    <input type="text" class="form-control" id="admin_username" name="admin_username" value="admin" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="admin_email" class="form-label">Admin Email</label>
                                    <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="admin_password" class="form-label">Admin Password</label>
                            <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                        </div>

                        <hr>
                        <h5>Stripe Configuration (Optional)</h5>
                        <div class="mb-3">
                            <label for="stripe_publishable" class="form-label">Stripe Publishable Key</label>
                            <input type="text" class="form-control" id="stripe_publishable" name="stripe_publishable" placeholder="pk_test_...">
                            <small class="form-text text-muted">Required for payment processing</small>
                        </div>
                        <div class="mb-3">
                            <label for="stripe_secret" class="form-label">Stripe Secret Key</label>
                            <input type="text" class="form-control" id="stripe_secret" name="stripe_secret" placeholder="sk_test_...">
                            <small class="form-text text-muted">Required for payment processing</small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg">Install Application</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>