let tableCompensatorios;
let rowTable = ""; 
let divLoading = document.querySelector("#divLoading");

document.addEventListener('DOMContentLoaded', function(){

    ftnDateTimePickerConfiguration();

    tableCompensatorios = $('#tableCompensatorios').dataTable({
        "aProcessing":true,
        "aServerSide":true,
        "language": {
            "url": "././Assets/js/spanish.json"
        },
        "ajax":{
            "url": " "+base_url+"/Compensatorios/getCompensatorios",
            "dataSrc":""
        },
        "columns":[
            {"data":"FUN_NOMBRES"},
            {"data":"FUN_APELLIDOS"},
            {"data":"COM_FECHA_INICIO"},
            {"data":"COM_FECHA_FIN"},
            {"data":"ID_TIPO_COMPENSATORIO"},
            {"data":"COM_DESCRIPCION_ACTIVIDAD"},
            {"data":"COM_USUARIO_FINAL"},
            {"data":"COM_ESTADO"},
            {"data":"ACCIONES"}
        ],
        'dom': 'lBfrtip',
        'buttons': [
            {
                "extend": "copyHtml5",
                "text": "<i class='far fa-copy'></i> Copiar",
                "titleAttr":"Copiar",
                "className": "btn btn-secondary"
            },{
                "extend": "excelHtml5",
                "text": "<i class='fas fa-file-excel'></i> Excel",
                "titleAttr":"Esportar a Excel",
                "className": "btn btn-success"
            },{
                "extend": "pdfHtml5",
                "text": "<i class='fas fa-file-pdf'></i> PDF",
                "titleAttr":"Esportar a PDF",
                "className": "btn btn-danger"
            },{
                "extend": "csvHtml5",
                "text": "<i class='fas fa-file-csv'></i> CSV",
                "titleAttr":"Esportar a CSV",
                "className": "btn btn-info"
            }
        ],
        "resonsieve":"true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[2,"desc"]],
        "columnDefs": [
        {
            "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8],
            "orderable": false,
            "className": "text-center",
        }],  
    });

    if(document.querySelector("#formCompensatorio")){
        let formUsuario = document.querySelector("#formCompensatorio");
        formUsuario.onsubmit = function(e) {
           
            e.preventDefault();
            
            let strFechaInicio = document.querySelector('#txtFechaInicio').value;
            let strFechaFin = document.querySelector('#txtFechaFin').value;
            let strDescripcionActividad = document.querySelector('#txtDescripcionActividad').value;
            let strActividad = document.querySelector('#txtActividad').value;
            let ListadoUsuarios = document.querySelector('#ListaUsuarios').value;
            let strTrabajoRequerido = document.querySelector('#txtTrabajoRequerido').value;
            let intEstado = document.querySelector('#txtEstado').value;

            let compensatorio = {strFechaInicio, strFechaFin, strDescripcionActividad, strActividad, ListadoUsuarios, strTrabajoRequerido, intEstado};
            console.table(compensatorio);
    
            if(strFechaInicio == '' || strFechaFin == '' || strDescripcionActividad == '' || strActividad == '' || ListadoUsuarios == '' || strTrabajoRequerido == '' || intEstado == ''){
                swal("Atención", "Todos los campos son obligatorios." , "error");
                return false;
            }

            let elementsValid = document.getElementsByClassName("valid");
            for (let i = 0; i < elementsValid.length; i++) { 
                if(elementsValid[i].classList.contains('is-invalid')) { 
                    swal("Atención", "Por favor verifique los campos en rojo." , "error");
                    return false;
                } 
            } 
            divLoading.style.display = "flex";
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Compensatorios/setCompensatorio'; 
            let formData = new FormData(formUsuario);
            
            request.open("POST",ajaxUrl,true);
            request.send(formData);
            
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status){
                        if(rowTable == ""){
                            tableCompensatorios.api().ajax.reload();
                        }else{
                            tableCompensatorios.api().ajax.reload();
                        }
                        $('#modalFormCompensatorio').modal("hide");

                        formUsuario.reset();
                        swal("Usuario", objData.msg ,"success");
                    }else{
                        swal("Error", objData.msg , "error");
                    }
                }
                divLoading.style.display = "none";
                return false;
            }
        }
    }
},false);

