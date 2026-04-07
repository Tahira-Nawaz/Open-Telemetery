<?php
require __DIR__ . '/vendor/autoload.php';

use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SDK\Common\Attribute\Attributes;

// ----------------------------
// 1️⃣ Azure connection
// ----------------------------
$connectionString = "InstrumentationKey=fad75225-e11f-49da-b5ef-66482eec4123;IngestionEndpoint=https://westus2-2.in.applicationinsights.azure.com/;LiveEndpoint=https://westus2.livediagnostics.monitor.azure.com/;ApplicationId=bde46449-9ee8-4937-bcd1-098689586da8";
$instrumentationKey = "fad75225-e11f-49da-b5ef-66482eec4123";

// Use classic ingestion endpoint (works from PHP)
$endpoint = "https://westus2-2.in.applicationinsights.azure.com/v2/track";

// ----------------------------
// 2️⃣ Helper to send span to Azure
// ----------------------------
function sendTelemetry(array $spanData, string $endpoint, string $ikey): void {
    $payload = [
        "name" => $spanData['name'],
        "time" => gmdate("c"),
        "iKey" => $ikey,
        "tags" => [
            "ai.cloud.role" => $spanData['serviceName'] ?? 'unknown-service',
        ],
        "data" => [
            "baseType" => "RequestData",
            "baseData" => [
                "id" => uniqid(),
                "name" => $spanData['name'],
                "duration" => $spanData['duration'] ?? "00:00:00.100",
                "responseCode" => $spanData['responseCode'] ?? "200",
                "success" => $spanData['success'] ?? true,
            ],
        ],
    ];

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        echo "Telemetry error: $err\n";
    } else {
        echo "Telemetry sent successfully! Response: $response\n";
    }
}

// ----------------------------
// 3️⃣ Tracer setup (simulated)
// ----------------------------
$resource = ResourceInfo::create(
    Attributes::create(['service.name' => 'tahira-app-1'])
);

$tracerProvider = new TracerProvider();
$tracer = $tracerProvider->getTracer('tahira-tracer', '1.0.0');

// ----------------------------
// 4️⃣ Example span
// ----------------------------
$span = $tracer->spanBuilder('HomepageRequest')->startSpan();
usleep(100000); // simulate work
$span->end();

// ----------------------------
// 5️⃣ Send span to Azure
// ----------------------------
sendTelemetry([
    'name' => 'HomepageRequest',
    'serviceName' => 'tahira-app-1',
    'duration' => '00:00:00.100',
    'responseCode' => '200',
    'success' => true,
], $endpoint, $instrumentationKey);

// ----------------------------
// 6️⃣ Shutdown tracer
// ----------------------------
$tracerProvider->shutdown();

echo "Done!";