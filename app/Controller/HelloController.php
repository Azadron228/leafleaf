<?php

namespace app\Controller;

use app\Model\User;

class HelloController
{
  private $user;

  public function __construct(User $user)
  {
    $this->user= $user;
  }

  public function index()
  {
    echo "Hello ". $this->user->name();
  }
}