/*// Función para validar las fechas de inicio y fin
 function validateDates() {
     var fechaInicio = new Date(document.getElementById('txtFechaInicio').value);
     var fechaFin = new Date(document.getElementById('txtFechaFin').value);

     if (fechaInicio > fechaFin) {
         alert("La fecha de inicio debe ser igual o menor a la fecha de fin.");
         return false;
     }

     return true;
 }

 Validacion fecha y horas
 document.addEventListener("DOMContentLoaded", function () {
     const txtFechaInicio = document.getElementById("txtFechaInicio");
     const txtFechaFin = document.getElementById("txtFechaFin");
     const formulario = document.getElementById("tuFormulario"); // Reemplaza "tuFormulario" con el ID de tu formulario

     txtFechaInicio.addEventListener("input", validateDateTime);
     txtFechaFin.addEventListener("input", validateDateTime);

     function validateDateTime() {
         const fechaInicio = new Date(txtFechaInicio.value);
         const fechaFin = new Date(txtFechaFin.value);
         if (fechaInicio.getTime() === fechaFin.getTime()) {
             txtFechaInicio.setCustomValidity("Las horas no pueden ser las mismas");
         } else if (fechaInicio >= fechaFin) {
             txtFechaInicio.setCustomValidity("La fecha de inicio debe ser mayor a la fecha fin");
         } else if (fechaInicio.getHours() === fechaFin.getHours() && fechaInicio.getMinutes() === fechaFin.getMinutes()) {
             txtFechaInicio.setCustomValidity("La fecha fin no puede ser mayor a la fecha de inicio");
         } else {
             txtFechaInicio.setCustomValidity("");
             validateHours();
         }
     }

     // Validacion horas
     function validateHours() {
         const horaInicio = new Date(txtFechaInicio.value);
         const horaFin = new Date(txtFechaFin.value);

         if (horaInicio.getHours() >= horaFin.getHours() || (horaInicio.getHours() === horaFin.getHours() && horaInicio.getMinutes() >= horaFin.getMinutes())) {
             txtFechaInicio.setCustomValidity("La hora de inicio debe ser anterior a la hora de fin.");
         } else {
             txtFechaInicio.setCustomValidity("");
             if (fechaInicio >= fechaFin) {
                 formulario.submit(); // Envía el formulario si fechaInicio es mayor o igual a fechaFin
             }
         }
     }
 });*/


 /////////

function ftnDateTimePickerConfiguration(){

    let inicio = document.querySelector('#datetimepickerInicio');
    let final = document.querySelector('#datetimepickerFinal');

    $(document).ready(function() {

        let timePickerConfiguration = {
        
            format: "DD/MM/yyyy hh:mm A",
            locale: moment.locale('es-mx'),
            buttons:{
                showToday: true,
                showClear: true,
                showClose: true
            },
            icons: {
                time: "fa fa-clock fa-lg",
                date: "fa fa-calendar-plus fa-lg",
                up: "fa fa-caret-up fa-lg",
                down: "fa fa-caret-down fa-lg",
                previous: "fa fa-chevron-left",
                next: "fa fa-chevron-right",
                today: "fa fa-calendar-check",
                clear: "fa fa-trash",
                close: "fa fa-xmark",
            }
              
        };

        // Crea una copia del objeto de configuración
        let finalTimePickerConfiguration = Object.assign({}, timePickerConfiguration);
        // Agrega el campo dinámico a la copia
        finalTimePickerConfiguration['useCurrent'] = false;

        $(inicio).datetimepicker(timePickerConfiguration);
        // Aplica la configuración modificada al datetimepicker
        $(final).datetimepicker(finalTimePickerConfiguration);

        $(inicio).on("change.datetimepicker", function(e){
            $(final).datetimepicker('minDate', e.date);
        });
        
        $(final).on("change.datetimepicker", function(e){
            $(inicio).datetimepicker('maxDate', e.date);
        });

    });
};

function ftnAprobarCompensatorio(ID_COMPENSATORIO) { //Funcion para el boton de aprobacion
    swal({
        title: "Aprobar Compensatorio",
        text: "¿Realmente quieres aprobar este compensatorio?",
        type: "info",
        showCancelButton: true,
        confirmButtonText: "Sí, aprobar",
        cancelButtonText: "Cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            divLoading.style.display = "flex"; // Mostrar el div de carga
            
            let request = new XMLHttpRequest();
            let ajaxUrl = base_url + '/Compensatorios/aprobarCompensatorio';
            let strData = "ID_COMPENSATORIO=" + ID_COMPENSATORIO;
            
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            
            request.onreadystatechange = function () {
                if (request.readyState === 4 && request.status === 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        tableCompensatorios.api().ajax.reload();
                        swal("Compensatorio Aprobado", objData.msg, "success");
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
                divLoading.style.display = "none"; // Ocultar el div de carga
            };
        }
    });
}

