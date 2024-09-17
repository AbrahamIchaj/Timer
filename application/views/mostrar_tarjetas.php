<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tarjetas de Cronómetros/Temporizadores</title>
    <!-- Css -->
    <link rel="stylesheet" href="<?php echo base_url('styles/styles.css'); ?>">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo base_url('styles/bootstrap.css'); ?>">

    <!-- jQuery -->
    <script type="text/javascript" src="<?php echo base_url('styles/jquery-3.3.1.min.js'); ?>"></script>

</head>
<body class="container">

    <h1>Bienvenido, <?php echo $username; ?>!</h1>
    <h2>Cantidad de tarjetas: <span id="cantidad-tarjetas"><?php echo count($tarjetas); ?></span></h2>

    <div class="cards-container" id="cards-container">
        <?php foreach ($tarjetas as $index => $tarjeta): ?>
            <div class="card" data-index="<?php echo $index; ?>">
                <!-- Botones de eliminar y editar tarjeta -->
                <button class="delete-button">X</button>
                <button class="edit-button">✎</button>
                <input type="text" name="tarjetas[<?php echo $index; ?>][nombre]" value="<?php echo $tarjeta['nombre']; ?>" disabled />
                
                <label for="tipo">Tipo:</label>
                <select name="tarjetas[<?php echo $index; ?>][tipo]" class="tipo-select">
                    <option value="cronometro" <?php echo $tarjeta['tipo'] == 'cronometro' ? 'selected' : ''; ?>>Cronómetro</option>
                    <option value="temporizador" <?php echo $tarjeta['tipo'] == 'temporizador' ? 'selected' : ''; ?>>Temporizador</option>
                </select>
                <div class="message"></div>
            </div>
        <?php endforeach; ?>
    </div>

    <button class="add-button" id="add-button">+ Agregar tarjeta</button>

    <script>
        $(document).ready(function() {
            // Eliminar tarjeta
            $(document).on('click', '.delete-button', function() {
                $(this).closest('.card').remove();
                actualizarCantidadTarjetas();
            });

            // Agregar nueva tarjeta
            $('#add-button').click(function() {
                var nuevaTarjeta = `
                    <div class="card" data-index="">
                        <button class="delete-button">X</button>
                        <button class="edit-button">✎</button>
                        <input type="text" name="tarjetas[][nombre]" value="Tarjeta Nueva" disabled />
                        <label for="tipo">Tipo:</label>
                        <select name="tarjetas[][tipo]" class="tipo-select">
                            <option value="cronometro">Cronómetro</option>
                            <option value="temporizador">Temporizador</option>
                        </select>
                        <div class="message"></div>
                    </div>`;
                $('#cards-container').append(nuevaTarjeta);
                actualizarCantidadTarjetas();
            });

            // Editar nombre de la tarjeta
            $(document).on('click', '.edit-button', function() {
                var input = $(this).siblings('input[type="text"]');
                if (input.is(':disabled')) {
                    input.prop('disabled', false).focus();
                    $(this).text('✓').css('background-color', 'green');
                } else {
                    input.prop('disabled', true);
                    $(this).text('✎').css('background-color', 'blue');
                }
            });

            // Mostrar mensajes según la selección del combobox
            $(document).on('change', '.tipo-select', function() {
                var messageDiv = $(this).siblings('.message');
                var selectedValue = $(this).val();
                if (selectedValue === 'cronometro') {
                    messageDiv.text('Hola');
                } else if (selectedValue === 'temporizador') {
                    messageDiv.text('Qué tal');
                } else {
                    messageDiv.text('');
                }
            });

            // Actualizar la cantidad de tarjetas
            function actualizarCantidadTarjetas() {
                var cantidad = $('.card').length;
                $('#cantidad-tarjetas').text(cantidad);
            }

           
        });
    </script>

</body>
</html>
