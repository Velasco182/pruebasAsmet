$('.login-content [data-toggle="flip"]').click(function() {//utilizando jQuery para agregar un evento de clic a un elemento
	$('.login-box').toggleClass('flipped');
	return false;
});
//validacion del formulario
var divLoading = document.querySelector("#divLoading");//Se obtiene una referencia al elemento HTML
document.addEventListener('DOMContentLoaded', function(){//ejecutará la función proporcionada
	if(document.querySelector("#formLogin")){//si existe un elemento con el id "formLogin"
		let formLogin = document.querySelector("#formLogin");//ejecuta el código
		formLogin.onsubmit = function(e) {
			e.preventDefault();

			let strEmail = document.querySelector('#txtEmail').value;//obtencionDeDatos
			let strPassword = document.querySelector('#txtPassword').value;

			if(strEmail == "" || strPassword == "")//validacion campos vacios
			{
				swal("Por favor", "Escribe usuario y contraseña.", "error");
				return false;
			}else{
				divLoading.style.display = "flex";
				var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');//intancia para manejar distintas versiones del navehador
				var ajaxUrl = base_url+'/Login/loginUser'; //define Url solicitud del Ajax
				var formData = new FormData(formLogin);//se envia datos al servidor
				request.open("POST",ajaxUrl,true);
				request.send(formData);
				request.onreadystatechange = function(){
					if(request.readyState != 4) return;//estado de solicitud si no es 4 se sale de la funcion
					if(request.status == 200){//estado 200(exito), almacena en abjData
						var objData = JSON.parse(request.responseText);
						if(objData.status)//es Ver, se diriege a DASBOARD
						{
							window.location = base_url+'/dashboard';
						}else{//SI NO ES 200, GENERAR error
							swal("Atención", objData.msg, "error");
							document.querySelector('#txtPassword').value = "";
						}
					}else{
						swal("Atención","Error en el proceso", "error");
					}
					divLoading.style.display = "none";
					return false;
				}
			}
		}
	}

	if(document.querySelector("#formRecetPass")){	
		let formRecetPass = document.querySelector("#formRecetPass");
		formRecetPass.onsubmit = function(e) {
			e.preventDefault();

			let strEmail = document.querySelector('#txtEmailReset').value;
			if(strEmail == "")
			{
				swal("Por favor", "Escribe tu correo electrónico.", "error");
				return false;
			}else{
				divLoading.style.display = "flex";
				var request = (window.XMLHttpRequest) ? 
								new XMLHttpRequest() : 
								new ActiveXObject('Microsoft.XMLHTTP');
								
				var ajaxUrl = base_url+'/Login/resetPass'; 
				var formData = new FormData(formRecetPass);
				request.open("POST",ajaxUrl,true);
				request.send(formData);
				request.onreadystatechange = function(){
					if(request.readyState != 4) return;

					if(request.status == 200){
						var objData = JSON.parse(request.responseText);
						if(objData.status)
						{
							swal({
								title: "",
								text: objData.msg,
								type: "success",
								confirmButtonText: "Aceptar",
								closeOnConfirm: false,
							}, function(isConfirm) {
								if (isConfirm) {
									window.location = base_url;
								}
							});
						}else{
							swal("Atención", objData.msg, "error");
						}
					}else{
						swal("Atención","Error en el proceso", "error");
					}
					divLoading.style.display = "none";
					return false;
				}	
			}
		}
	}

	if(document.querySelector("#formCambiarPass")){
		let formCambiarPass = document.querySelector("#formCambiarPass");
		formCambiarPass.onsubmit = function(e) {
			e.preventDefault();

			let strPassword = document.querySelector('#txtPassword').value;
			let strPasswordConfirm = document.querySelector('#txtPasswordConfirm').value;
			let idUsuario = document.querySelector('#idUsuario').value;

			if(strPassword == "" || strPasswordConfirm == ""){
				swal("Por favor", "Escribe la nueva contraseña." , "error");
				return false;
			}else{
				if(strPassword.length < 5 ){
					swal("Atención", "La contraseña debe tener un mínimo de 5 caracteres." , "info");
					return false;
				}
				if(strPassword != strPasswordConfirm){
					swal("Atención", "Las contraseñas no son iguales." , "error");
					return false;
				}
				divLoading.style.display = "flex";
				var request = (window.XMLHttpRequest) ? 
							new XMLHttpRequest() : 
							new ActiveXObject('Microsoft.XMLHTTP');
				var ajaxUrl = base_url+'/Login/setPassword'; 
				var formData = new FormData(formCambiarPass);
				request.open("POST",ajaxUrl,true);
				request.send(formData);
				request.onreadystatechange = function(){
					if(request.readyState != 4) return;
					if(request.status == 200){
						var objData = JSON.parse(request.responseText);
						if(objData.status)
						{
							swal({
								title: "",
								text: objData.msg,
								type: "success",
								confirmButtonText: "Iniciar sessión",
								closeOnConfirm: false,
							}, function(isConfirm) {
								if (isConfirm) {
									window.location = base_url+'/login';
								}
							});
						}else{
							swal("Atención",objData.msg, "error");
						}
					}else{
						swal("Atención","Error en el proceso", "error");
					}
					divLoading.style.display = "none";
				}
			}
		}
	}

}, false);