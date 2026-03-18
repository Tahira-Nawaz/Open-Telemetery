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

// 1️⃣ Create factory
$transportFactory = new OtlpHttpTransportFactory();

// 2️⃣ Create transport (pass endpoint & headers)
$transport = $transportFactory->create(
    $endpoint,
    [
        "Authorization" => "Bearer $connectionString"
    ]
);

// 3️⃣ Create SpanExporter
$exporter = new SpanExporter($transport);

// 4️⃣ Resource info (your Azure Web App name)
$resource = ResourceInfo::create(Attributes::create([
    "service.name" => "tahira-app-1"
]));

// 5️⃣ Tracer provider
$tracerProvider = new TracerProvider(
    new SimpleSpanProcessor($exporter),
    $resource
);

$tracer = $tracerProvider->getTracer("tahira-tracer");

// 6️⃣ Example span
$span = $tracer->spanBuilder("HomepageRequest")->startSpan();
usleep(100000); // simulate work
$span->end();

// 7️⃣ Flush telemetry
$tracerProvider->shutdown();

echo "Telemetry sent to Azure Application Insights!";