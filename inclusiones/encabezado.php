<?php
require_once 'config.php';

$pagina_actual = basename($_SERVER['PHP_SELF']);
$ruta_base = obtenerRutaBase();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios de Emergencia Cuautitlán</title>
</head>

<body>
    <header>
        <nav>
            <h1>Servicios de Emergencia Cuautitlán</h1>
            <p>Fecha y hora: <?php echo date('d/m/Y H:i:s'); ?></p>
            <ul>
                <li><a href="<?php echo $ruta_base; ?>index.php">Inicio</a></li>
                <li><a href="<?php echo $ruta_base; ?>modulos/admin/index.php">Administración</a></li>
                <li><a href="<?php echo $ruta_base; ?>modulos/bomberos/index.php">Bomberos</a></li>
                <li><a href="<?php echo $ruta_base; ?>modulos/medico/index.php">Médico</a></li>
                <li><a href="<?php echo $ruta_base; ?>modulos/policia/index.php">Policía</a></li>
            </ul>
        </nav>
    </header>
    <main>