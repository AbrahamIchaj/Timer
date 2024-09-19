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

</head>
<body class="container-fluid text-center mt-5">
    <div class="container">
        <h1 class="mb-4">Bienvenido, <?php echo $username; ?>!</h1>

        <div class="text-center mb-4">
            <button class="btn add-button" id="add-button">+ Agregar tarjeta</button>
        </div>

        <div class="text-center mb-4">
            <button class="btn btn-danger" id="exit-button">Salir</button>
        </div>

        <h2>Cantidad de tarjetas: <span id="cantidad-tarjetas"><?php echo count($tarjetas); ?></span></h2>

        <div class="row justify-content-center mt-4" id="cards-container">
        
        <?php foreach ($tarjetas as $index => $tarjeta): ?>
            <br>
            <div class="col-md-4 mb-4 card-container" data-index="<?php echo $index; ?>">
                <div class="card shadow-sm">
                    <div class="d-flex justify-content-between mb-3">
                        <button class="delete-button" title="Eliminar tarjeta">X</button>
                        <button class="edit-button" title="Editar tarjeta">✎</button>
                    </div>

                    <input type="text" class="form-control mb-3 tarjeta-nombre" name="tarjetas[<?php echo $index; ?>][nombre]" value="<?php echo $tarjeta['nombre']; ?>" disabled />

                    <div class="form-group">
                        <label for="tipo" style="color: black;">Seleccionar:</label>
                        <select name="tarjetas[<?php echo $index; ?>][tipo]" class="form-control tipo-select">
                            <option value="cronometro" <?php echo $tarjeta['tipo'] == 'cronometro' ? 'selected' : ''; ?>>Cronómetro</option>
                            <option value="temporizador" <?php echo $tarjeta['tipo'] == 'temporizador' ? 'selected' : ''; ?>>Temporizador</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="time-inputs mt-3 mb-3" style="display: <?php echo $tarjeta['tipo'] == 'temporizador' ? 'block' : 'none'; ?>">
                            <input type="number" class="form-control d-inline-block hours-input input-w" placeholder="Horas" min="0" max="23">
                            <input type="number" class="form-control d-inline-block minutes-input input-w" placeholder="Minutos" min="0" max="59">
                            <input type="number" class="form-control d-inline-block seconds-input input-w" placeholder="Segundos" min="0" max="59">
                        </div>
                    </div>

                    <div class="time-display" id="display-<?php echo $index; ?>">00:00:00:000</div>

                    <div class="text-center mt-3">
                        <button class="btn btn-primary start-btn" data-index="<?php echo $index; ?>">Iniciar</button>
                        <button class="btn btn-dark reset-btn" data-index="<?php echo $index; ?>">Reiniciar</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>

        <script>
        $(document).ready(function() {
                let timers = {};         // Guardar intervalos activos
                let intervals = {};      // Guardar el tiempo acumulado en cada cronómetro/temporizador
                let startTimes = {};     // Guardar el tiempo en el que el cronómetro comenzó o se reanudó
                let pausedTimes = {};
                let totalElapsedTimes = {};

            cargarTarjetasDesdeLocalStorage();


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
                        // Pausar cronómetro
                        clearInterval(timers[index]);
                        timers[index] = null;
                        pausedTimes[index] = Date.now();  // Guardar el tiempo de pausa
                        $(this).text('Iniciar');
                    } else {
                        // Reanudar o iniciar cronómetro
                        if (pausedTimes[index]) {
                            let pausedDuration = Date.now() - pausedTimes[index];
                            startTimes[index] += pausedDuration;  // Ajustar tiempo de inicio
                            pausedTimes[index] = null;
                        } else if (!totalElapsedTimes[index]) {
                            startTimes[index] = Date.now();
                            totalElapsedTimes[index] = 0;  // Tiempo total inicial
                        }

                        if (tipo === 'cronometro') {
                            startCronometro(index, display);
                        } else if (tipo === 'temporizador') {
                            startTemporizador(index, display, card);
                        }
                        $(this).text('Pausar');
                    }
                    saveTimerStateToLocalStorage();
                });

                // Cronómetro
                function startCronometro(index, display) {
                    timers[index] = setInterval(function() {
                        let currentTime = Date.now() - startTimes[index];
                        intervals[index] = totalElapsedTimes[index] + currentTime;
                        display.text(formatTime(intervals[index] / 1000));
                        saveTimerStateToLocalStorage();
                    }, 10);
                }

                // Temporizador
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
                        saveTimerStateToLocalStorage();
                    }, 10);
                }

                // Formato de tiempo
                function formatTime(s) {
                    const hours = Math.floor(s / 3600);
                    const minutes = Math.floor((s % 3600) / 60);
                    const seconds = Math.floor(s % 60);
                    const milliseconds = Math.floor((s % 1) * 1000);
                    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}:${milliseconds.toString().padStart(3, '0')}`;
                }

                // Reiniciar
                $(document).on('click', '.reset-btn', function() {
                    let index = $(this).data('index');

                    clearInterval(timers[index]);
                    timers[index] = null;
                    intervals[index] = 0;
                    totalElapsedTimes[index] = 0;
                    startTimes[index] = null;
                    pausedTimes[index] = null;

                    $('#display-' + index).text('00:00:00:000');
                    $('.start-btn[data-index="' + index + '"]').text('Iniciar');
                    saveTimerStateToLocalStorage();
                });

                // Guardar cronómetro/temporizador en localStorage sin eliminar tarjetas
                function saveTimerStateToLocalStorage() {
                    $('.card-container').each(function(index) {
                        localStorage.setItem('timer-' + index, JSON.stringify({
                            timeElapsed: intervals[index] || 0,
                            startTime: startTimes[index] || null,
                            pausedTime: pausedTimes[index] || null,
                            totalElapsed: totalElapsedTimes[index] || 0,
                            paused: timers[index] === null
                        }));
                    });
                }

                // Cargar estado del cronómetro/temporizador desde localStorage
                function loadStateFromLocalStorage(index, display) {
                    const savedData = localStorage.getItem('timer-' + index);
                    if (savedData) {
                        const timerData = JSON.parse(savedData);
                        intervals[index] = timerData.timeElapsed || 0;
                        startTimes[index] = timerData.startTime || null;
                        pausedTimes[index] = timerData.pausedTime || null;
                        totalElapsedTimes[index] = timerData.totalElapsed || 0;

                        if (timerData.paused) {
                            timers[index] = null;
                            display.text(formatTime(totalElapsedTimes[index] / 1000));
                            $('.start-btn[data-index="' + index + '"]').text('Iniciar');
                        } else if (startTimes[index]) {
                            startCronometro(index, display);
                        }
                    }
                }      

            // Agregar nueva tarjeta
            $('#add-button').click(function() {
                var index = $('.card-container').length; // Índice basado en la cantidad de tarjetas actuales
                var nuevaTarjeta = crearTarjetaHTML(index, '', 'cronometro');
                $('#cards-container').append(nuevaTarjeta);
                actualizarCantidadTarjetas();
                reorganizarTarjetas();
                guardarTarjetasEnLocalStorage(); // Guardar las tarjetas
            });

            // Actualizar cantidad de tarjetas
            function actualizarCantidadTarjetas() {
        var cantidadTarjetas = $('.card-container').length;
        $('#cantidad-tarjetas').text(cantidadTarjetas);
    }

            // Guardar tarjetas en localStorage
            function guardarTarjetasEnLocalStorage() {
        var tarjetas = [];
        $('.card-container').each(function() {
            var nombre = $(this).find('.tarjeta-nombre').val();
            var tipo = $(this).find('.tipo-select').val();
            tarjetas.push({
                nombre: nombre,
                tipo: tipo
            });
        });
        localStorage.setItem('tarjetas', JSON.stringify(tarjetas));
    }

            // Cargar tarjetas desde localStorage solo si existen
            function cargarTarjetasDesdeLocalStorage() {
        var tarjetasGuardadas = JSON.parse(localStorage.getItem('tarjetas')) || [];

        if (tarjetasGuardadas.length > 0) {
            $('#cards-container').empty();  // Limpiar el contenedor

            tarjetasGuardadas.forEach(function(tarjeta, index) {
                var tarjetaHTML = crearTarjetaHTML(index, tarjeta.nombre, tarjeta.tipo);
                $('#cards-container').append(tarjetaHTML);
                loadStateFromLocalStorage(index, $('#display-' + index));
            });

            actualizarCantidadTarjetas();
        }
    }

            // Crear una nueva tarjeta
            function crearTarjetaHTML(index, nombre, tipo) {
        var isTemporizador = tipo === 'temporizador' ? 'block' : 'none';
        return `
            <div class="col-md-4 mb-4 card-container" data-index="${index}">
                <div class="card shadow-sm">
                    <div class="d-flex justify-content-between mb-3">
                        <button class="delete-button" title="Eliminar tarjeta">X</button>
                        <button class="edit-button" title="Editar tarjeta">✎</button>
                    </div>
                    <input type="text" class="form-control mb-3 tarjeta-nombre" name="tarjetas[${index}][nombre]" value="${nombre}" />
                    <div class="form-group">
                        <label for="tipo" style="color: black;">Seleccionar:</label>
                        <select name="tarjetas[${index}][tipo]" class="form-control tipo-select">
                            <option value="cronometro" ${tipo === 'cronometro' ? 'selected' : ''}>Cronómetro</option>
                            <option value="temporizador" ${tipo === 'temporizador' ? 'selected' : ''}>Temporizador</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="time-inputs mt-3 mb-3" style="display: ${isTemporizador};">
                            <input type="number" class="form-control d-inline-block hours-input input-w" placeholder="Horas" min="0" max="23">
                            <input type="number" class="form-control d-inline-block minutes-input input-w" placeholder="Minutos" min="0" max="59">
                            <input type="number" class="form-control d-inline-block seconds-input input-w" placeholder="Segundos" min="0" max="59">
                        </div>
                        <p class="time-display" id="display-${index}">00:00:00:000</p>
                        <div class="text-center mt-3">
                                 <button class="btn btn-primary start-btn" data-index="${index}">Iniciar</button>
                                <button class="btn btn-dark reset-btn" data-index="${index}">Reiniciar</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

            // Guardar cambios al agregar tarjeta
            $(document).on('click', '#add-card', function() {
                    var index = $('.card-container').length;
                    var tarjetaHTML = crearTarjetaHTML(index, '', 'cronometro');
                    $('#cards-container').append(tarjetaHTML);
                    actualizarCantidadTarjetas();
                    guardarTarjetasEnLocalStorage();
                });

            // Editar nombre de la tarjeta
            $(document).on('click', '.edit-button', function() {
                var card = $(this).closest('.card');
                var inputNombre = card.find('.tarjeta-nombre');
                inputNombre.prop('disabled', !inputNombre.prop('disabled'));

                if (inputNombre.prop('disabled')) {
                    $(this).text('✎');
                } else {
                    $(this).text('✔');
                }
            });


             // Reorganizar índices de tarjetas
             function reorganizarTarjetas() {
                $('.card-container').each(function(index) {
                    $(this).attr('data-index', index);
                    $(this).find('.start-btn').attr('data-index', index);
                    $(this).find('.reset-btn').attr('data-index', index);
                    $(this).find('.time-display').attr('id', 'display-' + index);
                });
            }

               // Eliminar tarjeta
                    $(document).on('click', '.delete-button', function() {
                $(this).closest('.card-container').remove();
                guardarTarjetasEnLocalStorage();
                actualizarCantidadTarjetas();
            });

            // Guardar tarjetas cuando se modifican
            $(document).on('change', '.tarjeta-nombre, .tipo-select', function() {
                guardarTarjetasEnLocalStorage();
            });

            // Botón salir
            $('#exit-button').click(function() {
                localStorage.clear();
                window.location.href = "<?php echo site_url('CronometroController/cronometro_form'); ?>";
            });

        });
        </script>


</body>