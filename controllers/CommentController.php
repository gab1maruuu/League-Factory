<?php
class CommentController {
    private $commentModel;
    public function __construct(){ $pdo=(new Database())->getPdo(); $this->commentModel = new Comment($pdo); }
    public function store(){
        if(!isset($_SESSION['user_id'])) { header("Location: index.php?action=login"); exit; }
        $content = trim($_POST['content']);
        $postId = $_POST['post_id'];
        $this->commentModel->store($content, $_SESSION['user_id'], $postId);
        header("Location: index.php?action=show_post&id=$postId");
    }
}
?>