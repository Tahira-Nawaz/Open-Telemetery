<?php
require 'vendor/autoload.php';

use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\Exporter\Otlp\OtlpHttpExporter;

// Replace with your Azure Connection String
$connectionString = 'InstrumentationKey=6129d3c2-30b9-47eb-97ca-59a04b4c9b46;IngestionEndpoint=https://westus2-2.in.applicationinsights.azure.com/;LiveEndpoint=https://westus2.livediagnostics.monitor.azure.com/;ApplicationId=ea4df62a-5d2d-498d-a5c4-687a0ec9dd44';

// Create exporter to send telemetry to Application Insights
$exporter = new OtlpHttpExporter(
    endpoint: $connectionString
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

echo "Telemetry sent to Azure Application Insights!";