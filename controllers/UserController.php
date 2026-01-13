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

    if (empty($email) || empty($password)) {
      $_SESSION['error'] = 'Email y contraseña son requeridos';
      include 'views/auth/login.php';
      return;
    }

    $userData = $this->user->findByEmail($email);

    if (!$userData) {
      $_SESSION['error'] = 'Email o contraseña incorrectos';
      include 'views/auth/login.php';
      return;
    }

    if (!password_verify($password, $userData['password_hash'])) {
      $_SESSION['error'] = 'Email o contraseña incorrectos';
      include 'views/auth/login.php';
      return;
    }

    // Login exitoso
    $_SESSION['user_id'] = $userData['id'];
    $_SESSION['user_name'] = $userData['nombre_completo'];
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

    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validaciones
    if (empty($name) || empty($email) || empty($password) || empty($password_confirm)) {
      $_SESSION['error'] = 'Todos los campos son requeridos';
      include 'views/auth/register.php';
      return;
    }

    if (strlen($password) < 6) {
      $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres';
      include 'views/auth/register.php';
      return;
    }

    if ($password !== $password_confirm) {
      $_SESSION['error'] = 'Las contraseñas no coinciden';
      include 'views/auth/register.php';
      return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['error'] = 'Email inválido';
      include 'views/auth/register.php';
      return;
    }

    // Verificar si el email ya existe
    if ($this->user->findByEmail($email)) {
      $_SESSION['error'] = 'El email ya está registrado';
      include 'views/auth/register.php';
      return;
    }

    // Crear el usuario
    $data = [
      'nombre' => $name,
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
      include 'views/auth/register.php';
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
}
