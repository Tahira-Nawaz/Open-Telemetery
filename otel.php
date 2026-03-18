<?php
require __DIR__ . '/vendor/autoload.php';

use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SDK\Common\Attribute\Attributes;
use OpenTelemetry\Contrib\Otlp\SpanExporter;
use OpenTelemetry\Contrib\Otlp\OtlpHttpTransportFactory;

// Azure OTLP connection
$connectionString = "InstrumentationKey=688d2b7d-daea-49b0-9d30-b7d7ba5b02e1;IngestionEndpoint=https://westus2-2.in.applicationinsights.azure.com/;LiveEndpoint=https://westus2.livediagnostics.monitor.azure.com/;ApplicationId=705a3523-c215-4677-95cc-f86d159e2140";
$endpoint = "https://westus2-2.otlp.applicationinsights.azure.com/v1/traces";

// 1️⃣ Create transport via factory
$transportFactory = new OtlpHttpTransportFactory(
    $endpoint,
    [
        "Authorization" => "Bearer $connectionString"
    ]
);

$transport = $transportFactory->create(); // ✅ must call create()

// 2️⃣ Create SpanExporter with transport
$exporter = new SpanExporter($transport);

// 3️⃣ Resource info (your Azure Web App name)
$resource = ResourceInfo::create(Attributes::create([
    "service.name" => "tahira-app-1"
]));

// 4️⃣ Tracer provider
$tracerProvider = new TracerProvider(
    new SimpleSpanProcessor($exporter),
    $resource
);

$tracer = $tracerProvider->getTracer("tahira-tracer");

// 5️⃣ Example span
$span = $tracer->spanBuilder("HomepageRequest")->startSpan();
usleep(100000); // simulate work
$span->end();

// 6️⃣ Flush telemetry
$tracerProvider->shutdown();

echo "Telemetry sent to Azure Application Insights!";