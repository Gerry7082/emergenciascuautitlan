<?php
// Activar reporte de errores al inicio
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../inclusiones/auth.php';
require_once '../../repositories/BomberosRepository.php';

// Verificar permisos
if (!tienePermiso('bomberos') && !tienePermiso('admin')) {
    header('Location: ../../index.php');
    exit;
}

require_once '../../inclusiones/conexion.php';

// Obtener conexión
$conexion = getConexion();

// Verificar que la conexión se crea correctamente
if (!$conexion) {
    die("Error: No se pudo establecer conexión con la base de datos");
}

$repo = new BomberosRepository($conexion);

// Manejar filtros
$estatus = $_GET['estatus'] ?? '';
$tipo = $_GET['tipo'] ?? '';
$search = $_GET['search'] ?? '';

// Obtener datos según filtros
try {
    if ($search) {
        $reportes = $repo->search($search);
    } elseif ($estatus) {
        $reportes = $repo->getByEstatus($estatus);
    } elseif ($tipo) {
        $reportes = $repo->getByTipo($tipo);
    } else {
        $reportes = $repo->getAll();
    }
    
    // Verificar que $reportes es un array
    if (!is_array($reportes)) {
        error_log("Error: reportes no es un array. Tipo: " . gettype($reportes));
        $reportes = [];
    }
    
} catch (Exception $e) {
    error_log("Error al obtener reportes: " . $e->getMessage());
    $reportes = [];
}

include '../../inclusiones/encabezado.php';
?>

<div class="container mt-4">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">
                        <i class="fas fa-list"></i> Listado de Reportes
                    </h2>
                    <p class="text-muted">Total: <?php echo count($reportes); ?> reportes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros</h5>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <select class="form-control" name="estatus">
                                <option value="">Todos los estados</option>
                                <option value="Pendiente" <?php echo $estatus == 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="En proceso" <?php echo $estatus == 'En proceso' ? 'selected' : ''; ?>>En proceso</option>
                                <option value="Atendido" <?php echo $estatus == 'Atendido' ? 'selected' : ''; ?>>Atendido</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="search"
                                placeholder="Buscar..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="listado.php" class="btn btn-secondary w-100">
                                <i class="fas fa-redo"></i> Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Reportes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Víctima</th>
                                    <th>Tipo</th>
                                    <th>Lugar</th>
                                    <th>Teléfono</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($reportes)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                            No hay reportes registrados
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($reportes as $reporte): ?>
                                        <?php
                                        // Depuración: verificar estructura de $reporte
                                        if (!is_array($reporte)) {
                                            continue; // Saltar si no es array
                                        }
                                        ?>
                                        <tr>
                                            <td><strong>#<?php echo isset($reporte['Id']) ? $reporte['Id'] : 'N/A'; ?></strong></td>
                                            <td><?php echo isset($reporte['NombreVictima']) ? htmlspecialchars($reporte['NombreVictima']) : 'N/A'; ?></td>
                                            <td>
                                                <?php if (isset($reporte['Evento'])): ?>
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
                                                <?php else: ?>
                                                <span class="badge bg-secondary">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo isset($reporte['LugarEvento']) ? htmlspecialchars(substr($reporte['LugarEvento'], 0, 30)) . '...' : 'N/A'; ?></td>
                                            <td><?php echo isset($reporte['NumeroTelEmergencia']) ? htmlspecialchars($reporte['NumeroTelEmergencia']) : 'N/A'; ?></td>
                                            <td>
                                                <?php if (isset($reporte['Estatus'])): ?>
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
                                                <?php else: ?>
                                                <span class="badge bg-secondary">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <?php if (isset($reporte['Id'])): ?>
                                                    <button type="button" class="btn btn-sm btn-info ver-detalle"
                                                        data-id="<?php echo $reporte['Id']; ?>"
                                                        title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-warning editar-estatus"
                                                        data-id="<?php echo $reporte['Id']; ?>"
                                                        data-victima="<?php echo htmlspecialchars($reporte['NombreVictima']); ?>"
                                                        data-evento="<?php echo htmlspecialchars($reporte['Evento']); ?>"
                                                        data-estatus="<?php echo $reporte['Estatus']; ?>"
                                                        title="Editar Estado">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <?php else: ?>
                                                    <button type="button" class="btn btn-sm btn-info disabled">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-warning disabled">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <a href="nuevo.php" class="btn btn-danger">
                            <i class="fas fa-plus"></i> Nuevo Reporte
                        </a>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalles -->
<div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="detalleModalLabel">
                    <i class="fas fa-file-alt me-2"></i>Detalles del Reporte
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-detalle-content">
                <!-- Contenido cargado por AJAX -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando detalles...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar estatus -->
