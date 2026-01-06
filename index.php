<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/otel.php';

echo "<h2>PHP OpenTelemetry Demo</h2>";

$span = $tracer->spanBuilder('dummy-processing')->startSpan();

try {
    echo "<p>Processing started</p>";
    sleep(1);
    echo "<p>Processing completed</p>";
} finally {
    $span->end();
}

echo "<p>Request completed successfully</p>";
?>
