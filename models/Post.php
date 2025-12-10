<?php
class Post {
  private $db;
  public function __construct($pdo){ $this->db = $pdo; }
  public function all(){
    $stmt = $this->db->query("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id=users.id ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function find($id){
    $stmt = $this->db->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id=users.id WHERE posts.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
  public function store($title,$content,$userId){
    $stmt = $this->db->prepare("INSERT INTO posts (title,content,user_id) VALUES (?,?,?)");
    $stmt->execute([$title,$content,$userId]);
    return $this->db->lastInsertId();
  }
}
