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
<body class="container-fluid text-center mt-5">
    <div class="container">
        <h1 class="mb-4">Bienvenido, <?php echo $username; ?>!</h1>

        <div class="text-center mb-4">
            <button class="btn add-button" id="add-button">+ Agregar tarjeta</button>
        </div>

        <h2>Cantidad de tarjetas: <span id="cantidad-tarjetas"><?php echo count($tarjetas); ?></span></h2>

        <div class="row justify-content-center mt-4" id="cards-container">
            <?php foreach ($tarjetas as $index => $tarjeta): ?>
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
                            <div class="time-inputs mt-3 mb-3" style="display: <?php echo $tarjeta['tipo'] == 'temporizador' ? 'block' : 'none'; ?> ">
                                <input type="number"  class="form-control d-inline-block hours-input input-w" placeholder="Horas" min="0" max="23">
                                <input type="number"  class="form-control d-inline-block minutes-input input-w" placeholder="Minutos" min="0" max="59">
                                <input type="number"  class="form-control d-inline-block seconds-input input-w" placeholder="Segundos" min="0" max="59">
                            </div>
                        </div>

                        <div class="time-display" id="display-<?php echo $index; ?> ">00:00:00:000</div>

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
                let timers = {};         // Guardar intervalos activos
                let intervals = {};      // Guardar el tiempo acumulado en cada cronómetro/temporizador
                let startTimes = {};     // Guardar el tiempo en el que el cronómetro comenzó o se reanudó


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
                        // Si ya está corriendo, se pausa
                        clearInterval(timers[index]);
                        timers[index] = null;  // Indicar que está pausado
                        $(this).text('Iniciar');
                        
                    } else {
                        // Si está pausado, reanudar
                        startTimes[index] = Date.now();
                        if (tipo === 'cronometro') {
                            startCronometro(index, display);
                            
                        } else if (tipo === 'temporizador') {
                            startTemporizador(index, display, card);
                        }
                        $(this).text('Pausar');
                    }
    });

                // Cronómetro
                function startCronometro(index, display) {
                    let elapsed = intervals[index] || 0;  // Tiempo transcurrido hasta el momento
                    timers[index] = setInterval(function() {
                        let currentTime = Date.now() - startTimes[index] + elapsed;
                        intervals[index] = currentTime;
                        display.text(formatTime(currentTime / 1000));  // Convertir ms a segundos para mostrar
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
                    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}:${milliseconds.toString().padStart(3, '0')}`;
                }

                // // Reiniciar cronómetro o temporizador
                // $(document).on('click', '.reset-btn', function() {
                //     let index = $(this).data('index');  // Obtener el índice de la tarjeta
                    
                //     clearInterval(timers[index]);       // Detener el cronómetro/temporizador
                //     timers[index] = null;               // Limpiar el intervalo del temporizador
                    
                //     intervals[index] = 0;               // Reiniciar el tiempo transcurrido
                //     startTimes[index] = null;           // Limpiar el tiempo de inicio

                //     // Restablecer el valor en el display
                //     $('#display-' + index).text('00:00:00:000'); // Resetear el contador visual a 0

                //     // Cambiar el texto del botón de iniciar/pausar a "Iniciar"
                //     $('.start-btn[data-index="' + index + '"]').text('Iniciar');

                //     // Asegurarse de que el contador comience desde 0 cuando se vuelva a iniciar
                //     if (typeof isRunning[index] !== 'undefined') {
                //         isRunning[index] = false; // Reiniciar el estado de ejecución del cronómetro
                //     }

                //     // Opcional: Restablecer los campos de entrada si estás usando un temporizador
                //     $('#input-hours-' + index).val(0);
                //     $('#input-minutes-' + index).val(0);
                //     $('#input-seconds-' + index).val(0);
                // });


    $(document).on('click', '.reset-btn', function() {
    let index = $(this).data('index');  // Obtener el índice de la tarjeta

    console.log("Reiniciando cronómetro en el índice:", index); // Debug

    // Detener el cronómetro/temporizador
    if (timers[index] !== null) {
        clearInterval(timers[index]);  // Detener el intervalo
        timers[index] = null;          // Asegurarse de que se limpie el intervalo
    } else {
        console.log("No hay un intervalo activo en el índice:", index);
    }

    // Reiniciar variables
    intervals[index] = 0;               // Reiniciar el tiempo transcurrido
    startTimes[index] = null;           // Limpiar el tiempo de inicio

    // Restablecer el valor en el display
    let displayElement = $('#time-display' + index);
    if (displayElement.length > 0) {
        displayElement.text('00:00:00:000'); // Resetear el contador visual a 0
    } else {
        console.log("No se encontró el display con ID: #display-" + index);
    }

    // Cambiar el texto del botón de iniciar/pausar a "Iniciar"
    let startButton = $('.start-btn[data-index="' + index + '"]');
    if (startButton.length > 0) {
        startButton.text('Iniciar');
    } else {
        console.log("No se encontró el botón de iniciar con índice:", index);
    }

    // Reiniciar el estado del cronómetro
    if (typeof isRunning[index] !== 'undefined') {
        isRunning[index] = false; // Reiniciar el estado de ejecución del cronómetro
    } else {
        console.log("isRunning no está definido para el índice:", index);
    }

    // Opcional: Restablecer los campos de entrada si estás usando un temporizador
    $('#input-hours-' + index).val(0);
    $('#input-minutes-' + index).val(0);
    $('#input-seconds-' + index).val(0);
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
                    $(this).closest('.card-container').remove();
                    reorganizarTarjetas();
                    actualizarCantidadTarjetas();
                });

                // Agregar nueva tarjeta
                $('#add-button').click(function() {
                    var nuevaTarjeta = `
                        <div class="col-md-4 card-container">
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
                                <input type="number" class="form-control d-inline-block minutes-input input-w" placeholder="Minutos" min="0" max="59" >
                                <input type="number" class="form-control d-inline-block seconds-input input-w" placeholder="Segundos" min="0" max="59" >
                            </div>
                        </div>

                        <div class="time-display" id="display-<?php echo $index; ?> ">00:00:00:000</div>

                                <div class="text-center mt-3">
                                    <button class="btn btn-success start-btn" data-index="">Iniciar</button>
                                    <button class="btn btn-warning reset-btn" data-index="">Reiniciar</button>
                                </div>
                            </div>
                        </div>`;
                    $('#cards-container').append(nuevaTarjeta);
                    reorganizarTarjetas();
                    actualizarCantidadTarjetas();
                });

                // Función para reorganizar las tarjetas y actualizar los índices
                function reorganizarTarjetas() {
                    $('.card-container').each(function(index) {
                        $(this).attr('data-index', index);
                        $(this).find('.start-btn').data('index', index);
                        $(this).find('.reset-btn').data('index', index);
                    });
                }

                // Función para actualizar la cantidad de tarjetas
                function actualizarCantidadTarjetas() {
                    $('#cantidad-tarjetas').text($('.card-container').length);
                }
            });
        </script>
    </div>
</body>
</html>
