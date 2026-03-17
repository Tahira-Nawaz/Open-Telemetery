<?php
require 'vendor/autoload.php';

use ApplicationInsights\Telemetry_Client;

// Replace with your Instrumentation Key from Azure
$ikey = '6129d3c2-30b9-47eb-97ca-59a04b4c9b46';

$telemetry = new Telemetry_Client();
$telemetry->getContext()->setInstrumentationKey($ikey);

// Track the current page request
$telemetry->trackRequest("HomepageRequest", "http://tahira-app-1.azurewebsites.net/", time(), 100, "200");

// Optional: track a custom event
$telemetry->trackEvent("TestEvent", ["User" => "Tahira"]);

// Optional: track exception (if any)
// try { ... } catch (\Exception $e) { $telemetry->trackException($e); }

// Flush all telemetry to Application Insights
$telemetry->flush();

echo "Telemetry sent to Azure Application Insights!";