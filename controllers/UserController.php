<?php
require_once 'models/User.php';
class UserController {
  private $userModel;
  public function __construct(){ $pdo=(new Database())->getPdo(); $this->userModel = new User($pdo); }
  public function login(){
    if(!isset($_POST['email']) || !isset($_POST['password'])){
      header("Location: index.php?action=login&error=1");
      exit;
    }
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $user = $this->userModel->findByEmail($email);
    if($user && password_verify($password, $user['password_hash'])){
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['role'] = $user['role'];
      header("Location: index.php?action=posts");
    } else {
      header("Location: index.php?action=login&error=1");
    }
    
    exit;
  }

  // En controllers/UserController.php
public function register() {
    include 'views/auth/register.php';
}
}

?>