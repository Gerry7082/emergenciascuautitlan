<?php
// repositories/BomberosRepository.php
require_once 'BaseRepository.php';

class BomberosRepository extends BaseRepository
{

    public function __construct($conexion)
    {
        parent::__construct($conexion, 'tblbomberos');
    }

    // Métodos específicos para bomberos

    public function getByTipo($tipo)
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE Evento LIKE ? ORDER BY Id DESC";
        $stmt = $this->execute($sql, ["%$tipo%"]);
        $result = $stmt->get_result();

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    }

    public function getActivas()
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE Estatus IN ('Pendiente', 'En proceso') ORDER BY Id DESC";
        $stmt = $this->execute($sql);
        $result = $stmt->get_result();

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    }

    public function getByEstatus($estatus)
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE Estatus = ? ORDER BY Id DESC";
        $stmt = $this->execute($sql, [$estatus]);
        $result = $stmt->get_result();

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    }

    public function updateEstatus($id, $estatus)
    {
        $sql = "UPDATE {$this->tabla} SET Estatus = ? WHERE Id = ?";
        $stmt = $this->execute($sql, [$estatus, $id]);

        return $stmt->affected_rows > 0;
    }

    public function getEstadisticas()
    {
        $sql = "SELECT 
                    Evento,
                    COUNT(*) as total,
                    SUM(CASE WHEN Estatus = 'Atendido' THEN 1 ELSE 0 END) as atendidos
                FROM {$this->tabla} 
                GROUP BY Evento";

        $stmt = $this->execute($sql);
        $result = $stmt->get_result();

        $estadisticas = [];
        while ($row = $result->fetch_assoc()) {
            $estadisticas[] = $row;
        }
        return $estadisticas;
    }

    public function search($termino)
    {
        $sql = "SELECT * FROM {$this->tabla} 
                WHERE NombreVictima LIKE ? 
                   OR LugarEvento LIKE ? 
                   OR DescripcionEvento LIKE ?
                ORDER BY Id DESC";

        $likeTerm = "%$termino%";
        $stmt = $this->execute($sql, [$likeTerm, $likeTerm, $likeTerm]);
        $result = $stmt->get_result();

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    }
}
