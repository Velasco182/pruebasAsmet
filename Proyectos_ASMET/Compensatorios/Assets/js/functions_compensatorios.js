let tableCompensatorios;
let rowTable = ""; 
let divLoading = document.querySelector("#divLoading");

document.addEventListener('DOMContentLoaded', function(){
    //Llamado a la función de configuración del datetimepicker
    ftnDateTimePickerConfiguration();
    //Definición y configuración del datatable.
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
            //{"data":"ID_TIPO_COMPENSATORIO"},
            {"data":"TIP_COM_NOMBRE"},
            {"data":"COM_USUARIO_FINAL"},
            {"data":"COM_DESCRIPCION_ACTIVIDAD"},
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
    //Instancia del formulario
    if(document.querySelector("#formCompensatorio")){
        //configuración del formulario
        let formUsuario = document.querySelector("#formCompensatorio");

        formUsuario.onsubmit = function(e) {
           
            e.preventDefault();
            
            let strFechaInicio = document.querySelector('#txtFechaInicio').value;
            let strFechaFin = document.querySelector('#txtFechaFin').value;
            let strDescripcionActividad = document.querySelector('#txtDescripcionActividad').value;
            let strActividad = document.querySelector('#txtActividad').value;
            let listadoUsuarios = document.querySelector('#listaUsuarios').value;
            let strTrabajoRequerido = document.querySelector('#txtTrabajoRequerido').value;
            let intEstado = document.querySelector('#txtEstado').value;

            /*let compensatorio = {strFechaInicio, strFechaFin, strDescripcionActividad, strActividad, listadoUsuarios, strTrabajoRequerido, intEstado};
            console.table(compensatorio);*/
    
            if(strFechaInicio == '' || strFechaFin == '' || strDescripcionActividad == '' || strActividad == '' || listadoUsuarios == '' || strTrabajoRequerido == '' || intEstado == ''){
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

                    if(objData.status === false){

                        swal("Error", objData.msg , "error");
                    
                    }else{
                        
                        console.log(objData.status);
                        if(rowTable == ""){
                            tableCompensatorios.api().ajax.reload();
                        }else{
                            tableCompensatorios.api().ajax.reload();
                        }

                        $('#modalFormCompensatorio').modal("hide");

                        formUsuario.reset();
                        swal("Usuario", objData.msg ,"success");
                        
                    }
                }
                divLoading.style.display = "none";
                return false;
            }
        }
    }
    
},false);
//función para la configuración de datetimepicker
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

        // Aplica la configuración modificada al datetimepicker
        $(inicio).datetimepicker(timePickerConfiguration);
        $(final).datetimepicker(finalTimePickerConfiguration);

        $(inicio).on("change.datetimepicker", function(e){
            $(final).datetimepicker('minDate', e.date);
        });
        
        $(final).on("change.datetimepicker", function(e){
            $(inicio).datetimepicker('maxDate', e.date);
        });

    });
};
//Función para aprobación del compensatorio
function ftnAprobarCompensatorio(idCompensatorio) { //Funcion para el boton de aprobacion
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
            let strData = "idCompensatorio=" + idCompensatorio;
            
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
//Función para rechazo del compensatorio
function ftnRechazarCompensatorio(idCompensatorio) { // Funcion para boton boton de rechazo
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
            let strData = "idCompensatorio=" + idCompensatorio;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        tableCompensatorios.api().ajax.reload();
                        swal("Compensatorio Rechazado", objData.msg, "success");
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
            }
        }
    });
}
//Función para ver compensatorio por id mostrando el modal
function ftnViewCompensatorio(idCompensatorio){
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Compensatorios/getCompensatorio/'+idCompensatorio;
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
                document.querySelector("#InfoTipoCompensatorio").innerHTML = objData.data.TIP_COM_NOMBRE;
                document.querySelector("#InfoEstado").innerHTML = estado;
                document.querySelector("#InfoHorasRealizadas").innerHTML = objData.data.horasrealizadas;
                
                if (objData.data.url_portada) {
                    document.querySelector("#DescargarSoporte").innerHTML = '<a href="' + objData.data.url_portada + '" target="_blank"><i class="fas fa-download"> Evidencia disponible</i></a>';
                } else {
                    // Si objData.data.url_portada está vacío, muestra un mensaje de "No hay evidencia disponible"
                    document.querySelector("#DescargarSoporte").innerHTML = 'No hay evidencia disponible';
                }
                
                $('#modalViewFuncionario').modal('show');

            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}
//Función para editar el compensatorio mostrando el modal
function ftnEditCompensatorio(element,idCompensatorio){

    ftnTotalUsuarios();
    ftnTotalTipoCompensatorio();
    
    rowTable = element.parentNode.parentNode.parentNode; 
    document.querySelector('#titleModal').innerHTML ="Actualizar compensatorio";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML ="Actualizar";

    //var idCompensatorio = idCompensatorio;
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Compensatorios/editCompensatorio/'+idCompensatorio;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){

        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            if(objData.status){

                document.querySelector("#idCompensatorio").value = objData.data.idCompensatorio;
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
//función para abrir el modal
function openModal(){
    rowTable = "";
    document.querySelector('#idCompensatorio').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Enviar solicitud";
    document.querySelector('#titleModal').innerHTML = "Nuevo Compensatorio";
    document.querySelector("#formCompensatorio").reset();
    ajustarFormulario();
    ftnTotalUsuarios();
    ftnTotalTipoCompensatorio();

    $('#modalFormCompensatorio').modal('show');
}
//Función para subir evidencias mostrando el modal
function ftnEvidencias(idCompensatorio) {
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
                fd.append("idCompensatorio", idCompensatorio);

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
//función para llenar el select de usuarios
function ftnTotalUsuarios(){
   
    if(document.querySelector('#listaUsuarios')){
        let ajaxUrl = base_url+'/Compensatorios/getSelectUsuarios';
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET",ajaxUrl,true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                document.querySelector('#listaUsuarios').innerHTML = request.responseText;
                
                $('#listaUsuarios').selectpicker('refresh');
                $('#listaUsuarios').selectpicker('render');
            }
        }
    }
}
//Función para llenar el select de tipo de compensatorios
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
//Ajustar formulario para la renderización o no de la lista de usuarios
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
                $("#listaUsuarios").closest(".form-group").css("display","none");
            }
        }
    }
    request.send();
}

window.onload = function() {
    ajustarFormulario();
};