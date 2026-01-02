<?php
require __DIR__ . '/vendor/autoload.php';

use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\Exporter\Otlp\OtlpHttpExporter;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Trace\TracerProvider;

$resource = ResourceInfo::create([
    'service.name' => 'php-web-app'
]);

$exporter = new OtlpHttpExporter(
    endpoint: getenv('OTEL_EXPORTER_OTLP_ENDPOINT')
);

$spanProcessor = new SimpleSpanProcessor($exporter);

$tracerProvider = new TracerProvider(
    $spanProcessor,
    $resource
);

$tracer = $tracerProvider->getTracer('php-tracer');
