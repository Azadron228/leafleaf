<?php

namespace leaf\Router;

class Route
{
  protected $handler;
  protected string $method;
  protected string $path;
  protected array $params = [];
  protected array  $middleware = [];

  public function __construct(string $method, string $path, $handler, array $middleware)
  {
    $this->method     = $method;
    $this->path       = $path;
    $this->handler    = $handler;
    $this->middleware = $middleware;
  }

  public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
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
      var_dump($this->handler);
      //call_user_func_array($this->handler, $this->params);
      call_user_func($this->handler);
    } else {
      list($controllerClass, $method) = $this->handler;
      $controller = new $controllerClass();
      $controller->$method();
    }
  }

  public function getMiddleware(): array
  {
    return $this->middleware;
  }

  public function matchesPath(string $path): bool
  {
    return $this->path === $path;
  }

  function getQueryParams() : array {
    $queryParams = [];
    if (isset($_SERVER['QUERY_STRING'])) {
        parse_str($_SERVER['QUERY_STRING'], $queryParams);
    }
    return $queryParams;
  }
    
  function getPathParams($route, $path): array
  {
    $path = parse_url($path, PHP_URL_PATH);
    $routeSegments = explode('/', trim($route, '/'));
    $pathSegments = explode('/', trim($path, '/'));

    $params = [];

    foreach ($routeSegments as $index => $segment) {
        // If the segment starts with '{' and ends with '}', it's a path parameter
        if (strpos($segment, '{') === 0 && substr($segment, -1) === '}') {
            $paramName = trim($segment, '{}'); // Remove the braces to get the parameter name
            $params[$paramName] = $pathSegments[$index] ?? null; // Get the corresponding value from the path
        }
    }
    return $params;
  }
}
