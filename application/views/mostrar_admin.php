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
      <ul class="navbar-nav">
      </ul>
      <!-- Sección derecha con botones y usuario -->
      <div class="d-flex align-items-center">
      <span class="user-section">Bienvenido: <?php echo isset($username) ? $username : 'Invitado'; ?>!</span>
      <br>
      <span class="user-section">Rol: <?php echo isset($rol) ? $rol : 'Sin rol'; ?>!</span>

        <button class="btn add-button mx-2" id="add-button">Roles</button>
        <button class="btn add-button mx-2" id="add-button">Users</button>
        <button class="btn btn-danger" id="exit-button">Cerrar Sesión</button>
      </div>
    </div>
  </nav>

    <div class="content">
        <h2>Lista de Roles</h2>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#agregarModal">Agregar Rol</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($roles)) : ?>
                    <?php foreach ($roles as $rol) : ?>
                        <tr>
                            <td><?php echo $rol->id; ?></td>
                            <td><?php echo $rol->role_name; ?></td>
                            <td>
                                <button class="btn btn-warning" data-toggle="modal" data-target="#editarModal<?php echo $rol->id; ?>">Editar</button>
                                <a href="<?php echo site_url('CronometroController/eliminar_rol/' . $rol->id); ?>" class="btn btn-danger">Eliminar</a>
                            </td>
                        </tr>

                        <!-- Modal para Editar -->
                        <div class="modal fade" id="editarModal<?php echo $rol->id; ?>" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel<?php echo $rol->id; ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editarModalLabel<?php echo $rol->id; ?>">Editar Rol</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="<?php echo site_url('CronometroController/editar_rol/' . $rol->id); ?>" method="post">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="role_name">Nombre del Rol</label>
                                                <input type="text" class="form-control" id="role_name" name="role_name" value="<?php echo $rol->role_name; ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3">No hay roles disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para Agregar -->
    <div class="modal fade" id="agregarModal" tabindex="-1" role="dialog" aria-labelledby="agregarModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarModalLabel">Agregar Rol</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo site_url('CronometroController/agregar_rol'); ?>" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="role_name">Nombre del Rol</label>
                            <input type="text" class="form-control" id="role_name" name="role_name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Agregar Rol</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    
    <script>

    // Botón salir
    $('#exit-button').click(function() {
        localStorage.clear();
        window.location.href = "<?php echo site_url('CronometroController/cronometro_form'); ?>";
    });

    var userRole = "<?php echo isset($rol) ? $rol : ''; ?>";
    console.log("El rol del usuario es:", userRole);

</body>
</html>
