<?php

namespace leaf\Router;

use leaf\Router\Route;

class Router
{
  use RouterRequestMethodsTrait;

  protected array $routes = [];
  protected $handler;
  protected string $method;
  protected string $path;
  protected array $middleware;

  public function add($method, $path, $handler, $middleware = []): Route
  {
    $route = new Route($method, $path, $handler, $middleware);
    $this->routes[] = $route;
    return $route;
  }

  public function dispatch(string $method, string $path)
  {
    $route = $this->matchRoute($method, $path);

    var_dump($route);

    if ($route) {
      foreach ($route->getMiddleware() as $middleware) {
        $middlewareInstance = new $middleware();
        $middlewareInstance->handle();
      }

      $route->executeHandler();
    } else {
      echo "404 Not Found";
    }
  }
}
