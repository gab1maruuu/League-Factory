<?php
class Database {
  private $pdo;
  public function __construct(){
    $host = getenv('DB_HOST') ?: 'db';
    $db   = getenv('DB_NAME') ?: 'blog_db';
    $user = getenv('DB_USER') ?: 'blog_user';
    $pass = getenv('DB_PASS') ?: 'blog_pass';
    $this->pdo = new PDO("mysql:host={$host};dbname={$db};charset=utf8mb4", $user, $pass, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
  }
  public function getPdo(){ return $this->pdo; }
}