let tableHoras;
let rowTable = ""; 
let divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function(){

    tableHoras = $('#tableHoras').dataTable({
        "aProcessing":true,
        "aServerSide":true,
        "language": {
            "url": "././Assets/js/spanish.json"
        },
        "ajax":{
            "url": " "+base_url+"/Horas/getHoras",
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
            
            // {"data":"TOM_HORAS_SOLI"},
            // {"data":"FUN_ESTADO"},
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
        "order":[[0,"asc"]]  
    });

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
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
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

function fntViewHora(ID_TOMA){
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Horas/getHora/'+ID_TOMA;
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
                document.querySelector("#celHorasTotales").innerHTML = objData.data.HORAS_TOTALES;
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

function fntEditFuncionario(element,idfuncionario){
    rowTable = element.parentNode.parentNode.parentNode; 
    document.querySelector("#listRolid").innerHTML="";
    document.querySelector('#titleModal').innerHTML ="Actualizar Funcionario";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML ="Actualizar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Funcionarios/getFuncionario/'+idfuncionario;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){

        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            if(objData.status){
                document.querySelector("#idFuncionario").value = objData.data.ID_FUNCIONARIO;
                document.querySelector("#txtIdentificacion").value = objData.data.FUN_IDENTIFICACION;
                document.querySelector("#txtNombre").value = objData.data.FUN_NOMBRES;
                document.querySelector("#txtApellido").value = objData.data.FUN_APELLLIDOS;
                document.querySelector("#txtUsuario").value = objData.data.FUN_USUARIO;
                document.querySelector("#txtEmail").value = objData.data.FUN_CORREO;
                document.querySelector("#listRolid").innerHTML = objData.data.ROLES;
                document.querySelector("#listRolid").value =objData.data.ID_ROL;
                $('#listRolid').val(objData.data.ID_ROL);
                $('#listRolid').selectpicker('refresh');
                $('#listRolid').selectpicker('render');

                if(objData.data.FUN_ESTADO == 1){
                    document.querySelector("#listStatus").value = 1;
                }else{
                    document.querySelector("#listStatus").value = 0;
                }
                $('#listStatus').selectpicker('render');
            }
        }
        $('#modalFormFuncionario').modal('show');
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

function ftnAprobar(ID_TOMA) { //Funcion para el boton de aprobacion
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
            let strData = "ID_TOMA=" + ID_TOMA;
            
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

function ftnRechazar(ID_TOMA) { // Funcion para boton boton de rechazo
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
            let ajaxUrl = base_url + '/Horas/RechazarSolicitud';
            let strData = "ID_TOMA=" + ID_TOMA;
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

function openModal(){
    rowTable = "";
    // document.querySelector("#listRolid").innerHTML="";
    document.querySelector('#idHora').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Enviar solicitud";
    document.querySelector('#titleModal').innerHTML = "Solicitu de horas";
    document.querySelector("#formHora").reset();
    fntRolesUsuario();
    $('#modalFormHora').modal('show');
}