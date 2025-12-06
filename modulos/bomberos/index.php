<?php
// modulos/bomberos/index.php

// Incluir archivos necesarios
require_once '../../inclusiones/conexion.php';
require_once '../../inclusiones/encabezado.php';

// Verificar que sea bombero o admin
if (!tienePermiso('bomberos') && !tienePermiso('admin')) {
    header('Location: ../../index.php');
    exit;
}

// Conectar a la base de datos
$conexion = getConexion();

// Incluir repositorio de bomberos
require_once '../../repositories/BomberosRepository.php';

// Inicializar repositorio
$repo = new BomberosRepository($conexion);

// Obtener datos
try {
    $total = $repo->count();
    $activas = $repo->getActivas();
    $estadisticas = $repo->getEstadisticas();
    $ultimosReportes = $repo->getAll(5);
} catch (Exception $e) {
    // Si hay error, mostrar mensaje pero continuar
    $error_db = $e->getMessage();
}

// Cerrar conexión
$conexion->close();
?>

<!-- CONTENIDO ESPECÍFICO DE BOMBEROS -->
<div class="card shadow mb-4">
    <div class="card-header bg-danger text-white">
        <h3 class="mb-0"><i class="fas fa-fire"></i> Panel de Bomberos</h3>
    </div>
    <div class="card-body">
        <?php if (isset($error_db)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> Error: <?php echo $error_db; ?>
            </div>
        <?php endif; ?>

        <p class="lead">Bienvenido al módulo de bomberos, <strong><?php echo $nombre_corto; ?></strong>.</p>

        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Reportes</h5>
                        <h2 class="display-4"><?php echo $total ?? 0; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Activas</h5>
                        <h2 class="display-4"><?php echo count($activas ?? []); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Atendidas</h5>
                        <h2 class="display-4">
                            <?php
                            $atendidas = 0;
                            if (isset($estadisticas)) {
                                foreach ($estadisticas as $estadistica) {
                                    $atendidas += $estadistica['atendidos'] ?? 0;
                                }
                            }
                            echo $atendidas;
                            ?>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Tipos</h5>
                        <h2 class="display-4"><?php echo count($estadisticas ?? []); ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-bolt"></i> Acciones Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="nuevo.php" class="btn btn-danger">
                                <i class="fas fa-plus"></i> Nuevo Reporte
                            </a>
                            <a href="listado.php" class="btn btn-primary">
                                <i class="fas fa-list"></i> Ver Todos
                            </a>
                            <a href="listado.php?estatus=Pendiente" class="btn btn-warning">
                                <i class="fas fa-clock"></i> Pendientes
                            </a>
                            <a href="listado.php?estatus=Atendido" class="btn btn-success">
                                <i class="fas fa-check"></i> Atendidos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- GALERÍA DE BOMBEROS - Nueva sección añadida -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-images"></i> Galería de Bomberos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Imagen 1 -->
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center p-3">
                                        <h6 class="card-title mb-3">
                                            <i class="fas fa-fire-extinguisher"></i> Equipo en Acción
                                        </h6>
                                        <img src="https://images.unsplash.com/photo-1636483392196-f6e81d0979bc?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                                            class="img-fluid rounded shadow-sm"
                                            alt="Bomberos en acción"
                                            style="height: 200px; object-fit: cover; width: 100%;">
                                        <p class="text-muted small mt-2">
                                            Equipo especializado atendiendo emergencia
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Imagen 2 -->
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center p-3">
                                        <h6 class="card-title mb-3">
                                            <i class="fas fa-truck"></i> Unidades de Respuesta
                                        </h6>
                                        <img src="https://images.unsplash.com/photo-1576241836269-8ce9e4b8c7c5?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                                            class="img-fluid rounded shadow-sm"
                                            alt="Unidades de bomberos"
                                            style="height: 200px; object-fit: cover; width: 100%;">
                                        <p class="text-muted small mt-2">
                                            Vehículos especializados para todo tipo de emergencias
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Video -->
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center p-3">
                                        <h6 class="card-title mb-3">
                                            <i class="fas fa-video"></i> Video Informativo
                                        </h6>
                                        <div class="ratio ratio-16x9">
                                            <iframe
                                                src="https://www.youtube.com/watch?v=doI5J-ZXXmQ"
                                                title="Video informativo de bomberos"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen
                                                class="rounded shadow-sm">
                                            </iframe>
                                        </div>
                                        <p class="text-muted small mt-2">
                                            Protocolos de seguridad y respuesta
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información adicional -->
                        <div class="alert alert-info mt-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fa-2x me-3"></i>
                                <div>
                                    <h6 class="mb-1">¿Sabías que?</h6>
                                    <p class="mb-0 small">
                                        Nuestro equipo de bomberos está disponible 24/7 y responde en menos de 5 minutos
                                        a emergencias dentro del área urbana. Contamos con equipamiento de última generación
                                        y personal altamente capacitado.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Últimos reportes -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-history"></i> Últimos 5 Reportes</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($ultimosReportes) && count($ultimosReportes) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Víctima</th>
                                            <th>Tipo</th>
                                            <th>Estado</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ultimosReportes as $reporte): ?>
                                            <tr>
                                                <td><strong>#<?php echo $reporte['Id']; ?></strong></td>
                                                <td><?php echo htmlspecialchars($reporte['NombreVictima']); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php
                                                                            switch ($reporte['Evento']) {
                                                                                case 'Incendio':
                                                                                    echo 'danger';
                                                                                    break;
                                                                                case 'Fuga gas':
                                                                                    echo 'warning';
                                                                                    break;
                                                                                case 'Rescate animal':
                                                                                    echo 'success';
                                                                                    break;
                                                                                default:
                                                                                    echo 'info';
                                                                            }
                                                                            ?>">
                                                        <?php echo htmlspecialchars($reporte['Evento']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php
                                                                            switch ($reporte['Estatus']) {
                                                                                case 'Pendiente':
                                                                                    echo 'warning';
                                                                                    break;
                                                                                case 'En proceso':
                                                                                    echo 'info';
                                                                                    break;
                                                                                case 'Atendido':
                                                                                    echo 'success';
                                                                                    break;
                                                                                default:
                                                                                    echo 'secondary';
                                                                            }
                                                                            ?>">
                                                        <?php echo $reporte['Estatus']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <small><?php echo date('d/m/Y', strtotime($reporte['Fecha_reporte'])); ?></small>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No hay reportes registrados todavía.
                                <a href="nuevo.php" class="btn btn-sm btn-danger ms-2">
                                    <i class="fas fa-plus"></i> Crear primer reporte
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Incluir pie de página
require_once '../../inclusiones/pie.php';
?>