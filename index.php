<?php
// Enable PHP errors for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Confirm PHP reached index
echo "PHP reached index.php<br>";

// Load Composer autoloader
require __DIR__ . '/vendor/autoload.php';

// Load OTEL setup
require __DIR__ . '/otel.php';

// Print test message
echo "Hello, Tahira! This is a test<br>";
