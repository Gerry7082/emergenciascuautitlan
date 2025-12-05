<?php
// modulos/bomberos/actualizar_estatus.php
header('Content-Type: application/json');

// Activar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 0); // Cambiar a 1 si hay problemas

require_once '../../inclusiones/auth.php';
require_once '../../inclusiones/conexion.php';

// Verificar permisos
if (!tienePermiso('bomberos') && !tienePermiso('admin')) {
    echo json_encode([
        'success' => false,
        'message' => 'Acceso denegado. No tiene permisos para esta acción.'
    ]);
    exit;
}

// Verificar que sea una solicitud POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
    ]);
    exit;
}

// Validar datos recibidos
$id = $_POST['id'] ?? 0;
$estatus = $_POST['estatus'] ?? '';

if (!is_numeric($id) || $id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'ID de reporte no válido.'
    ]);
    exit;
}

$estatusesValidos = ['Pendiente', 'En proceso', 'Atendido'];
if (!in_array($estatus, $estatusesValidos)) {
    echo json_encode([
        'success' => false,
        'message' => 'Estado no válido.'
    ]);
    exit;
}

try {
    // Obtener conexión
    $conexion = getConexion();
    
    if (!$conexion) {
        throw new Exception('Error de conexión a la base de datos');
    }
    
    // Primero, verificar que el reporte existe
    $sqlVerificar = "SELECT Id, Estatus FROM tblbomberos WHERE Id = ?";
    $stmtVerificar = $conexion->prepare($sqlVerificar);
    $stmtVerificar->bind_param("i", $id);
    $stmtVerificar->execute();
    $resultado = $stmtVerificar->get_result();
    
    if ($resultado->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Reporte no encontrado.'
        ]);
        exit;
    }
    
    $reporteActual = $resultado->fetch_assoc();
    $estatusAnterior = $reporteActual['Estatus'];
    
    // Actualizar el estado
    $sqlActualizar = "UPDATE tblbomberos SET Estatus = ? WHERE Id = ?";
    $stmtActualizar = $conexion->prepare($sqlActualizar);
    $stmtActualizar->bind_param("si", $estatus, $id);
    
    if (!$stmtActualizar->execute()) {
        throw new Exception('Error al ejecutar la actualización');
    }
    
    if ($stmtActualizar->affected_rows > 0) {
        // Registrar el cambio
        $usuario = obtenerUsuarioActual();
        $nombreUsuario = $usuario['nombre_completo'] ?? 'Usuario desconocido';
        
        echo json_encode([
            'success' => true,
            'message' => "Estado actualizado correctamente de '$estatusAnterior' a '$estatus'.",
            'estatus_anterior' => $estatusAnterior,
            'estatus_nuevo' => $estatus,
            'usuario' => $nombreUsuario
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No se realizaron cambios. El estado puede ser el mismo.'
        ]);
    }
    
    // Cerrar statements
    $stmtVerificar->close();
    $stmtActualizar->close();
    $conexion->close();
    
} catch (Exception $e) {
    error_log("Error al actualizar estado: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor: ' . $e->getMessage()
    ]);
}
?>