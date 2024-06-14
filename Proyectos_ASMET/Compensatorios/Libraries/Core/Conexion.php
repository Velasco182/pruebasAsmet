<?php
class Conexion{
	private $conect;

	public function __construct(){
		$tns = "  
			(DESCRIPTION =
				(ADDRESS_LIST =
				(ADDRESS = (PROTOCOL = TCP)(HOST = ".DB_HOST.")(PORT = ".DB_PORT."))
				)
				(CONNECT_DATA =
				(SID = ".DB_SID.")
				)
			)
		";

		$this->conect = oci_connect(DB_USER, DB_PASSWORD, $tns);

		if(!$this->conect) {
		    $error = oci_error();
		    echo "Error al conectar a Oracle: " . $error['message'];
		    exit;
		}

		/*
		try{
			$this->conect = oci_connect(DB_USER, DB_PASSWORD, $tns);
			//$this->conect = new PDO("oci:dbname=".$tns,DB_USER,DB_PASSWORD);
			//$this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//echo "<br>**Conectado a la BD**<br>";
		    //echo "conexión exitosa";
		}catch(PDOException $e){
			$this->conect = 'Error de conexión';
		    echo "<br>Error en la conexion a la base de datos<br>";
			//echo "ERROR: " . $e->getMessage();
		}

		
		/*
		if (extension_loaded('pdo_oci')) {
			echo 'PDO OCI está habilitado en tu instalación de PHP.';
		} else {
			echo 'PDO OCI no está habilitado en tu instalación de PHP.';
		}
		*/
	}
	
	public function conect(){
		return $this->conect;
	}
}

?>