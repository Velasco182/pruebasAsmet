let tableHoras;
let rowTable = ""; 
let divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function(){
    //Llamado a la función de configuración del datetimepicker
    fntDatePickerConfiguration();
    //Configuración y recuperación de datos para el datatable
    tableHoras = $('#tableHoras').dataTable({
        "aProcessing":true,
        "aServerSide":true,
        "language": {
            "url": "././Assets/js/spanish.json"
        },
        "ajax":{
            "url": " "+base_url+"/Horas/obtenerHoras",
            "dataSrc":""
        },
        "columns":[
            {"data":"FUN_NOMBRES"},
            {"data":"FUN_APELLIDOS"},
            {"data":"FUN_CORREO"},
            {"data":"TOM_MOTIVO"},
            {"data":"TOM_FECHA_SOLI"},
            {"data":"TOM_HORAS_SOLI"},
            {"data":"TOM_ESTADO"},
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
        "order":[[4,"desc"]],
        "columnDefs": [
            {
                "targets": [0, 1, 2, 3, 4, 5, 6, 7],
                "orderable": false,
                "className": "text-center",
            }],  
    });
    //Instancia del formulario de creación de horas
    if(document.querySelector("#formHora")){
        
        let formUsuario = document.querySelector("#formHora");

        formUsuario.onsubmit = function(e) {
            e.preventDefault();
            
            let strMotivo = document.querySelector('#txtMotivo').value;
            let intEstado = document.querySelector('#txtEstado').value;
            let strFecha = document.querySelector('#txtFecha').value;
            let strHoras = document.querySelector('#txtHoras').value;

            if(strMotivo == '' || intEstado == '' || strFecha == '' || strHoras == ''){
                swal("Atención", "Todos los campos son obligatorios." , "error");
                return false;
            }

            let obj = {
                strMotivo,
                intEstado,
                strFecha,
                strHoras
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
            let ajaxUrl = base_url+'/Horas/setHora'; 
            let formData = new FormData(formUsuario);
            request.open("POST",ajaxUrl,true);
            request.send(formData);
            request.onreadystatechange = function(){
                let objData; 
                if(request.readyState == 4 && request.status == 200){
                    objData = JSON.parse(request.responseText);

                    if(objData.status){
                        if(rowTable == ""){
                            tableHoras.api().ajax.reload();
                        }else{
                            tableHoras.api().ajax.reload();
                        }
                        $('#modalFormHora').modal("hide");
                        formUsuario.reset();
                        swal("Usuarios", objData.msg ,"success");
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

//Función de configuración para el datepicker
function fntDatePickerConfiguration(){

    let picker = document.querySelector('#datetimepicker');

    $(document).ready(function() {

        let timePickerConfiguration = {
        
            format: "DD/MM/yyyy",
            format: 'L',
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
            },
            useCurrent: true,   

        };

        $(picker).datetimepicker(timePickerConfiguration);

    });

}
//Función para verificar roles
function fntRolesUsuario(){
    if(document.querySelector('#listRolid')){
        let ajaxUrl = base_url+'/Roles/getSelectRoles';
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET",ajaxUrl,true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                document.querySelector('#listRolid').innerHTML = request.responseText;
                $('#listRolid').selectpicker('refresh');
                $('#listRolid').selectpicker('render');
            }
        }
    }
}
//Función para llenar el modal de ver con la información del usuario
function fntViewHora(idToma){
    
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Horas/getHora/'+idToma;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            if(objData.status){
                let estado = objData.data.TOM_ESTADO == 1 ? 
                '<span class="badge badge-warning">Pendiente</span>' : 
                (objData.data.TOM_ESTADO == 2 ? '<span class="badge badge-success">Aprobado</span>' :
                '<span class="badge badge-danger">Rechazado</span>');

                document.querySelector("#celNombres").innerHTML = objData.data.FUN_NOMBRES;
                document.querySelector("#celApellidos").innerHTML = objData.data.FUN_APELLIDOS;
                //document.querySelector("#celHorasTotales").innerHTML = objData.data.DIFERENCIA_HORAS;
                document.querySelector("#celCorreo").innerHTML = objData.data.FUN_CORREO;
                document.querySelector("#celMotivo").innerHTML = objData.data.TOM_MOTIVO;
                document.querySelector("#celFecha").innerHTML = objData.data.TOM_FECHA_SOLI;
                document.querySelector("#celHoras").innerHTML = objData.data.TOM_HORAS_SOLI;
                document.querySelector("#celEstado").innerHTML = estado;
                $('#modalViewHora').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}
//Función para llenar horas disponibles en el modal de solicitar horas
function fntViewHorasDisponibles(){
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Horas/obtenerHorasDisponibles';
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            let response = objData.msg;
            let arrResponse = response.split(',');

            let disponibles = arrResponse[0];
            let mensaje = arrResponse[1];

            if(objData.status){
                document.querySelector("#txtDisponibles").innerHTML = `<h5>${disponibles}</h5>${mensaje}`;
                //swal(disponibles, mensaje, "warning");
            }else{
                swal({
                    title: disponibles,
                    text: "",
                    type: "error",
                    confirmButtonText: "OK",
                    closeOnConfirm: true,
                }, function(isConfirm){
            
                    if(isConfirm){

                        $('#modalFormHora').modal('hide');

                    }
                });
            }
        }
    }
}
//Función para el boton de aprobacion
function fntAprobar(idToma) { 
    swal({
        title: "Aprobar la solicitud",
        text: "¿Realmente quieres aprobar esta solicitud?",
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
            let ajaxUrl = base_url + '/Horas/aprobarSolicitud';
            let strData = "ID_TOMA="+idToma;
            
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            
            request.onreadystatechange = function () {
                if (request.readyState === 4 && request.status === 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        tableHoras.api().ajax.reload();
                        swal("La solicitud fue aprobada", objData.msg, "success");
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
                divLoading.style.display = "none"; // Ocultar el div de carga
            };
        }
    });
}
//Función para boton boton de rechazo
function fntRechazar(idToma) { 
    swal({
        title: "Rechazar esta solicitud",
        text: "¿Realmente quieres rechazar esta solicitud?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, rechazar",
        cancelButtonText: "Cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Horas/rechazarSolicitud';
            let strData = "ID_TOMA="+idToma;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        tableHoras.api().ajax.reload();
                        swal("Solicitud rechazada", objData.msg, "error");
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
            }
        }
    });
}
//Función para abrir modal
function openModal(){
    rowTable = "";
    // document.querySelector("#listRolid").innerHTML="";
    document.querySelector('#idHora').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Enviar solicitud";
    document.querySelector('#titleModal').innerHTML = "Solicitud de horas";
    document.querySelector("#formHora").reset();

    fntRolesUsuario();
    fntViewHorasDisponibles();
    
    $('#modalFormHora').modal('show');
/**/
}