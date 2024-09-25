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
</head>

<!-- MENU -->
<nav class="navbar navbar-expand-lg navbar-dark">
    
<!-- <h3>Total de contadores: <span id="cantidad-tarjetas"><?php echo count($tarjetas); ?></span></h3> -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <!-- Menú de navegación -->
      <ul class="navbar-nav">
      </ul>
      <!-- Sección derecha con botones y usuario -->
      <div class="d-flex align-items-center">
      <span class="user-section">Bienvenido: <?php echo isset($username) ? $username : 'Invitado'; ?>!</span>
      <br>
      <span class="user-section">Rol: <?php echo isset($rol) ? $rol : 'Sin rol'; ?>!</span>

        <button class="btn add-button mx-2" id="add-button">+ Agregar tarjeta</button>
        <button class="btn btn-danger" id="exit-button">Cerrar Sesión</button>
      </div>
    </div>
  </nav>
    
<!-- TARJETAS -->
<body>
    <div class="container">
       
        <div class="row justify-content-center mt-4" id="cards-container">
        
      
        </div>

<script>

    // Botón salir
    $('#exit-button').click(function() {
        localStorage.clear();
        window.location.href = "<?php echo site_url('CronometroController/cronometro_form'); ?>";
    });

    var userRole = "<?php echo isset($rol) ? $rol : ''; ?>";
    console.log("El rol del usuario es:", userRole);

</script>

</body>