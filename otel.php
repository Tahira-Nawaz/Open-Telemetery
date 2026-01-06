<?php
use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\Exporter\OTLP\SpanExporter;

// Initialize OTLP exporter
$exporter = new SpanExporter([
    'endpoint' => 'http://localhost:4317' // change if using real OTLP collector
]);

// Create TracerProvider
$tracerProvider = new TracerProvider(
    new SimpleSpanProcessor($exporter)
);

// Get tracer
$tracer = $tracerProvider->getTracer('slim-dice-tracer');
