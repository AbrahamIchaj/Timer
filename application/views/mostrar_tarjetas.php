<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tarjetas de Cronómetros/Temporizadores</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
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
    <!-- Incluir jQuery desde CDN -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body class="container">

    <h1>Bienvenido, <?php echo $username; ?>!</h1>
    <h2>Cantidad de tarjetas: <span id="cantidad-tarjetas"><?php echo count($tarjetas); ?></span></h2>

    <div class="cards-container" id="cards-container">
        <?php foreach ($tarjetas as $index => $tarjeta): ?>
            <div class="card" data-index="<?php echo $index; ?>">
                <!-- Botón de eliminar tarjeta -->
                <button class="delete-button">X</button>
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
                        <h3>Nueva Tarjeta</h3>
                        <label for="tipo">Tipo:</label>
                        <select name="tarjetas[][tipo]">
                            <option value="cronometro">Cronómetro</option>
                            <option value="temporizador">Temporizador</option>
                        </select>
                    </div>`;
                $('#cards-container').append(nuevaTarjeta);
                actualizarCantidadTarjetas();
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
