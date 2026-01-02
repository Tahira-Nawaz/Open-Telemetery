<?php
require __DIR__ . '/vendor/autoload.php';

use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\Exporter\Otlp\OtlpHttpExporter;

// Resource info
$resource = ResourceInfo::create([
    'service.name' => 'php-web-app'
]);

// OTLP Exporter pointing to your collector VM
$exporter = new OtlpHttpExporter(
    endpoint: 'http://4.154.175.112:4318/v1/traces'
);

// Span processor
$spanProcessor = new SimpleSpanProcessor($exporter);

// Tracer provider
$tracerProvider = new TracerProvider(
    $spanProcessor,
    $resource
);

// Get tracer
$tracer = $tracerProvider->getTracer('php-tracer');
