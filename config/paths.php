<?php
/**
 * Path Configuration
 * Define all application paths and URL helpers
 */

// Base paths
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

define('CONFIG_PATH', ROOT_PATH . '/config');
define('VIEW_PATH', ROOT_PATH . '/app/views');
define('AUTH_PATH', ROOT_PATH . '/auth');
define('ADMIN_PATH', ROOT_PATH . '/admin');
define('API_PATH', ROOT_PATH . '/api');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Public asset paths (relative to webroot)
define('ASSETS_PATH', 'public/assets');
define('CSS_PATH', 'public/css');
define('IMAGES_PATH', 'public/images');
define('JS_PATH', 'public/js');
define('SLICK_PATH', 'public/slick');
define('GALLERY_PATH', 'public/gallery');
define('UPLOADS_PATH', 'public/uploads');

/**
 * Helper function to get asset URL
 * @param string $path Path relative to public directory
 * @return string Full URL to asset
 */
function asset($path) {
    // Remove leading slash if present
    $path = ltrim($path, '/');

    // If path starts with known public directories, add public/ prefix
    $publicDirs = ['assets', 'css', 'images', 'js', 'slick', 'gallery', 'uploads'];
    $firstDir = explode('/', $path)[0];

    if (in_array($firstDir, $publicDirs)) {
        return 'public/' . $path;
    }

    return $path;
}

/**
 * Helper function to get URL to a page
 * @param string $page Page name
 * @return string URL to page
 */
function url($page = '') {
    $base = '';
    if (!empty($page)) {
        return $base . $page;
    }
    return $base;
}

/**
 * Include a config file
 * @param string $file Filename without .php extension
 */
function config($file) {
    require_once CONFIG_PATH . '/' . $file . '.php';
}
?>
