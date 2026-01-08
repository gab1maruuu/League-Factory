<?php
class User {
  private $db;
  public function __construct($pdo){ $this->db = $pdo; }
  public function findByEmail($email){
    $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
?>