<?php
session_start();
require 'config/Database.php';
$action = $_GET['action'] ?? 'posts';
switch($action){
  case 'posts': require 'controllers/PostController.php'; (new PostController())->index(); break;
  case 'login': require 'controllers/UserController.php'; (new UserController())->login(); break;
  // añade más rutas: show_post, post_create, comment_store, admin_...
  default: echo "Ruta no encontrada";
}