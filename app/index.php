<?php
namespace leaf;

use app\Controller\HelloController;
use app\Middleware\HelloMiddleware;
use app\Model\User;
use leaf\Container\Container;
use leaf\Router\Router;

require_once __DIR__ . '/../vendor/autoload.php';
//
// $container = new Container();
//
// $container->set('user', function () {
//     return new User();
// });
//
// $user = $container->get('user');
// $controller = new HelloController($user);
//
// $router = new Router();
//
// $router->get('/user/{id}', function() {
//   $user = new User();
//   $controller = new HelloController($user);  
//   $controller->index();
// })->middleware(HelloMiddleware::class);
//

$router = new Router();

$router->get('/home', [HelloController::class, 'index']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

