<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario Cronómetro/Temporizador</title>
</head>
<body>

    <h1>Configurar Cronómetros y Temporizadores</h1>

    <!-- Formulario para ingresar nombre de usuario y cantidad de tarjetas -->
    <?php echo form_open('CronometroController/procesarFormulario'); ?>
    
        <label for="username">Nombre de usuario:</label>
        <input type="text" name="username" value="<?php echo set_value('username'); ?>" required /><br/>

        <label for="num_controles">Cantidad de tarjetas:</label>
        <input type="number" name="num_controles" value="<?php echo set_value('num_controles'); ?>" min="1" required /><br/>

        <input type="submit" value="Aceptar" />

    <?php echo form_close(); ?>

    <!-- Limpiar el localStorage al cargar el formulario -->
    <script>
        localStorage.clear();
    </script>

</body>
</html>
