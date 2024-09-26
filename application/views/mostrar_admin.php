<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Roles</title>
     <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url('styles/bootstrap.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('styles/styles.css'); ?>">

    <!-- jQuery -->
    <script type="text/javascript" src="<?php echo base_url('styles/jquery-3.3.1.min.js'); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.11/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    

    <style>
        .content {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<!-- MENU -->
<nav class="navbar navbar-expand-lg navbar-dark">
    
<!-- <h3>Total de contadores: <span id="cantidad-tarjetas"><?php echo count($tarjetas); ?></span></h3> -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <!-- Menú de navegación -->
      <h1>VISTA DE ADMINISTRADOR</h1>
      <ul class="navbar-nav">
      </ul>
      <!-- Sección derecha con botones y usuario -->
      <div class="d-flex align-items-center">
      <span class="user-section">Bienvenido: <?php echo isset($username) ? $username : 'Invitado'; ?>!</span>
      <span class="user-section">Rol: <?php echo isset($rol) ? $rol : 'Sin rol'; ?>!</spa>

        <button class="btn btn-danger" id="exit-button">Cerrar Sesión</button>
      </div>
    </div>
  </nav>

  
    <!-- Vista de roles -->
    <?php $this->load->view('CRUD/v_roles'); ?>
  
    <!-- Vista de usuarios -->
    <?php $this->load->view('CRUD/v_users'); ?>
    
    <script>
    $(document).ready(function() {
    $('#exit-button').click(function() {
    window.location.href = "<?php echo site_url('CronometroController/cronometro_form'); ?>";
    });

    var userRole = "<?php echo isset($rol) ? $rol : ''; ?>";
    console.log("El rol del usuario es:", userRole);

    });
  

</script>

</body>

