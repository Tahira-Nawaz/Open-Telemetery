<?php

require __DIR__ . '/vendor/autoload.php';

use OpenTelemetry\API\Globals;
use OpenTelemetry\API\Trace\SpanKind;
use OpenTelemetry\API\Trace\StatusCode;
use OpenTelemetry\Contrib\Otlp\Exporter;
use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SDK\Trace\Sampler\AlwaysOnSampler;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SemConv\ResourceAttributes;

// 1. Configure and initialize the OpenTelemetry SDK
$resource = ResourceInfo::create([
    ResourceAttributes::SERVICE_NAME => 'my-php-service',
    ResourceAttributes::SERVICE_VERSION => '1.0.0',
]);
$exporter = new Exporter();
$spanProcessor = new SimpleSpanProcessor($exporter);
$tracerProvider = new TracerProvider(
    $spanProcessor,
    new AlwaysOnSampler(),
    $resource
);

Globals::setTracerProvider($tracerProvider);

// 2. Get the tracer instance
$tracer = $tracerProvider->getTracer('my-app/index.php', '1.0.0');

// 3. Create a new span (trace the main operation)
$span = $tracer->spanBuilder('main-operation')
    ->setSpanKind(SpanKind::KIND_SERVER)
    ->startSpan();

// 4. Set the current span as active in the context
$scope = $span->activate();

try {
    echo "Hello, OpenTelemetry!\n";
    
    // Simulate some work with a nested span
    $nestedSpan = $tracer->spanBuilder('nested-work')
        ->startSpan();
    try {
        usleep(10000); // Simulate some work
        $nestedSpan->setAttribute('work.status', 'successful');
    } finally {
        $nestedSpan->end();
    }

    $span->setStatus(StatusCode::STATUS_OK, 'Operation successful');

} catch (\Exception $e) {
    $span->setStatus(StatusCode::STATUS_ERROR, $e->getMessage());
} finally {
    // 5. End the span and close the scope
    $scope->detach();
    $span->end();

    // 6. Ensure all telemetry data is sent before the script ends
    $tracerProvider->shutdown();
}
