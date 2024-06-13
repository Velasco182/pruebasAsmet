let tableCompensatorios;
let id_colab_compe;
let id_colab_compeA;

//Rutas para hacer las peticiones del backend a la db
const rutaColaboradores =
  "/sena/Proyectos/pruebaCompensatorios/assets/php/colaboradores.php";
const rutaCompensatorios =
  "/sena/Proyectos/pruebaCompensatorios/assets/php/compensatorios.php";

/*const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))*/

/////#################### INSTANCIA DE FORMULARIO Y BOTONES DEL FRONT-END ##########################
let formularioCompensatorios = document.getElementById(
  "formularioCompensatorios"
);
let formularioActualizarCompensatorios = document.getElementById(
  "formularioActualizarCompensatorios"
);
let btnEnviarCompensatorio = document.getElementById("btn-accept");
let btnEditar = document.getElementById("btn-actualizar");
let btnValidarCompensatorio = document.getElementById("btn-validar");
let btnCerrar = document.querySelector("#btn-cerrar");
let btnClose = document.querySelector("#btn-close");

let colaboradoresF = document.querySelector("#colaboradores");
let descripcion_compeF = document.querySelector("#descripcion");
let inicio_compeF = document.querySelector("#inicio");
let final_compeF = document.querySelector("#final");
let validacion_compeF = document.querySelector("#validacion");

//let colaboradoresA = document.querySelector("#colaboradores");
let descripcion_compeA = document.querySelector("#descripcionA");
let inicio_compeA = document.querySelector("#inicioA");
let final_compeA = document.querySelector("#finalA");
let validacion_compeA = document.querySelector("#validacionA");

///#################### Instancia de los campos de fecha y hora ##########################
let inicioPicker = document.getElementById("inicioDateTimePicker");
let finalPicker = document.getElementById("finalDateTimePicker");

//https://www.codexworld.com/bootstrap-datetimepicker-add-date-time-picker-input-field/

