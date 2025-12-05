<?php
// inclusiones/pie.php

// PREVENIR CARGA MÚLTIPLE
if (defined('PIE_INCLUIDO')) {
    return;
}
define('PIE_INCLUIDO', true);

// Asegurar que $usuario_actual existe
if (!isset($usuario_actual) && function_exists('obtenerUsuarioActual')) {
    $usuario_actual = obtenerUsuarioActual();
}
?>
            </div> <!-- Cierre del container-principal -->
        </main> <!-- Cierre del main-content -->
        
        <!-- FOOTER -->
        <footer style="
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 30px 0 20px;
            margin-top: 50px;
        ">
            <div style="
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
            ">
                <div style="
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 30px;
                    margin-bottom: 30px;
                ">
                    <!-- Información de contacto -->
                    <div>
                        <h4 style="color: #ffd700; margin-bottom: 15px; border-bottom: 2px solid rgba(255,215,0,0.3); padding-bottom: 8px;">
                            <i class="fas fa-ambulance"></i> Emergencias Cuautitlán
                        </h4>
                        <p style="margin-bottom: 10px;">
                            <i class="fas fa-map-marker-alt" style="color: #26d0ce; margin-right: 10px;"></i>
                            Cuautitlán, Estado de México
                        </p>
                        <p style="margin-bottom: 10px;">
                            <i class="fas fa-phone" style="color: #26d0ce; margin-right: 10px;"></i>
                            Emergencias: 911
                        </p>
                        <p>
                            <i class="fas fa-envelope" style="color: #26d0ce; margin-right: 10px;"></i>
                            contacto@emergenciascuautitlan.mx
                        </p>
                    </div>
                    
                    <!-- Información del usuario -->
                    <div>
                        <h4 style="color: #ffd700; margin-bottom: 15px; border-bottom: 2px solid rgba(255,215,0,0.3); padding-bottom: 8px;">
                            <i class="fas fa-user"></i> Sesión Activa
                        </h4>
                        <p style="margin-bottom: 10px;">
                            <i class="fas fa-id-card" style="color: #26d0ce; margin-right: 10px;"></i>
                            <?php echo htmlspecialchars($usuario_actual['nombre_completo'] ?? 'Usuario'); ?>
                        </p>
                        <p style="margin-bottom: 10px;">
                            <span style="
                                background: #e74c3c;
                                color: white;
                                padding: 3px 10px;
                                border-radius: 15px;
                                font-size: 0.9rem;
                            ">
                                <?php echo $usuario_actual['rol'] ?? 'invitado'; ?>
                            </span>
                            <small style="margin-left: 10px;">
                                <i class="fas fa-clock"></i> <?php echo date('H:i:s'); ?>
                            </small>
                        </p>
                        <p>
                            <i class="fas fa-user-tag" style="color: #26d0ce; margin-right: 10px;"></i>
                            Usuario: <?php echo $usuario_actual['username'] ?? 'N/A'; ?>
                        </p>
                    </div>
                    
                    <!-- Información técnica -->
                    <div>
                        <h4 style="color: #ffd700; margin-bottom: 15px; border-bottom: 2px solid rgba(255,215,0,0.3); padding-bottom: 8px;">
                            <i class="fas fa-code"></i> Sistema
                        </h4>
                        <p style="margin-bottom: 10px;">
                            <i class="fas fa-database" style="color: #26d0ce; margin-right: 10px;"></i>
                            MySQL - PHP
                        </p>
                        <p style="margin-bottom: 10px;">
                            <i class="fas fa-paint-brush" style="color: #26d0ce; margin-right: 10px;"></i>
                            Bootstrap 5
                        </p>
                        <p>
                            <i class="fas fa-shield-alt" style="color: #26d0ce; margin-right: 10px;"></i>
                            Sistema seguro
                        </p>
                    </div>
                </div>
                
                <hr style="border-color: rgba(255,255,255,0.1); margin: 20px 0;">
                
                <div style="
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: space-between;
                    align-items: center;
                    gap: 20px;
                ">
                    <div style="flex: 1; min-width: 300px;">
                        <small>
                            Desarrollado por: 
                            <strong style="color: #ffd700;">
                                Amezcua Sagrero, Sergio Daniel |
                                Lujano Valeriano, Dulce Odalys |
                                Cruz Vergara, Laura Rocxana |
                                Vargas Almanza, Gerardo Enrique
                            </strong>
                        </small>
                    </div>
                    
                    <div>
                        <small>
                            &copy; <?php echo date('Y'); ?> Sistema de Emergencias Cuautitlán
                        </small>
                        <br>
                        <small style="opacity: 0.8;">
                            Todos los derechos reservados
                        </small>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Scripts personalizados -->
        <script>
            // Actualizar hora en tiempo real
            function actualizarReloj() {
                const ahora = new Date();
                const horas = ahora.getHours().toString().padStart(2, '0');
                const minutos = ahora.getMinutes().toString().padStart(2, '0');
                const segundos = ahora.getSeconds().toString().padStart(2, '0');
                
                // Actualizar todos los elementos con clase 'user-time'
                document.querySelectorAll('.user-time').forEach(el => {
                    el.textContent = horas + ':' + minutos + ':' + segundos;
                });
            }
            
            // Actualizar cada segundo
            setInterval(actualizarReloj, 1000);
            actualizarReloj(); // Ejecutar inmediatamente
            
            // Confirmación para logout
            document.querySelectorAll('.btn-logout-header').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    if (!confirm('¿Estás seguro de cerrar sesión?')) {
                        e.preventDefault();
                    }
                });
            });
        </script>
    </body>
</html>