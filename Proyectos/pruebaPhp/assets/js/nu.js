var tableMenus;
var divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function(){
	tableMenus = $('#tableMenus').dataTable( {
		"aProcessing":true,
		"aServerSide":true,
        "language": {
        	"url": base_media+"/js/plugins/datatablesSpanish.json"
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
        "responsive":"true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[1,"asc"]],
        columnDefs:[{
            targets:[0,1,2,3,4],
            orderable: false 
        }],
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
                    notiAceptada("center","success","Menus",objData.msg);
                    tableMenus.api().ajax.reload();
                }else{
                    notiAceptada("center","error","Menus",objData.msg);
                }              
            } 
            divLoading.style.display = "none";
            return false;
        }
    }
});


/*
            -- Compensatorios --

- Formulario para registro de horas
- Hora de inicio :: input html5 -> bootdate fecha -> boost fecha y hora 
- Hora de final
- Lista de funcionarios con un buscador (select2) jquery
- Validar que inicio sea menor que el final en el back (Php)

+ si el mismo usuario lo hace queda en espera de la validación
+ si un admin lo edita si lo puede aprobar

- descripcion: porque del compensatorio.
- aceptado o denegado

----Resultado para el admin----
columnas: 
+ Funcionario
+ fecha y hora de inicio
+ fecha y hora de final
+ descripción
+ diferencia horas (Horas compensatorias)(NO en DB)
+ Validación (Aceptado o Denegado)

- lista horas compensatorias hecha desde el backend, no en la db.

¿El resultado se debe reflejar en una tabla a la cual sólo tiene acceso el admin?, lo que llamo Resultado!

*/



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

<button class="btn btn-primary btn-sm btnEditMenu" onclick="fntEditMenu(3)" title="Editar"><i class="fa fa-sm fa-pencil-alt" aria-hidden="true"></i></button>
sweetAlert


function fntEditMenu(ID_MENU){
    document.querySelector('#titleModal').innerHTML ="Actualizar Menu";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML ="Actualizar";

    var ID_MENU = ID_MENU;
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl  = base_url+'/Menus/getMenu/'+ID_MENU;
    request.open("GET",ajaxUrl ,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);
            if(objData.status){
                document.querySelector("#idMenu").value = objData.data.ID_MENU;
                document.querySelector("#txtNombre").value = objData.data.MEN_TITULO;
                document.querySelector("#txtCodigo").value = objData.data.MEN_CODIGO;
                document.querySelector("#listStatus").value = objData.data.MEN_ESTADO;
                document.querySelector("#txtIcono").value = objData.data.MEN_ICONO;
                $('#modalFormMenu').modal('show');
            }else{
                notiAceptada("center","error","Menus",objData.msg);
            }
        }
    }
}

function fntDelMenu(ID_MENU){
    var ID_MENU = ID_MENU;
    swal.fire({
        title: "Eliminar",
        text: "¿Realmente quiere eliminar el registro?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: 'Si, Eliminar',
        confirmButtonColor: '#3085d6',
        cancelButtonText: 'Cancelar',
        cancelButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
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
                        notiAceptada("center","success","Menus",objData.msg);
                        tableMenus.api().ajax.reload(function(){
                            //
                        });
                    }else{
                        notiAceptada("center","error","Menus",objData.msg);
                    }
                }
            }
        }
    });
}