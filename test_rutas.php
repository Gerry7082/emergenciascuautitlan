<?php
// test_rutas.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Prueba de rutas CSS</h1>";

// Probar desde diferentes ubicaciones
$locations = [
    'css/estilo.css' => 'Desde raíz',
    '../css/estilo.css' => 'Desde modulos/bomberos/',
    '../../css/estilo.css' => 'Desde modulos/bomberos/ otra ruta',
    './css/estilo.css' => 'Desde directorio actual',
];

echo "<h3>Verificando existencia del archivo CSS:</h3>";
foreach ($locations as $path => $desc) {
    if (file_exists($path)) {
        echo "<p style='color:green;'>✅ $desc: <strong>$path</strong> EXISTE</p>";
    } else {
        echo "<p style='color:red;'>❌ $desc: <strong>$path</strong> NO existe</p>";
    }
}

echo "<h3>Ruta actual del script:</h3>";
echo "<p><strong>SCRIPT_NAME:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p><strong>PHP_SELF:</strong> " . $_SERVER['PHP_SELF'] . "</p>";
echo "<p><strong>DOCUMENT_ROOT:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";

echo "<h3>Probando incluir encabezado:</h3>";
echo "<pre>";
include 'inclusiones/encabezado.php';
echo "</pre>";
?>