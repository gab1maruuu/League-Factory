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
}
?>