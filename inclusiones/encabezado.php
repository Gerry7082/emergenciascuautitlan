<?php
require_once 'config.php';

$ruta_base = obtenerRutaBase();
$uri_actual = $_SERVER['REQUEST_URI'];

function generar_encabezado($page_title, $ruta_base, $uri_actual) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> | Emergencia Cuautitlán</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
            body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .main-header {
            width: 100%;
            position: sticky; 
            top: 0;
            z-index: 1000;
            background-color: #004d99;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        .navbar {
            display: flex;
            justify-content: space-between; 
            align-items: center;
            padding: 10px 20px;
            max-width: 1200px; 
            margin: 0 auto;
        }
        .navbar-brand .logo {
            color: #ffffff;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: bold;
            display: flex;
            align-items: center;
        }
        .navbar-brand .logo i {
            margin-right: 10px;
            color: #ff3333; 
        }

        .navbar-info {
            color: #ffffff; 
            font-size: 0.9rem;
            margin-left: auto; 
            text-align: right;
        }
        #datetime-container {
            display: flex;
            flex-direction: column; 
            align-items: flex-end; 
            line-height: 1.2;
            padding: 2px 0;
        }
        .date-part {
            font-weight: normal;
            font-size: 0.9rem;
            color: #cccccc; 
        }
        .time-part {
            font-weight: bold;
            font-size: 1.2rem; 
            color: #ffffff; 
        }
        .date-part i, .time-part i {
            margin-right: 5px;
        }

        /* Estilos de Navegación y Resaltado */
        .navbar-nav {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex; 
            align-items: center;
            margin-left: 20px; 
        }
        .nav-item {
            margin-left: 15px;
        }
        .nav-link {
            display: block;
            padding: 8px 12px;
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }
        .nav-link:hover {
            background-color: #007bff; 
            color: #ffffff;
        }
        .nav-link.active {
            background-color: #ff3333; 
            color: #ffffff;
            font-weight: bold;
        }
        .nav-link i {
            margin-right: 5px;
        }

        main {
            padding: 20px;
            max-width: 1200px;
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 15px;
            flex: 1 1 calc(33% - 20px); 
            min-width: 280px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card h3 {
            margin-top: 0;
            border-bottom: 2px solid #004d99;
            padding-bottom: 5px;
            color: #004d99;
        }

        footer {
            text-align: center;
            padding: 15px;
            background-color: #333;
            color: #fff;
            margin-top: 30px;
        }

        @media (max-width: 900px) {
            .navbar {
                flex-wrap: wrap; 
                justify-content: center;
            }
            .navbar-brand, .navbar-info {
                flex: 1 1 100%; 
                text-align: center;
                margin-bottom: 10px;
            }
            .navbar-nav {
                flex-direction: column; 
                width: 100%;
            }
            .nav-item {
                margin: 5px 0;
                width: 100%;
            }
            .card {
                flex: 1 1 100%;
            }
        }
    </style>
</head>

<body>
    <header class="main-header">
        <nav class="navbar">
            <div class="navbar-brand">
                <a href="<?php echo $ruta_base; ?>index.php" class="logo">
                    <i class="fas fa-ambulance"></i> 
                    Servicios de Emergencia Cuautitlán
                </a>
            </div>

            <div class="navbar-info">
                <div id="datetime-container">
                    <span class="date-part">
                        <i class="far fa-calendar-alt"></i> 
                        <?php echo date('d/m/Y'); ?>
                    </span>
                    <span class="time-part">
                        <i class="far fa-clock"></i> 
                        <span id="current-time"></span>
                    </span>
                </div>
            </div>

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="<?php echo $ruta_base; ?>index.php" class="nav-link 
                        <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' && strpos($_SERVER['REQUEST_URI'], 'modulos') === false) ? 'active' : ''; ?>">
                        Inicio
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="<?php echo $ruta_base; ?>modulos/admin/index.php" class="nav-link 
                        <?php echo (strpos($uri_actual, 'modulos/admin') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-user-shield"></i> Administración
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $ruta_base; ?>modulos/bomberos/index.php" class="nav-link 
                        <?php echo (strpos($uri_actual, 'modulos/bomberos') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-fire-extinguisher"></i> Bomberos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $ruta_base; ?>modulos/medico/index.php" class="nav-link 
                        <?php echo (strpos($uri_actual, 'modulos/medico') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-user-md"></i> Médico
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $ruta_base; ?>modulos/policia/index.php" class="nav-link 
                        <?php echo (strpos($uri_actual, 'modulos/policia') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-shield-alt"></i> Policía
                    </a>
                </li>
            </ul>
        </nav>
    </header>
    <main>
<?php
}

function generar_pie_pagina() {
?>
    </main>
    <footer>
        &copy; <?php echo date('Y'); ?> Servicios de Emergencia Cuautitlán. Todos los derechos reservados.
    </footer>

    <script>
        function updateTime() {
            const now = new Date();
            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false, 
                timeZone: 'America/Mexico_City' 
            };

            const timeString = new Intl.DateTimeFormat('es-MX', timeOptions).format(now);
            document.getElementById('current-time').textContent = timeString;
        }

        updateTime();
        setInterval(updateTime, 1000);
    </script>
</body>
</html>
<?php
}

    <main>
