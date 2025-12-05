<?php
// login.php
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($username) || empty($password)) {
        $error = 'Usuario y contraseña son requeridos';
    } else {
        require_once 'inclusiones/conexion.php';
        $conexion = getConexion();
        
        $sql = "SELECT Id, nombre_completo, username, password, rol, email FROM tblusuarios WHERE username = ? AND estatus = 'activo'";
        
        if ($stmt = $conexion->prepare($sql)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $usuario = $result->fetch_assoc();
                
                if (password_verify($password, $usuario['password'])) {
                    $_SESSION['usuario_autenticado'] = true;
                    $_SESSION['usuario'] = [
                        'id' => $usuario['Id'],
                        'nombre_completo' => $usuario['nombre_completo'],
                        'username' => $usuario['username'],
                        'rol' => $usuario['rol'],
                        'email' => $usuario['email']
                    ];
                    
                    switch ($usuario['rol']) {
                        case 'admin': header('Location: modulos/admin/index.php'); break;
                        case 'bomberos': header('Location: modulos/bomberos/index.php'); break;
                        case 'medico': header('Location: modulos/medico/index.php'); break;
                        case 'policia': header('Location: modulos/policia/index.php'); break;
                        default: header('Location: index.php');
                    }
                    exit;
                } else {
                    $error = 'Contraseña incorrecta';
                }
            } else {
                $error = 'Usuario no encontrado';
            }
            
            $stmt->close();
        }
        
        $conexion->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Emergencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1a2980, #26d0ce);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 class="text-center mb-4">
            <i class="fas fa-user-shield text-primary"></i><br>
            Acceso Personal
        </h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Usuario</label>
                <input type="text" name="username" class="form-control" required value="gerardo.vargas">
            </div>
            <div class="mb-3">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control" required value="password">
                <small class="text-muted">Contraseña: <strong>password</strong></small>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-sign-in-alt"></i> Ingresar
            </button>
        </form>

        <div class="mt-3 text-center">
            <a href="index.php" class="text-decoration-none">
                <i class="fas fa-arrow-left"></i> Volver al inicio
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>