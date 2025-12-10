<?php
require_once 'models/Post.php';
class PostController {
  private $postModel;
  public function __construct(){ $pdo=(new Database())->getPdo(); $this->postModel = new Post($pdo); }
  public function index(){ $posts = $this->postModel->all(); include 'views/posts/index.php'; }
  public function show(){ $id = $_GET['id']; $post = $this->postModel->find($id); include 'views/posts/show.php'; }
  public function store(){
    // valida rol: admin/writer
    if(!in_array($_SESSION['role'] ?? '', ['admin','writer'])) { header("Location: index.php?action=login"); exit; }
    $id = $this->postModel->store(trim($_POST['title']), trim($_POST['content']), $_SESSION['user_id']);
    // notificar n8n (ver paso Webhooks)
    header("Location: index.php?action=show_post&id=$id");
  }
}
