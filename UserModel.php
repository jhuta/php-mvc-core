<?php

namespace jhuta\phpmvccore;

use jhuta\phpmvccore\db\DbModel;

abstract class UserModel extends DbModel {
  abstract public function getDisplayName(): string;
}