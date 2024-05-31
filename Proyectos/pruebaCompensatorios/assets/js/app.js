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

});


let crearCompensatorio = () => {

  //formularioCompensatorios.addEventListener('submit',  function (e){
    //e.preventDefault();

    //Instancia de los campos del formulario
    let colaboradores = document.querySelector("#colaboradores").value;
    let descripcion = document.querySelector("#descripcion").value;
    let inicio = document.querySelector("#inicio").value;
    let final = document.querySelector("#final").value;

    ///Separar los valores de nombre y cédula del colaborador para crear el compensatorio
    let match = colaboradores.match(/^(.+?) \((\d+)\)$/);

    let nombre = match[1];
    let identificacion = match[2];

    let validacion = 0;

    if(identificacion == '' || nombre == '' || descripcion == '' || inicio == '' || final == ''){
      Swal.fire({
        title: "Atención",
        text: "Todos los campos son obligatorios.",
        icon: "error"
      });
      return false;
    }

    fetch(rutaCompensatorios, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        identificacion,
        nombre,
        descripcion,
        inicio,
        final,
        validacion
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
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
            //Si es confirmada el modal, cerramos el modal del registro del compensatorio
            $("#exampleModal").modal("hide");
            //y recargamos la tabla para obtener todos los compensatorios actualizados
            tableCompensatorios.ajax.reload();
            //Borrar campos del formulario
            formularioCompensatorios.reset();
          
          } else if (result.isDenied) {
            Swal.fire("Se descartó el compensatorio", "", "info");
          }
        });
      })
      .catch((error) => console.error("Error al crear Compensatorio:", error));

  //});
  
};

let renderizarCompensatorios = () => {

$(document).ready(function() {   

    tableCompensatorios = new DataTable('#tableCompensatorios',{

        responsive: "true",
        order: [[1, "asc"]],
        columnDefs: [
        {
            targets: [0, 1, 2, 3, 4, 5, 6],
            orderable: false,
            className: "text-center"
        },
        //Para agregar botones de acción a la tabla de forma "dinámica"
            { // Columna de acciones
                targets: 6, 
                className: "text-center",
                render: function (row) {
                    let id = row.id;
                    let identificacion = row.identificacion;
                    let nombre = row.nombre;
                    let descripcion = row.descripcion;
                    let inicio = row.inicio;
                    let final = row.final;
                    let validacion = row.validacion;
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
                        <i class="btn fa-solid fa-pen" style="background-color: #2e9c9d; color: white;" type="button" data-bs-toggle="modal" data-bs-target="#actualizarModal" data-id="${row.id}" onclick="actualizarCliente('${row.id}', '${row.nombre}', '${row.apellido}', '${row.telefono}')"></i>
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
            { data: "identificacion" },
            { data: "nombre" },
            { data: "descripcion" },
            { data: "inicio" },
            { data: "final" },
            { data: null },
            { data: "validacion" },
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
          colaborador.nombre + " (" + colaborador.identificacion + ")"
        );

        datalist.append(option);
      });
    })
    .catch((err) => console.error("Error al obtener colaboradores: ", err));
};

/*let inicioFinComparison = (inicio, final) => {

};*/

//inicioFinComparison(inicioPicker, finalPicker);

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
      theme: "auto",
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
