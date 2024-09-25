
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
                // Pausar cronómetro o temporizador
                clearInterval(timers[index]);
                timers[index] = null;
                pausedTimes[index] = Date.now();  // Guardar el momento en el que se pausó
                $(this).text('Iniciar');
            } else {
                // Reanudar o iniciar cronómetro o temporizador
                if (pausedTimes[index]) {
                    let pausedDuration = Date.now() - pausedTimes[index];
                    startTimes[index] += pausedDuration;  // Ajustar el tiempo de inicio considerando la pausa
                    pausedTimes[index] = null;
                } else if (!totalElapsedTimes[index]) {
                    startTimes[index] = Date.now();
                    totalElapsedTimes[index] = 0;  // Tiempo total inicial
                }

                if (tipo === 'cronometro') {
                    startCronometro(index, display);
                } else if (tipo === 'temporizador') {
                    // Solo inicializar el temporizador si no se ha pausado antes
                    if (!intervals[index]) {
                        let hours = parseInt(card.find('.hours-input').val()) || 0;
                        let minutes = parseInt(card.find('.minutes-input').val()) || 0;
                        let seconds = parseInt(card.find('.seconds-input').val()) || 0;
                        intervals[index] = ((hours * 3600) + (minutes * 60) + seconds) * 1000;
                    }
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
        function startTemporizador(index, display, card, reanudar = false) {
            let totalMilliseconds;
            
            if (reanudar) {
                totalMilliseconds = intervals[index];  // Cargar el tiempo restante guardado
            } else {
                let hours = parseInt(card.find('.hours-input').val()) || 0;
                let minutes = parseInt(card.find('.minutes-input').val()) || 0;
                let seconds = parseInt(card.find('.seconds-input').val()) || 0;
                totalMilliseconds = ((hours * 3600) + (minutes * 60) + seconds) * 1000;
                intervals[index] = totalMilliseconds;  // Guardar el tiempo restante
            }

            timers[index] = setInterval(function() {
                totalMilliseconds -= 10;  // Restar 10 ms en cada iteración
                intervals[index] = totalMilliseconds;
                display.text(formatTime(totalMilliseconds / 1000));

                if (totalMilliseconds <= 0) {
                    clearInterval(timers[index]);
                    card.addClass('timer-finished');
                    alert('¡Tiempo terminado!');
                }
                saveTimerStateToLocalStorage();  // Guardar el estado actualizado
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
            let card = $(this).closest('.card'); // Obtener la tarjeta actual

            clearInterval(timers[index]);  // Detener el temporizador
            timers[index] = null;
            intervals[index] = 0;  // Restablecer tiempo transcurrido
            totalElapsedTimes[index] = 0;
            startTimes[index] = null;
            pausedTimes[index] = null;

            // Restablecer la pantalla de tiempo a 00:00:00:000
            $('#display-' + index).text('00:00:00:000');
            $('.start-btn[data-index="' + index + '"]').text('Iniciar');

            // Remover la clase de finalización de temporizador y restablecer el estilo por defecto
            card.removeClass('timer-finished');

            saveTimerStateToLocalStorage();  // Guardar el estado en localStorage
        });

        // Guardar cronómetro/temporizador en localStorage sin eliminar tarjetas
        function saveTimerStateToLocalStorage() {
            $('.card-container').each(function(index) {
                localStorage.setItem('timer-' + index, JSON.stringify({
                    timeElapsed: intervals[index] || 0,
                    startTime: startTimes[index] || null,
                    pausedTime: pausedTimes[index] || null,
                    totalElapsed: totalElapsedTimes[index] || 0,
                    paused: timers[index] === null,
                    tipo: $(this).find('.tipo-select').val(), // Guardar si es cronómetro o temporizador
                }));
            });
        }


        // Cargar estado del cronómetro/temporizador desde localStorage
        function loadStateFromLocalStorage(index, display, card) {
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
                    if (timerData.tipo === 'cronometro') {
                        startCronometro(index, display);
                    } else if (timerData.tipo === 'temporizador') {
                        startTemporizador(index, display, card, true); // true para reanudar
                    }
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
    $('.card-container').each(function(index) {
        var nombre = $(this).find('.tarjeta-nombre').val();
        var tipo = $(this).find('.tipo-select').val();
        var color = $(this).find('.background-color-input').val();
        var backgroundImage = $(this).find('.background-image-input').val();
        tarjetas.push({
            nombre: nombre,
            tipo: tipo,
            color: color,
            backgroundImage: backgroundImage
        });
    });
    localStorage.setItem('tarjetas', JSON.stringify(tarjetas));
}
    // Cargar tarjetas desde localStorage solo si existen
    function guardarTarjetasEnLocalStorage() {
    var tarjetas = [];
    $('.card-container').each(function(index) {
    var nombre = $(this).find('.tarjeta-nombre').val();
    var tipo = $(this).find('.tipo-select').val();
    var color = $(this).find('.background-color-input').val();
    var backgroundImage = $(this).find('.background-image-input').val();
    tarjetas.push({
        nombre: nombre,
        tipo: tipo,
        color: color,
        backgroundImage: backgroundImage
    });
    });
    localStorage.setItem('tarjetas', JSON.stringify(tarjetas));
    }

// Modificar la función de cargar tarjetas desde localStorage para aplicar color e imagen
    function cargarTarjetasDesdeLocalStorage() {
    var tarjetasGuardadas = JSON.parse(localStorage.getItem('tarjetas')) || [];

    if (tarjetasGuardadas.length > 0) {
    $('#cards-container').empty();  // Limpiar el contenedor

    tarjetasGuardadas.forEach(function(tarjeta, index) {
        var tarjetaHTML = crearTarjetaHTML(index, tarjeta.nombre, tarjeta.tipo);
        $('#cards-container').append(tarjetaHTML);

        let card = $('#cards-container').find(`[data-index="${index}"] .card`);
        card.css('background-color', tarjeta.color || '#ffffff');
        if (tarjeta.backgroundImage) {
            card.css('background-image', 'url(' + tarjeta.backgroundImage + ')');
        }
        loadStateFromLocalStorage(index, $('#display-' + index));
    });

    actualizarCantidadTarjetas();
    }
    }

    // Crear una nueva tarjeta
  

 
    
    function crearTarjetaHTML(index, nombre, tipo) {
        var isTemporizador = tipo === 'temporizador' ? 'block' : 'none';
        
        // Verifica si la variable userRole está disponible
        console.log("El rol del usuario es: ", userRole);
    
        // Variable para almacenar las opciones del select
        var selectOptions = '';
    
        // Si el rol es 'operador_cronometro', solo mostramos la opción de cronómetro
        if (userRole === 'operador_cronometro') {
            selectOptions = `
                <option value="cronometro" ${tipo === 'cronometro' ? 'selected' : ''}>Cronómetro</option>
            `;
        } else if (userRole === 'operador_temporizador') {
            selectOptions = `
                <option value="temporizador" ${tipo === 'temporizador' ? 'selected' : ''}>Temporizador</option>
          
            <input type="number" style="margin-top: 12px" class="form-control d-inline-block hours-input input-w" placeholder="Horas" min="0" max="23" style="margin-right: 10px;">
            <input type="number" class="form-control d-inline-block minutes-input input-w" placeholder="Minutos" min="0" max="59" style="margin-right: 10px;">
            <input type="number" class="form-control d-inline-block seconds-input input-w" placeholder="Segundos" min="0" max="59">
       
            `;
        }
    
        return `
        <div class="col-md-4 mb-4 card-container" data-index="${index}">
            <div class="card shadow-sm">
                <div class="d-flex justify-content-between mb-3">
                    <button class="delete-button" title="Eliminar tarjeta">X</button>
                    <button class="edit-button" title="Editar tarjeta">✎</button>
                </div>
                <input type="text" class="form-control mb-3 tarjeta-nombre" name="tarjetas[${index}][nombre]" value="${nombre}" disabled/>
                <div class="form-group">
                    <label for="tipo" style="color: white">Seleccionar:</label>
                    <select name="tarjetas[${index}][tipo]" class="form-control tipo-select">
                        ${selectOptions}
                    </select>
                </div>
                <div class="form-group">
                    <div class="time-inputs mt-3 mb-3" style="display: ${isTemporizador};">
                      
                    </div>
                    <p class="time-display" id="display-${index}">00:00:00:000</p>
                    <div class="text-center mt-3">
                        <button class="btn btn-primary start-btn" data-index="${index}">Iniciar</button>
                        <button class="btn btn-danger reset-btn" data-index="${index}">Reiniciar</button>
                    </div>
                </div>
                <div class="form-group">
                    <br>
                    <label for="backgroundColor" style="color: white">Color de fondo:</label>
                    <input type="color" style="background: rgba(20, 20, 20, 0.6);" class="form-control background-color-input" data-index="${index}">
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

    

});


    //Cambiar el color de fondo
    $(document).on('change', '.background-color-input', function() {
    let index = $(this).data('index');
    let color = $(this).val();
    $(this).closest('.card').css('background-color', color);
    guardarTarjetasEnLocalStorage(); // Guardar cambios en localStorage
    });
