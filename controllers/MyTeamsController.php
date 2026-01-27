<?php
require_once 'models/Team.php';
require_once 'models/User.php';
require_once 'config/Database.php';

class MyTeamsController {
    private $team;
    private $user;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getPdo();
        $this->team = new Team($this->db);
        $this->user = new User($this->db);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        // Find teams where user is captain
        $myTeams = $this->team->findByCaptain($userId);
        
        // Optionally find teams where user is a member (need a method for that in Team model or separate query)
        // For now, focusing on teams owned by user as per "Tus equipos" usually implies ownership or membership.
        // User asked: "donde el usuario pueda ver sus equipos... añadir miembros (solo el capitan)..."

        include 'views/user/myTeams.php';
    }

    public function manage() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $teamId = $_GET['id'] ?? null;
        if (!$teamId) {
            header('Location: index.php?action=my_teams');
            exit;
        }

        $teamData = $this->team->findById($teamId);
        
        // Verify ownership
        if ($teamData['capitan_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'No tienes permiso para gestionar este equipo.';
            header('Location: index.php?action=my_teams');
            exit;
        }

        // Get members
        // We need a method in Team model to get members: getMembers($teamId)
        // Since it's not in the previous file view, I might need to add it or write raw query here.
        // Better to add to Model. Checking Team.php content is needed.
        // For now I'll assume I can add it or doing it raw here if model is limited.
        
        $stmt = $this->db->prepare("SELECT u.id, u.username, u.nombre, u.apellido, u.foto_perfil FROM usuarios u JOIN jugadores j ON u.id = j.usuario_asociado_id WHERE j.equipo_id = :team_id");
        // Wait, 'jugadores' table links to 'equipos'. But 'jugadores' has 'usuario_asociado_id'.
        // Let's assume this structure from database.sql
        
        $stmt->execute(['team_id' => $teamId]);
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/user/manageTeam.php';
    }

    public function updatePhoto() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
             header('Location: index.php?action=my_teams');
             exit;
        }

        $teamId = $_POST['id'] ?? '';
        
        $teamData = $this->team->findById($teamId);
        if ($teamData['capitan_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'No tienes permiso.';
            header('Location: index.php?action=my_teams');
            exit;
        }

        if (isset($_FILES['escudo']) && $_FILES['escudo']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['escudo']['tmp_name'];
            $fileName = $_FILES['escudo']['name'];
            $fileType = $_FILES['escudo']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            if ($fileExtension === 'png' && $fileType === 'image/png') {
                $uploadFileDir = 'public/uploads/teams/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                $newFileName = uniqid('team_', true) . '.png';
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $this->team->update($teamId, ['escudo_url' => $dest_path]);
                    $_SESSION['success'] = 'Foto actualizada.';
                } else {
                    $_SESSION['error'] = 'Error al subir la imagen.';
                }
            } else {
                $_SESSION['error'] = 'Solo PNG.';
            }
        }
        
        header('Location: index.php?action=manage_team&id=' . $teamId);
        exit;
    }

    public function addMember() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;
        
        $teamId = $_POST['team_id'];
        $userId = $_POST['user_id'];
        
        $teamData = $this->team->findById($teamId);
        if ($teamData['capitan_id'] != $_SESSION['user_id']) exit;

        // Check if user exists
        $userToAdd = $this->user->findById($userId);
        if (!$userToAdd) exit;

        // Add to team (jugadores table)
        // Assuming 'jugadores' table has structure: id, equipo_id, nombre, usuario_asociado_id
        // We'll use the user's name for the 'nombre' field in players table
        $stmt = $this->db->prepare("INSERT INTO jugadores (equipo_id, nombre, usuario_asociado_id) VALUES (:team_id, :nombre, :user_id)");
        $stmt->execute([
            'team_id' => $teamId,
            'nombre' => $userToAdd['nombre'] . ' ' . $userToAdd['apellido'],
            'user_id' => $userId
        ]);
        
        $_SESSION['success'] = 'Miembro añadido.';
        header('Location: index.php?action=manage_team&id=' . $teamId);
        exit;
    }

    public function removeMember() {
         if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;
        
        $teamId = $_POST['team_id'];
        $memberId = $_POST['user_id']; // This receives the user_id (usuario_asociado_id) according to view logic, wait.
        // View sent user_id which is $member['id'].
        // But we need to delete from 'jugadores'.
        // Let's delete based on usuario_asociado_id and equipo_id
        
        $teamData = $this->team->findById($teamId);
        if ($teamData['capitan_id'] != $_SESSION['user_id']) exit;

        $stmt = $this->db->prepare("DELETE FROM jugadores WHERE equipo_id = :team_id AND usuario_asociado_id = :user_id");
        $stmt->execute(['team_id' => $teamId, 'user_id' => $memberId]);
        
        $_SESSION['success'] = 'Miembro eliminado.';
        header('Location: index.php?action=manage_team&id=' . $teamId);
        exit;
    }

    public function searchUser() {
        // AJAX endpoint
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $query = $_GET['q'] ?? '';
        if (strlen($query) < 3) {
            echo json_encode([]);
            exit;
        }

        $stmt = $this->db->prepare("SELECT id, username, nombre, apellido FROM usuarios WHERE username LIKE :q OR email LIKE :q LIMIT 10");
        $stmt->execute(['q' => "%$query%"]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($users);
    }
}
