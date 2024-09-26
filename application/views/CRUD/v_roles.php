<div class="content">
            <h2 style="color:black">Lista de Roles</h2>
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

                            <!--Editar -->
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

        <!--Agregar Rol -->
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
