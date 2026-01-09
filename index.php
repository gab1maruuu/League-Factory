<?php
session_start();
require_once 'config/Database.php';
require_once 'controllers/UserController.php';
require_once 'controllers/PostController.php';

$action = $_GET['action'] ?? 'home';

/**
 * 1. LÓGICA DE ACCIONES QUE REDIRIGEN (Sin HTML)
 * Ponemos aquí las acciones que NO deben cargar el header todavía
 */
if ($action === 'logout') {
    (new UserController())->logout(); // Este método hace session_destroy y header()
    exit;
}

// Si es un POST de login o registro, mejor procesarlo antes del HTML también
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'login') {
        (new UserController())->login();
        exit;
    }
    if ($action === 'register') {
        (new UserController())->register();
        exit;
    }
}

/**
 * 2. CARGA DE INTERFAZ (Con HTML)
 * Solo llegamos aquí si no hubo una redirección antes
 */
include __DIR__ ."/views/layout/header.php";

switch($action) {
    case 'home':
        include __DIR__ ."/views/layout/inicio.php";
        break;

    case 'login': 
        (new UserController())->showLogin(); // Solo muestra el formulario
        break;
        
    case 'register': 
        (new UserController())->showRegister(); // Solo muestra el formulario
        break;

    case 'posts': 
        (new PostController())->index(); 
        break;

    case 'admin':
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=home");
            exit;
        }
        (new UserController())->getAllUsers();
        break;

    default: 
        echo "<div class='text-white p-10 text-center'>Error 404: Página no encontrada</div>";
        break;
}

include __DIR__ ."/views/layout/footer.php";