<?php 
	
	class Oracle extends Conexion{
		private $conexion;
		private $strquery;
		private $arrValues;

		function __construct(){
			$this->conexion = new Conexion();
			$this->conexion = $this->conexion->conect();
		}

		//Busca un registro
		public function select(string $query){
			$this->strquery = $query;

			//$result = $this->conexion->prepare($this->strquery);
			$result = oci_parse($this->conexion, $this->strquery);

        	
			///$result->execute(); // Error
			$res = oci_execute($result);

			$data = array();
			while ($row = oci_fetch_array($result, OCI_ASSOC)) {
			    $data=$row;
			}       

        	return $data;
		}

		//buscar todos los registros
		public function select_all(string $query){
			$this->strquery = $query;
        	
        	//$result = $this->conexion->prepare($this->strquery);
        	$result = oci_parse($this->conexion, $this->strquery);

			//$result->execute();
			$res = oci_execute($result);
        	
        	//$data = $result->fetchall(PDO::FETCH_ASSOC);
        	$data = array();
			while ($row = oci_fetch_array($result, OCI_ASSOC)) {
			    $data[]=$row;
			}

        	return $data;
		}

		//Insertar un registro
		public function insert(string $query, array $arrValues){
			$this->strquery = $query;
			$this->arrValues = $arrValues;


        	//$insert = $this->conexion->prepare($this->strquery);
        	$result = oci_parse($this->conexion, $this->strquery);

        	//$resInsert = $insert->execute($this->arrVAlues);        	
        	foreach ($this->arrValues as $key => $value) {
        		//echo "KEY:: ".$key."VAL:: ".$value."<br>";
        		oci_bind_by_name($result,':'.($key), $this->arrValues[$key]);
    		}

    		$resInsert = oci_execute($result);

	        return $resInsert; 
		}

		//Actualizar registro
		public function update(string $query, array $arrValues){
			$this->strquery = $query;
			$this->arrValues = $arrValues;


			//$update = $this->conexion->prepare($this->strquery);
			$update = oci_parse($this->conexion, $this->strquery);

			foreach ($this->arrValues as $key => $value) {
        		oci_bind_by_name($update,':'.($key), $this->arrValues[$key]);
    		}

    		$resUpdate = oci_execute($update);

	        return $resUpdate;
		}
		//Eliminar un registros
		public function delete(string $query){
			$this->strquery = $query;

        	//$result = $this->conexion->prepare($this->strquery);
        	$result = oci_parse($this->conexion, $this->strquery);

			//$del = $result->execute();
			$del = oci_execute($result);

        	return $del;
		}
	}
 ?>