<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editarModalLabel">
                    <i class="fas fa-edit me-2"></i>Actualizar Estado del Reporte
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-editar-estatus" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="editar-id" name="id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Número de Reporte:</label>
                        <p class="form-control-plaintext" id="editar-numero">#</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Víctima:</label>
                        <p class="form-control-plaintext" id="editar-victima"></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipo de Evento:</label>
                        <p class="form-control-plaintext" id="editar-evento"></p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="estatus" class="form-label fw-bold">Estado Actual:</label>
                        <select class="form-select" id="estatus" name="estatus" required>
                            <option value="">Seleccione un estado</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="En proceso">En proceso</option>
                            <option value="Atendido">Atendido</option>
                        </select>
                        <div class="form-text">Seleccione el nuevo estado del reporte.</div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Solo puede modificar el estado del reporte. Los demás datos son de solo lectura.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Actualizar Estado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript para los modales -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si Bootstrap está disponible
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap no está cargado');
        return;
    }
    
    // ===== MODAL DE DETALLES =====
    // Manejar clic en botones "Ver Detalles"
    document.querySelectorAll('.ver-detalle').forEach(button => {
        button.addEventListener('click', function() {
            const reportId = this.getAttribute('data-id');
            cargarDetallesReporte(reportId);
        });
    });

    function cargarDetallesReporte(id) {
        if (!id || id === 'N/A') {
            alert('ID de reporte no válido');
            return;
        }
        
        // Mostrar modal con spinner
        const modalElement = document.getElementById('detalleModal');
        if (!modalElement) {
            alert('No se pudo encontrar el modal');
            return;
        }
        
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        
        // Actualizar título del modal
        document.getElementById('detalleModalLabel').innerHTML = 
            `<i class="fas fa-file-alt me-2"></i>Detalles del Reporte #${id}`;
        
        // Cargar contenido via AJAX
        fetch(`detalles_ajax.php?id=${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor: ' + response.status);
                }
                return response.text();
            })
            .then(html => {
                document.getElementById('modal-detalle-content').innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('modal-detalle-content').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error al cargar los detalles: ${error.message}
                    </div>
                `;
            });
    }
    
    // ===== MODAL DE EDICIÓN =====
    // Manejar clic en botones "Editar Estado"
    document.querySelectorAll('.editar-estatus').forEach(button => {
        button.addEventListener('click', function() {
            const reportId = this.getAttribute('data-id');
            const victima = this.getAttribute('data-victima');
            const evento = this.getAttribute('data-evento');
            const estatus = this.getAttribute('data-estatus');
            
            abrirModalEdicion(reportId, victima, evento, estatus);
        });
    });

    function abrirModalEdicion(id, victima, evento, estatusActual) {
        // Llenar los campos del modal
        document.getElementById('editar-id').value = id;
        document.getElementById('editar-numero').textContent = `#${id}`;
        document.getElementById('editar-victima').textContent = victima;
        document.getElementById('editar-evento').textContent = evento;
        document.getElementById('estatus').value = estatusActual;
        
        // Actualizar título
        document.getElementById('editarModalLabel').innerHTML = 
            `<i class="fas fa-edit me-2"></i>Actualizar Estado del Reporte #${id}`;
        
        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById('editarModal'));
        modal.show();
    }

    // Manejar envío del formulario de edición
    document.getElementById('form-editar-estatus').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const id = formData.get('id');
        const nuevoEstatus = formData.get('estatus');
        
        if (!id || !nuevoEstatus) {
            alert('Por favor complete todos los campos');
            return;
        }
        
        // Mostrar indicador de carga
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
        submitBtn.disabled = true;
        
        // Enviar datos via AJAX
        fetch('actualizar_estatus.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito
                alert(data.message || 'Estado actualizado correctamente');
                
                // Cerrar modal
                bootstrap.Modal.getInstance(document.getElementById('editarModal')).hide();
                
                // Recargar la página para ver los cambios
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                
            } else {
                alert(data.message || 'Error al actualizar el estado');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión. Por favor intente nuevamente.');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
    
    // ===== FUNCIONALIDADES ADICIONALES =====
    // Cerrar modales al presionar ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('.modal.show');
            modals.forEach(modal => {
                bootstrap.Modal.getInstance(modal).hide();
            });
        }
    });
    
    // Limpiar contenido del modal cuando se cierra
    document.getElementById('detalleModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('modal-detalle-content').innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2">Cargando detalles...</p>
            </div>
        `;
    });
});
</script>

<?php include '../../inclusiones/pie.php'; ?>