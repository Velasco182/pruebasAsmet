<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Iconos -->
    <script src="https://kit.fontawesome.com/466dd068aa.js" crossorigin="anonymous"></script>
    <!---->
    <!--Bootstrap 5.3 CSS 
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />-->
    <!--Iconos

    <script
      src="https://kit.fontawesome.com/466dd068aa.js"
      crossorigin="anonymous"
    ></script>-->

    <!--JQuery
    <script
      src="https://code.jquery.com/jquery-3.7.1.js"
      integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
      crossorigin="anonymous"
    ></script>-->

    <!--Bootstrap 5.3 JS 
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>-->
    <!-- Popperjs 
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
      integrity="sha256-BRqBN7dYgABqtY9Hd4ynE+1slnEw+roEPFzQ7TRRfcg="
      crossorigin="anonymous"
    ></script>-->
    <!-- Tempus Dominus JavaScript 
    <script
      src="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.9.4/dist/js/tempus-dominus.min.js"
      crossorigin="anonymous"
    ></script>-->

    <!-- Tempus Dominus Styles 
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.9.4/dist/css/tempus-dominus.min.css"
      crossorigin="anonymous"
    />

    <script src="https://cdn.jsdelivr.net/npm/@floating-ui/core@1.6.2"></script>
    <script src="https://cdn.jsdelivr.net/npm/@floating-ui/dom@1.6.5"></script>-->
    
  </head>
  <body>
    <div class="container card p-3 m-3 shadow-sm">
      <h2>Bootstrap DateTimePicker Example</h2>
      <div class="form-group">
        <label for="datetimepicker">Select Date and Time:</label>
        <div
          class="input-group date"
          id="datetimepicker"
          data-target-input="nearest"
        >
          <input
            type="text"
            class="form-control datetimepicker-input"
            data-target="#datetimepicker"
          />
          <div
            class="input-group-append"
            data-target="#datetimepicker"
            data-toggle="datetimepicker"
          >
            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
          </div>
        </div>
      </div>
    </div>

    <div class="row container card p-3 m-3 shadow-sm">
      <div class="col-sm-12" id="htmlTarget">
        <label for="datetimepicker1Input" class="form-label">Picker</label>
        <div
          class="input-group log-event"
          id="datetimepicker1"
          data-td-target-input="nearest"
          data-td-target-toggle="nearest"
        >
          <input
            id="datetimepicker1Input"
            type="text"
            class="form-control"
            data-td-target="#datetimepicker1"
          />
          <span
            class="input-group-text"
            data-td-target="#datetimepicker1"
            data-td-toggle="datetimepicker"
          >
            <i class="fa-regular fa-calendar-days fa-beat"></i>
          </span>
        </div>
      </div>
    </div>

    <div class="container card p-3 m-3 shadow-sm">
    <?php
    // Fecha y hora en formato 'Y-m-d H:i:s'
    $datetimeString = '2024-06-08 09:00:00';

    // Crear un objeto DateTime a partir de la cadena de fecha y hora
    $datetime = new DateTime($datetimeString);

    // Formatear la fecha y hora en el nuevo formato
    $formattedDate = $datetime->format('d/m/Y g:i A');

    // Reemplazar el espacio entre A. M./P. M. con un espacio especial
    $formattedDate = str_replace('AM', 'A. M.', $formattedDate);
    $formattedDate = str_replace('PM', 'P. M.', $formattedDate);

    echo $formattedDate; // Imprime: 06/08/2024 9:00 A. M.

    $formattedMeridian = formatearMeridiano("12/06/2024 01:00 PM");
    $formattedMeridian2 = formatearMeridiano("12/06/2024 01:00 P. M.");
    echo $formattedMeridian; // Output: 12/06/2024 01:00 P. M.
    echo "<br>";
    echo $formattedMeridian2; // Output: 12/06/2024 01:00 PM

    function formatearMeridiano($fecha) {
      //"12/06/2024 02:00 P. M."
      //Separo la cadena que llega por espacios
      $date_parts = preg_split('/\s+/u', $fecha);
      //Defino el primer tercio como fecha
      $date_day = $date_parts[0];
      //Defino el segundo tercio como hora
      $date_time = $date_parts[1];
      //Defino la tercera posición del arreglo como AM o PM
      $ampm = $date_parts[2];
      
      // Remove space and dots[' ', ]
      $formattedMeridian = str_replace('.', '', $ampm);
    
      // Convert to uppercase
      $formattedMeridian = strtoupper($formattedMeridian);
    
      // Replace "A. M." with "AM"
      if ($formattedMeridian === 'A') {
        $formattedMeridian = str_replace('A. M.', 'AM', $fecha);
      }
    
      // Replace "P. M." with "PM"
      if ($formattedMeridian === 'P') {
        $formattedMeridian = str_replace('P. M.', 'PM', $fecha);
      }

      // Replace "A. M." with "AM"
      if ($formattedMeridian === 'AM') {
        $formattedMeridian = str_replace('AM', 'A. M.' , $fecha);
      }
    
      // Replace "P. M." with "PM"
      if ($formattedMeridian === 'PM') {
        $formattedMeridian = str_replace('PM', 'P. M.' , $fecha);
      }
    
      return $formattedMeridian;
    }

    function formatMeridianIndicatorReverse($fecha) {
      //"12/06/2024 02:00 P. M."
      //Separo la cadena que llega por espacios
      $date_parts = preg_split('/\s+/u', $fecha);
      //Defino el primer tercio como fecha
      $date_day = $date_parts[0];
      //Defino el segundo tercio como hora
      $date_time = $date_parts[1];
      //Defino la tercera posición del arreglo como AM o PM
      $ampm = $date_parts[2];
      
      // Remove space and dots[' ', ]
      $formattedMeridian = str_replace('.', '', $ampm);
    
      // Convert to uppercase
      $formattedMeridian = strtoupper($formattedMeridian);
    
      // Replace "A. M." with "AM"
      if ($formattedMeridian === 'AM') {
        $formattedMeridian = str_replace('AM', 'A. M.' , $fecha);
      }
    
      // Replace "P. M." with "PM"
      if ($formattedMeridian === 'PM') {
        $formattedMeridian = str_replace('PM', 'P. M.' , $fecha);
      }
    
      return $formattedMeridian;


    }
  ?>
    </div>

    <div class="container card p-3 m-3 shadow">
      <div class="p-3">
      <button style="background-color: #2e9c9d; color:white;" id="btn-mine" class="btn" aria-current="page" data-bs-toggle="modal" 
        data-bs-target="#exampleModal" data-bs-whatever="Nuevo Compensatorio">
        <i class="fa-solid fa-circle-plus fa-lg"></i>
        &nbsp;&nbsp;Nuevo
      </button>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="Nuevo user1" data-modal-id="user1">Edit User 1</button>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="Nuevo user2" data-modal-id="user2">Edit User 2</button>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="Nuevo product1" data-modal-id="product1">Edit Product 1</button>

      </div>
    </div>

    <!-- Modal editar y crear Compensatorios prueba -->
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div style="background-color: #2e9c9d; color: white;" class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crea un Compensatorio</h5>
                    <button id="btn-close" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="submit" id="formularioCompensatorios" class="row g-3  was-validated  " onclick="">
                        <div class="form-floating">
                            <input class="form-control" data-live-search="true" list="collaborators" id="colaboradores"
                            placeholder="Ingresa tu número de Identificación" required>
                            <datalist id="collaborators">
                                <!--form-floating <option value="5551234">
                                    <option value="5555678">
                                        <option value="5558765">
                                            <option value="5554321">
                                                <option value="5556789">-->
                                                </datalist>
                                                <!--<select class="form-select" id="colaboradores" aria-label="Colaboradores" required>
                                                    <option selected>Seleccionar Colaborador</option>
                                                    <option value="1">5551234</option>
                                                    <option value="2">5555678</option>
                                                    <option value="3">5558765</option>
                                                    <option value="4">5554321</option>
                                                    <option value="5">5556789</option>
                                                    <option value="6">Three</option>
                                                </select>
                                                <label for="colaboradores">Seleccionar Colaborador</label> </select>-->
                                                
                            <label for="colaboradores" class="form-label">Selecciona el colaborador</label>
                        </div>
                        <div class="form-floating">
                            <textarea type="text" class="form-control" placeholder="Descripción del Compensatorio"
                                id="descripcion" name="descripcion" required></textarea>
                            <label for="descripcion">Descripción del Compensatorio</label>
                        </div>

                        <!--<input type="datetime-local"
                                value="2023-01-01T12:00" />-->

                        <div id="htmlTarget">
                            <label for="inicio" class="form-label">Inicio</label>
                            <div class="input-group input-append date" id="inicioDateTimePicker" data-td-target-input="nearest"
                                data-td-target-toggle="nearest">
                                
                                <input id="inicio" type="text" class="form-control was-validated"
                                    data-td-target="#inicioDateTimePicker" placeholder="Inicio" required/>
                                <span class="input-group-text" data-td-target="#inicioDateTimePicker" data-td-toggle="datetimepicker">
                                        <i class="fa-regular fa-calendar-days fa-beat"></i>
                                </span>
                            </div>
                            
                            <label for="final" class="mt-3 form-label">Final</label>
                            <div class="input-group input-append date" id="finalDateTimePicker" data-td-target-input="nearest"
                                data-td-target-toggle="nearest">
                                
                                <input id="final" type="text" class="form-control was-validated"
                                    data-td-target="#finalDateTimePicker" placeholder="Final" required/>
                                <span class="input-group-text" data-td-target="#finalDateTimePicker" data-td-toggle="datetimepicker">
                                        <i class="fa-regular fa-calendar-days fa-beat"></i>
                                </span>
                            </div>
                        </div>

                        <div id="" class="form-check me-2">
                            <label class="" for="validacion">Validar Compensatorio</label>
                            <select class="col-md-6 mt-2 form-select form-control was-validated" id="validacion" required>
                                <option value="Pendiente" selected>Pendiente</option>
                                <option value="Aceptado">Aceptado</option>
                                <option value="Rechazado">Rechazado</option>
                            </select>
                        </div>
                    </form>
                </div>
                <!--style="background-color: #cdeeee;" -->
                <div class="modal-footer">
                    <button type="button" id="btn-cerrar" class="btn btn-secondary"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn" id="btn-accept">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script>

      const exampleModal = document.getElementById('exampleModal')
      if (exampleModal) {
        exampleModal.addEventListener('show.bs.modal', event => {
          // Button that triggered the modal
          const button = event.relatedTarget
          // Extract info from data-bs-* attributes
          const recipient = button.getAttribute('data-bs-whatever')
          // If necessary, you could initiate an Ajax request here
          // and then do the updating in a callback.

          // Update the modal's content.
          const modalTitle = exampleModal.querySelector('.modal-title')
          const modalBodyInput = exampleModal.querySelector('.modal-body input')

          modalTitle.textContent = `${recipient}`
          modalBodyInput.value = recipient
        })
      }

    </script>

    <script>
      //import {computePosition} from 'https://cdn.jsdelivr.net/npm/@floating-ui/dom@1.6.5/+esm';
      $(document).ready(function() {
            $('#datetimepicker').datetimepicker({
              format: "DD/MM/yyyy hh:mm A",
                icons: {
                    time: 'fa fa-clock',
                    date: 'fa fa-calendar',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-check',
                    clear: 'fa fa-trash',
                    close: 'fa fa-times'
                }
            });
      });

      //const picker = new tempusDominus.TempusDominus(document.getElementById('datetimepicker1'));

      /*new tempusDominus.TempusDominus(
        document.getElementById("datetimepicker1"),
        {
          display: {
            icons: {
              type: "icons",
              time: "fa-regular fa-clock fa-lg",
              date: "fa-solid fa-calendar-plus",
              up: "fa-solid fa-caret-up",
              down: "fa-solid fa-caret-down",
              previous: "fa-solid fa-angles-left",
              next: "fa-solid fa-angles-right",
              today: "fa-solid fa-calendar-check",
              clear: "fa-solid fa-trash",
              close: "fa-solid fa-xmark",
            },
            sideBySide: false,
            calendarWeeks: false,
            viewMode: "calendar",
            toolbarPlacement: "bottom",
            keepOpen: false,
            buttons: {
              today: false,
              clear: false,
              close: false,
            },
            components: {
              calendar: true,
              date: true,
              month: true,
              year: true,
              decades: true,
              clock: true,
              hours: true,
              minutes: true,
              seconds: false,
              //deprecated use localization.hourCycle = 'h24' instead
              useTwentyfourHour: undefined,
            },
            inline: false,
            theme: "auto",
          },
        }
      );

      /*const subscription = picker.subscribe(tempusdominus.Namespace.events.change, (e) => {
            console.log(e);
            });

            // event listener can be unsubscribed to:
            subscription.unsubscribe();

            //you can also provide multiple events:
            const subscriptions = picker.subscribe(
                    [tempusdominus.Namespace.events.show,tempusdominus.Namespace.events.hide],
                    [(e)=> console.log(e), (e) => console.log(e)]
            )*/

      function parseFecha(fechaTexto) {
        // Dividir la fecha y hora
        const [fecha, tiempo, periodo] = fechaTexto.split(" ");

        // Dividir la fecha
        const [dia, mes, año] = fecha.split("/").map(Number);

        // Dividir el tiempo
        let [hora, minutos] = tiempo.split(":").map(Number);

        // Convertir a formato 24 horas
        if (periodo === "P.M." && hora !== 12) {
          hora += 12;
        } else if (periodo === "A.M." && hora === 12) {
          hora = 0;
        }

        // Crear objeto Date
        const dateObj = new Date(año, mes - 1, dia, hora, minutos);

        // Formatear a 'YYYY-MM-DD HH:mm:ss'
        const fechaMySQL = dateObj.toISOString().slice(0, 19).replace("T", " ");

        return fechaMySQL;
      }

      function parseDateTime(dateTimeStr) {
      // Eliminar espacios adicionales en 'A. M.' o 'P. M.'
      dateTimeStr = dateTimeStr.replace(/\s+([AP]\.\sM\.)$/, '$1');

      // Separar la parte de la fecha y la hora
      let [datePart, timePart, meridian] = dateTimeStr.split(/[\s]+/);
      let [day, month, year] = datePart.split('/').map(num => parseInt(num));
      let [hour, minute] = timePart.split(':').map(num => parseInt(num));

      // Convertir a formato de 24 horas
      if (meridian === 'P. M.' && hour !== 12) {
          hour += 12;
      } else if (meridian === 'A. M.' && hour === 12) {
          hour = 0;
      }

      // Crear el objeto Date
      return new Date(year, month - 1, day, hour, minute);
    }

  function getDifferenceInTime(date1, date2) {
      // Diferencia en milisegundos
      let diffMs = date2 - date1;

      // Convertir a días, horas y minutos
      let diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
      let diffHrs = Math.floor((diffMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      let diffMins = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));

      return { days: diffDays, hours: diffHrs, minutes: diffMins };
  }

  // Ejemplo de uso
  let dateTimeStr1 = '06/06/2024 2:52 P. M.';
  let dateTimeStr2 = '07/06/2024 3:45 A. M.';

  let date1 = parseDateTime(dateTimeStr1);
  let date2 = parseDateTime(dateTimeStr2);

  let difference = getDifferenceInTime(date1, date2);
  console.log(`Diferencia: ${difference.days} días, ${difference.hours} horas, ${difference.minutes} minutos`);



      const fechaTexto = "04/06/2024 8:00 A. M.";
      const fechaMySQL = parseFecha(fechaTexto);
      console.log(fechaMySQL); // Salida: 2024-06-04 08:00:00

    </script>

  </body>
</html>
