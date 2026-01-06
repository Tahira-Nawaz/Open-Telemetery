<?php
use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\Exporter\OTLP\SpanExporter;

// Initialize OTLP exporter
$exporter = new SpanExporter([
    'endpoint' => 'http://localhost:4317' // Replace with your collector endpoint if needed
]);

// Create TracerProvider with a simple span processor
$tracerProvider = new TracerProvider(
    new SimpleSpanProcessor($exporter)
);

// Get a tracer instance
$tracer = $tracerProvider->getTracer('slim-dice-tracer');
