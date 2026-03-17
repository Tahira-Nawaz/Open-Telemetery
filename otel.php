<?php
require 'vendor/autoload.php';

use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\Exporter\Otlp\OtlpHttpExporter;



// Create exporter to send telemetry to Application Insights
$exporter = new OtlpHttpExporter(
    endpoint: 'https://westus2-2.in.applicationinsights.azure.com/v1/traces',
    headers: [
        'InstrumentationKey' => '6129d3c2-30b9-47eb-97ca-59a04b4c9b46'
    ]
);

$tracerProvider = new TracerProvider(
    spanProcessor: new SimpleSpanProcessor($exporter)
);

$tracer = $tracerProvider->getTracer('tahira-app-tracer');

// Example trace
$span = $tracer->startAndActivateSpan('HomepageRequest');
// Simulate some work
usleep(100000); // 0.1 seconds
$span->end();

echo "Telemetry sent to Azure Application Insights! app-test";