function ftnRechazarCompensatorio(ID_COMPENSATORIO) { // Funcion para boton boton de rechazo
    swal({
        title: "Rechazar Compensatorio",
        text: "¿Realmente quieres rechazar este compensatorio?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, rechazarlo",
        cancelButtonText: "Cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Compensatorios/rechazarCompensatorio';
            let strData = "ID_COMPENSATORIO=" + ID_COMPENSATORIO;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        tableCompensatorios.api().ajax.reload();
                        swal("Compensatorio Rechazado", objData.msg, "error");
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
            }
        }
    });
}

function fntViewFuncionario(ID_COMPENSATORIO){
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Compensatorios/getCompensatorio/'+ID_COMPENSATORIO;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            if(objData.status){
                let estado = objData.data.COM_ESTADO == 1 ? 
                '<span class="badge badge-warning">Pendiente</span>' : 
                (objData.data.COM_ESTADO == 2 ? '<span class="badge badge-success">Aprobado</span>' :
                '<span class="badge badge-danger">Rechazado</span>');

                
                document.querySelector("#InfoNombres").innerHTML = objData.data.FUN_NOMBRES;
                document.querySelector("#InfoApellidos").innerHTML = objData.data.FUN_APELLIDOS;
                document.querySelector("#InfoCorreo").innerHTML = objData.data.FUN_CORREO;
                document.querySelector("#InfoDescripcion").innerHTML = objData.data.ID_TIPO_COMPENSATORIO;
                // document.querySelector("#InfoFechaInicio").innerHTML = objData.data.COM_FECHA_INICIO;
                // document.querySelector("#InfoFechaFinal").innerHTML = objData.data.COM_FECHA_FIN;
                document.querySelector("#InfoEstado").innerHTML = estado;
                document.querySelector("#InfoHorasRealizadas").innerHTML = objData.data.horasrealizadas;
                
                if (objData.data.url_portada) {
                    document.querySelector("#DescargarSoporte").innerHTML = '<a href="' + objData.data.url_portada + '" target="_blank"><i class="fas fa-download"> Evidencia disponible</i></a>';
                } else {
                    // Si objData.data.url_portada está vacío, muestra un mensaje de "No hay evidencia disponible"
                    document.querySelector("#DescargarSoporte").innerHTML = 'No hay evidencia disponible';
                }
                
                
            
                // document.querySelector('#com_estado').innerHTML = objData.data.FUN_ACCESO;

                // document.querySelector("#com_horas_realizadas").innerHTML = objData.data.com_horas_realizadas;
                // document.querySelector("#com_horas_compensadas").innerHTML = objData.data.com_horas_compensadas;
                // document.querySelector("#Horas_Trabajadas").innerHTML = objData.data.Horas_Trabajadas;

                $('#modalViewFuncionario').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}

function btnEditCompensatorio(element,ID_COMPENSATORIO){

    ftnTotalUsuarios();
    ftnTotalTipoCompensatorio();
    
    rowTable = element.parentNode.parentNode.parentNode; 
    // document.querySelector("#listRolid").innerHTML="";
    document.querySelector('#titleModal').innerHTML ="Actualizar compensatorio";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML ="Actualizar";

    var ID_COMPENSATORIO = ID_COMPENSATORIO;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Compensatorios/editCompensatorio/'+ID_COMPENSATORIO;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){

        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status){
                document.querySelector("#idCompensatorio").value = objData.data.ID_COMPENSATORIO;
                document.querySelector("#txtFechaInicio").value = objData.data.COM_FECHA_INICIO;
                document.querySelector("#txtFechaFin").value = objData.data.COM_FECHA_FIN;
                document.querySelector("#txtActividad").value = objData.data.ID_TIPO_COMPENSATORIO;
                document.querySelector("#txtTrabajoRequerido").value = objData.data.COM_USUARIO_FINAL;
                document.querySelector("#txtDescripcionActividad").value = objData.data.COM_DESCRIPCION_ACTIVIDAD;

                $('#modalFormCompensatorio').modal('show');
            }else{
                swal("Error", objData.msg, "error");
            }
        }
       
    }
}