///#################### CARGA DE ELEMENTOS CON EL DOM ##########################
document.addEventListener("DOMContentLoaded", function () {
  //Reinicamos el formulario
  formularioCompensatorios.reset();
  //Cargamos los colaboradores dentro del select
  renderizarColaboradores();
  //Cargamos los compensatorios en la tabla
  renderizarCompensatorios();
  //Configuración del DateTimePicker
  dateTimePicker(inicioPicker, finalPicker);
  //Evento de escucha para el boton de validar compensatorios
  //btnValidarCompensatorio.addEventListener("click", crearCompensatorio);
  clarearFormulario();
});
///#################### CIERRO DOM ##########################
let clarearFormulario = () => {
  //Evento al cerrar o cancelar modal//Borrar campos del formulario
  btnCerrar.addEventListener("click", () => {
    formularioCompensatorios.reset();
    formularioActualizarCompensatorios.reset()
  });

  btnClose.addEventListener("click", () => {
    formularioCompensatorios.reset();
    formularioActualizarCompensatorios.reset();
  });
};
///#################### FUNCIÓN FLECHA PARA MÉTODO CREATE ##########################
let crearCompensatorio = () => {
  //formularioCompensatorios.addEventListener('submit',  function (e){
  //e.preventDefault();

  //Instancia de los campos del formulario
  let colaboradores = colaboradoresF.value;

  let descripcion_compe = descripcion_compeF.value;
  let inicio_compe = inicio_compeF.value;
  let final_compe = final_compeF.value;
  let validacion_compe = validacion_compeF.value;

  //console.log(id_colab_compe);
  let colaborador_id_compe = id_colab_compe;
  //console.log(colaborador_id_compe);

  /*console.info("INI: "+inicio);
    console.info("INI: "+final);
    
    let inicio_compe = parsearFecha(inicio);
    let final_compe = parsearFecha(final);

    console.info("INI2: "+inicio_compe);
    console.info("INI2: "+final_compe);*/

  ///Separar los valores de nombre y cédula del colaborador para crear el compensatorio
  let match = colaboradores.match(/^(.+?) \((\d+)\)$/);

  let nombre_compe = match[1];
  let identificacion_compe = match[2];

  /*if(identificacion_compe == '' || nombre_compe == '' || descripcion_compe == '' || inicio_compe == '' || final_compe == ''){
    Swal.fire({
        title: "Atención",
        text: "Todos los campos son obligatorios.",
        icon: "error"
      });
      return false;
    }*/

  //Modales personalizados de SweetAlert2
  Swal.fire({
    title: "¿Deseas guardar el Compensatorio?",
    //showDenyButton: true,
    confirmButtonText: "Guardar",
    //denyButtonText: `Descartar`,
    showCancelButton: true,
    cancelButtonText: "Cancelar",
    //Modificar el color de los botones
    confirmButtonColor: "#2e9c9d",
    cancelButtonColor: "#d33",
  }).then((result) => {
    /* Read more about isConfirmed, isDenied below */
    if (result.isConfirmed) {
      //Swal.fire("¡Guardado con éxito!", "", "success");
      
      Swal.fire({
        confirmButtonColor: "#2e9c9d",
        title: "¡Guardado con éxito!",
        text: "Compensatorio creado exitosamente.",
        icon: "success"
      });

      fetch(rutaCompensatorios, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          colaborador_id_compe,
          //identificacion_compe,
          //nombre_compe,
          descripcion_compe,
          inicio_compe,
          final_compe,
          validacion_compe,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          console.log(data);
          //Si es confirmada el modal, cerramos el modal del registro del compensatorio
          $("#exampleModal").modal("hide");
          //y recargamos la tabla para obtener todos los compensatorios actualizados
          tableCompensatorios.ajax.reload();
          //Borrar campos del formulario
          formularioCompensatorios.reset();
        })
        .catch((error) =>
          console.error("Error al crear Compensatorio:", error)
        );
    } /*else if (result.isDenied) {
      Swal.fire("¡Se descartó la creación del compensatorio!", "", "info");
    }*/
  });

  //});
};
///#################### CIERRO CREATE ##########################
//Evento de escucha para el boton de guardar compensatorio
btnEnviarCompensatorio.addEventListener("click", crearCompensatorio);
///#################### FUNCIÓN FLECHA PARA MÉTODO READ (COMPENSATORIOS) ##########################
let renderizarCompensatorios = () => {
  $(document).ready(function () {
    tableCompensatorios = new DataTable("#tableCompensatorios", {
      responsive: "true",
      language: {
        url: "././assets/js/spanish.json",
      },
      order: [[3, "desc"]],
      columnDefs: [
        {
          targets: [0, 1, 2, 3, 4, 5, 6, 7],
          orderable: false,
          className: "text-center",
        },
        //Para agregar botones de acción a la tabla de forma "dinámica"
        {
          // Columna de acciones
          targets: 5,
          className: "text-center",
          render: function (row) {
            let diferencia = row.diferencia;

            //Mostramos en la columna 6 la columna de diferencia creada desde el backend
            //
            return `${diferencia}`;
          },
        },
        {
          targets: 8,
          visible: false, // Hide the seventh column
        },
        //Para agregar botones de acción a la tabla de forma "dinámica"
        {
          // Columna de acciones
          targets: 7,
          className: "text-center",
          render: function (row) {
            clarearFormulario();
            let colaborador_id_compe = row.colaborador_id_compe;
            let id_compensatorio = row.id_compe;
            /*let identificacion = row.identificacion_colab;
                    let nombre = row.nombre_colab;*/
            let id_colaborador = row.identificacion_colab;
            let descripcion = row.descripcion_compe;
            let inicio = row.inicio_compe;
            let final = row.final_compe;
            let validacion = row.validacion_compe;


            if (validacion == "Aceptado") {

              return `<button class="btn btn-danger fa-solid fa-trash" disabled></button>`;

            } else if (validacion == "Pendiente" || "Rechazado") {

              /*<button class="btn btn-warning btn-sm btnSendUser" 
              onclick="fntSendUser(834)" title="" data-bs-toggle="tooltip" 
              data-original-title="Enviar Credenciales de Acceso">
              <i class="fa fa-envelope" aria-hidden="true"></i>
              </button>
              
              style="background-color: #2e9c9d; color: white; :hover { background-color: #cdeeee; color: black; }"*/

              return `  <button id="btn-validar"  
                        data-bs-toggle="modal" data-bs-target="#actualizarModal"
                        data-original-title="" 
                        class="btn btn-sm" data-id="${row.id_compe}" 
                        onclick="actualizarCompensatorio('${colaborador_id_compe}','${id_compensatorio}', '${id_colaborador}', 
                        '${descripcion}', '${inicio}', '${final}', '${validacion}')" 
                        title="Editar Compensatorio">
                        <i class="fa fa-solid fa-pen" aria-hidden="true"></i></button>

                        <button id="btn-eliminar" data-id="${id_compensatorio}" type="button"
                        class="btn btn-sm" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Eliminar Compensatorio"
                        onclick="eliminarCompensatorio('${id_compensatorio}')"
                        title="Eliminar Compensatorio">
                        <i class="fa fa-solid fa-trash" aria-hidden="true"></i></button>
                        `;
            }
            /*let colaborador = {
                        id,
                        identificacion,
                        nombre,
                        descripcion,
                        inicio,
                        final,
                        validacion,
                    }*/
            //console.table(colaborador);
            // Aquí puedes personalizar los botones de acciones (editar, eliminar, etc.)

          },
        },
      ],
      ajax: {
        url: `${rutaCompensatorios}`,
        type: "GET",
        dataSrc: "",
      },
      columns: [
        { data: "identificacion_colab" },
        { data: "nombre_colab" },
        { data: "descripcion_compe" },
        { data: "inicio_compe" },
        { data: "final_compe" },
        { data: null },
        { data: "validacion_compe" },
        { data: null },
        { data: "colaborador_id_compe" }
      ],
    });
  });
};
///#################### CIERRO READ COMPENSATORIOS ##########################
///################# FUNCIÓN FLECHA PARA HACER GET POR ID A COLABORADORES #########################
let mostrarColaboradorIdentificacion = (identificacion_colab) => {
  fetch(`${rutaColaboradores}?identificacion_colab=${identificacion_colab}`)
    //Manejo de la respuesta
    .then((response) => {
      if (!response.ok) {
        throw new Error("Sin acceso a internet");
      }
      //Devuelve una promesa
      return response.json();
    })
    //Manejo de datos recibidos
    .then((data) => {
      //renderTable(data);
      console.table(data);

      let id = data.id_colab;
      let nombre = data.nombre_colab;
      let identificacion = data.identificacion_colab;

      //let fid = mostrarColaboradorID(id);

      console.table(id, nombre, identificacion);
      //console.table(fid);

      /*actualizarNombre.value = nombre;
      actualizarApellido.value = apellido;
      actualizarTelefono.value = telefono;*/

      //validacion();
    })
    //Manejo de errores
    .catch((err) => console.error("Error al obtener datos: ", err));

  return identificacion_colab;
};

