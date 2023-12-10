<?php
namespace leaf;

use app\Controller\HelloController;
use app\Middleware\HelloMiddleware;
use leaf\Middleware\NigMiddleware;
use leaf\Router\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$router = new Router();

$router->get('/user/{id}', [HelloController::class, 'index'])->middleware(HelloMiddleware::class);

$router->get('/greeting', function () {
    echo "Hello World";
});

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

