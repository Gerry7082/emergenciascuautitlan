<?php
// repositories/BaseRepository.php

class BaseRepository
{
    protected $conexion;
    protected $tabla;

    public function __construct($conexion, $tabla)
    {
        $this->conexion = $conexion;
        $this->tabla = $tabla;
    }

    // Método para ejecutar consultas preparadas
    protected function execute($sql, $params = [])
    {
        $stmt = $this->conexion->prepare($sql);

        if ($params) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt;
    }

    // Obtener todos los registros
    public function getAll($limit = 100)
    {
        $sql = "SELECT * FROM {$this->tabla} ORDER BY Id DESC LIMIT ?";
        $stmt = $this->execute($sql, [$limit]);
        $result = $stmt->get_result();

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    }

    // Obtener por ID
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE Id = ?";
        $stmt = $this->execute($sql, [$id]);
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // Crear nuevo registro
    public function create($data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $values = array_values($data);

        $sql = "INSERT INTO {$this->tabla} ($columns) VALUES ($placeholders)";
        $stmt = $this->execute($sql, $values);

        return $this->conexion->insert_id;
    }

    // Actualizar registro
    public function update($id, $data)
    {
        $setClause = implode(' = ?, ', array_keys($data)) . ' = ?';
        $values = array_values($data);
        $values[] = $id;

        $sql = "UPDATE {$this->tabla} SET $setClause WHERE Id = ?";
        $stmt = $this->execute($sql, $values);

        return $stmt->affected_rows > 0;
    }

    // Eliminar registro
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->tabla} WHERE Id = ?";
        $stmt = $this->execute($sql, [$id]);

        return $stmt->affected_rows > 0;
    }

    // Contar registros
    public function count()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->tabla}";
        $stmt = $this->execute($sql);
        $result = $stmt->get_result();

        return $result->fetch_assoc()['total'];
    }
}
