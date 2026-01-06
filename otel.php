<?php
// otel.php
require_once __DIR__ . '/vendor/autoload.php';

use OpenTelemetry\SDK\Sdk;
use OpenTelemetry\SDK\Trace\SpanExporter\OtlpHttpSpanExporterFactory;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SemConv\ResourceAttributes;

// Initialize the OpenTelemetry SDK
$sdk = Sdk::builder()
    ->withSpanProcessor(new SimpleSpanProcessor(
        (new OtlpHttpSpanExporterFactory())->create()
    ))
    ->withResource(ResourceInfo::create(
        attributes: [
            ResourceAttributes::SERVICE_NAME => 'my-azure-php-app',
            ResourceAttributes::DEPLOYMENT_ENVIRONMENT => 'azure-web-app',
        ]
    ))
    ->build();

// Set global a tracer provider
$tracer = $sdk->getTracerProvider()->getTracer('my-app-tracer');

// Optional: you can set environment variables in Azure Web App settings 
// to configure the OTLP exporter, e.g., OTLP_ENDPOINT
// $sdk->run(); // might be useful depending on your setup
?>
