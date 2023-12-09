<?php

namespace app\Middleware;

class HelloMiddleware {
  public function handle() {
    echo " ThisIsMiddleware ";
  }

}
