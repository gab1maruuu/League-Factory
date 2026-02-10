<?php
class User
{
  private $db;
  public function __construct($pdo)
  {
    $this->db = $pdo;
  }

  public function findById($id)
  {
    $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && empty($user['foto_perfil'])) {
        $user['foto_perfil'] = '/public/images/perfil.jpg';
    }

    return $user;
  }

  public function findByEmail($email)
  {
    $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function findByUsername($username)
  {
    $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE username = :username");
    $stmt->execute(['username' => $username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function findByEmailOrUsername($input)
  {
    $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :input OR username = :input");
    $stmt->execute(['input' => $input]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getAll()
  {
    $stmt = $this->db->query("SELECT * FROM usuarios");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function insert($data)
  {
    $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, apellido, username, email, password_hash, rol) VALUES (:nombre, :apellido, :username, :email, :password_hash, :rol)");
    return $stmt->execute($data);
  }

  public function update($id, $data)
  {
    if (isset($data['nombre'])) {
    }
    $set = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
    $data['id'] = $id;
    $stmt = $this->db->prepare("UPDATE usuarios SET $set WHERE id = :id");
    return $stmt->execute($data);
  }

  public function delete($id)
  {
    $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = :id");
    return $stmt->execute(['id' => $id]);
  }
}
?>