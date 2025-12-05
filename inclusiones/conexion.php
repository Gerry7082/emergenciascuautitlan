<?php
// inclusiones/conexion.php

class Database
{
    private $servidor = "sql101.infinityfree.com";
    private $usuario = "if0_40232109";
    private $password = "dDrxGnqq6Q";
    private $basedatos = "if0_40232109_restaurante";
    private $conexion;

    public function conectar()
    {
        $this->conexion = new mysqli($this->servidor, $this->usuario, $this->password, $this->basedatos);

        if ($this->conexion->connect_error) {
            error_log("Error de conexión: " . $this->conexion->connect_error);
            die("Error de conexión a la base de datos");
        }

        $this->conexion->set_charset("utf8mb4");
        return $this->conexion;
    }

    public function desconectar()
    {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }
}

function getConexion()
{
    $db = new Database();
    return $db->conectar();
}

function limpiarDatos($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>