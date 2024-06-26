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
//Eliminar espacios adicionales en 'A. M.' o 'P. M.'
        $fecha_inicio = preg_replace('/\s+([AP]\.M\.)$/', '$1', $fecha_inicio);
        $fecha_final = preg_replace('/\s+([AP]\. M\.)$/', '$1', $fecha_final);
        // Eliminar cualquier carácter adicional de espacios al final de la cadena
        //$fecha_inicio = trim($fecha_inicio);
        //$fecha_final = trim($fecha_final);
        // Crear un objeto DateTime
        $fecha_inicio = new DateTime($fecha_inicio);
        $fecha_final = new DateTime($fecha_final);
        // Crear un objeto DateTime desde el formato especificado
        $inicio_compe = date_format($fecha_inicio, 'd/m/Y g:i A');
        $final_compe = date_format($fecha_final, 'd/m/Y g:i A');

        //$fechaInicio = date("Y-m-d H:i:s", strtotime($inicio_compe));
        //$fechaFinal = date("Y-m-d H:i:s", strtotime($final_compe));

        Hacer validación en back (php), no en js, en js toma la hora del computador

        06/11/2024 10:00 P. M.
        06/11/2024 11:00 P. M.
                                        5 horas de diferencia ??
        2024-11-06 15:00:00
        2024-11-06 16:00:00

        //Pruebas para parsear la fecha y hora

                //$fechaInicio = date_create_from_format('Y-m-d H:i:s',  strtotime($inicio_compe));
        //$fechaFinal = date_create_from_format('Y-m-d H:i:s', strtotime($final_compe));

        //$fechaInicio = DateTime::createFromFormat('Y-m-d H:i:s', $inicio_compe);
        //$fechaFinal = DateTime::createFromFormat('Y-m-d H:i:s', $final_compe);
        
        //echo "Fecha inicio $fechaInicio";
        //echo "Fecha final $fechaFinal";

        //Funcionó ok!
        Función checkdate

        Compensatrios al 70%, método put funcionando. No sirve el parseo, hay que seguir intentando. Sólo funciona en Inglés. Falta hacer pruebas y validar campos de fecha

            -- Compensatorios --

- Formulario para registro de horas |YA|
- Hora de inicio :: input html5 -> bootdate fecha -> boost fecha y hora |YA| 
- Hora de final |YA|
- Lista de funcionarios con un buscador (select2) jquery |(pero Sin select2)|YA|
- Validar que inicio sea menor que el final en el back (Php) |FALTA|

+ si el mismo usuario lo hace queda en espera de la validación |+/-|*
+ si un admin lo edita si lo puede aprobar |+/-|*

- descripcion: porque del compensatorio. |YA|
- aceptado o denegado |FALTA| (Validar estados) |AVANZADO|
- si está aceptado no se puede editar ni eliminar, sólo se puede modificar si es pendiente o rechazado

----Resultado para el admin----
columnas: 
+ Funcionario (1.Identificación - 2.Nombre)
+ 3.descripción
+ 4.fecha y hora de inicio
+ 5.fecha y hora de final
+ 6.diferencia horas (Horas compensatorias)(NO en DB)
+ 7.Validación (Aceptado o Denegado)

- lista horas compensatorias hecha desde el backend, no en la db. |YA|

¿El resultado se debe reflejar en una tabla a la cual sólo tiene acceso el admin?, lo que llamo Resultado!

Hacer un join con la tabla de colaboradores para no tener datos duplicados |YA|
Mejorar la nomenclatura de las columnas de las dos tablas, para identificar mejor |YA|
Hacer envío de datos después de aceptar, no antes, no solo mostrar la alerta y ya |YA|
hacer un select en la base de datos para comparar los campos de fecha y hora |YA|
cambiar el tipo de dato a datetime |YA|
(pediente rechazado y aceptado) estados para la validación del compensatorio -No chekbox- Select |YA|
Cuando cierre el modal clarear el formulario |YA|
Ordenamiento por la columna fecha dato numérico -no cadena- |YA|
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