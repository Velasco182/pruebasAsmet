let tableCompensatorios;
let ruta = "/sena/Proyectos/pruebaCompensatorios/assets/php/back.php";

//Instancia del formulario y boton de crear y actualizar Cliente
let formularioCompensatorios = document.getElementById("formularioCompensatorios");
let btnEnviar = document.getElementById("btn-accept");

//Validar formularios
let validarFormulario = (formulario) => {

    formulario.addEventListener("input", () => {

      if (formulario.checkValidity()) {

        btnEnviar.disabled = false;

      } else {

        btnEnviar.disabled = true;

      }
    });
};
  
validarFormulario(formularioCompensatorios);

// El segundo parámetro evita el reinicio de la paginación
// tableCompensatorios.ajax.reload(null, false);

//https://www.codexworld.com/bootstrap-datetimepicker-add-date-time-picker-input-field/

document.addEventListener('DOMContentLoaded', function(){

    //Para la hora precisa, pero no existe la funcion datetimepicker

    var today = new Date();
    var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
    var time = today.getHours() + ":" + today.getMinutes();
    var dateTime = date+' '+time;

    console.log(dateTime);

    /**/$('#formularioCompensatorios .input-append.date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayBtn: true,
        //startDate: dateTime
    });

    /*$('#datetimepicker').datetimepicker()

    $(function () {
        $('#datetimepicker').datetimepicker({
          format: 'L'
        });
    });*/


    tableCompensatorios = $('#tableCompensatorios').dataTable({

        "ajax": {
            "url": `${ruta}`
        },
        "columns":[
            {"data":"identificacion"},
            {"data":"nombre"},
            {"data":"descripcion"},
            {"data":"inicio"},
            {"data":"final"},
            {"data":"validacion"},
        ],
        "responsive":"true",
        "order":[[1,"asc"]],
        columnDefs:[{
            targets: [0,1,2,3,4,5,6],
            orderable: false,
            className: "text-center"
        }]

    });

});