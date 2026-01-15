<?php
require_once 'models/User.php';
require_once 'config/Database.php';

class UserController
{
  private $user;
  private $db;

  public function __construct()
  {
    $database = new Database();
    $this->db = $database->getPdo();
    $this->user = new User($this->db);
  }

  /**
   * Muestra el formulario de login
   */
  public function showLogin()
  {
    include 'views/auth/login.php';
  }

  /**
   * Procesa el login del usuario
   */
  public function login()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->showLogin();
      return;
    }

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';


    // Guardamos los datos
    $_SESSION['old_input'] = $_POST;

    if (empty($email) || empty($password)) {
      $_SESSION['error'] = 'Email y contraseña son requeridos';
      header('Location: index.php?action=login');
      exit;
    }

    $userData = $this->user->findByEmail($email);

    if (!$userData) {
      $_SESSION['error'] = 'Email o contraseña incorrectos';
      header('Location: index.php?action=login');
      exit;
    }

    if (!password_verify($password, $userData['password_hash'])) {
      $_SESSION['error'] = 'Email o contraseña incorrectos';
      header('Location: index.php?action=login');
      exit;
    }

    // Login exitoso
    $_SESSION['user_id'] = $userData['id'];
    $_SESSION['user_name'] = $userData['nombre'];
    $_SESSION['user_surname'] = $userData['apellido'];
    $_SESSION['user_email'] = $userData['email'];
    $_SESSION['user_role'] = $userData['rol'];

    header('Location: index.php?action=home');
    exit;
  }

  /**
   * Muestra el formulario de registro
   */
  public function showRegister()
  {
    include 'views/auth/register.php';
  }

  /**
   * Procesa el registro de un nuevo usuario
   */
  public function register()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->showRegister();
      return;
    }

    $_SESSION['old_input'] = $_POST;

    $name = $_POST['name'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validaciones
    if (empty($name) || empty($apellido) || empty($email) || empty($password) || empty($password_confirm)) {
      $_SESSION['error'] = 'Todos los campos son requeridos';
      header('Location: index.php?action=register');
      exit;
    }

    if (strlen($password) < 6) {
      $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres';
      header('Location: index.php?action=register');
      exit;
    }

    if ($password !== $password_confirm) {
      $_SESSION['error'] = 'Las contraseñas no coinciden';
      header('Location: index.php?action=register');
      exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['error'] = 'Email inválido';
      header('Location: index.php?action=register');
      exit;
    }

    // Verificar si el email ya existe
    if ($this->user->findByEmail($email)) {
      $_SESSION['error'] = 'El email ya está registrado';
      header('Location: index.php?action=register');
      exit;
    }

    // Crear el usuario
    $data = [
      'nombre' => $name,
      'apellido' => $apellido,
      'email' => $email,
      'password_hash' => password_hash($password, PASSWORD_BCRYPT),
      'rol' => 'usuario'
    ];

    if ($this->user->insert($data)) {
      $_SESSION['success'] = 'Usuario registrado correctamente. Por favor, inicia sesión';
      header('Location: index.php?action=login');
      exit;
    } else {
      $_SESSION['error'] = 'Error al registrar el usuario';
      header('Location: index.php?action=register');
      exit;
    }
  }

  /**
   * Obtiene el perfil del usuario actual
   */
  public function profile()
  {
    if (!isset($_SESSION['user_id'])) {
      header('Location: index.php?action=login');
      exit;
    }

    $userData = $this->user->findById($_SESSION['user_id']);
    include 'views/auth/profile.php';
  }

  /**
   * Logout del usuario
   */
  public function logout()
  {
    session_destroy();
    header('Location: index.php?action=home');
    exit;
  }

  /**
   * Obtiene todos los usuarios (solo para admin)
   */
  public function getAllUsers()
  {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
      $_SESSION['error'] = 'No tienes permisos para acceder a esta sección';
      header('Location: index.php?action=home');
      exit;
    }

    $users = $this->user->getAll();
    include 'views/admin/users.php';
  }

  /**
   * Actualiza un usuario (Admin)
   */
  public function updateUser()
  {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
      $_SESSION['error'] = 'No tienes permisos';
      header('Location: index.php?action=home');
      exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('Location: adminPanel.php');
      exit;
    }

    $id = $_POST['id'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $email = $_POST['email'] ?? '';
    $rol = $_POST['rol'] ?? 'usuario';

    if (empty($id) || empty($email)) {
      $_SESSION['error'] = 'ID y Email son requeridos';
      header('Location: adminPanel.php');
      exit;
    }

    $data = [
      'nombre' => $nombre,
      'apellido' => $apellido,
      'email' => $email,
      'rol' => $rol
    ];

    // Si se envía password (opcional)
    if (!empty($_POST['password'])) {
      $data['password_hash'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
    }

    if ($this->user->update($id, $data)) {
      $_SESSION['success'] = 'Usuario actualizado correctamente';
    } else {
      $_SESSION['error'] = 'Error al actualizar el usuario';
    }

    header('Location: adminPanel.php');
    exit;
  }
}

