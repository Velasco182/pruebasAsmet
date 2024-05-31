let tableCompensatorios;

//Rutas para hacer las peticiones del backend a la db
let rutaColaboradores =
  "/sena/Proyectos/pruebaCompensatorios/assets/php/colaboradores.php";
let rutaCompensatorios =
  "/sena/Proyectos/pruebaCompensatorios/assets/php/compensatorios.php";

//Instancia del formulario y boton de crear y actualizar Cliente
let formularioCompensatorios = document.getElementById(
  "formularioCompensatorios"
);
let btnEnviarCompensatorio = document.getElementById("btn-accept");
let btnValidarCompensatorio = document.getElementById("btn-validar");
let btnCerrar = document.getElementById("btn-cerrar");
let btnClose = document.getElementById("btn-close");

//Instancia de los campos de fecha y hora
let inicioPicker = document.getElementById("inicioDateTimePicker");
let finalPicker = document.getElementById("finalDateTimePicker");


// El segundo parámetro evita el reinicio de la paginación
// tableCompensatorios.ajax.reload(null, false);

//https://www.codexworld.com/bootstrap-datetimepicker-add-date-time-picker-input-field/

document.addEventListener("DOMContentLoaded", function () {
  //Reinicamos el formulario
  formularioCompensatorios.reset();
  //Cargamos los colaboradores dentro del select
  renderizarColaboradores();
  //Cargamos los compensatorios en la tabla
  renderizarCompensatorios();
  //Configuración del DateTimePicker
  dateTimePicker(inicioPicker, finalPicker);
  //Evento de escucha para el boton de guardar compensatorio
  btnEnviarCompensatorio.addEventListener("click", crearCompensatorio);
  //Evento de escucha para el boton de validar compensatorios
  //btnValidarCompensatorio.addEventListener("click", crearCompensatorio);
  //Evento al cerrar o cancelar modal
  btnCerrar.addEventListener("click", clarearFormulario);
  btnClose.addEventListener("click", clarearFormulario);
});

let clarearFormulario = () =>{
  //Borrar campos del formulario
  formularioCompensatorios.reset();
}


let crearCompensatorio = () => {

  //formularioCompensatorios.addEventListener('submit',  function (e){
    //e.preventDefault();

    //Instancia de los campos del formulario
    let colaboradores = document.querySelector("#colaboradores").value;
    let descripcion_compe = document.querySelector("#descripcion").value;
    let inicio_compe = document.querySelector("#inicio").value;
    let final_compe = document.querySelector("#final").value;

    ///Separar los valores de nombre y cédula del colaborador para crear el compensatorio
    let match = colaboradores.match(/^(.+?) \((\d+)\)$/);

    let nombre_compe = match[1];
    let identificacion_compe = match[2];

    let validacion_compe = 0;

    if(identificacion_compe == '' || nombre_compe == '' || descripcion_compe == '' || inicio_compe == '' || final_compe == ''){
      Swal.fire({
        title: "Atención",
        text: "Todos los campos son obligatorios.",
        icon: "error"
      });
      return false;
    }

    //Modales personalizados de SweetAlert2
    Swal.fire({
      title: "¿Deseas guardar el Compensatorio?",
      showDenyButton: true,
      showCancelButton: true,
      confirmButtonText: "Guardar",
      denyButtonText: `Descartar`
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
            identificacion_compe,
            nombre_compe,
            descripcion_compe,
            inicio_compe,
            final_compe,
            validacion_compe
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
          .catch((error) => console.error("Error al crear Compensatorio:", error));
      
      } else if (result.isDenied) {
        Swal.fire("Se descartó el compensatorio", "", "info");
      }
    });

  //});
  
};

let renderizarCompensatorios = () => {

$(document).ready(function() {   

    tableCompensatorios = new DataTable('#tableCompensatorios',{

        responsive: "true",
        language: {
          url: '././assets/js/spanish.json'
        },
        order: [[3, "desc"]],
        columnDefs: [
        {
            targets: [0, 1, 2, 3, 4, 5, 6, 7],
            orderable: false,
            className: "text-center"
        },
          //Para agregar botones de acción a la tabla de forma "dinámica"
          { // Columna de acciones
            targets: 6, 
            className: "text-center",
            render: function (row) {
                let id = row.id_compe;
                let identificacion = row.identificacion_compe;
                let nombre = row.nombre_compe;
                let descripcion = row.descripcion_compe;
                let inicio = row.inicio_compe;
                let final = row.final_compe;
                let validacion = row.validacion_compe;
                let colaborador = {
                    id,
                    identificacion,
                    nombre,
                    descripcion,
                    inicio,
                    final,
                    validacion,
                }
                //console.table(colaborador);
                // Aquí puedes personalizar los botones de acciones (editar, eliminar, etc.)
                return `Pendiente`;
            },
            
        },
        //Para agregar botones de acción a la tabla de forma "dinámica"
            { // Columna de acciones
                targets: 7, 
                className: "text-center",
                render: function (row) {
                    let id = row.id_compe;
                    let identificacion = row.identificacion_compe;
                    let nombre = row.nombre_compe;
                    let descripcion = row.descripcion_compe;
                    let inicio = row.inicio_compe;
                    let final = row.final_compe;
                    let validacion = row.validacion_compe;
                    let colaborador = {
                        id,
                        identificacion,
                        nombre,
                        descripcion,
                        inicio,
                        final,
                        validacion,
                    }
                    //console.table(colaborador);
                    // Aquí puedes personalizar los botones de acciones (editar, eliminar, etc.)
                    return `
                        <i id="btn-validar" class="btn fa-solid fa-pen" style="background-color: #2e9c9d; color: white; :hover { background-color: #cdeeee; color: black; }" 
                        type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="${row.id_compe}" 
                        onclick="actualizarCliente('${row.id_compe}', '${row.nombre_compe}', 
                        '${row.apellido_compe}', '${row.telefono_compe}')"></i>
                        <i class="btn btn-danger fa-solid fa-trash" data-id="${row.id}" onclick="eliminarCliente('${row.id}')"></i>
                        `;
                },
                
            },

        ],
        ajax: {
            url: `${rutaCompensatorios}`,
            type: "GET",
            dataSrc: ''
            },
        columns: [
            { data: "identificacion_compe" },
            { data: "nombre_compe" },
            { data: "descripcion_compe" },
            { data: "inicio_compe" },
            { data: "final_compe" },
            { data: null },
            { data: "validacion_compe" },
            { data: null },
        ]
    });
});
}

let renderizarColaboradores = () => {
  fetch(rutaColaboradores)
    .then((response) => response.json())
    .then((data) => {
      console.table(data);

      let datalist = $("#collaborators");

      data.forEach(function (colaborador) {
        let option = $("<option></option>").attr(
          "value",
          colaborador.nombre_colab + " (" + colaborador.identificacion_colab + ")"
        );

        datalist.append(option);
      });
    })
    .catch((err) => console.error("Error al obtener colaboradores: ", err));
};

let dateTimePicker = (inicio, final) => {

  //new tempusDominus.TempusDominus(inicio);
  //new tempusDominus.TempusDominus(final);
  let configuracionPicker = {
    localization: {
      format: "dd/MM/yyyy h:mm T",
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
      theme: "light",
    }
  }

  let inicioP = new tempusDominus.TempusDominus(inicio, configuracionPicker);

  let finalP = new tempusDominus.TempusDominus(final, configuracionPicker /*{
   
    useCurrent: false,
    
  }*/);

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
  
}
