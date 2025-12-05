<?php
// modulos/bomberos/detalles_ajax.php

// Activar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar que se recibe el ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die(json_encode(['error' => 'ID no válido']));
}

$id = intval($_GET['id']);

try {
    // Cargar archivos necesarios
    require_once '../../inclusiones/auth.php';
    require_once '../../inclusiones/conexion.php';
    
    // Verificar permisos
    if (!tienePermiso('bomberos') && !tienePermiso('admin')) {
        die('<div class="alert alert-danger">Acceso denegado</div>');
    }
    
    // Obtener conexión
    $conexion = getConexion();
    
    if (!$conexion) {
        die('<div class="alert alert-danger">Error de conexión a la base de datos</div>');
    }
    
    // Consulta simple para obtener el reporte
    $sql = "SELECT * FROM tblbomberos WHERE Id = ?";
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        die('<div class="alert alert-danger">Error preparando consulta: ' . $conexion->error . '</div>');
    }
    
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        die('<div class="alert alert-warning">Reporte #' . $id . ' no encontrado</div>');
    }
    
    $reporte = $result->fetch_assoc();
    
} catch (Exception $e) {
    die('<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>');
}

// Función para formatear fecha
function formatFecha($fecha) {
    if (!$fecha || $fecha == '0000-00-00 00:00:00') return 'No disponible';
    $date = new DateTime($fecha);
    return $date->format('d/m/Y H:i:s');
}

// Función para determinar el color del estado
function getColorEstado($estatus) {
    switch ($estatus) {
        case 'Pendiente': return 'warning';
        case 'En proceso': return 'info';
        case 'Atendido': return 'success';
        default: return 'secondary';
    }
}

// Función para determinar el color del tipo de evento
function getColorEvento($evento) {
    switch ($evento) {
        case 'Incendio': return 'danger';
        case 'Fuga gas': return 'warning';
        case 'Rescate': return 'success';
        default: return 'primary';
    }
}
?>

<div class="detalles-reporte">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-2">
                Reporte #<?php echo $reporte['Id']; ?>
                <span class="badge bg-<?php echo getColorEvento($reporte['Evento'] ?? ''); ?> ms-2">
                    <?php echo htmlspecialchars($reporte['Evento'] ?? 'Desconocido'); ?>
                </span>
            </h4>
            <p class="text-muted mb-0">
                <i class="fas fa-calendar-alt me-1"></i>
                Reportado el: <?php echo formatFecha($reporte['Fecha_reporte'] ?? ''); ?>
            </p>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge bg-<?php echo getColorEstado($reporte['Estatus'] ?? ''); ?> p-2 fs-6">
                <?php echo $reporte['Estatus'] ?? 'Desconocido'; ?>
            </span>
        </div>
    </div>

    <hr>

    <!-- Información de la Víctima -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3 text-primary">
                <i class="fas fa-user me-2"></i>Información de la Víctima
            </h5>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label fw-bold">Nombre completo:</label>
                <p class="form-control-plaintext"><?php echo htmlspecialchars($reporte['NombreVictima'] ?? 'No disponible'); ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label fw-bold">Edad:</label>
                <p class="form-control-plaintext"><?php echo htmlspecialchars($reporte['EdadVictima'] ?? '') . ' años'; ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label fw-bold">Teléfono:</label>
                <p class="form-control-plaintext">
                    <i class="fas fa-phone me-1"></i>
                    <?php echo htmlspecialchars($reporte['NumeroTelEmergencia'] ?? 'No disponible'); ?>
                </p>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label class="form-label fw-bold">Correo electrónico:</label>
                <p class="form-control-plaintext">
                    <i class="fas fa-envelope me-1"></i>
                    <?php echo htmlspecialchars($reporte['Correo'] ?? 'No disponible'); ?>
                </p>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label class="form-label fw-bold">Dirección:</label>
                <p class="form-control-plaintext">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    <?php echo htmlspecialchars($reporte['DireccionVictima'] ?? 'No disponible'); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Información del Evento -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3 text-primary">
                <i class="fas fa-exclamation-triangle me-2"></i>Información del Evento
            </h5>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label fw-bold">Lugar del evento:</label>
                <p class="form-control-plaintext">
                    <i class="fas fa-map-pin me-1"></i>
                    <?php echo htmlspecialchars($reporte['LugarEvento'] ?? 'No disponible'); ?>
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label fw-bold">Tipo de emergencia:</label>
                <p class="form-control-plaintext">
                    <span class="badge bg-<?php echo getColorEvento($reporte['Evento'] ?? ''); ?>">
                        <?php echo htmlspecialchars($reporte['Evento'] ?? 'Desconocido'); ?>
                    </span>
                </p>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label class="form-label fw-bold">Descripción detallada:</label>
                <div class="card">
                    <div class="card-body">
                        <?php echo nl2br(htmlspecialchars($reporte['DescripcionEvento'] ?? 'No hay descripción disponible')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información adicional -->
    <div class="row">
        <div class="col-12">
            <h5 class="mb-3 text-primary">
                <i class="fas fa-info-circle me-2"></i>Información Adicional
            </h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ID Central:</label>
                        <p class="form-control-plaintext">#<?php echo htmlspecialchars($reporte['IdCentral'] ?? 'N/A'); ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Fecha de reporte:</label>
                        <p class="form-control-plaintext">
                            <i class="fas fa-clock me-1"></i>
                            <?php echo formatFecha($reporte['Fecha_reporte'] ?? ''); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Cerrar conexión si existe
if (isset($stmt)) {
    $stmt->close();
}
if (isset($conexion)) {
    $conexion->close();
}
?>