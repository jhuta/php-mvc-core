<?php

namespace jhuta\phpmvccore;

use jhuta\phpmvccore\Middlewares\BaseMiddleware;

class Controller {
  public string $layout     = 'main';
  public string $action    = '';
  protected array $middlewares = [];

  public function setLayout($layout): void {
    $this->layout = $layout;
  }

  public function render($view, $params = []): string {
    return Application::$app->view->renderView($view, $params);
  }

  public function registerMiddleware(BaseMiddleware $middleware) {
    $this->middlewares[] = $middleware;
  }

  public function getMiddlewares(): array {
    return $this->middlewares;
  }
}

// public function setMiddlewares($middleware) {
//   $this->middlewares[] = $middleware;
// }