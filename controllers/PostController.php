<?php
require_once 'models/Post.php';
require_once 'config/Database.php';

class PostController
{
  private $post;
  private $db;

  public function __construct()
  {
    $database = new Database();
    $this->db = $database->getPdo();
    $this->post = new Post($this->db);
  }

  /**
   * Muestra todos los posts (homepage)
   */
  public function index()
  {
    $posts = $this->post->getAll();
    include 'views/posts/index.php';
  }

  /**
   * Muestra un post específico con sus comentarios
   */
  public function show()
  {
    if (!isset($_GET['id'])) {
      header('Location: index.php?action=home');
      exit;
    }

    $id = intval($_GET['id']);
    $post = $this->post->findById($id);

    if (!$post) {
      $_SESSION['error'] = 'Post no encontrado';
      header('Location: index.php?action=home');
      exit;
    }

    include 'views/posts/show.php';
  }

  /**
   * Muestra el formulario para crear un post
   */
  public function create()
  {
    if (!isset($_SESSION['user_id'])) {
      $_SESSION['error'] = 'Debes iniciar sesión para crear un post';
      header('Location: index.php?action=login');
      exit;
    }

    if (!in_array($_SESSION['user_role'] ?? '', ['admin', 'writer'])) {
      $_SESSION['error'] = 'No tienes permisos para crear posts';
      header('Location: index.php?action=home');
      exit;
    }

    include 'views/posts/create.php';
  }

  /**
   * Almacena un nuevo post en la BD
   */
  public function store()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->create();
      return;
    }

    if (!isset($_SESSION['user_id'])) {
      $_SESSION['error'] = 'Debes iniciar sesión';
      header('Location: index.php?action=login');
      exit;
    }

    if (!in_array($_SESSION['user_role'] ?? '', ['admin', 'writer'])) {
      $_SESSION['error'] = 'No tienes permisos para crear posts';
      header('Location: index.php?action=home');
      exit;
    }

    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    if (empty($title) || empty($content)) {
      $_SESSION['error'] = 'Título y contenido son requeridos';
      include 'views/posts/create.php';
      return;
    }

    if (strlen($title) < 3) {
      $_SESSION['error'] = 'El título debe tener al menos 3 caracteres';
      include 'views/posts/create.php';
      return;
    }

    if (strlen($content) < 10) {
      $_SESSION['error'] = 'El contenido debe tener al menos 10 caracteres';
      include 'views/posts/create.php';
      return;
    }

    $data = [
      'title' => trim($title),
      'content' => trim($content),
      'user_id' => $_SESSION['user_id']
    ];

    $postId = $this->post->insert($data);

    if ($postId) {
      $_SESSION['success'] = 'Post creado correctamente';
      header("Location: index.php?action=show_post&id=$postId");
      exit;
    } else {
      $_SESSION['error'] = 'Error al crear el post';
      include 'views/posts/create.php';
    }
  }

  /**
   * Muestra el formulario para editar un post
   */
  public function edit()
  {
    if (!isset($_GET['id'])) {
      header('Location: index.php?action=home');
      exit;
    }

    $id = intval($_GET['id']);
    $post = $this->post->findById($id);

    if (!$post) {
      $_SESSION['error'] = 'Post no encontrado';
      header('Location: index.php?action=home');
      exit;
    }

    if (!isset($_SESSION['user_id'])) {
      $_SESSION['error'] = 'Debes iniciar sesión';
      header('Location: index.php?action=login');
      exit;
    }

    if ($_SESSION['user_id'] != $post['user_id'] && $_SESSION['user_role'] !== 'admin') {
      $_SESSION['error'] = 'No tienes permisos para editar este post';
      header('Location: index.php?action=home');
      exit;
    }

    include 'views/posts/edit.php';
  }

  /**
   * Actualiza un post existente
   */
  public function update()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('Location: index.php?action=home');
      exit;
    }

    if (!isset($_POST['id'])) {
      $_SESSION['error'] = 'Post no especificado';
      header('Location: index.php?action=home');
      exit;
    }

    $id = intval($_POST['id']);
    $post = $this->post->findById($id);

    if (!$post) {
      $_SESSION['error'] = 'Post no encontrado';
      header('Location: index.php?action=home');
      exit;
    }

    if (!isset($_SESSION['user_id'])) {
      $_SESSION['error'] = 'Debes iniciar sesión';
      header('Location: index.php?action=login');
      exit;
    }

    if ($_SESSION['user_id'] != $post['user_id'] && $_SESSION['user_role'] !== 'admin') {
      $_SESSION['error'] = 'No tienes permisos para editar este post';
      header('Location: index.php?action=home');
      exit;
    }

    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    if (empty($title) || empty($content)) {
      $_SESSION['error'] = 'Título y contenido son requeridos';
      include 'views/posts/edit.php';
      return;
    }

    if (strlen($title) < 3) {
      $_SESSION['error'] = 'El título debe tener al menos 3 caracteres';
      include 'views/posts/edit.php';
      return;
    }

    if (strlen($content) < 10) {
      $_SESSION['error'] = 'El contenido debe tener al menos 10 caracteres';
      include 'views/posts/edit.php';
      return;
    }

    $data = [
      'title' => trim($title),
      'content' => trim($content)
    ];

    if ($this->post->update($id, $data)) {
      $_SESSION['success'] = 'Post actualizado correctamente';
      header("Location: index.php?action=show_post&id=$id");
      exit;
    } else {
      $_SESSION['error'] = 'Error al actualizar el post';
      include 'views/posts/edit.php';
    }
  }

  /**
   * Elimina un post
   */
  public function delete()
  {
    if (!isset($_POST['id'])) {
      $_SESSION['error'] = 'Post no especificado';
      header('Location: index.php?action=home');
      exit;
    }

    $id = intval($_POST['id']);
    $post = $this->post->findById($id);

    if (!$post) {
      $_SESSION['error'] = 'Post no encontrado';
      header('Location: index.php?action=home');
      exit;
    }

    if (!isset($_SESSION['user_id'])) {
      $_SESSION['error'] = 'Debes iniciar sesión';
      header('Location: index.php?action=login');
      exit;
    }

    if ($_SESSION['user_id'] != $post['user_id'] && $_SESSION['user_role'] !== 'admin') {
      $_SESSION['error'] = 'No tienes permisos para eliminar este post';
      header('Location: index.php?action=home');
      exit;
    }

    if ($this->post->delete($id)) {
      $_SESSION['success'] = 'Post eliminado correctamente';
      header('Location: index.php?action=home');
      exit;
    } else {
      $_SESSION['error'] = 'Error al eliminar el post';
      header("Location: index.php?action=show_post&id=$id");
      exit;
    }
  }

  /**
   * Obtiene posts por usuario (para el perfil)
   */
  public function getUserPosts($userId)
  {
    return $this->post->getByUserId($userId);
  }

  /**
   * Obtiene posts por búsqueda
   */
  public function search()
  {
    $query = $_GET['q'] ?? '';
    
    if (empty($query)) {
      header('Location: index.php?action=home');
      exit;
    }

    $posts = $this->post->search($query);
    include 'views/posts/index.php';
  }
}
