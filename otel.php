<?php
// Ensure the autoloader is included at the very top
require_once __DIR__ . '/vendor/autoload.php';

use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Trace\Sampler\AlwaysOnSampler;
// CORRECTED NAMESPACE: Note the inclusion of \Contrib\
use OpenTelemetry\Contrib\Otlp\SpanExporter;
use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SDK\Common\Attribute\Attributes;
use OpenTelemetry\SemConv\ResourceAttributes;
use OpenTelemetry\SDK\Common\Export\TransportFactoryInterface;
use OpenTelemetry\Contrib\Otlp\OtlpHttpTransportFactory;

// Resource info for the application
$resource = ResourceInfo::create(
    new Attributes([
        ResourceAttributes::SERVICE_NAME => 'php-otel-test'
    ])
);

// OTLP HTTP Exporter setup
// Note: Transport is required for newer SDK versions
$transport = (new OtlpHttpTransportFactory())->create('4.154.175.112', 'application/x-protobuf');
$exporter = new SpanExporter($transport);

// TracerProvider configuration
$tracerProvider = new TracerProvider(
    new SimpleSpanProcessor($exporter),
    new AlwaysOnSampler(),
    $resource
);

// Example span creation
$tracer = $tracerProvider->getTracer('test-tracer');
$span = $tracer->spanBuilder('test-span')->startSpan();

// Always end spans to ensure they are sent
$span->end();

// Ensure the provider is shut down to flush remaining spans
$tracerProvider->shutdown();
