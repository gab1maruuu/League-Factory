<?php
session_start();
require 'config/Database.php';
$action = $_GET['action'] ?? 'posts';
switch ($action) {
  case 'posts':
    require 'controllers/PostController.php';
    (new PostController())->index();
    break;
  case 'login':
    require 'controllers/UserController.php';
    (new UserController())->login();
    break;
  // añade más rutas: show_post, post_create, comment_store, admin_...
  default:
    echo "Ruta no encontrada";
}

include __DIR__ . "/views/layout/header.php"
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
</body>

</html>
<?php
include __DIR__ . "/views/layout/footer.php"
?>