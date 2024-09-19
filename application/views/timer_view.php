
























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

        <div class="row justify-content-center mt-4" id="cards-container"></div>

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
                                <input type="number"  class="form-control d-inline-block hours-input input-w" placeholder="Horas" min="0" max="23">
                                <input type="number"  class="form-control d-inline-block minutes-input input-w" placeholder="Minutos" min="0" max="59">
                                <input type="number"  class="form-control d-inline-block seconds-input input-w" placeholder="Segundos" min="0" max="59">
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
        
        <script>
            $(document).ready(function() {
                let timers = {};         
                let intervals = {};      
                let startTimes = {};     
                let pausedTimes = {};
                let totalElapsedTimes = {};
                let tarjetasData = [];

                // Evitar que se dupliquen tarjetas al recargar
                let tarjetasCargadas = false;

                // Cargar tarjetas desde localStorage al cargar la página
                loadAllDataFromLocalStorage();

                // Manejador de cambio de tipo (cronómetro o temporizador)
                $(document).on('change', '.tipo-select', function() {
                    var tipo = $(this).val();
                    var card = $(this).closest('.card');
                    if (tipo === 'cronometro') {
                        card.find('.time-inputs').hide();
                    } else if (tipo === 'temporizador') {
                        card.find('.time-inputs').show();
                    }
                    saveAllDataToLocalStorage();  // Guardar selección en localStorage
                });

                // Función para iniciar cronómetro/temporizador
                $(document).on('click', '.start-btn', function() {
                    let index = $(this).data('index');
                    let card = $(this).closest('.card');
                    let tipo = card.find('.tipo-select').val();
                    let display = card.find('.time-display');

                    if (timers[index]) {
                        clearInterval(timers[index]);
                        timers[index] = null;
                        pausedTimes[index] = Date.now();
                        $(this).text('Iniciar');
                    } else {
                        if (pausedTimes[index]) {
                            let pausedDuration = Date.now() - pausedTimes[index];
                            startTimes[index] += pausedDuration;  
                            pausedTimes[index] = null;  
                        } else if (!totalElapsedTimes[index]) {
                            startTimes[index] = Date.now();
                            totalElapsedTimes[index] = 0;
                        }

                        if (tipo === 'cronometro') {
                            startCronometro(index, display);
                        } else if (tipo === 'temporizador') {
                            startTemporizador(index, display, card);
                        }
                        $(this).text('Pausar');
                    }
                    saveAllDataToLocalStorage();
                });

                // Funciones de cronómetro/temporizador
                function startCronometro(index, display) {
                    timers[index] = setInterval(function() {
                        let currentTime = Date.now() - startTimes[index];
                        intervals[index] = totalElapsedTimes[index] + currentTime;
                        display.text(formatTime(intervals[index] / 1000));
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

                // Formato de tiempo para mostrar
                function formatTime(s) {
                    const hours = Math.floor(s / 3600);
                    const minutes = Math.floor((s % 3600) / 60);
                    const seconds = Math.floor(s % 60);
                    const milliseconds = Math.floor((s % 1) * 1000);
                    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}:${milliseconds.toString().padStart(3, '0')}`;
                }

                // Reiniciar cronómetro o temporizador
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
                    saveAllDataToLocalStorage();
                });

                // Editar nombre de tarjeta
                $(document).on('click', '.edit-button', function() {
                    var card = $(this).closest('.card');
                    var inputNombre = card.find('.tarjeta-nombre');
                    inputNombre.prop('disabled', !inputNombre.prop('disabled'));
                    $(this).text(inputNombre.prop('disabled') ? '✎' : '✔');
                    saveAllDataToLocalStorage();
                });

                // Eliminar tarjeta
                $(document).on('click', '.delete-button', function() {
                    $(this).closest('.card-container').remove();
                    reorganizarTarjetas();
                    actualizarCantidadTarjetas();
                    saveAllDataToLocalStorage();
                });

                // Agregar nueva tarjeta
                $('#add-button').click(function() {
                    agregarTarjeta();
                    actualizarCantidadTarjetas();
                    reorganizarTarjetas();
                    saveAllDataToLocalStorage();
                });

               // Botón salir
               $('#exit-button').click(function() {
                localStorage.clear();
                window.location.href = "<?php echo site_url('CronometroController/cronometro_form'); ?>";
            });

                // Guardar el estado completo en localStorage
                function saveAllDataToLocalStorage() {
                    let tarjetas = [];
                    $('.card-container').each(function(index) {
                        let card = $(this);
                        let nombre = card.find('.tarjeta-nombre').val();
                        let tipo = card.find('.tipo-select').val();
                        let timeElapsed = intervals[index] || 0;
                        let totalElapsed = totalElapsedTimes[index] || 0;
                        tarjetas.push({ nombre, tipo, timeElapsed, totalElapsed });
                    });
                    localStorage.setItem('tarjetas', JSON.stringify(tarjetas));
                }

                // Cargar el estado completo desde localStorage
                function loadAllDataFromLocalStorage() {
                    if (tarjetasCargadas) return;  // Evita duplicar la carga
                    tarjetasCargadas = true;

                    let savedTarjetas = JSON.parse(localStorage.getItem('tarjetas') || '[]');
                    savedTarjetas.forEach((tarjeta, index) => {
                        agregarTarjeta(tarjeta.nombre, tarjeta.tipo, tarjeta.timeElapsed, tarjeta.totalElapsed, index);
                    });
                    actualizarCantidadTarjetas();
                    reorganizarTarjetas();
                }

                // Agregar tarjeta al DOM

                function agregarTarjeta(nombre = '', tipo = 'cronometro', timeElapsed = 0, totalElapsed = 0, index = $('.card-container').length) {
                    let nuevaTarjeta = `
                        <div class="col-md-4 mb-4 card-container" data-index="${index}">
                            <div class="card">
                                <div class="card-body">
                                    <input type="text" class="form-control tarjeta-nombre mb-2" value="${nombre}" placeholder="Nombre de la tarjeta" disabled>
                                    <select class="form-select tipo-select mb-2">
                                        <option value="cronometro" ${tipo === 'cronometro' ? 'selected' : ''}>Cronómetro</option>
                                        <option value="temporizador" ${tipo === 'temporizador' ? 'selected' : ''}>Temporizador</option>
                                    </select>
                                    <div class="mb-2 time-inputs" style="display: ${tipo === 'temporizador' ? 'block' : 'none'};">
                                        <input type="number" class="form-control hours-input" placeholder="Horas">
                                        <input type="number" class="form-control minutes-input" placeholder="Minutos">
                                        <input type="number" class="form-control seconds-input" placeholder="Segundos">
                                    </div>
                                    <div class="time-display mb-2" id="display-${index}">${formatTime(timeElapsed / 1000)}</div>
                                    <button class="btn btn-primary start-btn" data-index="${index}">Iniciar</button>
                                    <button class="btn btn-secondary reset-btn" data-index="${index}">Reiniciar</button>
                                    <button class="btn btn-danger delete-button">Eliminar</button>
                                    <button class="btn btn-warning edit-button">✎</button>
                                </div>
                            </div>
                        </div>`;
                    $('#cards-container').append(nuevaTarjeta);
                    intervals[index] = timeElapsed;
                    totalElapsedTimes[index] = totalElapsed;
                }

                // Actualizar el contador de tarjetas
                function actualizarCantidadTarjetas() {
                    $('#cantidad-tarjetas').text($('.card-container').length);
                }

                // Reorganizar las tarjetas para mantener coherencia en los índices
                function reorganizarTarjetas() {
                    $('.card-container').each(function(index) {
                        $(this).attr('data-index', index);
                        $(this).find('.start-btn').data('index', index);
                        $(this).find('.reset-btn').data('index', index);
                    });
                }
            });
        </script>
    </div>
</body>
</html>
