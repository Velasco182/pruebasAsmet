let tableUsuarios;
let rowTable = ""; 
let divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function(){

    tableUsuarios = $('#tableFuncionarios').dataTable({
        "aProcessing":true,
        "aServerSide":true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax":{
            "url": " "+base_url+"/Funcionarios/getFuncionarios",
            "dataSrc":""
        },
        "columns":[
            {"data":"FUN_NOMBRES"},
            {"data":"FUN_APELLIDOS"},
            {"data":"FUN_USUARIO"},
            {"data":"FUN_CORREO"},
            {"data":"ROL_NOMBRE"},
            {"data":"FUN_ESTADO"},
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

    if(document.querySelector("#formFuncionario")){
        let formUsuario = document.querySelector("#formFuncionario");
        formUsuario.onsubmit = function(e) {
            e.preventDefault();
            //let strIdentificacion = document.querySelector('#txtIdentificacion').value;
            let strIdentificacion = '0';
            let strNombre = document.querySelector('#txtNombre').value;
            let strApellido = document.querySelector('#txtApellido').value;
            let strUsuario = document.querySelector('#txtUsuario').value;
            let strEmail = document.querySelector('#txtEmail').value;
            let intTipousuario = document.querySelector('#listRolid').value;
            let intStatus = document.querySelector('#listStatus').value;

            if(strIdentificacion == '' || strApellido == '' || strNombre == '' || strUsuario== '' || strEmail == ''
                || intTipousuario == '' || intStatus == ''){
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
            let ajaxUrl = base_url+'/Funcionarios/setFuncionario'; 
            let formData = new FormData(formUsuario);
            request.open("POST",ajaxUrl,true);
            request.send(formData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status){
                        if(rowTable == ""){
                            tableUsuarios.api().ajax.reload();
                        }else{
                            tableUsuarios.api().ajax.reload();
                        }
                        $('#modalFormFuncionario').modal("hide");
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

function fntViewFuncionario(idfuncionario){
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Funcionarios/getFuncionario/'+idfuncionario;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            if(objData.status){
               let estado = objData.data.FUN_ESTADO == 1 ? 
                '<span class="badge badge-success">Activo</span>' : 
                '<span class="badge badge-danger">Inactivo</span>';
                document.querySelector("#celNombre").innerHTML = objData.data.FUN_NOMBRES;
                document.querySelector("#celApellido").innerHTML = objData.data.FUN_APELLIDOS;
                document.querySelector("#celUsuario").innerHTML = objData.data.FUN_ACCESO;
                document.querySelector("#celEmail").innerHTML = objData.data.FUN_CORREO;
                document.querySelector("#celTipoUsuario").innerHTML = objData.data.ROL_NOMBRE;
                document.querySelector("#celEstado").innerHTML = estado;
                $('#modalViewFuncionario').modal('show');
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
                document.querySelector("#txtApellido").value = objData.data.FUN_APELLIDOS;
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

function openModal(){
    rowTable = "";
    document.querySelector("#listRolid").innerHTML="";
    document.querySelector('#idFuncionario').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Funcionario";
    document.querySelector("#formFuncionario").reset();
    fntRolesUsuario();
    $('#modalFormFuncionario').modal('show');
}