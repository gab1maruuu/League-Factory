<?php
class Post
{
  private $db;

  public function __construct($pdo)
  {
    $this->db = $pdo;
  }

  /**
   * Obtiene todos los posts ordenados por fecha descendente
   */
  public function getAll()
  {
    $stmt = $this->db->query(
      "SELECT posts.*, users.name, users.email 
       FROM posts 
       JOIN users ON posts.user_id = users.id 
       ORDER BY posts.created_at DESC"
    );
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Obtiene un post específico por ID
   */
  public function findById($id)
  {
    $stmt = $this->db->prepare(
      "SELECT posts.*, users.name, users.email 
       FROM posts 
       JOIN users ON posts.user_id = users.id 
       WHERE posts.id = :id"
    );
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * Obtiene posts de un usuario específico
   */
  public function getByUserId($userId)
  {
    $stmt = $this->db->prepare(
      "SELECT posts.*, users.name, users.email 
       FROM posts 
       JOIN users ON posts.user_id = users.id 
       WHERE posts.user_id = :user_id 
       ORDER BY posts.created_at DESC"
    );
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Busca posts por título o contenido
   */
  public function search($query)
  {
    $stmt = $this->db->prepare(
      "SELECT posts.*, users.name, users.email 
       FROM posts 
       JOIN users ON posts.user_id = users.id 
       WHERE posts.title LIKE :query OR posts.content LIKE :query 
       ORDER BY posts.created_at DESC"
    );
    $searchTerm = '%' . $query . '%';
    $stmt->execute(['query' => $searchTerm]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Inserta un nuevo post
   */
  public function insert($data)
  {
    $stmt = $this->db->prepare(
      "INSERT INTO posts (title, content, user_id, created_at, updated_at) 
       VALUES (:title, :content, :user_id, NOW(), NOW())"
    );

    $result = $stmt->execute([
      ':title' => $data['title'],
      ':content' => $data['content'],
      ':user_id' => $data['user_id']
    ]);

    if ($result) {
      return $this->db->lastInsertId();
    }
    return false;
  }

  /**
   * Actualiza un post existente
   */
  public function update($id, $data)
  {
    $fields = [];
    $params = ['id' => $id];

    if (isset($data['title'])) {
      $fields[] = 'title = :title';
      $params['title'] = $data['title'];
    }

    if (isset($data['content'])) {
      $fields[] = 'content = :content';
      $params['content'] = $data['content'];
    }

    $fields[] = 'updated_at = NOW()';

    $sql = "UPDATE posts SET " . implode(', ', $fields) . " WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute($params);
  }

  /**
   * Elimina un post
   */
  public function delete($id)
  {
    $stmt = $this->db->prepare("DELETE FROM posts WHERE id = :id");
    return $stmt->execute(['id' => $id]);
  }

  /**
   * Obtiene el total de posts
   */
  public function getTotalCount()
  {
    $stmt = $this->db->query("SELECT COUNT(*) as total FROM posts");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
  }

  /**
   * Obtiene posts con paginación
   */
  public function getPaginated($page = 1, $perPage = 10)
  {
    $offset = ($page - 1) * $perPage;

    $stmt = $this->db->prepare(
      "SELECT posts.*, users.name, users.email 
       FROM posts 
       JOIN users ON posts.user_id = users.id 
       ORDER BY posts.created_at DESC 
       LIMIT :limit OFFSET :offset"
    );

    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
