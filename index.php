<?php
session_start();
require 'config/Database.php';
$action = $_GET['action'] ?? 'posts';
?>

<div class="min-h-screen flex flex-col">
  <?php
  switch($action){
    case 'posts': require 'controllers/PostController.php'; (new PostController())->index(); break;
    case 'login': require 'controllers/UserController.php'; (new UserController())->login(); break;
    default: echo "Ruta no encontrada";
  }

  include __DIR__ ."/views/layout/header.php";
  include __DIR__ ."/views/layout/inicio.php";
  ?>
</div>

<?php 
include __DIR__ ."/views/layout/footer.php"
?>