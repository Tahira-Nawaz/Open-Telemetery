<?php
require __DIR__ . '/vendor/autoload.php';

use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\Exporter\Otlp\OtlpHttpExporter;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Trace\TracerProvider;

// Define resource info (service name)
$resource = ResourceInfo::create([
    'service.name' => 'php-web-app'
]);

// Set the OTLP endpoint, default to your collector IP if env var not set
$endpoint = getenv('OTEL_EXPORTER_OTLP_ENDPOINT') ?: 'http://4.154.175.112:4318';

// Create OTLP HTTP exporter
$exporter = new OtlpHttpExporter(
    endpoint: $endpoint
);

// Create span processor
$spanProcessor = new SimpleSpanProcessor($exporter);

// Create tracer provider
$tracerProvider = new TracerProvider(
    $spanProcessor,
    $resource
);

// Get tracer
$tracer = $tracerProvider->getTracer('php-tracer');
