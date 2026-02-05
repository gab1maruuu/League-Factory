<?php
class League
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM ligas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM ligas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAllSorted()
    {
        $stmt = $this->db->query("SELECT * FROM ligas ORDER BY fecha_creacion DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO ligas (nombre, descripcion, deporte, temporada, estado, creado_por) 
                VALUES (:nombre, :descripcion, :deporte, :temporada, :estado, :creado_por)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
            'deporte' => $data['deporte'] ?? 'Futbol', // Default value or require it
            'temporada' => $data['temporada'] ?? null,
            'estado' => $data['estado'] ?? 'abierta',
            'creado_por' => $data['creado_por']
        ]);
    }

    public function update($id, $data)
    {
        // Filter allowed fields to prevent arbitrary column updates
        $allowed = ['nombre', 'descripcion', 'deporte', 'temporada', 'estado'];
        $data = array_intersect_key($data, array_flip($allowed));

        if (empty($data)) {
            return false;
        }

        $set = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
        $data['id'] = $id;

        $stmt = $this->db->prepare("UPDATE ligas SET $set WHERE id = :id");
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM ligas WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getParticipants($leagueId)
    {
        $sql = "SELECT e.id, e.nombre, e.escudo_url 
                FROM inscripciones_liga il
                JOIN equipos e ON il.equipo_id = e.id
                WHERE il.liga_id = :leagueId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['leagueId' => $leagueId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getParticipantCount($leagueId)
    {
        $sql = "SELECT COUNT(*) as total FROM inscripciones_liga WHERE liga_id = :leagueId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['leagueId' => $leagueId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['total'] : 0;
    }

    public function getUserLeaguesWithStandings($userId)
    {
        // 1. Find all leagues where one of the user's managed teams is playing
        // OR simply where valid teams are participants. 
        // Logic: "Leagues with scores of each team of the leagues the user is part of"
        // If I am in a league, I want to see the WHOLE table of that league.
        
        // Find distinct league IDs where user has a team (captain or creator)
        $sqlLeagues = "
            SELECT DISTINCT l.* 
            FROM ligas l
            JOIN inscripciones_liga il ON l.id = il.liga_id
            JOIN equipos e ON il.equipo_id = e.id
            WHERE e.capitan_id = :userId OR e.creado_por = :userId
            ORDER BY l.fecha_creacion DESC
        ";
        $stmt = $this->db->prepare($sqlLeagues);
        $stmt->execute(['userId' => $userId]);
        $leagues = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2. For each league, get the full standings
        foreach ($leagues as &$league) {
            $sqlStandings = "
                SELECT e.nombre, e.escudo_url, il.* 
                FROM inscripciones_liga il
                JOIN equipos e ON il.equipo_id = e.id
                WHERE il.liga_id = :leagueId
                ORDER BY il.puntos DESC, il.goles_favor DESC
            ";
            $stmtStandings = $this->db->prepare($sqlStandings);
            $stmtStandings->execute(['leagueId' => $league['id']]);
            $league['standings'] = $stmtStandings->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $leagues;
    }
}
?>