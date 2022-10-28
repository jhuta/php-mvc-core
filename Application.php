<?php

namespace jhuta\phpmvccore;

use jhuta\phpmvccore\db\Database;

class Application {
  const EVENT_BEFORE_REQUEST = 'beforeRequest';
  const EVENT_AFTER_REQUEST  = 'afterRequest';

  protected array $eventListeners = [];

  public static Application $app;
  public static string $ROOT_DIR;

  public string $userClass;
  public string $layout = 'main';

  public Router $router;
  public Request $request;
  public Response $response;
  public ?Controller $controller = null;
  public Database $db;
  public Session $session;
  public View $view;
  public ?UserModel $user;

  public function __construct($rootPath, $config) {
    $this->user      = null;
    $this->userClass = $config['userClass'];
    self::$ROOT_DIR  = $rootPath;
    self::$app       = $this;
    $this->request  = new Request();
    $this->response = new Response();
    $this->router   = new Router($this->request, $this->response);
    $this->db       = new Database($config['db']);
    $this->session  = new Session();
    $this->view     = new View();

    $userId = Application::$app->session->get('user');
    if ($userId) {
      $key = $this->userClass::primaryKey();
      $this->user = $this->userClass::findOne([$key => $userId]);
    }
  }

  public static function isGuest() {
    return !self::$app->user;
  }

  public function login(UserModel $user) {
    $this->user  = $user;
    $className   = get_class($user);
    $primaryKey  = $className::primaryKey();
    $value       = $user->{$primaryKey};
    Application::$app->session->set('user', $value);
    return true;
  }

  public function logout() {
    $this->user = null;
    self::$app->session->remove('user');
  }

  public function run() {
    $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
    try {
      echo $this->router->resolve();
    } catch (\Exception $e) {
      $this->response->setStatusCode($e->getCode());
      echo $this->view->renderView('_error', [
        'exception' => $e,
      ]);
    }
  }

  public function triggerEvent($eventName) {
    $callbacks = $this->eventListeners[$eventName] ?? [];
    foreach ($callbacks as $callback) {
      call_user_func($callback);
    }
  }

  public function on($eventName, $callback) {
    $this->eventListeners[$eventName][] = $callback;
  }
}


  // public function getController() {
  //   return $this->controller;
  // }

  // public function setController($controller):void {
  //   $this->controller = $controller;
  // }