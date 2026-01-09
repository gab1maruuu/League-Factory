<?php
class User {
  private $db;
  public function __construct($pdo){ $this->db = $pdo; }
  
  public function findById($id) {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function findByEmail($email) {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getAll() {
    $stmt = $this->db->query("SELECT * FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function insert($data) {
    $stmt = $this->db->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (:name, :email, :password_hash, :role)");
    return $stmt->execute($data);
  }

  public function update($id, $data) {
    $set = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
    $data['id'] = $id;
    $stmt = $this->db->prepare("UPDATE users SET $set WHERE id = :id");
    return $stmt->execute($data);
  }

  public function delete($id) {
    $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
    return $stmt->execute(['id' => $id]);
  }
}
?>