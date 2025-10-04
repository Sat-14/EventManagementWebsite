<?php
// Include main configuration
require_once __DIR__ . '/../config/config.php';

// Stripe Configuration
// Note: Replace these with your actual Stripe keys from your Stripe Dashboard
define('STRIPE_PUBLISHABLE_KEY_CONFIG', STRIPE_PUBLISHABLE_KEY);
define('STRIPE_SECRET_KEY_CONFIG', STRIPE_SECRET_KEY);

// Validate Stripe configuration
if (empty(STRIPE_PUBLISHABLE_KEY) || empty(STRIPE_SECRET_KEY)) {
    if (APP_ENV === 'development') {
        throw new Exception('Stripe keys are not configured. Please set STRIPE_PUBLISHABLE_KEY and STRIPE_SECRET_KEY in your .env file');
    } else {
        error_log('Stripe configuration missing');
    }
}

// Currency settings
define('STRIPE_CURRENCY', 'usd');
define('STRIPE_COUNTRY', 'US');

// Payment success and cancel URLs
define('STRIPE_SUCCESS_URL', APP_URL . '/success.php');
define('STRIPE_CANCEL_URL', APP_URL . '/cancel.php');
?>