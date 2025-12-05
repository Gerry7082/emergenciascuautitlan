<?php
require_once 'inclusiones/encabezado.php';

// Llamada al header
generar_encabezado('Tablero Principal', $ruta_base, $uri_actual);
?>

    <h2><i class="fas fa-tachometer-alt"></i> Tablero Principal de Emergencias</h2>
    <p>Vista consolidada de la actividad y recursos en tiempo real de los servicios de emergencia de Cuautitlán.</p>

    <div class="card-container">
        <div class="card">
            <h3><i class="fas fa-ambulance"></i> Reportes Activos</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #ff3333;">12</p>
        </div>
        <div class="card">
            <h3><i class="fas fa-fire-extinguisher"></i> Unidades Despachadas</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #004d99;">4</p>
        </div>
        <div class="card">
            <h3><i class="fas fa-user-check"></i> Personal Conectado</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #28a745;">45</p>
        </div>
    </div>

<?php
// Llamada al footer
generar_pie_pagina();
?>
