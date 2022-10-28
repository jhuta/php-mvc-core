<?php

namespace jhuta\phpmvccore\db;

use jhuta\phpmvccore\Application;
use jhuta\phpmvccore\Model;

abstract class DbModel extends Model {
  abstract public static function tableName(): string;

  public static function primaryKey(): string {
    return 'id';
  }

  public function save() {
    $tableName  = $this->tableName();
    $attributes = $this->attributes();
    $params     = array_map(fn ($attr) => ":{$attr}", $attributes);

    $sql  = "INSERT INTO {$tableName} (" . implode(',', $attributes) . ") VALUES (" . implode(',', $params) . ");";
    $stmt = self::prepare($sql);
    foreach ($attributes as $attribute) {
      $stmt->bindValue(":{$attribute}", $this->{$attribute});
    }
    $stmt->execute();
    return true;
  }

  public static function prepare($sql): \PDOStatement {
    // return Application::$app->db->prepare($sql);
    return Application::$app->db->pdo->prepare($sql);
  }

  public static function findOne($where) { // [email => x@x.pl, first_name => JACKo]
    $tableName  = static::tableName();
    $attributes = array_keys($where);
    $sql = implode(" AND ", array_map(fn ($attr) => "$attr = :$attr", $attributes));
    $stmt = self::prepare("SELECT * FROM $tableName WHERE $sql");
    foreach ($where as $key => $item) {
      $stmt->bindValue(":$key", $item);
    }
    $stmt->execute();
    return $stmt->fetchObject(static::class);
  }


  // abstract public function primaryKey(): string;

}
