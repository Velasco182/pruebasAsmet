let tableTipoCompensatorios;
let rowTable = ""; 
let divLoading = document.querySelector("#divLoading");

document.addEventListener('DOMContentLoaded', function(){
    //Definición y configuración del datatable.
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
    //Instancia del formulario
    if(document.querySelector("#formTipoCompensatorio")){
        let formTipoCompensatorio = document.querySelector("#formTipoCompensatorio");
        //configuración del formulario
        formTipoCompensatorio.onsubmit = function(e) {
           
            e.preventDefault();
            
            let strNombreTipoCompensatorio = document.querySelector('#txtNombreTipoCompensatorio').value;
            let strDescripcionTipoCompensatorio = document.querySelector('#txtDescripcionTipoCompensatorio').value;
            let strEstadoTipoCompensatorio = document.querySelector('#txtEstadoTipoCompensatorio').value;
    
            if(strNombreTipoCompensatorio == '' || strDescripcionTipoCompensatorio == '' || strEstadoTipoCompensatorio == ''){
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
            //Registrar datos, ya sea crear o actualizar
            divLoading.style.display = "flex";
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Tipocompensatorios/setTipoCompensatorio'; 
            let formData = new FormData(formTipoCompensatorio);
            
            request.open("POST",ajaxUrl,true);
            request.send(formData);
            
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);

                    if(objData.status === false){

                        swal("Tipo de Compensatorio", objData.msg , "error");

                    }else {
                        
                        if(rowTable == ""){
                            tableTipoCompensatorios.api().ajax.reload();
                        }else{
                            tableTipoCompensatorios.api().ajax.reload();
                        }

                        $('#txtEstadoTipoCompensatorio').selectpicker('refresh');
                        $('#txtEstadoTipoCompensatorio').selectpicker('render');

                        $('#modalFormTipocompensatorios').modal("hide");
                        formTipoCompensatorio.reset();
                        swal("Tipo de Compensatorio", objData.msg ,"success");

                    }
                }
                divLoading.style.display = "none";
                return false;
            }
        }
    }
},false);
//Función para obtener el tipo compensatorio por id 
function fntViewTipoCompensatorio(idTipoCompensatorio){
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Tipocompensatorios/getTipoCompensatorio/'+idTipoCompensatorio;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            if(objData.status){
                let estado = objData.data.TIP_COM_ESTADO == 1 ? 
                '<span class="badge badge-success">Activo</span>' :
                '<span class="badge badge-danger">Inactivo</span>';

                document.querySelector("#InfoNombre").innerHTML = objData.data.TIP_COM_NOMBRE;
                document.querySelector("#InfoDescripcion").innerHTML = objData.data.TIP_COM_DESCRIPCION;
                document.querySelector("#InfoEstado").innerHTML = estado;
                
                $('#modalViewTipoCompensatorio').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}
//Función para editar el tipo de compensatorio mostrando el modal
function ftnEditTipoCompensatorio(element, idTipoCompensatorio){

    //ftnTotalUsuarios();    
    
    rowTable = element.parentNode.parentNode.parentNode; 
    // document.querySelector("#listRolid").innerHTML="";
    document.querySelector('#titleModal').innerHTML ="Actualizar Tipo de Compensatorio";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML ="Actualizar";

    //let ID_TIPO_COMPENSATORIO = idTipoCompensatorio;

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Tipocompensatorios/editTipoCompensatorio/'+idTipoCompensatorio;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){

        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            if(objData.status){

                document.querySelector("#idTipoCompensatorio").value = objData.data.ID_TIPO_COMPENSATORIO;
                document.querySelector("#txtNombreTipoCompensatorio").value = objData.data.TIP_COM_NOMBRE;
                document.querySelector("#txtDescripcionTipoCompensatorio").value = objData.data.TIP_COM_DESCRIPCION;
                document.querySelector("#txtEstadoTipoCompensatorio").value = objData.data.TIP_COM_ESTADO;

                $('#txtEstadoTipoCompensatorio').selectpicker('refresh');
                $('#txtEstadoTipoCompensatorio').selectpicker('render');

                $('#modalFormTipocompensatorios').modal('show');
            }else{
                swal("Error", objData.msg, "error");
            }
        }
       
    }
}
//Función para eliminar el tipo de compensatorio
function ftnDeleteTipoCompensatorio(idTipoCompensatorio){
    //Porque hacer una variable con el mismo nombre si se puede pasar directamente??
    //let idTipoCompensatorio
    swal({
        title: "Eliminando el Tipo de Compensatorio",
        text: "¿Desea continuar?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar!",
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm){

        if(isConfirm){
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Tipocompensatorios/delTipoCompensatorio/';
            let strData = 'ID_TIPO_COMPENSATORIO='+idTipoCompensatorio;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status){
                        swal("Tipo de Compensatorio", objData.msg, "success");
                        tableTipoCompensatorios.api().ajax.reload();
                    }else{
                        swal("Atención!", objData.msg, "error");
                    }
                }
            }
        }

    });
}
//Función para abrir el modal
function openModal(){
    rowTable = "";
    // document.querySelector("#listRolid").innerHTML=""; // Lista de rol
    document.querySelector('#idTipoCompensatorio').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Tipo de Compensatorio";
    document.querySelector("#formTipoCompensatorio").reset();
    //ajustarFormulario();
    // Reiniciar el valor seleccionado en el elemento <select>
    // document.querySelector("#ListaUsuarios").selectedIndex = -1;
    $('#txtEstadoTipoCompensatorio').selectedIndex = -1;
    $('#txtEstadoTipoCompensatorio').selectpicker('refresh');
    $('#txtEstadoTipoCompensatorio').selectpicker('render');

    $('#modalFormTipocompensatorios').modal('show');
}

