<?php

require __DIR__ . '/../src/config.php';

use App\Controllers\EmailController;
use App\Controllers\TokenController;
use App\Middleware\AuthMiddleware;
use App\Request;
use App\Response;
use App\Router;
use App\Services\OAuth2Service;

// Controller

$request = new Request();
$response = new Response();
$router = new Router($request, $response);

$tokenController = new TokenController(new OAuth2Service());
$emailController = new EmailController(new App\Queue\EmailQueue());

$router->post('/token', function ($request, $response) use ($tokenController) {
    $tokenController->generateToken($request, $response);
});

$router->post('/email/send-email', function ($request, $response) use ($emailController) {
    $emailController->sendEmail($request, $response);
}, [AuthMiddleware::class]);

$router->handleRequest();
