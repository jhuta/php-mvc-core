<?php

namespace jhuta\phpmvccore\db;

use jhuta\phpmvccore\Application;

class Database {
  public \PDO $pdo;

  public function __construct(array $dbConfig = []) {
    $dbDsn    = $dbConfig['dsn'] ?? '';
    $user     = $dbConfig['user'] ?? '';
    $password = $dbConfig['password'] ?? '';
    $this->pdo = new \PDO($dbDsn, $user, $password);
    $this->pdo->setAttribute(
      \PDO::ATTR_ERRMODE,
      \PDO::ERRMODE_EXCEPTION
    );
  }

  public function applyMigrations() {
    $this->createMigrationsTable();
    $appliedMigrations = $this->getAppliedMigrations();

    $newMigrations = [];
    $files = scandir(Application::$ROOT_DIR . '/migrations');
    $toApplyMigrations = array_diff($files, $appliedMigrations);
    foreach ($toApplyMigrations as $migration) {
      if ($migration === '.' || $migration === '..') {
        continue;
      }
      require_once Application::$ROOT_DIR . '/migrations/' . $migration;
      $className = pathinfo($migration, PATHINFO_FILENAME);
      $instance = new $className();
      $this->log("Applying migration {$migration}");
      $instance->up();
      $this->log("Applied migration {$migration}");
      $newMigrations[] = $migration;
    }
    if (!empty($newMigrations)) {
      $this->saveMigrations($newMigrations);
    } else {
      $this->log("All migrations are applied");
    }
  }

  protected function createMigrationsTable() {
    $this->pdo->exec("
    CREATE TABLE IF NOT EXISTS migrations (
      id INT AUTO_INCREMENT PRIMARY_KEY,
      migration VARCHAR(255),
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=INNODB;
    ");
  }

  protected function getAppliedMigrations() {
    $stmt = $this->pdo->prepare("SELECT migration FROM migrations");
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_COLUMN);
  }

  protected function saveMigrations(array $newMigrations) {
    // zmiana danych do zapytania... x => ('x')
    $str = implode(",", array_map(fn ($m) => "('$m')", $newMigrations));
    $stmt = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES {$str}");
    $stmt->execute();
  }

  public function prepare($sql): \PDOStatement {
    return $this->pdo->prepare($sql);
  }

  private function log($message) {
    echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
  }
}