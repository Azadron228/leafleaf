<?php

namespace leaf\Router;

use leaf\Router\Route;

trait RouterRequestMethodsTrait
{
  public function delete(string $path, $handler): Route
  {
    return $this->add('DELETE', $path, $handler);
  }

  public function get(string $path, $handler): Route
  {
    return $this->add('GET', $path, $handler);
  }

  function post(string $path, $handler): Route
  {

    return $this->add('POST', $path, $handler);
  }

  function put(string $path, $handler): Route
  {

    return $this->add('PUT', $path, $handler);
  }

  function patch(string $path, $handler): Route
  {

    return $this->add('PATCH', $path, $handler);
  }

  function options(string $path, $handler): Route
  {

    return $this->add('OPTIONS', $path, $handler);
  }

  function any(string $path, $handler): Route
  {

    return $this->add('ANY', $path, $handler);
  }
}
