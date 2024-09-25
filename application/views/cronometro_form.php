<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar Cronómetros y Temporizadores</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url('styles/bootstrap.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('styles/style_form.css'); ?>">

</head>
<body>

<div class="container">
    <div class="form-container">
        <div class="form-left">
            <img src="https://d41chssnpqdne.cloudfront.net/image_tools/37181690/task_8a94f71a-7977-4546-9279-7fd652ac73c9/zOVrR.png?Expires=1742359561&Signature=pV5NGn5sMozhExLrocDNRXo5uDq07SMJ1zPxPc7WMVj0JK67KPyOFDoyAQjZ7DnOQq4NsYYxreR~ORUGATP6tyckAx7QPST8MBukbQ7m92iM9DBMXwRqbe434KOvt1xcbhQNx0-v~ioYyjo1C~4hPVngH7QcNeqckXNvTC--elBh2LSNFQ5z5D-U0JxU8hy9OUvqqb2l4o5dNYmZju8Cqw-XpgvSi2WuMIa46aklLxdTB-BKMKAMBDQAiHwC9MizpcFtc-~WWmJgLwdevau1bfRjJWDEVV46qOBxsMUjGsV-mMnpoam0E85ZBZpfgOGev50D0siyY7uraOqNkaXbgA__&Key-Pair-Id=K3USGZIKWMDCSX">
            <h2>Bienvenido a Timer</h2>
            <p>Configura tus cronómetros y temporizadores fácilmente.</p>
        </div>

        <div class="form-right">
            <h3>Cronómetros y Temporizadores</h3>

            <!-- Formulario adaptado -->
            <?php echo form_open('CronometroController/procesarFormulario'); ?>
                <div class="form-group">
                    <label for="username">Nombre de usuario</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo set_value('username'); ?>" placeholder="Ingrese su nombre de usuario" required>
                </div>
                <!-- <div class="form-group">
                    <label for="num_controles">Cantidad de tarjetas</label>
                    <input type="number" class="form-control" id="num_controles" name="num_controles" value="<?php echo set_value('num_controles'); ?>" min="1" placeholder="Ingrese la cantidad de tarjetas" required>
                </div> -->
                <div class="form-group">
                    <label for="num_controles">Contraseña</label>
                    <input type="number" class="form-control" id="contrasena" name="contrasena"  placeholder="Ingrese la contraseña" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Aceptar</button>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Limpiar el localStorage al cargar el formulario -->
<script>
    localStorage.clear();
</script>

</body>
</html>
