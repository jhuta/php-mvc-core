<?php

namespace jhuta\phpmvccore\Exceptions;

class ForbiddenException extends \Exception {
  protected $code    = 404;
  protected $message = "Page Not Found.";
}