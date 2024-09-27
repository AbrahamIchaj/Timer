<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tarjetas de Cronómetros/Temporizadores</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url('styles/bootstrap.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('styles/styles.css'); ?>">

    <!-- jQuery -->
    <script type="text/javascript" src="<?php echo base_url('styles/jquery-3.3.1.min.js'); ?>"></script>

    <!-- Script de funciones -->
    <script type="text/javascript" src="<?php echo base_url('scripts/script.js'); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.11/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<!-- MENU -->
<nav class="navbar navbar-expand-lg navbar-dark">
    
<h3>Total de contadores: <span id="cantidad-tarjetas"><?php echo count($tarjetas); ?></span></h3>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <!-- Menú de navegación -->
      <ul class="navbar-nav">
      </ul>
      <!-- Sección derecha con botones y usuario -->
      <div class="d-flex align-items-center">
        <span class="user-section ">Bienvenido: <?php echo $username; ?>!</span>
        <button class="btn add-button mx-2" id="add-button">+ Agregar tarjeta</button>
        <button class="btn btn-danger" id="exit-button">Cerrar Sesión</button>
      </div>
    </div>
  </nav>
    
<!-- TARJETAS -->
<body>
    <div class="container">
        <div class="row justify-content-center mt-4" id="cards-container">
        <?php foreach ($tarjetas as $index => $tarjeta): ?>
            <div class="col-md-4 mb-4 card-container" data-index="<?php echo $index; ?>">
                <div class="card shadow-sm">
                    <div class="d-flex justify-content-between mb-3">
                        <button class="delete-button" title="Eliminar tarjeta">X</button>
                        <button class="edit-button" title="Editar tarjeta">✎</button>
                    </div>
                    <input type="text" class="form-control mb-3 tarjeta-nombre" name="tarjetas[<?php echo $index; ?>][nombre]" value="<?php echo $tarjeta['nombre']; ?>" disabled />

                    <!-- Aquí agrego el botón de notificaciones -->
                    <button class="btn btn-secondary notification-btn" data-index="<?php echo $index; ?>">Notificaciones</button>

                    <div class="form-group">
                        <label for="tipo" style="color: white">Seleccionar:</label>
                        <select name="tarjetas[<?php echo $index; ?>][tipo]" class="form-control tipo-select">
                            <option value="cronometro" <?php echo $tarjeta['tipo'] == 'cronometro' ? 'selected' : ''; ?>>Cronómetro</option>
                            <option value="temporizador" <?php echo $tarjeta['tipo'] == 'temporizador' ? 'selected' : ''; ?>>Temporizador</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="time-inputs mt-3 mb-3" style="display: <?php echo $tarjeta['tipo'] == 'temporizador' ? 'block' : 'none'; ?>">
                            <input type="number" class="form-control d-inline-block hours-input input-w" placeholder="Horas" min="0" max="23">
                            <input type="number" class="form-control d-inline-block minutes-input input-w" placeholder="Minutos" min="0" max="59">
                            <input type="number" class="form-control d-inline-block seconds-input input-w" placeholder="Segundos" min="0" max="59">
                        </div>
                    </div>

                    <div class="time-display" id="display-<?php echo $index; ?>">00:00:00:000</div>

                    <div class="form-group">
                <br>
            <label for="backgroundColor" style="color: white">Color de fondo:</label>
            <input type="color" style="background: rgba(20, 20, 20, 0.6);" class="form-control background-color-input" data-index="<?php echo $index; ?> style="background: rgba(20, 20, 20, 0.6);"">
        </div>

                    <div class="text-center mt-3">
                        <button class="btn btn-primary start-btn" data-index="<?php echo $index; ?>">Iniciar</button>
                        <button class="btn btn-danger reset-btn" data-index="<?php echo $index; ?>">Reiniciar</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>

         <!-- Modal para la gestión de notificaciones -->
         <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="notificationModalLabel">Configurar Notificaciones</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="notificationForm">
                            <div class="form-group">
                                <label for="notificationMessage">Mensaje de Notificación</label>
                                <input type="text" class="form-control" id="notificationMessage" placeholder="Ingrese el mensaje">
                            </div>
                            <input type="hidden" id="notificationIndex">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="saveNotification">Guardar Cambios</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>

    // Botón salir
    $('#exit-button').click(function() {
        localStorage.clear();
        window.location.href = "<?php echo site_url('CronometroController/cronometro_form'); ?>";
    });

    
</script>

</body>