let mostrarColaboradorID = (id_colab) => {
  fetch(`${rutaColaboradores}?id_colab=${id_colab}`)
    //Manejo de la respuesta
    .then((response) => {
      if (!response.ok) {
        throw new Error("Sin acceso a internet");
      }
      //Devuelve una promesa
      return response.json();
    })
    //Manejo de datos recibidos
    .then((data) => {
      //renderTable(data);
      console.table(data);

      let id = data.id_colab;
      let nombre = data.nombre_colab;
      let identificacion = data.identificacion_colab;

      console.table(id, nombre, identificacion);

      /*actualizarNombre.value = nombre;
      actualizarApellido.value = apellido;
      actualizarTelefono.value = telefono;*/

      //validacion();
    })
    //Manejo de errores
    .catch((err) => console.error("Error al obtener datos: ", err));
};
///################################ CIERRO GET POR ID ##################################
///################# FUNCIÓN FLECHA PARA HACER GET POR ID A COLABORADORES #########################
let mostrarCompensatorioID = (id_compe) => {
  fetch(`${rutaCompensatorios}?id_compe=${id_compe}`)
    //Manejo de la respuesta
    .then((response) => {
      if (!response.ok) {
        throw new Error("Sin acceso a internet");
      }
      //Devuelve una promesa
      return response.json();
    })
    //Manejo de datos recibidos
    .then((data) => {
      //renderTable(data);
      console.table(data);

      let id_colaborador = data.identificacion_colab;
      let descripcion_compe = data.descripcion_compe;
      let inicio_compe = data.inicio_compe;
      let final_compe = data.final_compe;
      let validacion_compe = data.validacion_compe;



      //validacion();
    })
    //Manejo de errores
    .catch((err) => console.error("Error al obtener datos: ", err));
};
///################################ CIERRO GET POR ID ##################################
///#################### FUNCIÓN FLECHA PARA MÉTODO PUT ##########################
let actualizarCompensatorio = (
  colaborador_id_compe,
  id_compe,
  id_colaborador,
  descripcion_compe,
  inicio_compe,
  final_compe,
  validacion_compe
) => {

  let compensatorio = {
    colaborador_id_compe,
    id_compe,
    id_colaborador,
    descripcion_compe,
    inicio_compe,
    final_compe,
    validacion_compe,
  };

  console.table(compensatorio);
  /*let id_c = mostrarColaboradorIdentificacion(id_colaborador);
  let id_i = mostrarColaboradorID(id_c);
  console.log(id_i);*/

  let colaborador = document.querySelector("#formularioActualizarCompensatorios #colaboradores");
  colaborador.value = id_colaborador;
  //colaboradoresF.value = id_colaboradorA;
  //
  descripcion_compeA.value = descripcion_compe;
  inicio_compeA.value = inicio_compe;
  final_compeA.value = final_compe;
  validacion_compeA.value = validacion_compe;
  //mostrarCompensatorioID(id_compe);
  //renderizarColaboradores();
  //console.table(compensatorio);

  /*let actualizarModal = $("#exampleModal");
  const modalTitle = document.querySelector("#exampleModal .modal-title");

  modalTitle.textContent = "Actualizar Compensatorio";
  let botonActualizar = document.querySelector(".modal-footer #btn-accept");

  botonActualizar.removeAttribute('id');

  botonActualizar.setAttribute('id', "btn-actualizar");
  botonActualizar.textContent = 'Actualizar';*/

  btnEditar.addEventListener("click", () => {

    let descripcion_compe = descripcion_compeA.value;
    let inicio_compe = inicio_compeA.value;
    let final_compe = final_compeA.value;
    let validacion_compe = validacion_compeA.value;

    //Modales personalizados de SweetAlert2
    Swal.fire({
      title: "¿Deseas actualizar el Compensatorio?",
      //showDenyButton: true,
    confirmButtonText: "Guardar",
    //denyButtonText: `Descartar`,
    showCancelButton: true,
    cancelButtonText: "Cancelar",
    //Modificar el color de los botones
    confirmButtonColor: "#2e9c9d",
    cancelButtonColor: "#d33",
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        //Swal.fire("¡Guardado con éxito!", "", "success");
      
        Swal.fire({
          confirmButtonColor: "#2e9c9d",
          title: "¡Actualizado con éxito!",
          text: "Compensatorio actualizado exitosamente.",
          icon: "success"
        });

        //?id=${id}
        fetch(`${rutaCompensatorios}?id=${id_compe}`, {
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ id_compe, colaborador_id_compe, descripcion_compe, inicio_compe, final_compe, validacion_compe }),
        })
          //Manejo de la respuesta
          .then(async (response) => {
            if (!response.ok) {
              const text = await response.text();
              throw new Error(text);
            }
            return response.json();
          })
          //Manejo de los datos recibidos
          .then((data) => {
            // Muestra un mensaje en la consola y refresca los datos en la tabla
            console.table(data);
            // Recargar la página actual con los datos actualizados
            if (data != null) {
              // Cierra el modal
              //Si es confirmada el modal, cerramos el modal del registro del compensatorio
              $('#actualizarModal').modal('hide');
              //y recargamos la tabla para obtener todos los compensatorios actualizados
              tableCompensatorios.ajax.reload();
              //Borrar campos del formulario
              formularioCompensatorios.reset();
            }
            //validacion();
          })
          //Manejo de errores
          .catch((error) => console.error("Error:", error));

      } /*else if (result.isDenied) {
        Swal.fire("Se descartó la actualización del Compensatorio", "", "info");
      }*/

    });

  });
};
///#################### CIERRO PUT ##########################
///#################### FUNCIÓN FLECHA PARA MÉTODO DELETE ##########################
let eliminarCompensatorio = (id_compe) => {
  console.log("Eliminar cliente con ID:", id_compe);

  //if (confirm("¿Estás seguro de que deseas eliminar este compensatorio?")) {
    Swal.fire({
      title: "¿Estás seguro de que deseas eliminar este compensatorio?",
      text: "¡No podrás recuperar esta información!",
      icon: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#2e9c9d",
      cancelButtonColor: "#d33",
      confirmButtonText: "Confirmar"
    }).then((result) => {
      if (result.isConfirmed) {

        fetch(`${rutaCompensatorios}?id_compe=${id_compe}`, {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ id_compe }),
        })
          //Manejo de la respuesta
          .then((response) => {
            if (!response.ok) throw new Error("Error al eliminar compensatorio");
            //Devuelve una promesa
            return response.json();
          })
          //Manejo de los datos recibidos
          .then((data) => {
            if (data.success) {
              console.table(data);
              //y recargamos la tabla para obtener todos los compensatorios actualizados
              tableCompensatorios.ajax.reload();
            } else {
              alert("No se puede eliminar el compensatorio");
              console.error("No se puede eliminar el compensatorio", data.message);
            }
          })
          //Manejo de errores
          .catch((error) => console.error("Error:", error));

        Swal.fire({
          title: "¡Eliminado!",
          text: "Compensatorio eliminado Exitosamente.",
          icon: "success"
        });
      }
    });
};
///################################ CIERRO DELETE ##################################
///#################### FUNCIÓN FLECHA PARA MÉTODO READ (COLABORADORES) ##########################
let renderizarColaboradores = () => {
  fetch(rutaColaboradores)
    .then((response) => response.json())
    .then((data) => {
      console.table(data);

      let datalist = $("#collaborators");

      data.forEach(function (colaborador) {
        //crearCompensatorio(colaborador.id_colab);

        let option = $("<option></option>").attr(
          "value",
          colaborador.nombre_colab +
          " (" +
          colaborador.identificacion_colab +
          ")"
        );

        option.attr("data-id", colaborador.id_colab);

        //option.attr("onclick", crearCompensatorio(colaborador.id_colab));

        datalist.append(option);

        //console.log(colaborador.id_colab);
      });

      $("#colaboradores").on("input", function () {
        var input = $(this).val();
        var option = $("#collaborators option").filter(function () {
          return this.value === input;
        });

        var id = option.attr("data-id");
        if (id !== undefined) {
          id_colab_compe = id;
          id_colab_compeA = id;
          //crearCompensatorio(id);
          console.log("ID del colaborador seleccionado:", id);
          //console.log("ID del colaborador seleccionado:", id_c);
        } else {
          console.log("No se encontró el ID para el colaborador seleccionado.");
        }
      });
    })
    .catch((err) => console.error("Error al obtener colaboradores: ", err));

  //$(document).ready(function() {

  //});
};
///#################### CIERRO READ COLABORADORES ##########################
///#################### FUNCIÓN FLECHA PARA PARSEAR FECHA (SIN USO) ##########################
let parsearFecha = (fechas) => {
  /*let fechaParts = fechas.split(" ");
  let fechaDia = fechaParts[0].split("/");
  let hora = fechaParts[1].replace("A. M.", "").trim();
 
  let fecha = moment.tz(`${fechaDia[2]}-${fechaDia[1]}-${fechaDia[0]} ${hora}`, "YYYY-MM-DD HH:mm", "America/Bogota");
 
  let fechaFormateada = fecha.format("YYYY-MM-DD HH:mm:ss");
 
  console.log(fechaFormateada); // Output: "2024-11-06 10:00:00"
 
  return fechaFormateada;*/

  let fechaParts = fechas.split(" ");
  let fechaDia = fechaParts[0].split("/");
  let hora = fechaParts[1].replace("A. M.", "").trim();

  let fecha = new Date(
    fechaDia[2],
    fechaDia[1] - 1,
    fechaDia[0],
    hora.split(":")[0],
    hora.split(":")[1]
  );
  //fecha.setTimezone("America/Bogota"); // Agregamos la zona horaria de Colombia
  //Funcionó +/-
  let fechaFormateada = fecha.toISOString().slice(0, 19).replace("T", " ");
  //let opciones = { timeZone: "America/Bogota", timeZoneName: "short" };
  //let fechaFormateada = fecha.toLocaleString("es-CO", opciones);

  console.log(fechaFormateada); // Output: "2024-06-04 04:00:00"

  return fechaFormateada;
  ////////
  /*let partes = fechas.split(" ");
  let fecha = partes[0];
  let horaAMorPM = partes[1];
 
  let horaNum = horaAMorPM.includes("P.M.") ? Number(horaAMorPM.replace("P.M.", "").trim()) + 12 : Number(horaAMorPM.replace("A.M.", "").trim());
  let horaFormateada = `${fecha} ${horaNum.toString().padStart(2, "0")}:00:00`;
 
  return horaFormateada;*/
};
///#################### CIERRO PARSEAR FECHA ##########################
///#################### CONFIGURACIÓN DEL TIMEPICKER ##########################
let dateTimePicker = (inicio, final) => {
  //new tempusDominus.TempusDominus(inicio);
  //new tempusDominus.TempusDominus(final);
  let configuracionPicker = {
    localization: {
      // T
      format: "dd/MM/yyyy hh:mm T",
      locale: 'es-CO'
      //format: form,
    },
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
        today: true,
        clear: true,
        close: true,
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
      theme: "light",
    },
  };

  //tempusDominus.extend(tempusDominus.plugins.moment_parse, 'dd/MM/yyyy hh:mm A');

  let inicioP = new tempusDominus.TempusDominus(inicio, configuracionPicker);

  let finalP = new tempusDominus.TempusDominus(
    final,
    configuracionPicker /*{
   
    useCurrent: false,
    
  }*/
  );


  //using event listeners
  inicio.addEventListener(tempusDominus.Namespace.events.change, (e) => {
    finalP.updateOptions({
      restrictions: {
        minDate: e.detail.date,
      },
    });
  });

  //using subscribe method
  const subscription = finalP.subscribe(
    tempusDominus.Namespace.events.change,
    (e) => {
      inicioP.updateOptions({
        restrictions: {
          maxDate: e.date,
        },
      });
    }
  );
};
///#################### CIERRO TIMEPICKER ##########################
