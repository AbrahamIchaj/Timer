<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tarjetas de Cronómetros/Temporizadores</title>
    <style>
        .card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            margin: 10px;
            display: inline-block;
            width: 200px;
            text-align: center;
            position: relative;
        }
        .delete-button {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
        }
        .add-button {
            margin-top: 20px;
            padding: 10px;
            background-color: green;
            color: white;
            border: none;
            cursor: pointer;
        }
        .cards-container {
            display: flex;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>

    <h1>Bienvenido, <?php echo $username; ?>!</h1>
    <h2>Cantidad de tarjetas: <?php echo count($tarjetas); ?></h2>

    <div class="cards-container">
        <?php foreach ($tarjetas as $index => $tarjeta): ?>
            <div class="card">
                <!-- Botón de eliminar tarjeta -->
                <button class="delete-button" onclick="eliminarTarjeta(<?php echo $index; ?>)">X</button>
                <h3><?php echo $tarjeta['nombre']; ?></h3>

                <!-- Combobox para elegir el tipo (cronómetro o temporizador) -->
                <label for="tipo">Tipo:</label>
                <select name="tarjetas[<?php echo $index; ?>][tipo]">
                    <option value="cronometro" <?php echo $tarjeta['tipo'] == 'cronometro' ? 'selected' : ''; ?>>Cronómetro</option>
                    <option value="temporizador" <?php echo $tarjeta['tipo'] == 'temporizador' ? 'selected' : ''; ?>>Temporizador</option>
                </select>
            </div>
        <?php endforeach; ?>
    </div>

    <h3>Tarjetas disponibles: <?php echo count($tarjetas); ?></h3>
    <button class="add-button" onclick="agregarTarjeta()">+ Agregar tarjeta</button>

    <form id="form-eliminar" method="POST" action="<?php echo base_url('CronometroController/eliminarTarjeta'); ?>">
    <input type="hidden" name="username" value="<?php echo $username; ?>">
    <input type="hidden" id="index-eliminar" name="index" value="">
    <?php foreach ($tarjetas as $index => $tarjeta): ?>
        <input type="hidden" name="tarjetas[<?php echo $index; ?>][tipo]" value="<?php echo $tarjeta['tipo']; ?>">
    <?php endforeach; ?>
</form>

<form id="form-agregar" method="POST" action="<?php echo base_url('CronometroController/agregarTarjeta'); ?>">
    <input type="hidden" name="username" value="<?php echo $username; ?>">
    <?php foreach ($tarjetas as $index => $tarjeta): ?>
        <input type="hidden" name="tarjetas[<?php echo $index; ?>][tipo]" value="<?php echo $tarjeta['tipo']; ?>">
    <?php endforeach; ?>
</form>


    <script>
        function eliminarTarjeta(index) {
            // Asignar el índice al input hidden y enviar el formulario
            document.getElementById('index-eliminar').value = index;
            document.getElementById('form-eliminar').submit();
        }

        function agregarTarjeta() {
            // Enviar el formulario para agregar tarjeta
            document.getElementById('form-agregar').submit();
        }
    </script>

</body>
</html>
