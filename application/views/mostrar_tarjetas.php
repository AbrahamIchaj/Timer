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

    <style>
        .card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .time-display {
            font-size: 30px;
            margin: 10px 0;
            font-weight: bold;
        }
        button {
            margin: 5px;
        }
        .time-inputs {
            display: none;
        }
    </style>
</head>
<body class="container">

    <h1>Bienvenido, <?php echo $username; ?>!</h1>
    <button class="add-button" id="add-button">+ Agregar tarjeta</button>
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

                <!-- Inputs de tiempo para temporizador -->
                <div class="time-inputs">
                    <input type="number" class="hours-input" placeholder="Horas" min="0" max="23">
                    <input type="number" class="minutes-input" placeholder="Minutos" min="0" max="59">
                    <input type="number" class="seconds-input" placeholder="Segundos" min="0" max="59">
                </div>

                <!-- Contenedor de tiempo -->
                <div class="time-display" id="display-<?php echo $index; ?>">00:00:00.000</div>
                
                <!-- Botones de control -->
                 <content>
                <button class="start-btn" data-index="<?php echo $index; ?>">Iniciar</button>
                <button class="reset-btn" data-index="<?php echo $index; ?>">Reiniciar</button></content>
            </div>
        <?php endforeach; ?>
    </div>

    

    <script>
        $(document).ready(function() {
            let timers = {};
            let intervals = {};
            let startTimes = {};

            // Ocultar o mostrar los inputs según la selección de combobox
            $(document).on('change', '.tipo-select', function() {
                var tipo = $(this).val();
                var card = $(this).closest('.card');
                if (tipo === 'cronometro') {
                    card.find('.time-inputs').hide();
                } else if (tipo === 'temporizador') {
                    card.find('.time-inputs').show();
                }
            });

            // Iniciar cronómetro o temporizador
            $(document).on('click', '.start-btn', function() {
                let index = $(this).data('index');
                let card = $(this).closest('.card');
                let tipo = card.find('.tipo-select').val();
                let display = card.find('.time-display');
                
                if (timers[index]) {
                    clearInterval(timers[index]);
                    timers[index] = null;
                    $(this).text('Iniciar');
                } else {
                    startTimes[index] = Date.now(); // Almacena el tiempo de inicio
                    if (tipo === 'cronometro') {
                        startCronometro(index, display);
                    } else if (tipo === 'temporizador') {
                        startTemporizador(index, display, card);
                    }
                    $(this).text('Pausar');
                }
            });

            // Función para el cronómetro (contar hacia arriba)
            function startCronometro(index, display) {
                let elapsed = intervals[index] || 0;
                timers[index] = setInterval(function() {
                    elapsed = Date.now() - startTimes[index];
                    intervals[index] = elapsed;
                    display.text(formatTime(elapsed / 1000));
                }, 10);
            }

            // Función para el temporizador (contar hacia abajo)
            function startTemporizador(index, display, card) {
                let hours = parseInt(card.find('.hours-input').val()) || 0;
                let minutes = parseInt(card.find('.minutes-input').val()) || 0;
                let seconds = parseInt(card.find('.seconds-input').val()) || 0;
                
                let totalMilliseconds = ((hours * 3600) + (minutes * 60) + seconds) * 1000;
                intervals[index] = totalMilliseconds;

                timers[index] = setInterval(function() {
                    totalMilliseconds -= 10;
                    intervals[index] = totalMilliseconds;
                    display.text(formatTime(totalMilliseconds / 1000));

                    if (totalMilliseconds <= 0) {
                        clearInterval(timers[index]);
                        alert('¡Tiempo terminado!');
                    }
                }, 10);
            }

            // Formatear el tiempo en horas, minutos, segundos y milisegundos
            function formatTime(s) {
                const hours = Math.floor(s / 3600);
                const minutes = Math.floor((s % 3600) / 60);
                const seconds = Math.floor(s % 60);
                const milliseconds = Math.floor((s % 1) * 1000);
                return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}.${milliseconds.toString().padStart(3, '0')}`;
            }

            // Reiniciar cronómetro o temporizador
            $(document).on('click', '.reset-btn', function() {
                let index = $(this).data('index');
                clearInterval(timers[index]);
                timers[index] = null;
                intervals[index] = 0;
                $('#display-' + index).text('00:00:00.000');
                $('.start-btn[data-index="' + index + '"]').text('Iniciar');
            });

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
                        <div class="time-inputs">
                            <input type="number" class="hours-input" placeholder="Horas" min="0" max="23">
                            <input type="number" class="minutes-input" placeholder="Minutos" min="0" max="59">
                            <input type="number" class="seconds-input" placeholder="Segundos" min="0" max="59">
                        </div>
                        <div class="time-display" id="display-new">00:00:00.000</div>
            <content>
                <button class="start-btn" data-index="<?php echo $index; ?>">Iniciar</button>
                <button class="reset-btn" data-index="<?php echo $index; ?>">Reiniciar</button>
            </content>
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
