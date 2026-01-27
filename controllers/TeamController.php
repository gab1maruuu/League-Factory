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
        $capitan_id = $_SESSION['user_id'];
        
        $escudo_url = '';

        // Handle File Upload
        if (isset($_FILES['escudo']) && $_FILES['escudo']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['escudo']['tmp_name'];
            $fileName = $_FILES['escudo']['name'];
            $fileSize = $_FILES['escudo']['size'];
            $fileType = $_FILES['escudo']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            if ($fileExtension === 'png' && $fileType === 'image/png') {
                $uploadFileDir = 'public/uploads/teams/';
                // Ensure dir exists (should exist from previous step, but good safety)
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                
                $newFileName = uniqid('team_', true) . '.png';
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $escudo_url = $dest_path;
                } else {
                    $_SESSION['error'] = 'Error al mover el archivo subido.';
                    header('Location: index.php?action=create_team');
                    exit;
                }
            } else {
                $_SESSION['error'] = 'Solo se permiten imágenes PNG.';
                header('Location: index.php?action=create_team');
                exit;
            }
        }

        if (empty($nombre)) {
            $_SESSION['error'] = 'El nombre del equipo es obligatorio.';
            header('Location: index.php?action=create_team');
            exit;
        }

        $data = [
            'nombre' => $nombre,
            'escudo_url' => $escudo_url,
            'capitan_id' => $capitan_id,
            'creado_por' => $_SESSION['user_id']
        ];

        try {
            if ($this->team->insert($data)) {
                $_SESSION['success'] = 'Equipo creado exitosamente.';
                header('Location: index.php?action=home'); // Redirect to home or team list
                exit;
            } else {
                $_SESSION['error'] = 'Error al crear el equipo.';
                header('Location: index.php?action=create_team');
                exit;
            }
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') { // Integrity constraint violation
                $_SESSION['error'] = 'El ID del capitán proporcionado no es válido (no existe el usuario).';
            } else {
                $_SESSION['error'] = 'Error de base de datos: ' . $e->getMessage();
            }
            header('Location: index.php?action=create_team');
            exit;
        }
    }
}
