<?php
require_once '../../inclusiones/auth.php';

if (!tienePermiso('policia') && !tienePermiso('admin')) {
    $rol = obtenerUsuarioActual()['rol'];
    redirigirAModulo($rol);
}

include '../../inclusiones/encabezado.php';
?>

<div class="contenedor-modulo">
    <h2>Módulo de Policía</h2>
    <p>Hola Mundo - Policía</p>
</div>

<?php include '../../inclusiones/pie.php'; ?>