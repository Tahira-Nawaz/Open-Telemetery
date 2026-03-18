<?php
require __DIR__ . '/vendor/autoload.php';

use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SDK\Common\Attribute\Attributes;
use OpenTelemetry\Contrib\Otlp\SpanExporter;
use OpenTelemetry\Contrib\Otlp\OtlpHttpTransportFactory;

// 1️⃣ Azure OTLP connection
$connectionString = "InstrumentationKey=688d2b7d-daea-49b0-9d30-b7d7ba5b02e1;IngestionEndpoint=https://westus2-2.in.applicationinsights.azure.com/;LiveEndpoint=https://westus2.livediagnostics.monitor.azure.com/;ApplicationId=705a3523-c215-4677-95cc-f86d159e2140";
$endpoint = "https://westus2-2.otlp.applicationinsights.azure.com/v1/traces";

// 2️⃣ Create transport
$transportFactory = new OtlpHttpTransportFactory();
$transport = $transportFactory->create(
    $endpoint,
    'application/x-protobuf'
);

// 3️⃣ Create SpanExporter with headers
$exporter = new SpanExporter($transport, [
    'Authorization' => "Bearer $connectionString"
]);

// 4️⃣ Create TracerProvider (span processor only)
$tracerProvider = new TracerProvider(
    new SimpleSpanProcessor($exporter)
);

// 5️⃣ Create ResourceInfo
$resource = ResourceInfo::create(Attributes::create([
    'service.name' => 'tahira-app-1'
]));

// 6️⃣ Get tracer with resource
$tracer = $tracerProvider->getTracer('tahira-tracer', null, $resource);

// 7️⃣ Example span
$span = $tracer->spanBuilder("HomepageRequest")->startSpan();
usleep(100000); // simulate work
$span->end();

// 8️⃣ Flush telemetry
$tracerProvider->shutdown();

echo "Telemetry sent to Azure Application Insights!";