<?php
require_once 'models/League.php';
require_once 'models/Team.php';
require_once 'config/Database.php';

class LeagueController
{
    private $league;
    private $team;
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getPdo();
        $this->league = new League($this->db);
        $this->team = new Team($this->db);
    }

    public function index()
    {
        require_once 'views/league/index.php';
    }

    public function joinList()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
             // Optional: redirect to login or just show empty list (but we want them to join, so maybe allow viewing but not joining action acts as trigger)
             // For now, let's keep it open, but we need variables for the view.
        }

        $leagues = $this->league->findAllSorted();
        
        // Add participants capability to join view (count/limit)
        foreach ($leagues as &$league) {
            $league['participant_count'] = $this->league->getParticipantCount($league['id']);
            // We can also fetch the exact list if needed for the modal, but count is essential for the card
        }
        unset($league);

        $myTeams = [];
        if (isset($_SESSION['user_id'])) {
            $myTeams = $this->team->findMyTeams($_SESSION['user_id']);
        }

        require_once 'views/league/join.php';
    }

    public function myStandings()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $myLeagues = $this->league->getUserLeaguesWithStandings($_SESSION['user_id']);
        require_once 'views/league/standings.php';
    }

    public function joinLeagueWithTeam()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = __('login_required_join');
            header("Location: index.php?action=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $leagueId = $_POST['league_id'] ?? null;
            $teamId = $_POST['team_id'] ?? null;

            if (!$leagueId || !$teamId) {
                $_SESSION['error'] = __('invalid_league_team');
                header("Location: index.php?action=join_league");
                exit;
            }

            // Check limit (Max 14)
            $currentCount = $this->league->getParticipantCount($leagueId);
            if ($currentCount >= 14) {
                $_SESSION['error'] = __('league_full_error');
                header("Location: index.php?action=join_league");
                exit;
            }

            // Verify team ownership validation
            $team = $this->team->findById($teamId);
            if (!$team || ($team['capitan_id'] != $_SESSION['user_id'] && $team['creado_por'] != $_SESSION['user_id'])) {
                $_SESSION['error'] = __('permission_denied_team');
                header("Location: index.php?action=join_league");
                exit;
            }

            // Check if already registered (should be DB constraint or check here)
            // Assuming DB has UNIQUE(liga_id, equipo_id) as seen in schema
            
            try {
                // Insert into inscripciones_liga
                $stmt = $this->db->prepare("INSERT INTO inscripciones_liga (liga_id, equipo_id) VALUES (:liga_id, :equipo_id)");
                $stmt->execute(['liga_id' => $leagueId, 'equipo_id' => $teamId]);
                $_SESSION['success'] = __('team_joined_success');
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) { // Duplicate entry
                     $_SESSION['error'] = __('team_already_joined');
                } else {
                     $_SESSION['error'] = __('join_error') . $e->getMessage();
                }
            }

            header("Location: index.php?action=join_league");
            exit;
        }
    }

    public function getLeagueParticipants()
    {
        // AJAX endpoint
        ob_clean(); // Clear any previous output (header.php, etc)
        header('Content-Type: application/json');
        
        $leagueId = $_GET['id'] ?? null;
        if (!$leagueId) {
            echo json_encode([]);
            exit;
        }

        try {
            $participants = $this->league->getParticipants($leagueId);
            echo json_encode($participants);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
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