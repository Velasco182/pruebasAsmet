//import { tempusDominus, Namespace } from '@eonasdan/tempus-dominus';

let tableCompensatorios;
let rutaColaboradores =
  "/sena/Proyectos/pruebaCompensatorios/assets/php/colaboradores.php";
let rutaCompensatorios =
  "/sena/Proyectos/pruebaCompensatorios/assets/php/compensatorios.php";

//Instancia del formulario y boton de crear y actualizar Cliente
let formularioCompensatorios = document.getElementById(
  "formularioCompensatorios"
);
let btnEnviar = document.getElementById("btn-accept");

//Instancia de los campos de fecha y hora
let inicioPicker = document.getElementById("inicioDateTimePicker");
let finalPicker = document.getElementById("finalDateTimePicker");

//Instancia de los campos del formulario
let colaboradores = document.querySelector("#colaboradores").value;
let descripcion = document.querySelector("#descripcion").value;
let inicio = document.querySelector("#inicio").value;
let final = document.querySelector("#final").value;
let valid = document.querySelector("#validacion");

//Validar formularios
console.log(valid.checked);

let validarFormulario = (formulario) => {
  //colaboradores.value != '' || descripcion.value != '' || inicio.value != '' || final.value
  formulario.addEventListener("input", () => {
    if (formulario.checkValidity()) {
      btnEnviar.disabled = false;
      console.log(valid.checked);
    } else {
      btnEnviar.disabled = true;
    }
  });

  /*valid.addEventListener('click', ()=>{
        if(valid.checked){
            btnEnviar.disabled = false;
        }else{
            btnEnviar.disabled = true;
        }
    });*/
};

// El segundo parámetro evita el reinicio de la paginación
// tableCompensatorios.ajax.reload(null, false);

//https://www.codexworld.com/bootstrap-datetimepicker-add-date-time-picker-input-field/

document.addEventListener("DOMContentLoaded", function () {
  formularioCompensatorios.reset();
  inicioFinComparison(inicioPicker, finalPicker);

  dateTimePicker(inicioPicker);
  dateTimePicker(finalPicker);

  validarFormulario(formularioCompensatorios);

  renderizarColaboradores();
  renderizarCompensatorios();

  btnEnviar.addEventListener(
    "click",
    crearCompensatorio(colaboradores, descripcion, inicio, final, valid)
  );
  //Para la hora precisa, pero no existe la funcion datetimepicker

  /*var today = new Date();
  var date =
    today.getFullYear() + "-" + (today.getMonth() + 1) + "-" + today.getDate();
  var time = today.getHours() + ":" + today.getMinutes();
  var dateTime = date + " " + time;

  console.log(dateTime);*/
});

let crearCompensatorio = (colaborador, descripcion, inicio, final, valid) => {
  ///Separar los valores de nombre y cédula del colaborador para crear el compensatorio
  let match = colaborador.match(/^(.+?) \((\d+)\)$/);

  if (match) {
    let nombre = match[1];
    let identificacion = match[2];

    let validacion = valid.checked;

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
        validacion,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        $("#exampleModal").modal("hide");
        table.ajax.reload();
      })
      .catch((error) => console.error("Error al crear Compensatorio:", error));
  }
};

let renderizarCompensatorios = () => {

$(document).ready(function() {   

    table = new DataTable('#tableCompensatorios',{

        responsive: "true",
        order: [[1, "asc"]],
        columnDefs: [
        {
            targets: [0, 1, 2, 3, 4, 5, 6],
            orderable: false,
            className: "text-center"
        },
            {
                render: function (row) {
                    let id = row.id;
                    let nombre = row.identificacion;
                    let apellido = row.nombre;
                    let descripcion = row.descripcion;
                    let inicio = row.inicio;
                    let final = row.final;
                    let validacion = row.validacion;
                    let colaborador = {
                        id,
                        nombre,
                        apellido,
                        descripcion,
                        inicio,
                        final,
                        validacion,
                    };
                    console.table(colaborador);
                },
            },
        ],
        ajax: {
            url: `${rutaCompensatorios}`,
            type: "GET",
            },
        columns: [
            { data: "identificacion" },
            { data: "nombre" },
            { data: "descripcion" },
            { data: "inicio" },
            { data: "final" },
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

let inicioFinComparison = (inicio, final) => {
  let inicioP = new tempusDominus.TempusDominus(inicio);

  let finalP = new tempusDominus.TempusDominus(final, {
    useCurrent: false,
  });

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

//inicioFinComparison(inicioPicker, finalPicker);

let dateTimePicker = (idCampo) => {
  new tempusDominus.TempusDominus(
    idCampo,

    {
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
      },
    }
  );
};
