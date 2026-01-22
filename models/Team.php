<?php
class Team
{
    private $db;
    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM equipos WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM equipos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $data)
    {
        // Filter allowed fields
        $allowed = ['nombre', 'escudo_url', 'capitan_id'];
        $data = array_intersect_key($data, array_flip($allowed));

        if (empty($data)) {
            return false;
        }

        $set = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
        $data['id'] = $id;

        $stmt = $this->db->prepare("UPDATE equipos SET $set WHERE id = :id");
        return $stmt->execute($data);
    }

    public function insert($data)
    {
        $allowed = ['nombre', 'escudo_url', 'capitan_id', 'creado_por'];
        $data = array_intersect_key($data, array_flip($allowed));
        
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        
        $sql = "INSERT INTO equipos ($columns) VALUES ($values)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM equipos WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>