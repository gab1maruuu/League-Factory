<?php
class Comment {
    private $db;
    public function __construct($pdo){ $this->db = $pdo; }
    public function allByPost($postId){
        $stmt = $this->db->prepare("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id=users.id WHERE comments.post_id = ? ORDER BY comments.id DESC");
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function find($id){
        $stmt = $this->db->prepare("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id=users.id WHERE comments.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function store($content,$userId,$postId){
        $stmt = $this->db->prepare("INSERT INTO comments (content,user_id,post_id) VALUES (?,?,?)");
        $stmt->execute([$content,$userId,$postId]);
        return $this->db->lastInsertId();
    }
}