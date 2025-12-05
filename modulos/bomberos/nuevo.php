<?php
// modulos/bomberos/nuevo.php - VERSIÓN CORREGIDA

require_once '../../inclusiones/auth.php';

// Verificar permisos
if (!tienePermiso('bomberos') && !tienePermiso('admin')) {
    header('Location: ../../index.php');
    exit;
}

$error = '';
$success = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../inclusiones/conexion.php';
    require_once '../../repositories/BomberosRepository.php';
    
    $conexion = getConexion();
    $repo = new BomberosRepository($conexion);

    // Recoger y validar datos
    $datos = [
        'NombreVictima' => trim($_POST['nombre_victima'] ?? ''),
        'EdadVictima' => intval($_POST['edad_victima'] ?? 0),
        'Evento' => trim($_POST['evento'] ?? ''),
        'LugarEvento' => trim($_POST['lugar_evento'] ?? ''),
        'NumeroTelEmergencia' => trim($_POST['telefono'] ?? ''),
        'Correo' => trim($_POST['correo'] ?? ''),
        'DireccionVictima' => trim($_POST['direccion'] ?? ''),
        'DescripcionEvento' => trim($_POST['descripcion'] ?? ''),
        'Estatus' => 'Pendiente'
    ];

    // Validaciones básicas
    if (empty($datos['NombreVictima']) || empty($datos['Evento']) || empty($datos['DireccionVictima'])) {
        $error = 'Los campos marcados con * son obligatorios';
    } else {
        try {
            $id = $repo->create($datos);
            $success = "✅ Reporte #$id creado exitosamente. Se redirigirá en 3 segundos...";
            
            // Redirigir después de 3 segundos
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'index.php?mensaje=Reporte+creado+exitosamente&tipo=success';
                }, 3000);
            </script>";
            
        } catch (Exception $e) {
            $error = '❌ Error al crear el reporte: ' . $e->getMessage();
            error_log("Error en nuevo.php: " . $e->getMessage());
        }
    }
    
    if (isset($conexion)) {
        $conexion->close();
    }
}

// Incluir encabezado después de procesar POST
include '../../inclusiones/encabezado.php';
?>

