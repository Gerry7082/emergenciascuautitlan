<?php
// inclusiones/config.php - VERSIÓN SIMPLE

// Función para obtener ruta base (para archivos)
function obtenerRutaBase() {
    $script_path = $_SERVER['SCRIPT_NAME'];
    $niveles = substr_count(dirname($script_path), '/');
    
    if ($niveles == 0) {
        return './';
    } else {
        return str_repeat('../', $niveles);
    }
}

// Otras configuraciones si las necesitas
?>