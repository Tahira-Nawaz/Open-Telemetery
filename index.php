<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/otel.php';

$app = AppFactory::create();

$app->get('/rolldice', function (Request $request, Response $response) use ($tracer) {
    $span = $tracer->spanBuilder('dice-roll')->startSpan();

    try {
        $dice = random_int(1, 6);

        // Simple message
        echo "<h2>Dice Roller with OpenTelemetry</h2>";
        $response->getBody()->write("You rolled a dice and got: $dice");

    } finally {
        $span->end();
    }

    return $response;
});

$app->run();
