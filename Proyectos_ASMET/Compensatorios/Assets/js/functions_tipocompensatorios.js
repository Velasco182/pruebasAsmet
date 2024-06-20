let tableTipoCompensatorios;
let rowTable = ""; 
let divLoading = document.querySelector("#divLoading");

document.addEventListener('DOMContentLoaded', function(){

    tableTipoCompensatorios = $('#tableTipoCompensatorios').dataTable({
        "aProcessing":true,
        "aServerSide":true,
        "language": {
            "url": "././Assets/js/spanish.json"
        },
        "ajax":{
            "url": " "+base_url+"/Tipocompensatorios/getTipoCompensatorios",
            "dataSrc":""
        },
        "columns":[
            {"data":"TIP_COM_NOMBRE"},
            {"data":"TIP_COM_DESCRIPCION"},
            {"data":"TIP_COM_ESTADO"},
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
        "columnDefs": [
            {
                "targets": [0, 1, 2, 3],
                "orderable": false,
                "className": "text-center",
            }], 
    });

    if(document.querySelector("#formTipoCompensatorio")){
        let formUsuario = document.querySelector("#formTipoCompensatorio");
        formUsuario.onsubmit = function(e) {
           
            e.preventDefault();
            
            let strNombreTipoCompensatorio = document.querySelector('#txtNombreTipoCompensatorio').value;
            let strDescripcionTipoCompensatorio = document.querySelector('#txtDescripcionTipoCompensatorio').value;
    
            if(strNombreTipoCompensatorio == '' || strDescripcionTipoCompensatorio == ''){
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
            let ajaxUrl = base_url+'/Tipocompensatorios/setTipoCompensatorio'; 
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
                        $('#modalFormTipocompensatorios').modal("hide");
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

function ftnAprobarTipoCompensatorio(ID_COMPENSATORIO) { //Funcion para el boton de aprobacion
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

function ftnRechazarTipoCompensatorio(ID_COMPENSATORIO) { // Funcion para boton boton de rechazo
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
                document.querySelector("#InfoDescripcion").innerHTML = objData.data.COM_ACTIVIDAD_DESARROLLAR;
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

function btnEditTipoCompensatorio(element,ID_COMPENSATORIO){

    ftnTotalUsuarios();    
    
    rowTable = element.parentNode.parentNode.parentNode; 
    // document.querySelector("#listRolid").innerHTML="";
    document.querySelector('#titleModal').innerHTML ="Actualizar Tipo de Compensatorio";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML ="Actualizar";

    var ID_COMPENSATORIO = ID_COMPENSATORIO;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Tipocompensatorios/editTipoCompensatorio/'+ID_COMPENSATORIO;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){

        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status){
                document.querySelector("#idTipoCompensatorio").value = objData.data.ID_TIPO_COMPENSATORIO;
                document.querySelector("#txtNombreTipoCompensatorio").value = objData.data.TIP_COM_NOMBRE;
                document.querySelector("#txtDescripcionTipoCompensatorio").value = objData.data.TIP_COM_DESCRIPCION;
                document.querySelector("#txtEstadoTipoCompensatorio").value = objData.data.TIP_COM_ESTADO;

                $('#modalFormTipoCompensatorios').modal('show');
            }else{
                swal("Error", objData.msg, "error");
            }
        }
       
    }
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
    document.querySelector('#idTipoCompensatorio').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Tipo de Compensatorio";
    document.querySelector("#formTipoCompensatorio").reset();
    ajustarFormulario();
    // Reiniciar el valor seleccionado en el elemento <select>
    // document.querySelector("#ListaUsuarios").selectedIndex = -1;

    $('#modalFormTipocompensatorios').modal('show');
}

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

///Revisar para hacer el get al option list
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
