<?php 
	//definir la constante para la ruta de la aplicacion
	const BASE_URL = "http://localhost/test/Compensatorios/Compensatorios";

	//Datos de conexión a Base de Datos
	const DB_HOST = "172.16.0.178";
	const DB_PORT = "1521";
	const DB_USER = "CONSULTA_PBI";
	const DB_PASSWORD = "gato*2021";
	const DB_SID = "planos";
	const DB_CHARSET = "AL32UTF8";

	//definir las contantes para los modulos del sistema, para extraer los permisos
	//DASHBOARD
	const COD_MOD_DAS = "1D";
	//MODULO DE FUNCIONARIOS
	const COD_MOD_FUN = "2F";
	//MODULO DE MENUS
	const COD_MOD_MENU = "3M";
	//MODULO DE MODULOS
	const COD_MOD_MOD = "4M";
	//MODULO DE ROLES
	const COD_MOD_ROLES = "5R";
	//MODULO DE COMPENSATORIOS
	const COD_MOD_COM = "6C";
	//MODULO DE HORAS
	const COD_MOD_HOR = "7T";
	//MODULO TIPO COMPENSATORIO
	const COD_MOD_TIPCOMP = "8T";

	//definir titulos de la aplicacion
	const TITULO_APP = "Tucas";
	const TITULO_APP_FAV = "- Tucas";

	//Zona horaria
	date_default_timezone_set('America/Bogota');

	//Definir el patron de contraseña
	const SYS_PATRON_PASS = ".23";

	//Deliminadores decimal y millar Ej. 24,1989.00
	const SPD = ",";
	const SPM = ".";

	//Simbolo de moneda
	const SMONEY = "Q";

	//Datos envio de correo
	const NOMBRE_REMITENTE = "Fabian Mendez";
	const EMAIL_REMITENTE = "estivenmendez550@gmail.com";
	const NOMBRE_EMPESA = "Asmet Salud";
	const WEB_EMPRESA = "www.asmetsalud.com";
 ?>