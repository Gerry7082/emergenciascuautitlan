<?php
// Incluir auth.php con rutas correctas
require_once '../../inclusiones/auth.php';

// Primero verificar que el usuario esté autenticado
verificarAutenticacion();

// Obtener usuario actual
$usuario = obtenerUsuarioActual();

// Verificar permisos específicos para bomberos
if (!tienePermiso('bomberos') && !tienePermiso('admin')) {
    // Si no tiene permiso, mostrar mensaje y redirigir
    echo "<script>
        alert('No tienes permisos para acceder al módulo de Bomberos');
        window.location.href = '../../index.php';
    </script>";
    exit;
}

// Ahora sí incluir el repositorio
require_once '../../repositories/BomberosRepository.php';

// Inicializar repositorio
require_once '../../inclusiones/conexion.php';
$repo = new BomberosRepository($conexion);

// Obtener estadísticas
$total = $repo->count();
$activas = $repo->getActivas();
$estadisticas = $repo->getEstadisticas();
$ultimosReportes = $repo->getAll(5);

// Incluir encabezado
include '../../inclusiones/encabezado.php';
?>

<div class="container mt-4">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h1 class="card-title">
                        <i class="fas fa-fire"></i> Módulo de Bomberos
                    </h1>
                    <p class="card-text">Gerardo Enrique Vargas Almanza</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Total Reportes</h5>
                    <h2 class="display-4"><?php echo $total; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Activas</h5>
                    <h2 class="display-4"><?php echo count($activas); ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Atendidas</h5>
                    <h2 class="display-4"><?php
                                            $atendidas = array_filter($estadisticas, function ($e) {
                                                return $e['Evento'] == 'Atendido';
                                            });
                                            echo count($atendidas);
                                            ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Tipos</h5>
                    <h2 class="display-4"><?php echo count($estadisticas); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-bolt"></i> Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="nuevo.php" class="btn btn-danger btn-lg w-100">
                                <i class="fas fa-plus"></i> Nuevo Reporte
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="listado.php" class="btn btn-warning btn-lg w-100">
                                <i class="fas fa-list"></i> Ver Todos
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="listado.php?estatus=Pendiente" class="btn btn-info btn-lg w-100">
                                <i class="fas fa-clock"></i> Pendientes
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="#estadisticas" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-chart-bar"></i> Estadísticas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos Reportes -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Últimos Reportes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Víctima</th>
                                    <th>Tipo</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
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
                                                                        case 'Rescate':
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
                                            <a href="ver.php?id=<?php echo $reporte['Id']; ?>"
                                                class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="editar.php?id=<?php echo $reporte['Id']; ?>"
                                                class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tipos de Emergencia -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Tipos de Emergencia</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach ($estadisticas as $estadistica): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($estadistica['Evento']); ?>
                                <span class="badge bg-primary rounded-pill">
                                    <?php echo $estadistica['total']; ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../inclusiones/pie.php'; ?>