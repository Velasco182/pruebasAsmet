let tableCompensatorios;
let id_colab_compe;

//Rutas para hacer las peticiones del backend a la db
const rutaColaboradores =
  "/sena/Proyectos/pruebaCompensatorios/assets/php/colaboradores.php";
const rutaCompensatorios =
  "/sena/Proyectos/pruebaCompensatorios/assets/php/compensatorios.php";

/////#################### INSTANCIA DE FORMULARIO Y BOTONES DEL FRONT-END ##########################
let formularioCompensatorios = document.getElementById(
  "formularioCompensatorios"
);
let formularioActualizarCompensatorios = document.getElementById(
  "formularioActualizarCompensatorios"
);
let btnEnviarCompensatorio = document.getElementById("btn-accept");
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

let clarearFormulario = () =>{
  //Evento al cerrar o cancelar modal//Borrar campos del formulario
  btnCerrar.addEventListener("click", ()=>{
    formularioCompensatorios.reset();
    formularioActualizarCompensatorios.reset()
  });

  btnClose.addEventListener("click", ()=>{
    formularioCompensatorios.reset();
    formularioActualizarCompensatorios.reset();
  });
};

///#################### CIERRO DOM ##########################
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
    showDenyButton: true,
    showCancelButton: true,
    confirmButtonText: "Guardar",
    denyButtonText: `Descartar`,
  }).then((result) => {
    /* Read more about isConfirmed, isDenied below */
    if (result.isConfirmed) {
      Swal.fire("Guardado con éxito", "", "success");
      
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
      } else if (result.isDenied) {
        Swal.fire("Se descartó el compensatorio", "", "info");
    }
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
        //Para agregar botones de acción a la tabla de forma "dinámica"
        {
          // Columna de acciones
          targets: 7,
          className: "text-center",
          render: function (row) {
            clarearFormulario();
            let id_compensatorio = row.id_compe;
            /*let identificacion = row.identificacion_colab;
                    let nombre = row.nombre_colab;*/
            let id_colaborador = row.identificacion_colab;
            let descripcion = row.descripcion_compe;
            let inicio = row.inicio_compe;
            let final = row.final_compe;
            let validacion = row.validacion_compe;

            if(validacion == "Aceptado"){

              return `<i class="btn btn-danger fa-solid fa-trash" data-id="${id_compensatorio}" onclick="eliminarCompensatorio('${id_compensatorio}')" disabled></i>`;

            }else if(validacion == "Pendiente" || "Rechazado"){

              return `  <i id="btn-validar" class="btn fa-solid fa-pen" style="background-color: #2e9c9d; color: white; :hover { background-color: #cdeeee; color: black; }" 
                        type="button" data-bs-toggle="modal" data-bs-target="#actualizarModal" data-id="${row.id_compe}" 
                        onclick="actualizarCompensatorio('${id_compensatorio}', '${id_colaborador}', 
                        '${descripcion}', '${inicio}', '${final}', '${validacion}')"></i>
                        <i class="btn btn-danger fa-solid fa-trash" data-id="${id_compensatorio}" onclick="eliminarCompensatorio('${id_compensatorio}')"></i>
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
      ],
    });
  });
};
///#################### CIERRO READ COMPENSATORIOS ##########################
///################# FUNCIÓN FLECHA PARA HACER GET POR ID A COLABORADORES #########################
let mostrarColaboradorID = (id) => {
  fetch(`${rutaColaboradores}?id=${id}`)
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

      let nombre = data.nombre_colab;
      let identificacion = data.identificacion_colab;

      console.table(data.id_colab, nombre, identificacion);

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
let mostrarCompensatorioID = (id) => {
  fetch(`${rutaCompensatorios}?id=${id}`)
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



      let nombreA = data.nombre;
      let apellidoA = data.apellido;
      let telefonoA = data.telefono;

      console.table(data.id, nombreA, apellidoA, telefonoA);

      actualizarNombre.value = nombreA;
      actualizarApellido.value = apellidoA;
      actualizarTelefono.value = telefonoA;

      //validacion();
    })
    //Manejo de errores
    .catch((err) => console.error("Error al obtener datos: ", err));
};
///################################ CIERRO GET POR ID ##################################
///#################### FUNCIÓN FLECHA PARA MÉTODO PUT ##########################
let actualizarCompensatorio = (
  id_compe,
  id_colaborador,
  descripcion_compe,
  inicio_compe,
  final_compe,
  validacion_compe
) => {

  let compensatorio = {
    id_compe,
    id_colaborador,
    descripcion_compe,
    inicio_compe,
    final_compe,
    validacion_compe,
  };


  console.table(compensatorio);

  /*let actualizarModal = $("#exampleModal");
  const modalTitle = document.querySelector("#exampleModal .modal-title");

  modalTitle.textContent = "Actualizar Compensatorio";
  let botonActualizar = document.querySelector(".modal-footer #btn-accept");

  botonActualizar.removeAttribute('id');

  botonActualizar.setAttribute('id', "btn-actualizar");
  botonActualizar.textContent = 'Actualizar';*/

  let colaborador = document.querySelector("#formularioActualizarCompensatorios #colaboradores");
  colaborador.value = id_colaborador;
  //colaboradoresF.value = id_colaborador;
  descripcion_compeA.value = descripcion_compe;
  inicio_compeA.value = inicio_compe;
  final_compeA.value = final_compe;
  validacion_compeA.value = validacion_compe;
  
  let btnEditar = document.querySelector("#btn-actualizar");
  
  btnEditar.addEventListener('click', function (){

    $(colaborador).on("input", function () {
      var input = $(this).val();
      var option = $("#collaborators option").filter(function () {
        return this.value === input;
      });

      var id = option.attr("data-id");
      console.log(id);
      if (id !== undefined) {
        let colaborador_id_compe = id;
        //crearCompensatorio(id);
        console.log("ID del colaborador seleccionado:", id);

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
                //location.reload(true);
                // Cierra el modal
                //modalInstance.hide();
                $('#actualizarModal').modal('hide');
                //validacion();
              }
              //validacion();
            })
            //Manejo de errores
            .catch((error) => console.error("Error:", error));

      } else {
        console.log("No se encontró el ID para el colaborador seleccionado.");
      }
    });

    console.log("Actualizar BTN");

  });



};
///#################### CIERRO PUT ##########################
///#################### FUNCIÓN FLECHA PARA MÉTODO DELETE ##########################
let eliminarCompensatorio = (id_compe) => {
  console.log("Eliminar cliente con ID:", id_compe);

  if (confirm("¿Estás seguro de que deseas eliminar este compensatorio?")) {
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
  }
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
          //crearCompensatorio(id);
          console.log("ID del colaborador seleccionado:", id);
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
      locale: 'en-CO'
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
