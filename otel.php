<?php

require_once __DIR__ . '/vendor/autoload.php';

use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\Exporter\Otlp\SpanExporterFactory;
use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SemConv\ResourceAttributes;


// Create an OTLP exporter
$exporter = (new SpanExporterFactory())->create([
    'endpoint' => 'http://127.0.0.1:4318/v1/traces',
]);


// Create a tracer provider with a simple span processor
$tracerProvider = new TracerProvider(
    spanProcessors: [
        new SimpleSpanProcessor($exporter)
    ],
    resource: ResourceInfo::create(
        attributes: [
            ResourceAttributes::SERVICE_NAME => 'my-azure-php-app',
            ResourceAttributes::DEPLOYMENT_ENVIRONMENT => 'azure-web-app',
        ]
    )
);

// Get a tracer
$tracer = $tracerProvider->getTracer('my-app-tracer');

// Optional: start a span
$span = $tracer->spanBuilder('test-span')->startSpan();
$span->end();

echo "OpenTelemetry SDK initialized successfully!";
