<?php

// Define app routes

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
        
    $app->group(
        '/api',
        function (RouteCollectorProxy $app) {
            // Generate token for authorization 
            $app->post('/getToken', \App\Action\Auth\GenerateTokenAction::class);
            // Pixel
            $app->post('/pixel', \App\Action\Pixel\PixelAction::class);
            
            $app->post('/soi', \App\Action\Subscriber\SoiAction::class);
            $app->post('/doi', \App\Action\Subscriber\DoiAction::class);
            $app->get('/confirm/{emailHash}/{hash}', \App\Action\Subscriber\ConfirmAction::class);
        }
    );
};
