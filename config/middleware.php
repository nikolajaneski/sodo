<?php

use Selective\BasePath\BasePathMiddleware;
use Selective\Validation\Middleware\ValidationExceptionMiddleware;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Tuupola\Middleware\JwtAuthentication\RequestPathRule;

$logger = new Logger("slim");
$rotating = new RotatingFileHandler(__DIR__ . "/../logs/slim.log", 0, Logger::DEBUG);
$logger->pushHandler($rotating);

return function (App $app) use ($logger) {
    $app->add(new Tuupola\Middleware\JwtAuthentication([
        "ignore" => ["(.+)\/confirm", "(.+)\/getToken"],
        "secret"=> "verypersonalsecret", 
        "error" => function ($response, $arguments)
        {
            $data["success"] = 0;
            $data["response"] = $arguments["message"];
            $data["status_code"] = "401";
            
            return $response->withHeader("Content-type","application/json")
                ->getBody()->write(json_encode($data,JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
    ]));
    $app->addBodyParsingMiddleware();
    $app->add(ValidationExceptionMiddleware::class);
    $app->addRoutingMiddleware();
    $app->add(BasePathMiddleware::class);
    $app->add(ErrorMiddleware::class);
};
