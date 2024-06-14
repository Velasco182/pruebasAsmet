// permite controlar la entrada de teclado en un campo de texto permitiendo solo
// dígitos del 0 al 9 y espacios en blanco, pero también permite el uso de 
//la tecla de retroceso, la tecla TAB y las teclas de función sin restricciones.
function controlTag(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8) return true; 
    else if (tecla==0||tecla==9)  return true;
    patron =/[0-9\s]/;
    n = String.fromCharCode(tecla);
    return patron.test(n); 
}
//esta función comprueba si una cadena de texto contiene únicamente letras
//(mayúsculas, minúsculas y acentuadas) y espacios en blanco. Si la 
//cadena cumple con el patrón definido, la función devuelve true, de lo contrario, devuelve false.
function testText(txtString){
    var stringText = new RegExp(/^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü\s]+$/);
    if(stringText.test(txtString)){
        return true;
    }else{
        return false;
    }
}
//esta función comprueba si un valor es un número entero válido, permitiendo 
// solo dígitos del 0 al 9. Si el valor cumple con el patrón definido,
// la función devuelve true, de lo contrario, devuelve false.
function testEntero(intCant){
    var intCantidad = new RegExp(/^([0-9])*$/);
    if(intCantidad.test(intCant)){
        return true;
    }else{
        return false;
    }
}
//esta función comprueba si una cadena de texto corresponde a una dirección de
// correo electrónico válida. Utiliza una expresión regular para verificar si la
// cadena cumple con el formato estándar de una dirección de correo electrónico.
// Si la cadena cumple con el patrón, la función devuelve true, de lo contrario, devuelve false.
function fntEmailValidate(email){
    var stringEmail = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
    if (stringEmail.test(email) == false){
        return false;
    }else{
        return true;
    }
}
//esta función realiza la validación en tiempo real de los campos de texto con la clase 
//"validText" en un formulario.
function fntValidText(){
	let validText = document.querySelectorAll(".validText");
    validText.forEach(function(validText) {
        validText.addEventListener('keyup', function(){
			let inputValue = this.value;
			if(!testText(inputValue)){
				this.classList.add('is-invalid');
			}else{
				this.classList.remove('is-invalid');
			}				
		});
	});
}
//validacion de campos numericos en el formulario
function fntValidNumber(){
	let validNumber = document.querySelectorAll(".validNumber");
    validNumber.forEach(function(validNumber) {
        validNumber.addEventListener('keyup', function(){
			let inputValue = this.value;
			if(!testEntero(inputValue)){
				this.classList.add('is-invalid');
			}else{
				this.classList.remove('is-invalid');
			}				
		});
	});
}
//alidación en tiempo real de los campos de correo electrónico con la clase "validEmail" en el formulario
function fntValidEmail(){
	let validEmail = document.querySelectorAll(".validEmail");
    validEmail.forEach(function(validEmail) {
        validEmail.addEventListener('keyup', function(){
			let inputValue = this.value;
			if(!fntEmailValidate(inputValue)){
				this.classList.add('is-invalid');
			}else{
				this.classList.remove('is-invalid');
			}				
		});
	});
}
// Esto garantiza que las funciones de validación se ejecuten una vez que la página haya cargado completamente.
window.addEventListener('load', function() {
	fntValidText();
	fntValidEmail(); 
	fntValidNumber();
}, false);