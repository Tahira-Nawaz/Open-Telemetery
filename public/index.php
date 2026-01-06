<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../otel.php';

$app = AppFactory::create();

$app->get('/rolldice', function (Request $request, Response $response) use ($tracer) {
    // Start a span for tracing
    $span = $tracer->spanBuilder('dice-roll')->startSpan();

    try {
        $result = random_int(1, 6);

        // Display a heading message
        echo "<h2>Welcome to the Dice Roller with OpenTelemetry!</h2>";

        // Show dice roll result
        $response->getBody()->write("You rolled the dice and got: $result");

    } finally {
        $span->end();
    }

    return $response;
});

$app->run();
