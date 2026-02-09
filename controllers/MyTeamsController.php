<?php
require_once 'models/Team.php';
require_once 'models/User.php';
require_once 'config/Database.php';

class MyTeamsController
{
    private $team;
    private $user;
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getPdo();
        $this->team = new Team($this->db);
        $this->user = new User($this->db);
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        
        $captainTeams = $this->team->findByCaptain($userId);

        $memberTeams = [];
        $currentUser = $this->user->findById($userId);
        if ($currentUser && !empty($currentUser['equipo_id'])) {
            $team = $this->team->findById($currentUser['equipo_id']);
            if ($team) {
                $memberTeams[] = $team;
            }
        }

        $allTeams = array_merge($captainTeams, $memberTeams);
        $myTeams = [];
        foreach ($allTeams as $t) {
            $myTeams[$t['id']] = $t;
        }
        $myTeams = array_values($myTeams);

        include 'views/user/myTeams.php';
    }

    public function manage()
    {
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

        if ($teamData['capitan_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = __('permission_denied');
            header('Location: index.php?action=my_teams');
            exit;
        }

        $stmt = $this->db->prepare("SELECT id, username, nombre, apellido, foto_perfil FROM usuarios WHERE equipo_id = :team_id");
        $stmt->execute(['team_id' => $teamId]);
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $captain = $this->user->findById($teamData['capitan_id']);

        include 'views/user/manageTeam.php';
    }

    public function updatePhoto()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=my_teams');
            exit;
        }

        $teamId = $_POST['id'] ?? '';

        $teamData = $this->team->findById($teamId);
        if ($teamData['capitan_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = __('permission_denied');
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
                    $_SESSION['success'] = __('photo_updated');
                } else {
                    $_SESSION['error'] = __('error_upload');
                }
            } else {
                $_SESSION['error'] = __('error_png');
            }
        }

        header('Location: index.php?action=manage_team&id=' . $teamId);
        exit;
    }

    public function addMember()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
            exit;

        $teamId = $_POST['team_id'];
        $userId = $_POST['user_id'] ?? null;
        $username = $_POST['username'] ?? null;

        $teamData = $this->team->findById($teamId);
        if ($teamData['capitan_id'] != $_SESSION['user_id'])
            exit;

        $userToAdd = null;
        if ($userId) {
            $userToAdd = $this->user->findById($userId);
        } elseif ($username) {
            $userToAdd = $this->user->findByUsername($username);
        }

        if (!$userToAdd) {
            $_SESSION['error'] = __('user_not_found');
            header('Location: index.php?action=manage_team&id=' . $teamId);
            exit;
        }

        $stmt = $this->db->prepare("UPDATE usuarios SET equipo_id = :team_id WHERE id = :user_id");
        $stmt->execute([
            'team_id' => $teamId,
            'user_id' => $userToAdd['id']
        ]);

        $_SESSION['success'] = __('member_added');
        header('Location: index.php?action=manage_team&id=' . $teamId);
        exit;
    }

    public function removeMember()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
            exit;

        $teamId = $_POST['team_id'];
        $memberId = $_POST['user_id'];

        $teamData = $this->team->findById($teamId);
        if ($teamData['capitan_id'] != $_SESSION['user_id'])
            exit;

        $stmt = $this->db->prepare("UPDATE usuarios SET equipo_id = NULL WHERE id = :user_id AND equipo_id = :team_id");
        $stmt->execute(['team_id' => $teamId, 'user_id' => $memberId]);

        $_SESSION['success'] = __('member_removed');
        header('Location: index.php?action=manage_team&id=' . $teamId);
        exit;
    }

    public function searchUser()
    {
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

        $stmt = $this->db->prepare("SELECT id, username, nombre, apellido FROM usuarios WHERE username LIKE :q OR email LIKE :q OR nombre LIKE :q OR apellido LIKE :q LIMIT 10");
        $stmt->execute(['q' => "%$query%"]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($users);
    }
}
