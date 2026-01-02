<?php
// Enable PHP errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Test PHP execution
echo "PHP reached index.php<br>";

// Test OTEL inclusion (optional)
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/otel.php';

// Print your name as test
echo "Hello, Tahira! This is a test<br>";
