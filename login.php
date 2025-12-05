<?php
// Activar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Si ya está autenticado, redirigir
if (isset($_SESSION['usuario_autenticado']) && $_SESSION['usuario_autenticado'] === true) {
    if (isset($_SESSION['usuario']['rol'])) {
        switch ($_SESSION['usuario']['rol']) {
            case 'admin':
                header('Location: modulos/admin/index.php');
                break;
            case 'bomberos':
                header('Location: modulos/bomberos/index.php');
                break;
            case 'medico':
                header('Location: modulos/medico/index.php');
                break;
            case 'policia':
                header('Location: modulos/policia/index.php');
                break;
            default:
                header('Location: index.php');
        }
        exit;
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Usuario y contraseña son requeridos';
    } else {
        // Conexión simple y directa
        $conn = new mysqli("sql101.infinityfree.com", "if0_40232109", "dDrxGnqq6Q", "if0_40232109_restaurante");

        if ($conn->connect_error) {
            $error = "Error de conexión: " . $conn->connect_error;
        } else {
            // Usar consulta preparada
            $sql = "SELECT Id, nombre_completo, username, password, rol, email FROM tblusuarios WHERE username = ? AND estatus = 'activo'";

            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 1) {
                    $usuario = $result->fetch_assoc();

                    // Verificar la contraseña - IMPORTANTE: usar solo "password" (sin 123)
                    if (password_verify($password, $usuario['password'])) {
                        // Configurar sesión
                        $_SESSION['usuario_autenticado'] = true;
                        $_SESSION['usuario'] = [
                            'id' => $usuario['Id'],
                            'nombre_completo' => $usuario['nombre_completo'],
                            'username' => $usuario['username'],
                            'rol' => $usuario['rol'],
                            'email' => $usuario['email']
                        ];

                        // Redirigir
                        switch ($usuario['rol']) {
                            case 'admin':
                                header('Location: modulos/admin/index.php');
                                break;
                            case 'bomberos':
                                header('Location: modulos/bomberos/index.php');
                                break;
                            case 'medico':
                                header('Location: modulos/medico/index.php');
                                break;
                            case 'policia':
                                header('Location: modulos/policia/index.php');
                                break;
                            default:
                                header('Location: index.php');
                        }
                        exit;
                    } else {
                        $error = 'Contraseña incorrecta. Usa: <strong>password</strong>';
                    }
                } else {
                    $error = 'Usuario no encontrado: <strong>' . htmlspecialchars($username) . '</strong>';
                }

                $stmt->close();
            } else {
                $error = 'Error en la consulta: ' . $conn->error;
            }

            $conn->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Emergencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a2980, #26d0ce);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: none;
        }

        .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo i {
            font-size: 3rem;
            color: #1a2980;
            margin-bottom: 15px;
        }

        .btn-login {
            background: linear-gradient(to right, #1a2980, #26d0ce);
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26, 41, 128, 0.3);
        }

        .user-list {
            font-size: 0.85rem;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 10px;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo">
                <i class="fas fa-shield-alt"></i>
                <h3>Acceso al Sistema</h3>
                <p class="text-muted">Emergencias Cuautitlán</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="mb-3">
                    <label class="form-label">Usuario</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="username" class="form-control"
                            placeholder="Ingresa tu usuario"
                            value="gerardo.vargas" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control"
                            placeholder="Ingresa tu contraseña"
                            value="password" required>
                    </div>
                    <div class="form-text">Contraseña predeterminada: <code>password</code></div>
                </div>

                <button type="submit" class="btn btn-login btn-lg w-100">
                    <i class="fas fa-sign-in-alt me-2"></i> Ingresar al Sistema
                </button>
            </form>

            <div class="user-list">
                <small class="text-muted"><strong>Usuarios disponibles:</strong></small><br>
                <div class="row mt-2">
                    <div class="col-6">
                        <small><i class="fas fa-user-cog text-primary"></i> <strong>laura.cruz</strong> (Admin)</small>
                    </div>
                    <div class="col-6">
                        <small><i class="fas fa-fire-extinguisher text-danger"></i> <strong>gerardo.vargas</strong> (Bomberos)</small>
                    </div>
                    <div class="col-6 mt-1">
                        <small><i class="fas fa-user-md text-success"></i> <strong>sergio.amezcua</strong> (Médico)</small>
                    </div>
                    <div class="col-6 mt-1">
                        <small><i class="fas fa-shield-alt text-warning"></i> <strong>dulce.lujano</strong> (Policía)</small>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="index.php" class="text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i> Volver al portal principal
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>

</html>