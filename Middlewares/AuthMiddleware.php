<?php

namespace jhuta\phpmvccore\Middlewares;

use jhuta\phpmvccore\Application;
use jhuta\phpmvccore\Exceptions\ForbiddenException;

class AuthMiddleware extends BaseMiddleware {
  // protected array $actions = [];

  public function __construct(protected array $actions = []) {
  }

  public function execute() {

    if (Application::isGuest()) {
      if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
        throw new ForbiddenException();
      }
    }
  }
}
