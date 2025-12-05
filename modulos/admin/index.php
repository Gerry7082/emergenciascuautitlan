<?php
require_once '../../inclusiones/auth.php';

if (!tienePermiso('admin')) {
    $rol = obtenerUsuarioActual()['rol'];
    redirigirAModulo($rol);
}

require_once '../../inclusiones/conexion.php';

// Obtener estadísticas
$total_reporte = $conexion->query("SELECT COUNT(*) as total FROM tblcentral")->fetch_assoc()['total'];
$pendientes = $conexion->query("SELECT COUNT(*) as total FROM tblcentral WHERE Estatus = 'Pendiente'")->fetch_assoc()['total'];
$en_proceso = $conexion->query("SELECT COUNT(*) as total FROM tblcentral WHERE Estatus = 'En proceso'")->fetch_assoc()['total'];
$atendidos = $conexion->query("SELECT COUNT(*) as total FROM tblcentral WHERE Estatus = 'Atendido'")->fetch_assoc()['total'];

include '../../inclusiones/encabezado.php';
?>

<div class="contenedor-modulo">
    <h2>Módulo de Administración</h2>
    <p>Hola Mundo - Administración</p>
</div>

<?php include '../../inclusiones/pie.php'; ?>