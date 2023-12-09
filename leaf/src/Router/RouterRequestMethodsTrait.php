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
}

