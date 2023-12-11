<?php

namespace leaf\Container;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
  private $services = [];

  public function get($id):mixed
  {
    if (!$this->has($id)) {
      throw new \Exception("Service not found: $id");
    }

    return $this->services[$id]();
  }

  public function has($id): bool
  {
    return isset($this->services[$id]);
  }

  public function set($id, callable $service)
  {
    $this->services[$id] = $service;
  }
}
