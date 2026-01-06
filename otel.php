<?php

require __DIR__ . '/vendor/autoload.php';

use OpenTelemetry\SDK\Sdk;

// Build SDK using environment variables
$sdk = Sdk::builder()->build();

$tracer = $sdk->getTracerProvider()->getTracer('azure-php-app');

// Test span
$span = $tracer->spanBuilder('startup-span')->startSpan();
$span->end();
