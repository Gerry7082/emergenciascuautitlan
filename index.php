<?php
// Activar errores para ver qué pasa
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Verificar si el archivo de conexión existe
if (!file_exists('inclusiones/conexion.php')) {
    die('ERROR: Archivo de conexión no encontrado');
}

require_once 'inclusiones/conexion.php';

$mensaje = '';
$error = '';

// Procesar formulario de reporte (público)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Obtener conexión
        $conexion = getConexion();

        if (!$conexion) {
            throw new Exception('No se pudo conectar a la base de datos');
        }

        // Datos del formulario (solo los que el usuario completa)
        $nombreVictima = trim($_POST['nombreVictima'] ?? '');
        $edadVictima = isset($_POST['edadVictima']) ? intval($_POST['edadVictima']) : 0;
        $numeroTelEmergencia = trim($_POST['numeroTelEmergencia'] ?? '');
        $direccionVictima = trim($_POST['direccionVictima'] ?? '');
        $evento = trim($_POST['evento'] ?? '');
        $descripcionEvento = trim($_POST['descripcionEvento'] ?? '');

        // VALORES AUTOMÁTICOS - NO los selecciona el usuario
        $centralReportes = 'Central Cuautitlán'; // Siempre este valor
        $departamentos = 'Pendiente Asignación'; // La central asignará después
        $prioridad = 'Media'; // Valor por defecto, la central ajusta
        $estatus = 'Pendiente'; // Siempre Pendiente al crear

        // Validaciones básicas
        if (empty($nombreVictima) || empty($evento) || empty($numeroTelEmergencia) || empty($direccionVictima)) {
            throw new Exception('Todos los campos marcados con * son requeridos');
        }

        if (!preg_match('/^\d{10}$/', $numeroTelEmergencia)) {
            throw new Exception('El teléfono debe tener exactamente 10 dígitos');
        }

        if ($edadVictima < 0 || $edadVictima > 120) {
            throw new Exception('La edad debe estar entre 0 y 120 años');
        }

        // Convertir teléfono a INT (porque tu tabla tiene INT(10))
        $telefono_int = (int) $numeroTelEmergencia;

        // Preparar la consulta SQL
        $sql = "INSERT INTO tblcentral 
            (CentralReportes, Departamentos, NombreVictima, EdadVictima, 
             NumeroTelEmergencia, DireccionVictima, Evento, DescripcionEvento, 
             Prioridad, Estatus) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conexion->prepare($sql);

        if (!$stmt) {
            throw new Exception('Error preparando consulta: ' . $conexion->error);
        }

        // Vincular parámetros
        $stmt->bind_param(
            "sssissssss",
            $centralReportes,
            $departamentos,
            $nombreVictima,
            $edadVictima,
            $telefono_int,
            $direccionVictima,
            $evento,
            $descripcionEvento,
            $prioridad,
            $estatus
        );

        // Ejecutar
        if ($stmt->execute()) {
            $id_reporte = $conexion->insert_id;
            $mensaje = '✅ Reporte #' . $id_reporte . ' registrado exitosamente. ';
            $mensaje .= 'La central lo evaluará y asignará al departamento correspondiente.';

            // Limpiar el formulario después del éxito
            $_POST = array();
        } else {
            throw new Exception('Error al registrar el reporte: ' . $stmt->error);
        }

        $stmt->close();
    } catch (Exception $e) {
        $error = '❌ ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Emergencias - Cuautitlán</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
        }

        .header-emergencia {
            background: linear-gradient(135deg, #dc3545 0%, #0066cc 100%);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
            position: relative;
        }

        .btn-login {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-login:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }
    </style>
</head>

<body>
    <!-- Header Público -->
    <div class="header-emergencia position-relative">
        <div class="container">
            <a href="login.php" class="btn btn-login">
                <i class="fas fa-sign-in-alt"></i> Acceso Personal
            </a>
            <h1 class="display-4">
                <i class="fas fa-ambulance"></i> Servicios de Emergencia Cuautitlán
            </h1>
            <p class="lead">Sistema de Reporte de Emergencias 24/7</p>
        </div>
    </div>

    <div class="container">
        <!-- Mensajes -->
        <?php if ($mensaje): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $mensaje; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Formulario de Reporte (MODIFICADO) -->
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-exclamation-triangle"></i> Reporte de Emergencia
                        </h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="" id="form-reporte">
                            <!-- Campos ocultos con valores automáticos -->
                            <input type="hidden" name="centralReportes" value="Central Cuautitlán">
                            <input type="hidden" name="departamentos" value="Pendiente Asignación">
                            <input type="hidden" name="prioridad" value="Media">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nombre de la Víctima *</label>
                                    <input type="text" class="form-control" name="nombreVictima" required
                                        value="<?php echo htmlspecialchars($_POST['nombreVictima'] ?? ''); ?>"
                                        placeholder="Ej: Juan Pérez García">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Edad *</label>
                                    <input type="number" class="form-control" name="edadVictima"
                                        min="0" max="120" required
                                        value="<?php echo htmlspecialchars($_POST['edadVictima'] ?? ''); ?>"
                                        placeholder="Años">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Teléfono de Emergencia *</label>
                                    <input type="tel" class="form-control" name="numeroTelEmergencia" required
                                        placeholder="10 dígitos (ej: 5512345678)"
                                        pattern="\d{10}"
                                        title="Debe tener exactamente 10 dígitos"
                                        value="<?php echo htmlspecialchars($_POST['numeroTelEmergencia'] ?? ''); ?>">
                                    <div class="form-text">10 dígitos exactos, sin espacios</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tipo de Emergencia *</label>
                                    <select class="form-control" name="evento" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="Incendio" <?php echo ($_POST['evento'] ?? '') == 'Incendio' ? 'selected' : ''; ?>>🔥 Incendio</option>
                                        <option value="Accidente" <?php echo ($_POST['evento'] ?? '') == 'Accidente' ? 'selected' : ''; ?>>🚗 Accidente de Tránsito</option>
                                        <option value="Asalto" <?php echo ($_POST['evento'] ?? '') == 'Asalto' ? 'selected' : ''; ?>>💰 Robo/Asalto</option>
                                        <option value="Emergencia Médica" <?php echo ($_POST['evento'] ?? '') == 'Emergencia Médica' ? 'selected' : ''; ?>>🏥 Emergencia Médica</option>
                                        <option value="Fuga de Gas" <?php echo ($_POST['evento'] ?? '') == 'Fuga de Gas' ? 'selected' : ''; ?>>💨 Fuga de Gas</option>
                                        <option value="Rescate" <?php echo ($_POST['evento'] ?? '') == 'Rescate' ? 'selected' : ''; ?>>🆘 Rescate</option>
                                        <option value="Otro" <?php echo ($_POST['evento'] ?? '') == 'Otro' ? 'selected' : ''; ?>>❓ Otro</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Dirección Completa *</label>
                                    <textarea class="form-control" name="direccionVictima" rows="2" required
                                        placeholder="Calle, número, colonia, municipio, referencia..."><?php echo htmlspecialchars($_POST['direccionVictima'] ?? ''); ?></textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Descripción Detallada *</label>
                                    <textarea class="form-control" name="descripcionEvento" rows="4" required
                                        placeholder="Describa la situación con detalle..."><?php echo htmlspecialchars($_POST['descripcionEvento'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-danger btn-lg">
                                    <i class="fas fa-paper-plane"></i> Enviar Reporte a Central
                                </button>
                                <button type="reset" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-redo"></i> Limpiar
                                </button>
                                <p class="text-muted mt-2 small">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    Su información es confidencial y solo será usada para atender la emergencia.
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Información Lateral -->
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-phone"></i> Números de Emergencia</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>🚒 Bomberos:</strong> 555-123-4567
                            </li>
                            <li class="list-group-item">
                                <strong>👮 Policía:</strong> 555-987-6543
                            </li>
                            <li class="list-group-item">
                                <strong>🚑 Ambulancias:</strong> 555-456-7890
                            </li>
                            <li class="list-group-item">
                                <strong>📞 Emergencias:</strong> 911
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Proceso del Sistema</h5>
                    </div>
                    <div class="card-body">
                        <ol class="mb-3">
                            <li>Usted reporta la emergencia</li>
                            <li>Central recibe y registra el reporte</li>
                            <li>Analistas evalúan la situación</li>
                            <li>Se asigna al departamento adecuado</li>
                            <li>Equipo especializado atiende</li>
                        </ol>
                        <div class="alert alert-warning">
                            <small>
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Importante:</strong> En caso de peligro inminente, llame directamente al 911.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Público -->
        <footer class="mt-5 pt-4 border-top">
            <div class="row">
                <div class="col-md-6">
                    <h5>Central de Emergencias Cuautitlán</h5>
                    <p class="text-muted">Sistema de reporte en línea 24 horas</p>
                    <a href="https://docs.google.com/document/d/e/2PACX-1vTXpyqHyavrRxuKiGeZIB_eP8rnfwnkCIRx-8XCfsLZT2xNG1caOMi0fHU1zRxSNsTjQs598ZP2g5Y7/pub"
                        target="_blank"
                        class="btn btn-outline-light btn-sm mt-2">
                        <i class="fas fa-book me-1"></i> Documentación del Sistema
                    </a>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-0">
                        <strong>Desarrollado por:</strong><br>
                        Amezcua Sagrero, Sergio Daniel | Lujano Valeriano, Dulce Odalys<br>
                        Cruz Vergara, Laura Rocxana | Vargas Almanza, Gerardo Enrique
                    </p>
                    <p class="text-muted small mt-2">
                        &copy; <?php echo date('Y'); ?> Sistema de Emergencias Cuautitlán
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-reporte');
            const telefonoInput = form.querySelector('input[name="numeroTelEmergencia"]');

            // Validar formato de teléfono
            telefonoInput.addEventListener('input', function() {
                const valor = this.value.replace(/\D/g, '');
                this.value = valor.substring(0, 10);
            });

            form.addEventListener('submit', function(e) {
                // Validar teléfono
                if (!/^\d{10}$/.test(telefonoInput.value)) {
                    e.preventDefault();
                    alert('El teléfono debe tener exactamente 10 dígitos');
                    telefonoInput.focus();
                    return false;
                }

                // Validar edad
                const edadInput = form.querySelector('input[name="edadVictima"]');
                const edad = parseInt(edadInput.value);
                if (isNaN(edad) || edad < 0 || edad > 120) {
                    e.preventDefault();
                    alert('La edad debe estar entre 0 y 120 años');
                    edadInput.focus();
                    return false;
                }

                // Confirmar envío
                if (!confirm('¿Enviar reporte de emergencia a la Central?')) {
                    e.preventDefault();
                    return false;
                }

                // Mostrar cargando
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Enviando...';
                submitBtn.disabled = true;
            });
        });
    </script>
</body>

</html>