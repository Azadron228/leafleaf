<?php

namespace leaf\Router;

use DI\Container;
use DI\ContainerBuilder;
use leaf\Router\Route;

class Router
{
  private Container $container;

  public function __construct()
  {
    $this->container = $this->buildContainer();
  }

  private function buildContainer(): Container
  {
    $builder = new ContainerBuilder();
    $builder->addDefinitions(__DIR__ . '/config.php');
    return $builder->build();
  }

  use RouterRequestMethodsTrait;

  protected array $routes = [];
  protected $handler;
  protected string $method;
  protected string $path;
  protected array $middleware;

  public function dispatch(string $method, string $path)
  {
    echo "dispatchEr";
    $route = $this->matchRoute($method, $path);

    if ($route) {
      foreach ($route->getMiddleware() as $middleware) {
        $middlewareInstance = new $middleware();
        $middlewareInstance->handle();
      }

      $this->invoke($route->handler, $params = []);
    } else {
      echo "404 Not Found";
    }
  }

  private function invoke($handler, $params = [])
  {
    if (is_callable($handler)) {
      call_user_func_array($handler, $params);
    } elseif (is_array($handler) !== false) {
      list($controller, $method) = $handler;

      if (!$this->container->has($controller)) {
        trigger_error("$controller not found in the container");
      }

      $controllerInstance = $this->container->get($controller);

      if (!method_exists($controllerInstance, $method)) {
        trigger_error("$method method not found in $controller");
      }

      // Call non-static method
      if (call_user_func_array([$controllerInstance, $method], $params) === false) {
        // Call static method
        if (forward_static_call_array([$controllerInstance, $method], $params) === false);
      }
    }
  }

  private function invvoke($handler, $params = [])
  {
    if (is_callable($handler)) {
      call_user_func_array(
        $handler,
        $params
      );
    } elseif (is_array($handler) !== false) {
      list($controller, $method) = $handler;

      if (!class_exists($controller)) {
        trigger_error("$controller not found");
      }
      if (!method_exists($controller, $method)) {
        trigger_error("$method method not found in $controller");
      }
      // Call non Static method
      if (call_user_func_array([new $controller(), $method], $params) === false) {
        // Call Static method
        if (forward_static_call_array([$controller, $method], $params) === false);
      }
    }
  }

  function matchRoute($method, $path): Route
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

      $params = $route->getPathParams($route->getPath(), $path);
      echo "loh";

      if ($params !== null) {
        $route->setParams($params);
        return $route;
      }
    }

    return null;
  }
}
