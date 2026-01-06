<?php
// index.php
require_once __DIR__ . '/otel.php';

// Get the global tracer initialized in otel.php
// Alternatively, use the global TracerProvider if configured as global
use OpenTelemetry\API\Instrumentation\CachedInstrumentation;

$instrumentation = new CachedInstrumentation('my-app-instrumentation');
$tracer = $instrumentation->getTracer();

// Start a new trace span for the request
$span = $tracer->spanBuilder('http.server.request')
    ->setAttribute('http.method', $_SERVER['REQUEST_METHOD'])
    ->setAttribute('http.url', (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]")
    ->startSpan();

// Set the span as the active span
$scope = $span->activate();

try {
    // Your application logic goes here
    echo "Hello from Azure Web App with OpenTelemetry!";

    // Add an event or log something
    $span->addEvent('Application logic executed');

    // Simulate some work
    usleep(50000);

} finally {
    // End the span and the scope
    $scope->detach();
    $span->end();

    // Ensure all telemetry is exported (important for short-lived scripts)
    // This can be handled by a shutdown function as well.
    // The SDK typically handles this if run() is called, but manual flushing is safe.
    // $sdk->forceFlush(); 
}
?>
