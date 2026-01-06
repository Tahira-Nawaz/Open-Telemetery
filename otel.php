<?php

use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\BatchSpanProcessor;
use OpenTelemetry\Exporter\OTLP\SpanExporter;

$exporter = new SpanExporter(
    endpoint: 'http://127.0.0.1:4318/v1/traces'
);

$tracerProvider = new TracerProvider(
    spanProcessor: new BatchSpanProcessor($exporter)
);

$tracer = $tracerProvider->getTracer('php-demo-app');
