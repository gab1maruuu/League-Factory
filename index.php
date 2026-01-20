<?php
session_start();
require_once 'config/Database.php';
require_once 'utils/i18n.php';
require_once 'controllers/UserController.php';
require_once 'controllers/PostController.php';
require_once 'controllers/TeamController.php';

$action = $_GET['action'] ?? 'home';


if ($action === 'logout') {
    (new UserController())->logout(); 
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'login') {
        (new UserController())->login();
        exit;
    }
    if ($action === 'register') {
        (new UserController())->register();
        exit;
    }
    if ($action === 'update_user') {
        (new UserController())->updateUser();
        exit;
    }
    if ($action === 'update_team') {
        (new UserController())->updateTeam();
        exit;
    }
}

include __DIR__ . "/views/layout/header.php";

switch ($action) {
    case 'home':
        include __DIR__ . "/views/layout/inicio.php";
        break;

    case 'profile':
        (new UserController())->profile();
        break;

    case 'login': 
        (new UserController())->showLogin();
        break;
        
    case 'register': 
        (new UserController())->showRegister(); 
        break;

    case 'register':
        (new UserController())->showRegister(); 
        break;

    case 'posts':
        (new PostController())->index();
        break;

    case 'create_team':
        (new TeamController())->create();
        break;

    case 'store_team':
        (new TeamController())->store();
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

include __DIR__ . "/views/layout/footer.php";