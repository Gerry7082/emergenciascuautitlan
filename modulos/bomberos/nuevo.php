<?php
require_once '../../inclusiones/auth.php';
require_once '../../repositories/BomberosRepository.php';

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
    $repo = new BomberosRepository($conexion);

    // Recoger y validar datos
    $datos = [
        'NombreVictima' => trim($_POST['nombre_victima']),
        'EdadVictima' => intval($_POST['edad_victima']),
        'Evento' => trim($_POST['evento']),
        'LugarEvento' => trim($_POST['lugar_evento']),
        'NumeroTelEmergencia' => trim($_POST['telefono']),
        'Correo' => trim($_POST['correo']),
        'DireccionVictima' => trim($_POST['direccion']),
        'DescripcionEvento' => trim($_POST['descripcion']),
        'Estatus' => 'Pendiente'
    ];

    // Validaciones básicas
    if (empty($datos['NombreVictima']) || empty($datos['Evento']) || empty($datos['DireccionVictima'])) {
        $error = 'Los campos marcados con * son obligatorios';
    } else {
        try {
            $id = $repo->create($datos);
            $success = "Reporte #$id creado exitosamente";

            // Limpiar formulario después de éxito
            $_POST = [];
        } catch (Exception $e) {
            $error = 'Error al crear el reporte: ' . $e->getMessage();
        }
    }
}

include '../../inclusiones/encabezado.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="fas fa-plus-circle"></i> Nuevo Reporte de Bomberos</h4>
                </div>

                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre de la Víctima *</label>
                                <input type="text" class="form-control" name="nombre_victima"
                                    value="<?php echo $_POST['nombre_victima'] ?? ''; ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Edad</label>
                                <input type="number" class="form-control" name="edad_victima"
                                    value="<?php echo $_POST['edad_victima'] ?? ''; ?>" min="0" max="120">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipo de Emergencia *</label>
                                <select class="form-control" name="evento" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Incendio" <?php echo ($_POST['evento'] ?? '') == 'Incendio' ? 'selected' : ''; ?>>🔥 Incendio</option>
                                    <option value="Fuga gas" <?php echo ($_POST['evento'] ?? '') == 'Fuga gas' ? 'selected' : ''; ?>>💨 Fuga de Gas</option>
                                    <option value="Choque" <?php echo ($_POST['evento'] ?? '') == 'Choque' ? 'selected' : ''; ?>>🚗 Choque</option>
                                    <option value="Ahogado" <?php echo ($_POST['evento'] ?? '') == 'Ahogado' ? 'selected' : ''; ?>>💧 Ahogado</option>
                                    <option value="Rescate animal" <?php echo ($_POST['evento'] ?? '') == 'Rescate animal' ? 'selected' : ''; ?>>🐕 Rescate Animal</option>
                                    <option value="Rescate estructuras" <?php echo ($_POST['evento'] ?? '') == 'Rescate estructuras' ? 'selected' : ''; ?>>🏢 Rescate Estructural</option>
                                    <option value="Capacitacion" <?php echo ($_POST['evento'] ?? '') == 'Capacitacion' ? 'selected' : ''; ?>>📚 Capacitación</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Teléfono de Emergencia</label>
                                <input type="tel" class="form-control" name="telefono"
                                    value="<?php echo $_POST['telefono'] ?? ''; ?>">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Lugar del Evento</label>
                                <input type="text" class="form-control" name="lugar_evento"
                                    value="<?php echo $_POST['lugar_evento'] ?? ''; ?>"
                                    placeholder="Ej: Parque Central, Av. Principal #123">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Dirección Completa *</label>
                                <textarea class="form-control" name="direccion" rows="2" required><?php echo $_POST['direccion'] ?? ''; ?></textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Descripción del Evento</label>
                                <textarea class="form-control" name="descripcion" rows="4"><?php echo $_POST['descripcion'] ?? ''; ?></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" name="correo"
                                    value="<?php echo $_POST['correo'] ?? ''; ?>">
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="fas fa-save"></i> Guardar Reporte
                            </button>
                            <a href="index.php" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../inclusiones/pie.php'; ?>