<?php

namespace leaf\Router;

class Route
{
  protected $handler;
  protected string $method;
  protected string $path;

  protected array  $middleware = [];

  public function __construct(string $method, string $path, $handler, array $middleware)
  {
    $this->method     = $method;
    $this->path       = $path;
    $this->handler    = $handler;
    $this->middleware = $middleware;
  }

  public function middleware($middleware): self
  {
    $this->middleware[] = $middleware;
    return $this;
  }

  public function getMethod(): string
  {
    return $this->method;
  }

  
  public function getPath(): string
  {
    return $this->path;
  }
  
  public function executeHandler()
  {
    if (is_callable($this->handler)) {
      return call_user_func($this->handler);
    } else {
      list($controllerClass, $method) = $this->handler; // Use the handler array directly
        $controller = new $controllerClass();
      return $controller->$method();

    }
  }

  public function getMiddleware(): array
  {
    return $this->middleware;
  }

  public function matchesPath(string $path): bool
  {
    // You might need a more advanced path matching logic depending on your requirements
    return $this->path === $path;
  }
}
