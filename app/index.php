<?php
namespace leaf;

use app\Controller\HelloController;
use app\Middleware\HelloMiddleware;
use app\Model\User;
use leaf\Container\Container;
use leaf\Router\Router;

require_once __DIR__ . '/../vendor/autoload.php';
$router = new Router();

$router->get('/user/{id}/edit/{name}', function($id,$name) {
  var_dump($id);
  var_dump($name);
})->middleware(HelloMiddleware::class);


$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

