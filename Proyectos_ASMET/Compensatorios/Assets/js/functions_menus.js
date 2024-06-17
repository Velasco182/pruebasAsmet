var tableMenus;
var divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function(){
	tableMenus = $('#tableMenus').dataTable( {
		"aProcessing":true,
		"aServerSide":true,
        "language": {
        	"url": "././Assets/js/spanish.json"
        },
        "ajax":{
            "url": " "+base_url+"/Menus/getMenus",
            "dataSrc":""
        },
        "columns":[
            {"data":"MEN_TITULO"},
            {"data":"MEN_CODIGO"},
            {"data":"MEN_ESTADO"},
            {"data":"MEN_ICONO"},
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

    var formMenu = document.querySelector("#formMenu");
    formMenu.onsubmit = function(e) {
        e.preventDefault();

        var intIdMenu = document.querySelector('#idMenu').value;
        var strNombre = document.querySelector('#txtNombre').value;
        var strCodigo = document.querySelector('#txtCodigo').value;
        var intEstado = document.querySelector('#listStatus').value;
        var strIcono = document.querySelector('#txtIcono').value; 
        if(strNombre == '' || strCodigo == '' || intEstado == '' || strIcono == ''){
            swal("Atención", "Todos los campos son obligatorios." , "error");
            return false;
        }
        divLoading.style.display = "flex";
        var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        var ajaxUrl = base_url+'/Menus/setMenu'; 
        var formData = new FormData(formMenu);
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        request.onreadystatechange = function(){
           if(request.readyState == 4 && request.status == 200){
                var objData = JSON.parse(request.responseText);
                if(objData.status){
                    $('#modalFormMenu').modal("hide");
                    formMenu.reset();
                    swal("Menus del sistema", objData.msg ,"success");
                    tableMenus.api().ajax.reload();
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
    document.querySelector('#idMenu').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Menu";
    document.querySelector("#formMenu").reset();
	$('#modalFormMenu').modal('show');
}

window.addEventListener('load', function() {
    /*fntEditRol();
    fntDelRol();
    fntPermisos();*/
}, false);

function fntEditMenu(ID_MENU){
    document.querySelector('#titleModal').innerHTML ="Actualizar Menu";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML ="Actualizar";

    var ID_MENU = ID_MENU;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl  = base_url+'/Menus/getMenu/'+ID_MENU;
    request.open("GET",ajaxUrl ,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status){
                document.querySelector("#idMenu").value = objData.data.ID_MENU;
                document.querySelector("#txtNombre").value = objData.data.MEN_TITULO;
                document.querySelector("#txtCodigo").value = objData.data.MEN_CODIGO;
                document.querySelector("#listStatus").value = objData.data.MEN_ESTADO;
                document.querySelector("#txtIcono").value = objData.data.MEN_ICONO;
                $('#modalFormMenu').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}

function fntDelMenu(ID_MENU){
    var ID_MENU = ID_MENU;
    swal({
        title: "Eliminar",
        text: "¿Realmente quiere eliminar el registro?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar!",
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm){
        if (isConfirm){
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url+'/Menus/delMenu/';
            var strData = "ID_MENU="+ID_MENU;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    var objData = JSON.parse(request.responseText);
                    if(objData.status){
                        swal("Eliminar!", objData.msg , "success");
                        tableMenus.api().ajax.reload(function(){
                        });
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }
    });
}