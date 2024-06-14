document.addEventListener('DOMContentLoaded', function(){//se dispara cuando se ha cargado completamente el HTML de la página.
    // Obtén el elemento div por su ID
    //se mostrará el conteo de diferentes categorías,como usuarios, roles, categorías, productos, sedes e insumos
    /*
    const divElementUser = document.getElementById('totalUser');
    var request= (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/dashboard/getCount';

    request.open("GET",ajaxUrl,true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);//convertir los datos en formato JSON en un objeto JavaScript
        
            // Se actualiza el contenido de los elementos HTML utilizando la propiedad textContent
            //os valores obtenidos del objeto
            divElementUser.textContent = objData.TOTALUSUARIOS;
            //divElementRol.textContent = objData.TOTALROLES;
        }
    }
    //se envía la solicitud AJAX al servidor utilizando el método send del objeto
    request.send(); 
    */

    obtenerModulos();
    
}, false);


function obtenerModulos(){
    fetch(
          "dashboard/getModulos",
          {
            method:"POST",
          }
      ).then((response)=>
      response.json()
    ).then((data)=>{
        $.each(data, function(key, value) {
            var tarjeta=verTarjetaModulo(value.MOD_TITULO,value.MOD_ACCESO,value.MOD_ICONO);
            $(".listar_modulos").append(tarjeta); 
        });
    }).catch((err)=>{
      
    });
}

function verTarjetaModulo(titulo,acceso,icono){
    var plantilla=
    '<a class="col-xl-3 col-md-6 mb-4" id="card_'+acceso+'" href="'+base_url+'/'+acceso+'">'+
        '<div class="card border-left-primary shadow h-100 py-2">'+
            '<div class="card-body">'+
                '<div class="row no-gutters align-items-center">'+
                    '<div class="col mr-2">'+
                        '<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">'+titulo+'</div>'+
                        '<div class="h5 mb-0 font-weight-bold text-gray-800" id="total_'+acceso+'"></div>'+
                    '</div>'+
                    '<div class="col-auto">'+
                        '<i class="fa '+icono+' fa-2x text-gray-300"></i>'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</div>'+
    '</a>';
    return plantilla;
}