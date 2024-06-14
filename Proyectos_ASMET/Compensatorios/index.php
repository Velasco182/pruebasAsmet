<?php 
	require_once("Config/Config.php");//variables y constantes de configuración.
	require_once("Helpers/Helpers.php");//funciones auxiliares 

	//cargar las librerias para envio de correos
	require_once("Libraries/Email/PHPMailer.php"); 
	require_once("Libraries/Email/SMTP.php"); 
	require_once("Libraries/Email/Exception.php");

	$url = !empty($_GET['url']) ? $_GET['url'] : 'home/home';//Aquí se obtiene la URL actual del parámetro 'url'
	$arrUrl = explode("/", $url);//se divide en dos
	$controller = $arrUrl[0];//metodo de controlador
	$method = $arrUrl[0];
	$params = "";

	//verifica si tiene un elemento
	if(!empty($arrUrl[1])){
		if($arrUrl[1] != ""){
			$method = $arrUrl[1];	
		}
	}

	if(!empty($arrUrl[2])){
		if($arrUrl[2] != ""){
			for ($i=2; $i < count($arrUrl); $i++) {
				$params .=  $arrUrl[$i].',';
			}
			$params = trim($params,',');
		}
	}
	require_once("Libraries/Core/Autoload.php");//carga automaticamente las clases necesarias
	require_once("Libraries/Core/Load.php");//cargar el controlador y ejecutar el metodo correspondiente
 ?>