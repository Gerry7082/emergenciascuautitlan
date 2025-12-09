<?php
// inclusiones/encabezado.php

// PREVENIR CARGA MÚLTIPLE
if (defined('ENCABEZADO_INCLUIDO')) {
    return;
}
define('ENCABEZADO_INCLUIDO', true);

// Incluir archivos necesarios
require_once __DIR__ . '/auth.php';

// Verificar autenticación
verificarAutenticacion();

// Obtener información del usuario
$usuario_actual = obtenerUsuarioActual();

// Determinar página activa
$pagina_actual = basename($_SERVER['PHP_SELF']);
$modulo_actual = '';

if (strpos($_SERVER['PHP_SELF'], 'modulos/admin') !== false) $modulo_actual = 'admin';
if (strpos($_SERVER['PHP_SELF'], 'modulos/bomberos') !== false) $modulo_actual = 'bomberos';
if (strpos($_SERVER['PHP_SELF'], 'modulos/medico') !== false) $modulo_actual = 'medico';
if (strpos($_SERVER['PHP_SELF'], 'modulos/policia') !== false) $modulo_actual = 'policia';

// Obtener nombre corto del usuario
$nombre_corto = explode(',', $usuario_actual['nombre_completo'])[0];
$nombre_corto = htmlspecialchars(trim($nombre_corto));

// Calcular la ruta correcta para logout
$ruta_logout = dirname(dirname($_SERVER['PHP_SELF']));
if ($ruta_logout == '/') {
    $ruta_logout = '';
}

$ruta_base = obtenerRutaBase();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Emergencias Cuautitlán</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (opcional pero útil) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- En la sección HEAD, reemplaza la línea del CSS -->
    <link rel="stylesheet" href="<?php echo obtenerRutaBase(); ?>css/estilo.css">

    <!-- ESTILOS INLINE -->
    <style>
        /* ESTILOS GLOBALES */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* HEADER */
        .emergencias-header {
            background: linear-gradient(135deg, #1a2980 0%, #26d0ce 100%);
            color: white;
            padding: 15px 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .header-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
            text-decoration: none;
        }

        .header-logo:hover {
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
            transition: transform 0.3s;
        }

        .logo-icon {
            font-size: 2rem;
            color: #ffd700;
        }

        .logo-text h1 {
            font-size: 1.6rem;
            margin: 0;
            font-weight: bold;
        }

        .logo-text .subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-top: 2px;
        }

        /* USUARIO */
        .user-section {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            font-size: 1rem;
        }

        .user-details {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            margin-top: 3px;
        }

        .user-role-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
        }

        .user-time {
            opacity: 0.9;
        }

        /* BOTÓN LOGOUT */
        .btn-logout-header {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-logout-header:hover {
            background: #c82333;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        /* NAVEGACIÓN */
        .emergencias-nav {
            background: white;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .nav-menu {
            display: flex;
            gap: 10px;
            padding: 12px 0;
            list-style: none;
            margin: 0;
            flex-wrap: wrap;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #495057;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .nav-link:hover {
            background: #f8f9fa;
            color: #1a2980;
            text-decoration: none;
        }

        .nav-link.active {
            background: #1a2980;
            color: white;
        }

        /* CONTENIDO PRINCIPAL */
        .main-content {
            flex: 1;
            width: 100%;
        }

        .container-principal {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .user-section {
                width: 100%;
                justify-content: space-between;
            }

            .user-info {
                text-align: left;
            }

            .nav-menu {
                justify-content: center;
            }

            .nav-link {
                padding: 6px 12px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .logo-text h1 {
                font-size: 1.3rem;
            }

            .user-section {
                flex-direction: column;
                gap: 10px;
            }

            .user-info {
                text-align: center;
            }

            .nav-menu {
                flex-direction: column;
                align-items: center;
            }

            .btn-logout-header span {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- HEADER -->
    <header class="emergencias-header">
        <div class="header-container">
            <!-- Logo -->
            <a href="index.php" class="header-logo">
                <i class="fas fa-ambulance logo-icon"></i>
                <div class="logo-text">
                    <h1>Sistema de Emergencias</h1>
                    <div class="subtitle">Cuautitlán, Estado de México</div>
                </div>
            </a>

            <!-- Información de usuario y logout -->
            <div class="user-section">
                <div class="user-info">
                    <div class="user-name"><?php echo $nombre_corto; ?></div>
                    <div class="user-details">
                        <span class="user-role-badge"><?php echo $usuario_actual['rol']; ?></span>
                        <span class="user-time"><?php echo date('H:i'); ?></span>
                    </div>
                </div>

                <a href="<?php echo $ruta_base; ?>logout.php"
                    class="btn-logout"
                    onclick="return confirm('¿Cerrar sesión?');">
                    <i class="fas fa-sign-out-alt"></i>
                    Salir
                </a>
            </div>
        </div>
    </header>

    <!-- NAVEGACIÓN -->
    <li>
        <a href="<?php echo $ruta_base; ?>index.php"
            class="nav-link <?php echo ($pagina_actual == 'index.php') ? 'active' : ''; ?>">
            <i class="fas fa-home"></i>
            <span>Inicio</span>
        </a>
    </li>

    <?php if (tienePermiso('admin') || tienePermiso('bomberos')): ?>
        <li>
            <a href="<?php echo $ruta_base; ?>modulos/bomberos/index.php"
                class="nav-link <?php echo ($modulo_actual == 'bomberos') ? 'active' : ''; ?>">
                <i class="fas fa-fire"></i>
                <span>Bomberos</span>
            </a>
        </li>
    <?php endif; ?>

    <?php if (tienePermiso('admin') || tienePermiso('medico')): ?>
        <li>
            <a href="<?php echo $ruta_base; ?>modulos/medico/index.php"
                class="nav-link <?php echo ($modulo_actual == 'medico') ? 'active' : ''; ?>">
                <i class="fas fa-ambulance"></i>
                <span>Servicio Médico</span>
            </a>
        </li>
    <?php endif; ?>

    <?php if (tienePermiso('admin') || tienePermiso('policia')): ?>
        <li>
            <a href="<?php echo $ruta_base; ?>modulos/policia/index.php"
                class="nav-link <?php echo ($modulo_actual == 'policia') ? 'active' : ''; ?>">
                <i class="fas fa-shield-alt"></i>
                <span>Policía</span>
            </a>
        </li>
    <?php endif; ?>

    <?php if (tienePermiso('admin')): ?>
        <li>
            <a href="<?php echo $ruta_base; ?>modulos/admin/index.php"
                class="nav-link <?php echo ($modulo_actual == 'admin') ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i>
                <span>Administración</span>
            </a>
        </li>
    <?php endif; ?>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="main-content">
        <div class="container-principal">
            <!-- Aquí va el contenido específico de cada página -->