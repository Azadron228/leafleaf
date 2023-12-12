<?php

namespace leaf\Router;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use leaf\Router\Route;
use Nyholm\Psr7\Request as Psr7Request;
use Nyholm\Psr7\Response as Psr7Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Router
{
  use RouterRequestMethodsTrait;

  protected array $routes = [];
  protected $handler;
  protected string $method;
  protected string $path;
  protected array $middleware;

  function cleanPath(string $path): string
  {
    $cleanedPath = trim($path, '/');

    $cleanedPath = strtok($cleanedPath, '?');

    return trim($cleanedPath, '/');
  }

  public function dispatch(string $method, string $requestPath)
  {
    $route = $this->matchRoute($method, $requestPath);
    $params = $this->handlePattern($route->getPath(), explode('/', $this->cleanPath($requestPath)));

    if ($route) {
      $this->callHandler($route->handler, $params);
    } else {
      echo "404 Not Found";
    }
  }

  private function callHandler($handler, $params = [])
  {
    if (is_callable($handler)) {
      call_user_func_array($handler, $params);
    }
  }

  protected function handlePattern(string $path, array $request)
  {
    $params  = null;
    $pattern = explode('/', ltrim($path, '/'));
    $count   = count($pattern);

    for ($i = 0; $i < $count; $i++) {
      if (preg_match('/{([a-zA-Z0-9_]*?)}/', $pattern[$i]) !== 0) {
        if (array_key_exists($i, $request)) {
          $params[] = $request[$i];
        }
        continue;
      }
    }

    return $params;
  }

  public function executeMiddleware($route)
  {
    foreach ($route->getMiddleware() as $middleware) {
      $middlewareInstance = new $middleware();
      $middlewareInstance->handle();
    }
  }

  function matchRoute($method, $path): ?Route
  {
    foreach ($this->routes as $route) {
      if ($route->getMethod() !== $method) {
        continue;
      }

      $routePathSegments = explode('/', trim($route->getPath(), '/'));
      $pathSegments = explode('/', trim($path, '/'));

      if (count($routePathSegments) !== count($pathSegments)) {
        continue;
      }

      return $route;
    }

    return null;
  }
}