<div class="container-principal">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow border-0">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="fas fa-plus-circle"></i> Nuevo Reporte de Bomberos</h4>
                </div>

                <div class="card-body p-4">
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo $success; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" id="form-reporte">
                        <div class="row">
                            <!-- Nombre de la Víctima -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nombre de la Víctima <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nombre_victima"
                                    value="<?php echo htmlspecialchars($_POST['nombre_victima'] ?? ''); ?>" 
                                    required
                                    placeholder="Ej: Juan Pérez García">
                            </div>

                            <!-- Edad -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Edad</label>
                                <input type="number" class="form-control" name="edad_victima"
                                    value="<?php echo htmlspecialchars($_POST['edad_victima'] ?? ''); ?>" 
                                    min="0" max="120"
                                    placeholder="Edad en años">
                            </div>

                            <!-- Tipo de Emergencia -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tipo de Emergencia <span class="text-danger">*</span></label>
                                <select class="form-control" name="evento" required>
                                    <option value="">Seleccionar tipo de emergencia...</option>
                                    <option value="Incendio" <?php echo ($_POST['evento'] ?? '') == 'Incendio' ? 'selected' : ''; ?>>🔥 Incendio</option>
                                    <option value="Fuga gas" <?php echo ($_POST['evento'] ?? '') == 'Fuga gas' ? 'selected' : ''; ?>>💨 Fuga de Gas</option>
                                    <option value="Choque" <?php echo ($_POST['evento'] ?? '') == 'Choque' ? 'selected' : ''; ?>>🚗 Choque/Accidente</option>
                                    <option value="Rescate animal" <?php echo ($_POST['evento'] ?? '') == 'Rescate animal' ? 'selected' : ''; ?>>🐕 Rescate Animal</option>
                                    <option value="Rescate estructuras" <?php echo ($_POST['evento'] ?? '') == 'Rescate estructuras' ? 'selected' : ''; ?>>🏢 Rescate en Estructuras</option>
                                    <option value="Capacitacion" <?php echo ($_POST['evento'] ?? '') == 'Capacitacion' ? 'selected' : ''; ?>>📚 Capacitación/Prevención</option>
                                    <option value="Otro" <?php echo ($_POST['evento'] ?? '') == 'Otro' ? 'selected' : ''; ?>>❓ Otro tipo de emergencia</option>
                                </select>
                            </div>

                            <!-- Teléfono -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Teléfono de Emergencia</label>
                                <input type="tel" class="form-control" name="telefono"
                                    value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>"
                                    placeholder="Ej: 5512345678">
                            </div>

                            <!-- Lugar del Evento -->
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Lugar del Evento</label>
                                <input type="text" class="form-control" name="lugar_evento"
                                    value="<?php echo htmlspecialchars($_POST['lugar_evento'] ?? ''); ?>"
                                    placeholder="Ej: Parque Central, Centro Comercial, Casa Habitación, etc.">
                            </div>

                            <!-- Dirección -->
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Dirección Completa <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="direccion" rows="2" required
                                    placeholder="Calle, número, colonia, municipio, referencia..."><?php echo htmlspecialchars($_POST['direccion'] ?? ''); ?></textarea>
                            </div>

                            <!-- Descripción -->
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Descripción Detallada del Evento</label>
                                <textarea class="form-control" name="descripcion" rows="4"
                                    placeholder="Describa con detalle lo ocurrido, condiciones del lugar, personas involucradas, etc."><?php echo htmlspecialchars($_POST['descripcion'] ?? ''); ?></textarea>
                            </div>

                            <!-- Correo -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Correo Electrónico (opcional)</label>
                                <input type="email" class="form-control" name="correo"
                                    value="<?php echo htmlspecialchars($_POST['correo'] ?? ''); ?>"
                                    placeholder="correo@ejemplo.com">
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-danger btn-lg px-4">
                                    <i class="fas fa-save me-2"></i> Guardar Reporte
                                </button>
                                <button type="reset" class="btn btn-outline-secondary btn-lg px-4">
                                    <i class="fas fa-eraser me-2"></i> Limpiar Formulario
                                </button>
                                <a href="index.php" class="btn btn-outline-dark btn-lg px-4">
                                    <i class="fas fa-times me-2"></i> Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Los campos marcados con <span class="text-danger">*</span> son obligatorios.
                        Todos los reportes nuevos se crean con estado "Pendiente".
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para validación del formulario -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-reporte');
    
    form.addEventListener('submit', function(e) {
        // Validación básica
        const nombre = form.querySelector('input[name="nombre_victima"]').value.trim();
        const evento = form.querySelector('select[name="evento"]').value;
        const direccion = form.querySelector('textarea[name="direccion"]').value.trim();
        
        if (!nombre || !evento || !direccion) {
            e.preventDefault();
            alert('Por favor complete todos los campos obligatorios (*)');
            return false;
        }
        
        // Validar edad si se proporciona
        const edadInput = form.querySelector('input[name="edad_victima"]');
        if (edadInput.value) {
            const edad = parseInt(edadInput.value);
            if (edad < 0 || edad > 120) {
                e.preventDefault();
                alert('La edad debe estar entre 0 y 120 años');
                edadInput.focus();
                return false;
            }
        }
        
        // Validar teléfono si se proporciona
        const telefonoInput = form.querySelector('input[name="telefono"]');
        if (telefonoInput.value) {
            const telefono = telefonoInput.value.trim();
            // Solo números, mínimo 10 dígitos
            if (!/^\d{10,15}$/.test(telefono)) {
                e.preventDefault();
                alert('El teléfono debe contener solo números (10-15 dígitos)');
                telefonoInput.focus();
                return false;
            }
        }
        
        // Mostrar mensaje de confirmación
        if (!confirm('¿Está seguro de crear este reporte de emergencia?')) {
            e.preventDefault();
            return false;
        }
        
        // Mostrar indicador de carga
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando...';
        submitBtn.disabled = true;
    });
    
    // Restaurar botón si hay error de validación
    form.addEventListener('invalid', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-save me-2"></i> Guardar Reporte';
        submitBtn.disabled = false;
    }, true);
});
</script>

<?php include '../../inclusiones/pie.php'; ?>