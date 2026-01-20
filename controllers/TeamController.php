<?php
require_once 'models/Team.php';
require_once 'config/Database.php';

class TeamController {
    private $team;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getPdo();
        $this->team = new Team($this->db);
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['admin', 'organizador', 'usuario'])) {
            $_SESSION['error'] = 'No tienes permisos para crear equipos.';
            header('Location: index.php?action=home');
            exit;
        }
        include 'views/layout/createTeam.php';
    }

    public function store() {
        if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['admin', 'organizador', 'usuario'])) {
            $_SESSION['error'] = 'No tienes permisos para crear equipos.';
            header('Location: index.php?action=home');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=create_team');
            exit;
        }

        $nombre = $_POST['nombre'] ?? '';
        $escudo_url = $_POST['escudo_url'] ?? '';
        $capitan_id = $_POST['capitan_id'] ?? null; // Optional, or handle as needed

        if (empty($nombre)) {
            $_SESSION['error'] = 'El nombre del equipo es obligatorio.';
            header('Location: index.php?action=create_team');
            exit;
        }

        $data = [
            'nombre' => $nombre,
            'escudo_url' => $escudo_url,
            'capitan_id' => $capitan_id
        ];

        if ($this->team->insert($data)) {
            $_SESSION['success'] = 'Equipo creado exitosamente.';
            header('Location: index.php?action=home'); // Redirect to home or team list
            exit;
        } else {
            $_SESSION['error'] = 'Error al crear el equipo.';
            header('Location: index.php?action=create_team');
            exit;
        }
    }
}
