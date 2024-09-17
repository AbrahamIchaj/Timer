<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tarjetas de Cronómetros/Temporizadores</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url('styles/bootstrap.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('styles/styles.css'); ?>">

    <!-- jQuery -->
    <script type="text/javascript" src="<?php echo base_url('styles/jquery-3.3.1.min.js'); ?>"></script>

    <!-- Custom Styles -->
</head>
<body class="container mt-5">

    <h1 class="text-center mb-4">Bienvenido, <?php echo $username; ?>!</h1>

    <div class="text-center">
        <button class="btn btn-primary add-button" id="add-button">+ Agregar tarjeta</button>
    </div>
    <h2 class="text-center mt-4">Cantidad de tarjetas: <span id="cantidad-tarjetas"><?php echo count($tarjetas); ?></span></h2>

    <div class="row" id="cards-container">
        <?php foreach ($tarjetas as $index => $tarjeta): ?>
            <div class="col-md-6">
                <div class="card shadow-sm mb-4" data-index="<?php echo $index; ?>">
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-danger btn-sm delete-button" title="Eliminar tarjeta">X</button>
                        <button class="btn btn-info btn-sm edit-button" title="Editar tarjeta">✎</button>
                    </div>
                    
                    <input type="text" class="form-control mb-3 tarjeta-nombre" name="tarjetas[<?php echo $index; ?>][nombre]" value="<?php echo $tarjeta['nombre']; ?>" disabled />

                    <div class="form-group">
                        <label for="tipo">Tipo:</label>
                        <select name="tarjetas[<?php echo $index; ?>][tipo]" class="form-control tipo-select">
                            <option value="cronometro" <?php echo $tarjeta['tipo'] == 'cronometro' ? 'selected' : ''; ?>>Cronómetro</option>
                            <option value="temporizador" <?php echo $tarjeta['tipo'] == 'temporizador' ? 'selected' : ''; ?>>Temporizador</option>
                        </select>
                    </div>

                    <div class="time-inputs mt-3 mb-3" style="display: <?php echo $tarjeta['tipo'] == 'temporizador' ? 'block' : 'none'; ?>">
                        <input type="number" class="form-control d-inline-block hours-input" placeholder="Horas" min="0" max="23">
                        <input type="number" class="form-control d-inline-block minutes-input" placeholder="Minutos" min="0" max="59">
                        <input type="number" class="form-control d-inline-block seconds-input" placeholder="Segundos" min="0" max="59">
                    </div>

                    <div class="time-display" id="display-<?php echo $index; ?>">00:00:00.000</div>

                    <div class="text-center mt-3">
                        <button class="btn btn-success start-btn" data-index="<?php echo $index; ?>">Iniciar</button>
                        <button class="btn btn-warning reset-btn" data-index="<?php echo $index; ?>">Reiniciar</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        $(document).ready(function() {
            let timers = {};
            let intervals = {};
            let startTimes = {};

            // Manejador de cambio de tipo (cronómetro o temporizador)
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
                    startTimes[index] = Date.now();
                    if (tipo === 'cronometro') {
                        startCronometro(index, display);
                    } else if (tipo === 'temporizador') {
                        startTemporizador(index, display, card);
                    }
                    $(this).text('Pausar');
                }
            });

            function startCronometro(index, display) {
                let elapsed = intervals[index] || 0;
                timers[index] = setInterval(function() {
                    elapsed = Date.now() - startTimes[index];
                    intervals[index] = elapsed;
                    display.text(formatTime(elapsed / 1000));
                }, 10);
            }

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

            // Función para editar el nombre de la tarjeta
            $(document).on('click', '.edit-button', function() {
                var card = $(this).closest('.card');
                var inputNombre = card.find('.tarjeta-nombre');
                inputNombre.prop('disabled', !inputNombre.prop('disabled'));

                if (inputNombre.prop('disabled')) {
                    $(this).text('✎');  // Vuelve al modo edición
                } else {
                    $(this).text('✔');  // Guardar cambios
                }
            });

            // Eliminar tarjeta
            $(document).on('click', '.delete-button', function() {
                $(this).closest('.card').remove();
                actualizarCantidadTarjetas();
            });

            // Agregar nueva tarjeta
            $('#add-button').click(function() {
                var nuevaTarjeta = `
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4" data-index="">
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-danger btn-sm delete-button" title="Eliminar tarjeta">X</button>
                                <button class="btn btn-info btn-sm edit-button" title="Editar tarjeta">✎</button>
                            </div>
                            <input type="text" class="form-control mb-3 tarjeta-nombre" name="tarjetas[][nombre]" value="Tarjeta Nueva" disabled />
                            <div class="form-group">
                                <label for="tipo">Tipo:</label>
                                <select name="tarjetas[][tipo]" class="form-control tipo-select">
                                    <option value="cronometro">Cronómetro</option>
                                    <option value="temporizador">Temporizador</option>
                                </select>
                            </div>
                            <div class="time-inputs mt-3 mb-3" style="display:none;">
                                <input type="number" class="form-control d-inline-block hours-input" placeholder="Horas" min="0" max="23">
                                <input type="number" class="form-control d-inline-block minutes-input" placeholder="Minutos" min="0" max="59">
                                <input type="number" class="form-control d-inline-block seconds-input" placeholder="Segundos
                                </input>
                            </div>
                            <div class="time-display">00:00:00.000</div>
                            <div class="text-center mt-3">
                                <button class="btn btn-success start-btn" data-index="">Iniciar</button>
                                <button class="btn btn-warning reset-btn" data-index="">Reiniciar</button>
                            </div>
                        </div>
                    </div>`;
                $('#cards-container').append(nuevaTarjeta);
                actualizarCantidadTarjetas();
            });

            function actualizarCantidadTarjetas() {
                var cantidad = $('.card').length;
                $('#cantidad-tarjetas').text(cantidad);
            }
        });
    </script>

</body>
</html>
