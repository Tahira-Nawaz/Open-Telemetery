<?php
require __DIR__ . '/vendor/autoload.php';

use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\Contrib\OtlpHttp\Exporter as OtlpHttpExporter;


// OTLP Exporter for Application Insights
$exporter = new OtlpHttpExporter(
    endpoint: 'https://westus2-2.otlp.applicationinsights.azure.com/v1/traces',
    headers: [
        'Authorization' => 'InstrumentationKey 6129d3c2-30b9-47eb-97ca-59a04b4c9b46'
    ]
);

$tracerProvider = new TracerProvider(
    spanProcessor: new SimpleSpanProcessor($exporter)
);

$tracer = $tracerProvider->getTracer('tahira-app-tracer');

// Example trace
$span = $tracer->startAndActivateSpan('HomepageRequest');
usleep(100000); // simulate work
$span->end();

echo "Telemetry sent to Azure Application Insights!";