<?php
class User {
  private $db;
  public function __construct($pdo){ $this->db = $pdo; }
  
  public function findById($id) {
    $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function findByEmail($email) {
    $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getAll() {
    $stmt = $this->db->query("SELECT * FROM usuarios");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function insert($data) {
    // Los datos vienen con claves: nombre, email, password_hash, rol
    // Pero la BD usa nombre_completo
    $stmt = $this->db->prepare("INSERT INTO usuarios (nombre_completo, email, password_hash, rol) VALUES (:nombre, :email, :password_hash, :rol)");
    return $stmt->execute($data);
  }

  public function update($id, $data) {
    // Renombrar nombre a nombre_completo si viene en los datos
    if (isset($data['nombre'])) {
      $data['nombre_completo'] = $data['nombre'];
      unset($data['nombre']);
    }
    $set = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
    $data['id'] = $id;
    $stmt = $this->db->prepare("UPDATE usuarios SET $set WHERE id = :id");
    return $stmt->execute($data);
  }

  public function delete($id) {
    $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = :id");
    return $stmt->execute(['id' => $id]);
  }
}
?>