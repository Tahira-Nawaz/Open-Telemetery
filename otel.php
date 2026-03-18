<?php
require __DIR__ . '/vendor/autoload.php';

use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\Contrib\Otlp\OtlpHttpExporter;
use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SDK\Common\Attribute\Attributes;

// 👉 Use your FULL connection string here
$connectionString = "InstrumentationKey=688d2b7d-daea-49b0-9d30-b7d7ba5b02e1;IngestionEndpoint=https://westus2-2.in.applicationinsights.azure.com/;LiveEndpoint=https://westus2.livediagnostics.monitor.azure.com/;ApplicationId=705a3523-c215-4677-95cc-f86d159e2140";

// Azure OTLP endpoint
$endpoint = "https://westus2-2.otlp.applicationinsights.azure.com/v1/traces";

// Correct exporter
$exporter = new OtlpHttpExporter(
    endpoint: $endpoint,
    headers: [
        "Authorization" => "Bearer $connectionString"
    ]
);

// Add service name (important for logs visibility)
$resource = ResourceInfo::create(Attributes::create([
    "service.name" => "tahira-php-app"
]));

$tracerProvider = new TracerProvider(
    new SimpleSpanProcessor($exporter),
    $resource
);

$tracer = $tracerProvider->getTracer("tahira-tracer");

// Start span
$span = $tracer->spanBuilder("HomepageRequest")->startSpan();

usleep(100000);

$span->end();

// IMPORTANT: send data
$tracerProvider->shutdown();

echo "Telemetry sent to Azure Application Insights!";