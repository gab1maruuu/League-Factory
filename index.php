<?php
session_start();
ob_start();
require_once 'config/Database.php';
require_once 'utils/i18n.php';
require_once 'controllers/UserController.php';
require_once 'controllers/PostController.php';
require_once 'controllers/TeamController.php';
require_once 'controllers/LeagueController.php';
require_once 'controllers/MyTeamsController.php';

$action = $_GET['action'] ?? 'home';


if ($action === 'logout') {
    (new UserController())->logout();
    exit;
}

// Global Authentication Check
// Whitelisted actions that don't require login
$publicActions = ['home', 'login', 'register'];

if (!isset($_SESSION['user_id']) && !in_array($action, $publicActions)) {
    // If it's an AJAX request (like get_league_participants), maybe return 401?
    // But for now, user requested redirection.
    header("Location: index.php?action=login");
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
    if ($action === 'update_league') {
        (new LeagueController())->updateLeague();
        exit;
    }
}


include __DIR__ . "/views/layout/header.php";

switch ($action) {
    case 'home':
        include __DIR__ . "/views/layout/inicio.php";
        break;

    case 'join_league':
        (new LeagueController())->joinList();
        break;

    case 'join_league_submit':
        (new LeagueController())->joinLeagueWithTeam();
        break;

    case 'get_league_participants':
        (new LeagueController())->getLeagueParticipants();
        break;

    case 'my_standings':
        (new LeagueController())->myStandings();
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

    case 'my_teams':
        (new MyTeamsController())->index();
        break;
    
    case 'manage_team':
        (new MyTeamsController())->manage();
        break;

    case 'update_team_photo':
        (new MyTeamsController())->updatePhoto();
        break;

    case 'add_member':
        (new MyTeamsController())->addMember();
        break;

    case 'remove_member':
        (new MyTeamsController())->removeMember();
        break;

    case 'search_user':
        (new MyTeamsController())->searchUser();
        break;

    default:
        echo "<div class='text-white p-10 text-center'>Error 404: Página no encontrada</div>";
        break;
}

include __DIR__ . "/views/layout/footer.php";