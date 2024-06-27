<?php

class TipoCompensatoriosModel extends Oracle{

	public $intIdTipoCompensatorio;
	public $strNombreTipoCompensatorio;
	public $strDescripcionTipoCompensatorio;
	public $intEstadoTipoCompensatorio;

	public function __construct(){
		parent::__construct();
	}

	//Método para insertar datos
	public function insertTipoCompensatorio(
		string $nombreTipoCompensatorio,
		string $descripcionTipoCompensatorio,
		int $estadoTipoCompensatorio) {

		$this->strNombreTipoCompensatorio = $nombreTipoCompensatorio;
		$this->strDescripcionTipoCompensatorio = $descripcionTipoCompensatorio;
		$this->intEstadoTipoCompensatorio = $estadoTipoCompensatorio;

		$return = 0;
		
		//Validar duplicidad de datos
		$sql = "SELECT * FROM BIG_TIPO_COMPENSATORIO 
			WHERE TIP_COM_NOMBRE = '{$this->strNombreTipoCompensatorio}' 
			OR TIP_COM_DESCRIPCION = '{$this->strDescripcionTipoCompensatorio}'";
		$request = $this->select_all($sql);
		
		if(empty($request)){

			$query_insert  = "INSERT INTO BIG_TIPO_COMPENSATORIO
				(
					TIP_COM_NOMBRE,
					TIP_COM_DESCRIPCION,
					TIP_COM_ESTADO
				) 
				VALUES
				(
					:TIP_COM_NOMBRE,
					:TIP_COM_DESCRIPCION,
					:TIP_COM_ESTADO
				)";
	
			$arrData = array(
				'TIP_COM_NOMBRE' 		=> $this->strNombreTipoCompensatorio, 
				'TIP_COM_DESCRIPCION' 	=> $this->strDescripcionTipoCompensatorio,
				'TIP_COM_ESTADO' 		=> $this->intEstadoTipoCompensatorio
			);

			$request_insert = $this->insert($query_insert, $arrData);
			$return = $request_insert;

		}else{
			$return = "exist";
		}

		return $return; 
	}
	//Método para seleccionar tipo de compensatorio para editar
	public function selectTipoCompensatorioEdit(int $idTipoCompensatorio){

		$this->intIdTipoCompensatorio = $idTipoCompensatorio;

		$sql = "SELECT 
				TC.ID_TIPO_COMPENSATORIO,
				TC.TIP_COM_NOMBRE,
				TC.TIP_COM_DESCRIPCION,
				TC.TIP_COM_ESTADO
				FROM BIG_TIPO_COMPENSATORIO TC
		WHERE TC.ID_TIPO_COMPENSATORIO = $this->intIdTipoCompensatorio
		";
		
		$request = $this->select($sql);

		return $request;
	}
	//Método para leer los datos del tipo de compensatorio en el datatable
	public function selectTipoCompensatorios() {
		
		// WHERE TC.TIP_COM_ESTADO='1'
		$sql = "SELECT * FROM BIG_TIPO_COMPENSATORIO TC";
		return $this->select_all($sql);

	}
	//Método para leer los datos del compensatorio en modal
	public function selectTipoCompensatorioVista(int $idTipoCompensatorio) {
		$this->intIdTipoCompensatorio = $idTipoCompensatorio;
		$sql = "SELECT
			TC.ID_TIPO_COMPENSATORIO,
			TC.TIP_COM_NOMBRE,
			TC.TIP_COM_DESCRIPCION,
			TC.TIP_COM_ESTADO
		FROM BIG_TIPO_COMPENSATORIO TC
		WHERE TC.ID_TIPO_COMPENSATORIO = $this->intIdTipoCompensatorio";

		$request = $this->select($sql);
	
		return $request;
	}
	//Método para actualizar el tipo de compensatorio
	public function updateTipoCompensatorio(
		int $idTipoCompensatorio,
		string $nombreTipoCompensatorio, 
		string $descripcionTipoCompensatorio,
		int $estadoTipoCompensatorio){

		$this->intIdTipoCompensatorio = $idTipoCompensatorio;
		$this->strNombreTipoCompensatorio = $nombreTipoCompensatorio;
		$this->strDescripcionTipoCompensatorio = $descripcionTipoCompensatorio;
		$this->intEstadoTipoCompensatorio = $estadoTipoCompensatorio;

		//Validacion del request
		$sql = "SELECT * FROM BIG_TIPO_COMPENSATORIO 
		WHERE TIP_COM_NOMBRE = '{$this->strNombreTipoCompensatorio}' 
		AND TIP_COM_DESCRIPCION = '{$this->strDescripcionTipoCompensatorio}'
		AND ID_TIPO_COMPENSATORIO != $this->intIdTipoCompensatorio";

		$request = $this->select_all($sql);

		if(empty($request)){

			$sqlUp = "UPDATE BIG_TIPO_COMPENSATORIO
			SET TIP_COM_NOMBRE = :TIP_COM_NOMBRE,
			TIP_COM_DESCRIPCION = :TIP_COM_DESCRIPCION,
			TIP_COM_ESTADO = :TIP_COM_ESTADO
			WHERE ID_TIPO_COMPENSATORIO = $this->intIdTipoCompensatorio
			";

			$arrData = array(
				'TIP_COM_NOMBRE'		=>$this->strNombreTipoCompensatorio,
				'TIP_COM_DESCRIPCION'	=>$this->strDescripcionTipoCompensatorio,
				'TIP_COM_ESTADO'		=>$this->intEstadoTipoCompensatorio
			);
			$requestUp = $this->update($sqlUp, $arrData);
		}else{
			$requestUp = "exist";
		}
		return $requestUp;

	}
	//Método para eliminar datos
	public function deleteTipoCompensatorio(int $idTipoCompensatorio){
		$this->intIdTipoCompensatorio = $idTipoCompensatorio;

		//validacion request. En compensatorios se valida la existencia de un registro con el id de tipo de compensatorio selecionado
		$sql = "SELECT * FROM BIG_COMPENSATORIOS WHERE ID_TIPO_COMPENSATORIO = $this->intIdTipoCompensatorio";
		$request = $this->select_all($sql);

		if(empty($request)){

			$sqlDel = "DELETE BIG_TIPO_COMPENSATORIO WHERE ID_TIPO_COMPENSATORIO = $this->intIdTipoCompensatorio";
			$requestDel = $this->delete($sqlDel);
			
			if($requestDel){
				$request = 'ok';
			}else{
				$request = 'error';
			}
		}else{
			$request = 'exist';
		}
		return $request;
	}
}
 ?>