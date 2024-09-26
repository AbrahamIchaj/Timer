<!-- LISTA DE USERS -->
<div class="content">
    <h2 style="color:black">Lista de Usuarios</h2>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#agregarModalUsuario">Agregar Usuario</button>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Password</th>
                <th>Role</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)) : ?>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo $user->id; ?></td>
                        <td><?php echo $user->username; ?></td>
                        <td><?php echo $user->password; ?></td>
                        <td><?php echo $user->role_name; ?></td>
                        <td>
                            <button class="btn btn-warning" data-toggle="modal" data-target="#editarModalUsuario<?php echo $user->id; ?>">Editar</button>
                            <a href="<?php echo site_url('CronometroController/eliminar_usuario/' . $user->id); ?>" class="btn btn-danger">Eliminar</a>
                        </td>
                    </tr>

                    <!-- Modal para Editar Usuario -->
                    <div class="modal fade" id="editarModalUsuario<?php echo $user->id; ?>" tabindex="-1" role="dialog" aria-labelledby="editarModalLabelUsuario<?php echo $user->id; ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editarModalLabelUsuario<?php echo $user->id; ?>">Editar Usuario</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="<?php echo site_url('CronometroController/editar_usuario/' . $user->id); ?>" method="post">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="username">Nombre de Usuario</label>
                                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $user->username; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Contraseña</label>
                                            <input type="text" class="form-control" id="password" name="password" value="<?php echo $user->password; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="role_id">Rol</label>
                                            <select class="form-control" id="role_id" name="role_id">
                                                <?php foreach ($roles as $rol) : ?>
                                                    <option value="<?php echo $rol->id; ?>" <?php echo ($rol->id == $user->role_id) ? 'selected' : ''; ?>>
                                                        <?php echo $rol->role_name; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
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
                        <td colspan="5">No hay usuarios disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

            <!--Agregar Usuario -->
            <div class="modal fade" id="agregarModalUsuario" tabindex="-1" role="dialog" aria-labelledby="agregarModalLabelUsuario" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="agregarModalLabelUsuario">Agregar Usuario</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="<?php echo site_url('CronometroController/agregar_usuario'); ?>" method="post">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="username">Nombre de Usuario</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Contraseña</label>
                                    <input type="text" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="form-group">
                                    <label for="role_id">Rol</label>
                                    <select class="form-control" id="role_id" name="role_id">
                                        <?php foreach ($roles as $rol) : ?>
                                            <option value="<?php echo $rol->id; ?>"><?php echo $rol->role_name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Agregar Usuario</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