function fntReserPass(idfuncionario){
    swal({
        title: "Reestablecer Contraseña",
        text: "¿Realmente quiere realizar el restablecimiento?",
        type: "info",
        showCancelButton: true,
        confirmButtonText: "Si, reestablecer!",
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm){
        if (isConfirm){
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Funcionarios/resetPassFuncionario';
            let strData = "idFuncionario="+idfuncionario;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status){
                        swal("Reestablecer Contraseña!", objData.msg , "success");
                        tableUsuarios.api().ajax.reload();
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }
    });
}

function fntDelFuncionario(idfuncionario,estado){
    swal({
        title: "Cambio de Estado",
        text: "¿Realmente quiere cambiar el estado del Funcionario?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, cambiar!",
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm){
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Funcionarios/statusFuncionario';
            let strData = "idFuncionario="+idfuncionario+"&status="+estado;

            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status){
                        swal("Cambio de estado!", objData.msg , "success");
                        tableUsuarios.api().ajax.reload();
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }
    });
}

function openModal(){
    rowTable = "";
    // document.querySelector("#listRolid").innerHTML=""; // Lista de rol
    document.querySelector('#idCompensatorio').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Enviar solicitud";
    document.querySelector('#titleModal').innerHTML = "Nuevo Compensatorio";
    document.querySelector("#formCompensatorio").reset();
    ajustarFormulario();
    // document.querySelector('#ListaUsuarios').remove("select");
    ftnTotalUsuarios();
    ftnTotalTipoCompensatorio();
    // fntRolesUsuario();
    

    // Reiniciar el valor seleccionado en el elemento <select>
    // document.querySelector("#ListaUsuarios").selectedIndex = -1;

    $('#modalFormCompensatorio').modal('show');
    // var selectElement = document.querySelector('#ListaUsuarios')
    // if (selectElement && selectElement.parentNode) {
    //     selectElement.parentNode.remove(selectElement);
    // }
}

// ftnEvidencias

function ftnEvidencias(ID_COMPENSATORIO) {
    // Abre el modal de subir evidencias
    $('#modalFormEvidencias').modal('show');
    document.querySelector("#formCargarEvidencias").reset();


// Al hacer clic en el botón de subir evidencia dentro del modal
document.getElementById("btnSubirEvidencia").addEventListener("click", function() {
    if (document.getElementById("archivoEvidencia").files[0]) {
        let fd = new FormData();
        let archivo = document.getElementById("archivoEvidencia").files[0];
        let nombreArchivo = archivo.name;
        let extension = nombreArchivo.split('.').pop().toLowerCase();
        let extensionesValidas = ["jpg","png","xlsx","docx","pdf"];

        if (extensionesValidas.includes(extension)) {
            fd.append("archivoEvidencia", archivo);
            fd.append("ID_COMPENSATORIO", ID_COMPENSATORIO);

            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Compensatorios/subirEvidencia'; // Reemplazar con la URL correcta
            request.open("POST", ajaxUrl, true);
            request.send(fd);
            request.onreadystatechange = function() {
                if (request.readyState == 4) { // Verifica que la solicitud esté en el estado 4 (completo)
                    if (request.status === 200) {
                        let data = JSON.parse(request.responseText);
                        // console.log("Volvio");
                        if (data.status){
                            swal("Evidencia: ", data.msg, "success");
                            $('#modalFormEvidencias').modal('hide');
                            document.getElementById("archivoEvidencia").value = "";
                        }else{
                            alert("Error al subir el archivo", data.msg, "error");
                        }
                    } else {
                        alert("Error en la solicitud: " + request.statusText);
                    }
                }
            };
            
        } else {
            swal("Extensión no válida", "El archivo contiene una extensión no permitida", "warning");
            document.getElementById("archivoEvidencia").value = "";
        }
    } else {
        swal("Seleccione un archivo para subir", "Ningun archivo seleccionado", "info");
    }
});

}

function ftnTotalUsuarios(){
   
    if(document.querySelector('#ListaUsuarios')){
        let ajaxUrl = base_url+'/Compensatorios/getSelectUsuarios';
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET",ajaxUrl,true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                document.querySelector('#ListaUsuarios').innerHTML = request.responseText;
                
                $('#ListaUsuarios').selectpicker('refresh');
                $('#ListaUsuarios').selectpicker('render');
            }
        }
    }
}

