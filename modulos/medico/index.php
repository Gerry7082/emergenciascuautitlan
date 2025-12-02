<?php include '../../inclusiones/encabezado.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Directorio de Servicios de Salud</title>
    <style>
        /* Estilos CSS Base */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
        }

        /* --- Contenedor Principal (Menú Lateral + Contenido) --- */
        .main-container {
            display: flex;
            min-height: 100vh; /* Ocupa toda la altura de la ventana */
        }

        /* --- Menú Lateral Izquierdo --- */
        .sidebar {
            width: 250px;
            background-color: #fff;
            padding: 20px 0;
            border-right: 1px solid #ddd;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
        }
        .sidebar-logo-placeholder {
            padding: 15px 20px;
            font-size: 20px;
            font-weight: bold;
            color: #005a9c;
            margin-bottom: 20px;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            margin: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s, border 0.2s;
            border: 1px solid transparent;
        }
        .sidebar-item.active {
            background-color: #fff;
            border: 1px solid #ff7a00;
            box-shadow: 0 1px 5px rgba(255, 122, 0, 0.1);
        }
        .sidebar-item:hover {
            background-color: #f0f0f0;
        }

        .sidebar-item-icon {
            font-size: 24px;
            margin-right: 15px;
            color: #005a9c;
        }

        /* --- Área de Contenido Central y Mapa --- */
        .content-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .top-title {
            padding: 20px 40px 0 40px;
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }

        .content-area {
            flex-grow: 1;
            display: flex;
            /* Asegura que el main-content y map-container estén lado a lado */
        }
        .main-content {
            flex: 2; /* Ocupa 2/3 del espacio de content-area */
            padding: 40px;
            background-color: #fff;
        }

        /* --- Estilos del Mapa (Controles y simulación visual) --- */
        .map-container {
            flex: 1; /* Ocupa 1/3 del espacio de content-area */
            position: relative;
            background-color: #eee;
            border-left: 1px solid #ddd;
        }

        .map-placeholder {
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"><rect width="100%" height="100%" fill="#E9E9E9"/><line x1="0" y1="0" x2="100%" y2="100%" stroke="#C0C0C0" stroke-width="2"/><line x1="100%" y1="0" x2="0" y2="100%" stroke="#C0C0C0" stroke-width="2"/><circle cx="50%" cy="50%" r="20" fill="#FF5733"/></svg>');
            background-size: cover;
            background-repeat: no-repeat;
        }

        .map-controls {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            z-index: 10;
        }

        .map-search {
            background-color: white;
            padding: 5px 10px;
            border-radius: 4px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
            margin-bottom: 10px;
        }
        .map-search i { margin-right: 5px; color: #777; }

        .map-zoom {
            display: flex;
            flex-direction: column;
            background-color: white;
            border-radius: 4px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
        }
        .map-zoom button {
            background: none;
            border: none;
            padding: 8px 10px;
            cursor: pointer;
            font-size: 16px;
            color: #555;
        }
        .map-zoom button:first-child { border-bottom: 1px solid #ddd; }
        .map-zoom button:hover { background-color: #f0f0f0; }

        /* --- Estilos del Formulario de Búsqueda --- */
        .form-content { display: none; }
        .form-content.active { display: block; }

        .search-form-group { margin-bottom: 25px; }
        .search-input-container {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 8px 15px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 600px;
        }
        .search-input-container i { color: #aaa; margin-right: 10px; }
        .search-input-container input { border: none; outline: none; font-size: 16px; width: 100%; }

        .form-section-title {
            font-size: 11px;
            color: #555;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }

        .form-row { display: flex; gap: 20px; margin-bottom: 15px; }
        .form-field { flex: 1; }
        .form-field label { display: block; margin-bottom: 5px; font-size: 14px; color: #333; }
        .form-field select, .form-field input[type="text"] {
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px; background-color: #fff;
            -webkit-appearance: none; -moz-appearance: none; appearance: none;
        }

        .search-button {
            background-color: #ff7a00;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            margin-top: 30px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <div class="main-container">
        
        <div class="sidebar">
            <div class="sidebar-logo-placeholder">
                <span style="color:#ff7a00;">GNP</span> **SEGUROS**
            </div>
            
            <div class="sidebar-item" data-tab="medicos">
                <i class="fas fa-user-md sidebar-item-icon"></i>
                <span>Médicos</span>
            </div>
            <div class="sidebar-item" data-tab="hospitales">
                <i class="fas fa-hospital sidebar-item-icon"></i>
                <span>Hospitales</span>
            </div>
            <div class="sidebar-item" data-tab="clinicas">
                <i class="fas fa-clinic-medical sidebar-item-icon"></i>
                <span>Clínicas</span>
            </div>
            <div class="sidebar-item active" data-tab="otros">
                <i class="fas fa-heartbeat sidebar-item-icon"></i>
                <span>Otros servicios de salud</span>
            </div>
        </div>

        <div class="content-wrapper">
            <h1 class="top-title">Servicios de Emergencia Cuautitlán</h1>
            
            <div class="content-area">
                
                <div class="main-content">
                    
                    <div id="forms-container">

                        <div id="medicos-form" class="form-content">
                            <div class="search-form-group">
                                <div class="search-input-container">
                                    <i class="fas fa-search"></i>
                                    <input type="text" placeholder="Buscar por especialidad">
                                </div>
                            </div>
                            <p class="form-section-title">MÉDICO</p>
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="nombre-medico">Nombre (Opcional)</label>
                                    <input type="text" id="nombre-medico" placeholder="">
                                </div>
                                <div class="form-field">
                                    <label for="circulo-tabulador">Círculo o Tabulador Médico</label>
                                    <select id="circulo-tabulador"><option>Todos</option></select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="especialidad">Especialidad</label>
                                    <select id="especialidad"><option>Todos</option></select>
                                </div>
                            </div>
                            <p class="form-section-title">LOCALIZACIÓN</p>
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="estado-m">Estado</label>
                                    <select id="estado-m"><option>Todos</option></select>
                                </div>
                                <div class="form-field">
                                    <label for="municipio-m">Municipio</label>
                                    <select id="municipio-m"><option>Todos</option></select>
                                </div>
                            </div>
                            <button type="submit" class="search-button">Buscar</button>
                        </div>

                        <div id="hospitales-form" class="form-content">
                             <div class="search-form-group">
                                <div class="search-input-container">
                                    <i class="fas fa-search"></i>
                                    <input type="text" placeholder="Buscar por nombre del hospital">
                                </div>
                            </div>
                            <p class="form-section-title">PLAN</p>
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="linea-negocio-h">Línea de negocio</label>
                                    <select id="linea-negocio-h"><option>Todos</option></select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="plan-hospitalario">Plan hospitalario</label>
                                    <select id="plan-hospitalario"><option>Todos</option></select>
                                </div>
                            </div>
                            <div style="font-size: 13px; margin-top: 10px; padding: 15px; background-color: #f9f9f9; border-left: 3px solid #005a9c;">
                                <p>Individual. La póliza de Gastos Médicos Mayores fue contratada a través de un Asesor Profesional de Seguros o tienda Departamental.</p>
                                <p>Colectivo. La póliza de Gastos Médicos Mayores es una presentación empresarial o bien, fue contratada a través de una empresa para contar con mayores beneficios.</p>
                            </div>
                            <p class="form-section-title">LOCALIZACIÓN</p>
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="estado-h">Estado</label>
                                    <select id="estado-h"><option>Todos</option></select>
                                </div>
                                <div class="form-field">
                                    <label for="municipio-h">Municipio</label>
                                    <select id="municipio-h"><option>Todos</option></select>
                                </div>
                            </div>
                            <button type="submit" class="search-button">Buscar</button>
                        </div>

                        <div id="clinicas-form" class="form-content">
                            <div class="search-form-group">
                                <div class="search-input-container">
                                    <i class="fas fa-search"></i>
                                    <input type="text" placeholder="Buscar por nombre de la clínica">
                                </div>
                            </div>

                            <p class="form-section-title">PLAN</p>
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="linea-negocio">Línea de negocio</label>
                                    <select id="linea-negocio"><option>Todos</option></select>
                                </div>
                            </div>

                            <p class="form-section-title">CLÍNICA</p>
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="tipo-clinica">Tipo de clínica</label>
                                    <select id="tipo-clinica"><option>Todos</option></select>
                                </div>
                            </div>

                            <p class="form-section-title">LOCALIZACIÓN</p>
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="estado">Estado</label>
                                    <select id="estado"><option>Todos</option></select>
                                </div>
                                <div class="form-field">
                                    <label for="municipio">Municipio</label>
                                    <select id="municipio"><option>Todos</option></select>
                                </div>
                            </div>

                            <button type="submit" class="search-button">Buscar</button>
                        </div>
                        
                        <div id="otros-form" class="form-content active">
                            <div class="search-form-group">
                                <div class="search-input-container">
                                    <i class="fas fa-search"></i>
                                    <input type="text" placeholder="Buscar por nombre del establecimiento">
                                </div>
                            </div>
                            
                            <p class="form-section-title">OTROS SERVICIOS</p>
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="tipo-servicio">Tipo de servicio</label>
                                    <select id="tipo-servicio">
                                        <option value="todos">Todos</option>
                                        </select>
                                </div>
                            </div>

                            <p class="form-section-title">LOCALIZACIÓN</p>
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="estado-o">Estado</label>
                                    <select id="estado-o">
                                        <option value="todos">Todos</option>
                                        </select>
                                </div>
                                <div class="form-field">
                                    <label for="municipio-o">Municipio</label>
                                    <select id="municipio-o">
                                        <option value="todos">Todos</option>
                                        </select>
                                </div>
                            </div>

                            <button type="submit" class="search-button">Buscar</button>
                        </div>

                    </div>
                </div>

                <div class="map-container">
                    <div class="map-placeholder">
                         </div>
                    <div class="map-controls">
                        <div class="map-search">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="map-zoom">
                            <button><i class="fas fa-plus"></i></button>
                            <button><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.sidebar-item');
            const formContents = document.querySelectorAll('.form-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-tab') + '-form';

                    // 1. Manejar la clase 'active' en el menú lateral
                    tabs.forEach(item => item.classList.remove('active'));
                    this.classList.add('active');

                    // 2. Mostrar u ocultar los formularios
                    formContents.forEach(content => {
                        if (content.id === targetId) {
                            content.classList.add('active');
                        } else {
                            content.classList.remove('active');
                        }
                    });
                });
            });
            
            // Asegura que el formulario 'otros' esté activo al cargar si la pestaña está activa
            const activeTab = document.querySelector('.sidebar-item.active');
            if (activeTab) {
                 const targetId = activeTab.getAttribute('data-tab') + '-form';
                 document.getElementById(targetId).classList.add('active');
            }
        });
    </script>

</body>
</html>
```http://googleusercontent.com/image_generation_content/0

<?php include '../../inclusiones/pie.php'; ?>
