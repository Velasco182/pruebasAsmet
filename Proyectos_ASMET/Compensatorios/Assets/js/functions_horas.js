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
            //{"data":"TOM_MOTIVO"},
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
                "titleAttr":"Exportar a Excel",
                "className": "btn btn-success"
            },{
                "extend": "pdfHtml5",
                "text": "<i class='fas fa-file-pdf'></i> PDF",
                "titleAttr":"Exportar a PDF",
                "className": "btn btn-danger"
            },{
                "extend": "csvHtml5",
                "text": "<i class='fas fa-file-csv'></i> CSV",
                "titleAttr":"Exportar a CSV",
                "className": "btn btn-info"
            }
        ],
        "resonsieve":"true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [], // Desactiva el ordenamiento por defecto
        "columnDefs": [
            {
                "targets": [0, 1, 2, 3, 4, 5, 6],
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

            let listadoUsuarios = document.querySelector('#listaUsuarios').value;

            if(strMotivo == '' || intEstado == '' || strFecha == '' || strHoras == '' || listadoUsuarios == ''){
                
                swal("Atención", "Todos los campos son obligatorios." , "error");
                return false;
           
            }else{

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

                let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                let ajaxUrl = base_url+'/Horas/setHora'; 
                let formData = new FormData(formUsuario);

                request.open("POST", ajaxUrl, true);
                request.send(formData);

                request.onreadystatechange = function(){
                    let objData; 
                    if(request.readyState == 4 && request.status == 200){
                        objData = JSON.parse(request.responseText);                  
                        
                        if(objData.status){
                            //Agregar donde sea necesario
                            divLoading.style.display = "flex";

                            if(rowTable == ""){
                                tableHoras.api().ajax.reload();
                            }else{
                                tableHoras.api().ajax.reload();
                            }

                            $('#modalFormHora').modal("hide");
                            
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
            minDate: moment(), // Establecer la fecha mínima seleccionable como la fecha y hora actual
            daysOfWeekDisabled: [0, 6],
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
                document.querySelector("#celCorreo").innerHTML = objData.data.FUN_CORREO;
                document.querySelector("#celMotivo").innerHTML = objData.data.TOM_MOTIVO;
                document.querySelector("#celFecha").innerHTML =  objData.data.TOM_FECHA_SOLI;
                document.querySelector("#celHoras").innerHTML = parseFloat(objData.data.TOM_HORAS_SOLI);
                document.querySelector("#celEstado").innerHTML = estado;
                $('#modalViewHora').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}
//Función que retorna una promesa para llenar horas disponibles en el modal de solicitar horas
function fntViewHorasDisponibles(){
        
    document.querySelector('#idToma').value ="";
    document.querySelector("#formHora").reset();
    $("#usersDiv").closest(".form-row").css("display","flex");
    ftnTotalUsuarios();

    return new Promise((resolve, reject) => {
    
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'/Horas/obtenerHorasDisponiblesSinId';
        request.open("GET",ajaxUrl,true);
        request.send();
        request.onreadystatechange = function(){

            if(request.readyState == 4 && request.status == 200){
                let objData = JSON.parse(request.responseText);

                let response = objData.msg;
                
                if(objData.status){
                    document.querySelector("#txtDisponibles").innerHTML = `<h5>${response}</h5>`;
                    openModal();
                    resolve(true);
                    //swal(disponibles, mensaje, "warning");
                }else{
                    document.querySelector("#txtDisponibles").innerHTML = `<h5>${response}</h5>`;
                    swal({
                        title: response,
                        text: "",
                        type: "error",
                        confirmButtonText: "OK",
                        closeOnConfirm: true,
                    }, function(isConfirm){

                        if(isConfirm){
                            $('#modalFormHora').modal('hide');
                            document.querySelector("#txtDisponibles").innerHTML = "";
                        }
                    });
                    resolve(false);
                }
            }

        }    
        
        var elemento = document.querySelector("#listaUsuarios").closest(".form-group");
        if (window.getComputedStyle(elemento).display !== "none") {
                            
            document.getElementById('listaUsuarios').addEventListener('change', function() {
            
                let userId = parseInt(this.value);
                    
                let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                let ajaxUrl = base_url+'/Horas/obtenerHorasDisponibles/'+userId;//
                request.open("GET",ajaxUrl,true);
                request.send();
                request.onreadystatechange = function(){    
                    if(request.readyState == 4 && request.status == 200){
                        let objData = JSON.parse(request.responseText);
        
                        let response = objData.msg;
                        
                        if(objData.status){

                            divLoading.style.display = "flex"; // Mostrar el div de carga

                            document.querySelector("#txtDisponibles").innerHTML = `<h5>${response}</h5>`;
                            openModal();
                            resolve(true);
                            //swal(disponibles, mensaje, "warning");
                        }else{
                            document.querySelector("#txtDisponibles").innerHTML = `<h5>${response}</h5>`;
                            swal({
                                title: response,
                                text: "",
                                type: "error",
                                confirmButtonText: "OK",
                                closeOnConfirm: true,
                            }, function(isConfirm){

                                if(isConfirm){
                                    $('#modalFormHora').modal('hide');
                                    document.querySelector("#txtDisponibles").innerHTML = "";
                                }
                            });
                            resolve(false);
                        }
                    }
                    divLoading.style.display = "none"; // Mostrar el div de carga
                }
                    
            });
        }
        // Resuelve la promesa aquí para asegurarte de que el código no se detenga
        resolve(true);
    }); 
}
//Función asíncrona para actualizar el registro de horas
async function fntEditToma(element, idToma){

    //let usuarios = ftnTotalUsuarios();
    let horasDisponibles = await fntViewHorasDisponibles();

    if (horasDisponibles) {
        divLoading.style.display = "flex"; // Mostrar el div de carga
    }

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Horas/editHora/'+idToma;
    request.open("GET",ajaxUrl,true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send();
    request.onreadystatechange = function(){

        if(request.readyState == 4 && request.status == 200){

            let objData = JSON.parse(request.responseText);

            if(objData.status){

                rowTable = element.parentNode.parentNode.parentNode; 
                document.querySelector('#titleModal').innerHTML ="Actualizar solicitud de horas";
                document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
                document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
                document.querySelector('#btnText').innerHTML = "Actualizar";

                document.querySelector("#idToma").value = objData.data.ID_TOMA;
                document.querySelector("#txtMotivo").value = objData.data.TOM_MOTIVO;
                document.querySelector("#txtFecha").value = objData.data.TOM_FECHA_SOLI;
                document.querySelector("#txtHoras").value = parseFloat(objData.data.TOM_HORAS_SOLI);
                
                $("#usersDiv").closest(".form-row").css("display","none");

                if(!horasDisponibles){   
                    $('#modalFormHora').modal('show');
                }

            }else{
                swal("Error", objData.msg, "error");
            }
        }
        divLoading.style.display = "none"; // Ocultar el div de carga
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
            divLoading.style.display = "flex"; // Mostrar el div de carga
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Horas/rechazarSolicitud';
            let strData = "ID_TOMA="+idToma;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState === 4 && request.status === 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        tableHoras.api().ajax.reload();
                        swal("Solicitud rechazada", objData.msg, "success");
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                    divLoading.style.display = "none"; // Ocultar el div de carga
                }
            }
        }
    });
}
//función para llenar el select de usuarios
function ftnTotalUsuarios(){

    return new Promise((resolve, reject) => {

        if(document.querySelector('#listaUsuarios')){
            let ajaxUrl = base_url+'/Horas/getSelectUsuarios';
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            request.open("GET", ajaxUrl, true);
            request.send();
            request.onreadystatechange = function(){
    
                if(request.readyState == 4 && request.status == 200){
                    
                    resolve(true);
    
                    document.querySelector('#listaUsuarios').innerHTML = request.responseText;
                    
                    $('#listaUsuarios').selectpicker('refresh');
                    $('#listaUsuarios').selectpicker('render');
    
                }else{
                    resolve(false);
                }
                
            }
        }

    });
   
}
//Función para verificar tipo de usuario y así mismo permisos
function ajustarFormulario() {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Horas/verificarRol';
    request.open("GET", ajaxUrl, true);

    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            let rol = JSON.parse(request.responseText).Rol;
            
            if (rol === '2'){
                $("#listaUsuarios").closest(".form-group").css("display","none");
            }
        }
    }

    request.send();
}
//Función para abrir modal
function openModal(){
    
    rowTable = "";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Enviar solicitud";
    document.querySelector('#titleModal').innerHTML = "Solicitud de horas";
    //document.querySelector("#formHora").reset();

    //fntRolesUsuario();
    //ftnTotalUsuarios();
    //await fntViewHorasDisponibles();
    ajustarFormulario();

    $('#modalFormHora').modal('show');
}
//Ajustar formulario al cargar la página
window.onload = function() {
    ajustarFormulario();
};
