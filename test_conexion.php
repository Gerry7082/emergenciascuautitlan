<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<h1>Prueba de conexión a InfinityFree</h1>";

try {
    // Intentar conexión directa
    $host = "sql101.infinityfree.com";
    $user = "if0_40232109";
    $pass = "dDrxGnqq6Q";
    $db = "if0_40232109_restaurante";
    
    echo "<p>Intentando conectar a:<br>";
    echo "Host: $host<br>";
    echo "Usuario: $user<br>";
    echo "Base de datos: $db</p>";
    
    $conn = new mysqli($host, $user, $pass, $db);
    
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }
    
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px;'>
            <strong style='color: #155724;'>✅ ¡Conexión exitosa!</strong>
          </div>";
    
    // Mostrar tablas
    echo "<h3>Tablas en la base de datos:</h3>";
    $result = $conn->query("SHOW TABLES");
    
    if ($result->num_rows > 0) {
        echo "<ul>";
        while($row = $result->fetch_array()) {
            echo "<li>" . $row[0] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No hay tablas en la base de datos.</p>";
    }
    
    // Verificar tabla tblusuarios específicamente
    echo "<h3>Verificando tabla 'tblusuarios':</h3>";
    $result = $conn->query("SHOW TABLES LIKE 'tblusuarios'");
    
    if ($result->num_rows == 1) {
        echo "<p style='color: green;'>✅ La tabla 'tblusuarios' existe.</p>";
        
        // Mostrar contenido
        $users = $conn->query("SELECT * FROM tblusuarios");
        if ($users->num_rows > 0) {
            echo "<h4>Usuarios en la tabla:</h4>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Username</th><th>Rol</th><th>Email</th></tr>";
            while($user = $users->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $user['Id'] . "</td>";
                echo "<td>" . $user['username'] . "</td>";
                echo "<td>" . $user['rol'] . "</td>";
                echo "<td>" . $user['email'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: orange;'>⚠️ La tabla 'tblusuarios' existe pero está vacía.</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ La tabla 'tblusuarios' NO existe.</p>";
        echo "<p>Crea la tabla con este comando SQL:</p>";
        echo "<pre style='background: #f8f9fa; padding: 10px;'>
CREATE TABLE IF NOT EXISTS `tblusuarios` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','bomberos','policia','medico') NOT NULL,
  `email` varchar(100) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `estatus` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;</pre>";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>
            <strong style='color: #721c24;'>❌ Error:</strong> " . $e->getMessage() . "
          </div>";
    
    // Información adicional para debugging
    echo "<h3>Información del servidor:</h3>";
    echo "<pre>";
    echo "PHP Version: " . phpversion() . "\n";
    echo "MySQLi disponible: " . (function_exists('mysqli_connect') ? 'Sí' : 'No') . "\n";
    echo "</pre>";
}
?>

<h3>Probar conexión manual:</h3>
<form method="POST">
    <input type="hidden" name="test" value="1">
    <button type="submit" class="btn btn-primary">Probar de nuevo</button>
</form>

<p><a href="login.php">Volver al login</a></p>