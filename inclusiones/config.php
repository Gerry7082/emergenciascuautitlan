<?php
date_default_timezone_set('America/Mexico_City'); 
function obtenerRutaBase() {
    $nombre_proyecto = 'emergenciascuautitlan';
    $uri = $_SERVER['REQUEST_URI'];
    $pos = strpos($uri, $nombre_proyecto);
    
    if ($pos !== false) {
        return substr($uri, 0, $pos + strlen($nombre_proyecto)) . '/';
    } else {
        return '/'; 
    }
}
?>
