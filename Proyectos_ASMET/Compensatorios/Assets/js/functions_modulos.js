var tableMenus;
var divLoading = document.querySelector("#divLoading");

document.addEventListener('DOMContentLoaded', function(){
	tableModulos = $('#tableModulos').dataTable( {
		"aProcessing":true,
		"aServerSide":true,
        "language": {
        	"url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax":{
            "url": " "+base_url+"/Modulos/getModulos",
            "dataSrc":""
        },
        "columns":[
            {"data":"MEN_TITULO"},
            {"data":"MOD_TITULO"},
            {"data":"MOD_CODIGO"},
            {"data":"MOD_ESTADO"},
            {"data":"MOD_ICONO"},
            {"data":"options"}
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



    var formModulos = document.querySelector("#formModulo");
    formModulos.onsubmit = function(e) {
        e.preventDefault();

        var intIdModulo = document.querySelector('#idModulo').value;
        
        var intIdMenu = document.querySelector('#listaMenus').value;
        var strNombre = document.querySelector('#txtNombre').value;
        var strDesc = document.querySelector('#txtDescripcion').value;
        var strCodigo = document.querySelector('#txtCodigo').value;
        var strIcono = document.querySelector('#txtIcono').value; 
        var strAcceso = document.querySelector('#txtAcceso').value; 
        var intListar = document.querySelector('#listaMostrar').value;
        var intEstado = document.querySelector('#listStatus').value;
        
        if(strNombre == '' || strDesc == '' || strCodigo == '' || strIcono == ''
            || strAcceso == '' || intListar == '' || intEstado==''){
            swal("Atención", "Todos los campos son obligatorios." , "error");
            return false;
        }
        divLoading.style.display = "flex";
        var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        var ajaxUrl = base_url+'/Modulos/setModulos'; 
        var formData = new FormData(formModulos);
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        request.onreadystatechange = function(){
           if(request.readyState == 4 && request.status == 200){
                var objData = JSON.parse(request.responseText);
                if(objData.status){
                    $('#modalFormModulo').modal("hide");
                    formModulos.reset();
                    swal("Modulos del sistema", objData.msg ,"success");
                    tableModulos.api().ajax.reload();
                }else{
                    swal("Error", objData.msg , "error");
                }              
            } 
            divLoading.style.display = "none";
            return false;
        }
    }
});

$('#tableMenus').DataTable();

function openModal(){
    document.querySelector('#idModulo').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Modulo";
    document.querySelector("#formModulo").reset();
    cargarMenus();
	$('#modalFormModulo').modal('show');
}

function fntEditModulo(ID_MODULO){
    document.querySelector('#titleModal').innerHTML ="Actualizar Modulo";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML ="Actualizar";

    var ID_MODULO = ID_MODULO;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl  = base_url+'/Modulos/getModulo/'+ID_MODULO;
    request.open("GET",ajaxUrl ,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status){
                document.querySelector('#idModulo').value=objData.data.ID_MODULO;
                document.querySelector("#listaMenus").innerHTML = objData.data.MENUS;
                document.querySelector("#listaMenus").value = objData.data.ID_MENU;
                document.querySelector("#txtNombre").value = objData.data.MOD_TITULO;
                document.querySelector("#txtDescripcion").value = objData.data.MOD_DESCRIPCION;
                document.querySelector("#txtCodigo").value = objData.data.MOD_CODIGO;
                document.querySelector("#txtIcono").value = objData.data.MOD_ICONO;
                document.querySelector("#txtAcceso").value = objData.data.MOD_ACCESO;
                document.querySelector("#listaMostrar").value = objData.data.MOD_LISTAR;
                document.querySelector("#listStatus").value = objData.data.MOD_ESTADO
                $('#modalFormModulo').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}

function fntViewModulo(ID_MODULO){
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Modulos/getModuloView/'+ID_MODULO;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            if(objData.status){
                document.querySelector("#celMenu").innerHTML = objData.data.MEN_TITULO;
                document.querySelector("#celTitulo").innerHTML = objData.data.MOD_TITULO;
                document.querySelector("#celDesc").innerHTML = objData.data.MOD_DESCRIPCION;
                document.querySelector("#celCodigo").innerHTML = objData.data.MOD_CODIGO;
                document.querySelector("#celIcono").innerHTML = objData.data.MOD_ICONO;
                document.querySelector("#celAcceso").innerHTML = objData.data.MOD_ACCESO;
                document.querySelector("#celListar").innerHTML = objData.data.MOD_LISTAR;
                document.querySelector("#celEstado").innerHTML = objData.data.MOD_ESTADO;
                
                $('#modalViewModulo').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}

function fntDelModulo(ID_MODULO){
    var ID_MODULO = ID_MODULO;
    swal({
        title: "Eliminar Modulo",
        text: "¿Realmente quiere eliminar el Modulo?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar!",
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    },function(isConfirm){
        if(isConfirm){
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url+'/Modulos/delModulo/';
            var strData = "ID_MODULO="+ID_MODULO;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    var objData = JSON.parse(request.responseText);
                    if(objData.status){
                        swal("Eliminar Modulo!", objData.msg , "success");
                        tableModulos.api().ajax.reload(function(){
                        });
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }
    });
}

function cargarMenus(){
    fetch(
        "Modulos/getMenus",{
            method:"POST",
        }
    ).then((response)=>
        response.json()
    ).then((data)=>{    
        $.each(data, function(key, value) {
            $("#listaMenus").append('<option value="' + value.ID_MENU + '">' + value.MEN_TITULO + '</option>');
        });
    }).catch((err)=>{
        //
    });
}
