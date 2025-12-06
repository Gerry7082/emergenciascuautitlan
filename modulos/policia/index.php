<?php
require_once '../../inclusiones/auth.php';

if (!tienePermiso('policia') && !tienePermiso('admin')) {
    $rol = obtenerUsuarioActual()['rol'];
    redirigirAModulo($rol);
}

include '../../inclusiones/encabezado.php';
?>

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $evento = $_POST['evento'];
    $lugar = $_POST['lugar'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $descripcion = $_POST['descripcion'];

    $sql = "INSERT INTO tblpolicias (NombreVictima, EdadVictima, Evento, LugarEvento, Correo, NumeroTelEmergencia, DireccionVictima, DescripcionEvento)
            VALUES ('$nombre', '$edad', '$evento', '$lugar', '$correo', '$telefono', '$direccion', '$descripcion')";

    $conn->query($sql);

    $consulta = $conn->query("SELECT * FROM tblpolicias");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../Proyecto Policia/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <script href="../Proyecto Policia/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../Proyecto Policia/estilos.css">
    <title>Módulo de Policía</title>
</head>
<body>
    <h1 class="titulo">Departamento de Policía</h1>
    <div class="row">
        <div class="col-3">
            <label class="robo">Robo/Asalto</label>
            <br>
            <image src="../Proyecto Policia/img/ChatGPT Image 5 dic 2025, 12_19_07 a.m..png" style="width: 300px;"/>
        </div>
        <div class="col-3">
            <label class="choque">Choque</label>
            <br>
            <image src="../Proyecto Policia/img/ChatGPT Image 5 dic 2025, 12_27_50 a.m..png" style="width: 300px;"/>
        </div>
        <div class="col-3">
            <label class="allanamiento">Allanamiento de morada</label>
            <br>
            <image src="../Proyecto Policia/img/ChatGPT Image 5 dic 2025, 12_31_52 a.m..png" style="width: 300px;"/>
        </div>
        <div class="col-3">
            <label class="orden">Alteración del orden público</label>
            <br>
            <image src="../Proyecto Policia/img/ChatGPT Image 5 dic 2025, 12_34_44 a.m..png" style="width: 300px;"/>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <label class="eventos">Resguardo de eventos</label>
            <br>
            <image src="../Proyecto Policia/img/ChatGPT Image 5 dic 2025, 12_37_03 a.m..png" style="width: 300px;"/>
        </div>
        <div class="col-4">
            <label class="detenidos">Traslado de detenidos</label>
            <br>
            <image src="../Proyecto Policia/img/IMG-20251205-WA0001.jpg" style="width: 300px;"/>
        </div>
        <div class="col-4">
            <label class="muertes">Muerte</label>
            <br>
            <image src="../Proyecto Policia/img/IMG-20251205-WA0005.jpg" style="width: 300px;"/>
        </div>
    </div>

    

    <form action="" method="POST" class="incidencias">
        <legend>Gestión de Incidentes</legend>
            <div class="form-container">
                <div class="row">
                    <div class="col-6">
                        <label for="exampleFormControlInput1" class="form-label">Nombre Completo Víctima</label>
                        <input type="text" class="form-control nomVic" id="exampleFormControlInput1" name="nombre" required/>
                    </div>
                    <div class="col-2">
                        <label for="exampleFormControlInput2" class="form-label">Edad Víctima</label>
                        <input type="number" class="form-control" id="exampleFormControlInput2" name="edad" required/>
                    </div>
                    <div class="col-4">
                        <label for="exampleFormControlInput3" class="form-label">Evento</label>
                        <select class="form-control" id="exampleFormControlInput3" name="evento" required>
                            <option value="">Selecciona una opción..</option>
                            <option value="Robo">Robo/Asalto</option>
                            <option value="Choque">Choque</option>
                            <option value="Allanamiento">Allanamiento de morada</option>
                            <option value="Orden">Alteración del orden público</option>
                            <option value="Eventos">Resguardo de eventos</option>
                            <option value="Detenidos">Traslado de detenidos</option>
                            <option value="Muerte">Muerte</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label for="exampleFormControlInput4" class="form-label">Lugar de los hechos</label>
                        <input type="text" class="form-control" id="exampleFormControlInput4" name="hechos" required/>
                    </div>
                    <div class="col-6">
                        <label for="exampleFormControlInput5" class="form-label">Correo electrónico</label>
                        <input type="text" class="form-control" id="exampleFormControlInput5" name="correo" required/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label for="exampleFormControlInput6" class="form-label">Número telefónico para emergencias</label>
                        <input type="text" class="form-control" id="exampleFormControlInput6" name="numero" required/>
                    </div>
                    <div class="col-6">
                        <label for="exampleFormControlInput7" class="form-label">Dirección de la víctima</label>
                        <input type="text" class="form-control" id="exampleFormControlInput7" name="direccion" required/>
                    </div>
                </div>
                <div class="row">
                    <label for="exampleFormControlInput8" class="form-label">Descripción de los hechos</label>
                    <textarea class="form-control" id="exampleFormControlInput8" name="descripcion" required></textarea>
                </div>
                    <button class="btn btn-primary mt-3">Guardar reporte</button>
            </div>
    </form>

    <table class="table table-bordered table-striped mt-4">
    <thead class="table-dark">
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Edad</th>
            <th>Evento</th>
            <th>Lugar</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Descripción</th>
        </tr>
    </thead>
        <tbody>
            <?php while($fila = $consulta->fetch_assoc()): ?>
                <tr>
                    <td><?= $fila['Id'] ?></td>
                    <td><?= $fila['NombreVictima'] ?></td>
                    <td><?= $fila['EdadVictima'] ?></td>
                    <td><?= $fila['Evento'] ?></td>
                    <td><?= $fila['LugarEvento'] ?></td>
                    <td><?= $fila['Correo'] ?></td>
                    <td><?= $fila['NumeroTelEmergencia'] ?></td>
                    <td><?= $fila['DireccionVictima'] ?></td>
                    <td><?= $fila['DescripcionEvento'] ?></td>
                </tr>
                <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php include '../../inclusiones/pie.php'; ?>
