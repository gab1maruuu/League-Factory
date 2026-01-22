<?php
require_once 'models/League.php';
require_once 'config/Database.php';

class LeagueController
{
    private $league;
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getPdo();
        $this->league = new League($this->db);
    }

    public function index()
    {
        require_once 'views/league/index.php';
    }

    public function updateLeague()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = 'No tienes permiso para realizar esta acción.';
            header("Location: index.php?action=home");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $nombre = $_POST['nombre'] ?? '';

            if (!$id || empty($nombre)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios.';
                header("Location: index.php?action=admin#ligas");
                exit;
            }

            $data = [
                'nombre' => $nombre
            ];

            if ($this->league->update($id, $data)) {
                $_SESSION['success'] = 'Liga actualizada correctamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar la liga.';
            }
            header("Location: index.php?action=admin#ligas");
            exit;
        }
    }
}
?>