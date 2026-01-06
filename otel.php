<?php

require __DIR__ . '/vendor/autoload.php';

use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Trace\SpanExporter\OtlpHttpSpanExporterFactory;
use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SemConv\ResourceAttributes;

// Create exporter using SDK factory (AUTOLOADED)
$exporter = (new OtlpHttpSpanExporterFactory())->create([
    'endpoint' => 'http://127.0.0.1:4318/v1/traces',
]);

$tracerProvider = new TracerProvider(
    spanProcessors: [
        new SimpleSpanProcessor($exporter),
    ],
    resource: ResourceInfo::create([
        ResourceAttributes::SERVICE_NAME => 'azure-php-app',
    ])
);

$tracer = $tracerProvider->getTracer('app-tracer');

// Test span
$span = $tracer->spanBuilder('startup-span')->startSpan();
$span->end();
