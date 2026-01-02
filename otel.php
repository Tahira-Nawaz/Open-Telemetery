<?php
use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Trace\Sampler\AlwaysOnSampler;
use OpenTelemetry\Exporter\OTLP\SpanExporter;
use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SDK\Common\Attribute\Attributes;
use OpenTelemetry\SemConv\ResourceAttributes;

// Exporter pointing to your VM collector
$exporter = new SpanExporter(
    endpoint: 'http://4.154.175.112:4318/v1/traces'
);

// Resource information
$resource = ResourceInfo::create(
    new Attributes([
        ResourceAttributes::SERVICE_NAME => 'php-otel-test'
    ])
);

// TracerProvider setup
$tracerProvider = new TracerProvider(
    new SimpleSpanProcessor($exporter),
    new AlwaysOnSampler(),
    $resource
);

// Create a test span
$tracer = $tracerProvider->getTracer('test-tracer');
$span = $tracer->spanBuilder('test-span')->startSpan();
$span->end();