function ftnTotalTipoCompensatorio(){
   
    if(document.querySelector('#txtActividad')){
        let ajaxUrl = base_url+'/Compensatorios/getSelectTipoCompensatorio';
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET",ajaxUrl,true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                document.querySelector('#txtActividad').innerHTML = request.responseText;
                
                $('#txtActividad').selectpicker('refresh');
                $('#txtActividad').selectpicker('render');
            }
        }
    }
}

function ajustarFormulario() { 
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Compensatorios/verificarRol';
    request.open("GET", ajaxUrl, true);

    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            let esAdministrador = JSON.parse(request.responseText).esAdministrador;
            // let estadoDiv = document.querySelector(".form-group.col-md-6");
            
            if (esAdministrador == 2) {
                // estadoDiv.style.display = "none";
                $("#ListaUsuarios").closest(".form-group").css("display","none");
            }
        }
    }
    request.send();
}

window.onload = function() {
    ajustarFormulario();
};


// function ajustarFormulario() { 
//     let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
//     let ajaxUrl = base_url + '/Compensatorios/verificarRol';
//     request.open("GET", ajaxUrl, true);

//     request.onreadystatechange = function() {
//         if (request.readyState == 4 && request.status == 200) {
//             let esAdministrador = JSON.parse(request.responseText).esAdministrador;
//             // let estadoDiv = document.querySelector("#ListaUsuarios");
//             // console.log(esAdministrador);
//             // console.log(estadoDiv);
//             // console.log(request.responseText)
//             if (esAdministrador == 2) {
//                 // estadoDiv.style.display = "none";
//                 $("#ListaUsuarios").closest(".form-group").css("display","none");
//             }
//         }
//     }
//     request.send();
// }

// window.onload = function() {
//     ajustarFormulario();
// };


// function ajustarFormulario() { 
//     let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
//     let ajaxUrl = base_url + '/Compensatorios/verificarRol';
//     request.open("GET", ajaxUrl, true);

//     request.onreadystatechange = function() {
//         if (request.readyState == 4 && request.status == 200) {
//             let esAdministrador = JSON.parse(request.responseText).esAdministrador;
//             let inputUsuario = document.getElementById("ListaUsuarios");
//             let labelUsuario = document.getElementById("ListaUsuarios");

//             if (!esAdministrador) {
                
//                 inputUsuario.style.display = "none";
//                 labelUsuario.style.display = "none";

//                 // inputUsuario.remove(); // Remover el elemento
//                 // labelUsuario.remove(); // Remover el elemento
//             }
//         }
//     }
//     request.send();
// }

// window.onload = function() {
//     ajustarFormulario();
// };


// function ajustarFormulario() { 
//     let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
//     let ajaxUrl = base_url + '/Compensatorios/verificarRol';
//     request.open("GET", ajaxUrl, true);

//     request.onreadystatechange = function() {
//         if (request.readyState == 4 && request.status == 200) {
//             let esAdministrador = JSON.parse(request.responseText).esAdministrador;
//             let inputUsuario = document.getElementById("ListaUsuarios"); // Cambia "ListaUsuarios" a tu ID correcto
//             let labelUsuario = document.getElementById("ListaUsuarios"); // Cambia "ListaUsuarios" a tu ID correcto

//             if (!esAdministrador) {
//                 inputUsuario.style.display = "none";
//                 labelUsuario.style.display = "none";
//             }
//         }
//     }
//     request.send(); // Aquí realizamos la solicitud AJAX
// }

// window.onload = function() {
//     ajustarFormulario();
// };



// function ajustarFormulario() {
//     var request;
    
//     if (window.XMLHttpRequest) {
//         request = new XMLHttpRequest();
//     } else {
//         request = new ActiveXObject('Microsoft.XMLHTTP');
//     }
    
//     var ajaxUrl = base_url+'/Compensatorios/verificarRol';
    
//     request.open("GET", ajaxUrl, true);
    
//     request.onreadystatechange = function() {
//         if (request.readyState === 4 && request.status === 200) {
//             var esAdministrador = JSON.parse(request.responseText).esAdministrador;
//             var inputUsuario = document.getElementById("ListaUsuarios");
//             var labelUsuario = document.getElementById("ListaUsuarios");
            
//             if (!esAdministrador) {
//                 inputUsuario.style.display = "none";
//                 labelUsuario.style.display = "none";
//             }
//         }
//     };
    
//     request.send();
// }

// window.onload = function() {
//     ajustarFormulario();
// };

