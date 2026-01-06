<?php
// index.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "PHP reached index.php<br>";

// 1. Load Autoloader
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
} else {
    die("Autoloader not found. Run 'composer install' in the project root.");
}

// 2. Load OTEL setup
require __DIR__ . '/otel.php';

echo "Hello, Tahira! This is a test..<br>";
