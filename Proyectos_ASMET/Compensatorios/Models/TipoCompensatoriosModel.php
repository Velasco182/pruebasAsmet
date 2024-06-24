<?php

class TipoCompensatoriosModel extends Oracle{

	public function __construct(){
		parent::__construct();
	}
	//Método para insertar datos
	public function insertTipoCompensatorio(
		string $strNombreTipoCompensatorio,
		string $strDescripcionTipoCompensatorio,
		int $intEstadoTipoCompensatorio) {

		//Dep($strNombreTipoCompensatorio. "model antes");

		$this->strNombreTipoCompensatorio = $strNombreTipoCompensatorio;
		$this->strDescripcionTipoCompensatorio = $strDescripcionTipoCompensatorio;
		$this->intEstadoTipoCompensatorio = $intEstadoTipoCompensatorio;

		$return = 0;

		// Obtener el ID del funcionario de la sesión
		//$idFuncionario = $_SESSION['userData']['ID_FUNCIONARIO'];

		// Tu consulta de inserción
		$query_insert  = "
		INSERT INTO BIG_TIPO_COMPENSATORIO
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
			'TIP_COM_NOMBRE' => $strNombreTipoCompensatorio, 
			'TIP_COM_DESCRIPCION' => $this->strDescripcionTipoCompensatorio,
			'TIP_COM_ESTADO' => $this->intEstadoTipoCompensatorio,
		);

		$request_insert = $this->insert($query_insert, $arrData);

		// var_dump($arrData);

		$return = $request_insert;

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

			// var_dump($request);			
		return $request;
	}
	//Método para leer los datos del tipo de compensatorio en el datatable
	public function selectTipoCompensatorios() {
		
		//where TIP_COM_ESTADO='1'
		$sql = " SELECT * FROM BIG_TIPO_COMPENSATORIO T";
		return $this->select_all($sql);

	}
	//Método para leer los datos del compensatorio en modal
	public function selectTipoCompensatorioVista(int $idTipoCompensatorio) {
		$this->IdTipoCompensatorio = $idTipoCompensatorio;
		$sql = "
		SELECT
			TC.ID_TIPO_COMPENSATORIO,
			TC.TIP_COM_NOMBRE,
			TC.TIP_COM_DESCRIPCION,
			TC.TIP_COM_ESTADO
		FROM BIG_TIPO_COMPENSATORIO TC
		WHERE TC.ID_TIPO_COMPENSATORIO = $this->IdTipoCompensatorio";

		$request = $this->select($sql);
	
		return $request;
	}
	//Método para actualizar el tipo de compensatorio
	public function updateTipoCompensatorio(
		int $intIdTipoCompensatorio,
		string $strNombreTipoCompensatorio, 
		string $strDescripcionTipoCompensatorio,
		int $intEstadoTipoCompensatorio){


		$this->intIdTipoCompensatorio = $intIdTipoCompensatorio;
		$this->strNombreTipoCompensatorio = $strNombreTipoCompensatorio;
		$this->strDescripcionTipoCompensatorio = $strDescripcionTipoCompensatorio;
		$this->intEstadoTipoCompensatorio = $intEstadoTipoCompensatorio;

		if(empty($request)){
			$sql = "
			UPDATE BIG_TIPO_COMPENSATORIO
			SET TIP_COM_NOMBRE = :TIP_COM_NOMBRE,
			TIP_COM_DESCRIPCION = :TIP_COM_DESCRIPCION,
			TIP_COM_ESTADO = :TIP_COM_ESTADO
			WHERE ID_TIPO_COMPENSATORIO = $this->intIdTipoCompensatorio
			";


			$arrData = array(
				'TIP_COM_NOMBRE'=>$this->strNombreTipoCompensatorio,
				'TIP_COM_DESCRIPCION'=>$this->strDescripcionTipoCompensatorio,
				'TIP_COM_ESTADO'=>$this->intEstadoTipoCompensatorio
			);
			$request = $this->update($sql, $arrData);
		}else{
			$request = "exist";
		}
		return $request;

	}
	//Método para eliminar datos
	public function deleteTipoCompensatorio(int $idTipoCompensatorio){
		$this->intIdTipoCompensatorio = $idTipoCompensatorio;
		$sql = "DELETE BIG_TIPO_COMPENSATORIO WHERE ID_TIPO_COMPENSATORIO = $this->intIdTipoCompensatorio";
		$arrData = array(0);
		$request = $this->update($sql,$arrData);
		return $request;
	}
########################################################################################################
	public function recuperar($ID_FUNCIONARIO){
		$sql = "SELECT
				FUN.ID_FUNCIONARIO,
				FUN.FUN_NOMBRES || ' ' ||FUN.FUN_APELLIDOS NOMBREFUNCIONARIO,
				FUN.FUN_CORREO,
				FUN.FUN_USUARIO
			FROM BIG_FUNCIONARIOS FUN
			WHERE FUN.ID_FUNCIONARIO = ".$ID_FUNCIONARIO."
			";

			$arrData = array(
				'ID_FUNCIONARIO' => $ID_FUNCIONARIO
			);

			$request = $this->select($sql, $arrData);

			return $request;
	}
	
	// Método para cambiar el estado del compensatorio 
	public function estadoAprobado($ID_COMPENSATORIO){
		$this->intIdFuncionario = $ID_COMPENSATORIO;
		$estadoAprobado = 2;

		$sql = "UPDATE BIG_COMPENSATORIOS SET COM_ESTADO = :COM_ESTADO WHERE ID_COMPENSATORIO = :ID_COMPENSATORIO";

		$arrData = array(
			'COM_ESTADO' => $estadoAprobado,
			'ID_COMPENSATORIO' => $this->intIdFuncionario
		);

		$request = $this->update($sql, $arrData);
	
		return $request;
	}

	public function correoAprobacion($ID_COMPENSATORIO){
		$sql = "SELECT
			FUN.ID_FUNCIONARIO,
			FUN.FUN_NOMBRES,
			FUN.FUN_APELLIDOS,
			FUN.FUN_CORREO,
			FUN.FUN_USUARIO,
			COM.COM_FECHA_INICIO,  -- Reemplaza 'OtroCampo1' con el nombre del campo que deseas seleccionar
			COM.COM_FECHA_FIN,  -- Reemplaza 'OtroCampo2' con el nombre del campo que deseas seleccionar
			COM.COM_ACTIVIDAD_DESARROLLAR,
			COM.COM_DESCRIPCION_ACTIVIDAD,
			COM.COM_USUARIO_FINAL
		FROM BIG_COMPENSATORIOS COM
		INNER JOIN BIG_FUNCIONARIOS FUN ON FUN.ID_FUNCIONARIO = COM.ID_FUNCIONARIO
		WHERE COM.ID_COMPENSATORIO = ".$ID_COMPENSATORIO."
		";

		$arrData = array(
			'ID_COMPENSATORIO' => $ID_COMPENSATORIO
		);

		$request = $this->select($sql, $arrData);

		return $request;
	}

	public function CorreoRechazo($ID_COMPENSATORIO){
		$sql = "SELECT 
		FUN.ID_FUNCIONARIO,
		FUN.FUN_NOMBRES, 
		FUN.FUN_APELLIDOS, 
		FUN.FUN_CORREO,
		FUN.FUN_USUARIO, 
		COM.COM_FECHA_INICIO,
		COM.COM_FECHA_FIN,
		COM.COM_ACTIVIDAD_DESARROLLAR,
		COM.COM_DESCRIPCION_ACTIVIDAD,
		COM.COM_USUARIO_FINAL
		FROM BIG_COMPENSATORIOS COM
		INNER JOIN BIG_FUNCIONARIOS FUN ON FUN.ID_FUNCIONARIO = COM.ID_FUNCIONARIO
		WHERE COM.ID_COMPENSATORIO = ".$ID_COMPENSATORIO."
		";

		$arrData = array(
			'ID_COMPENSATORIO' => $ID_COMPENSATORIO
		);

		$request = $this->select($sql, $arrData);

		return $request;
	}

	// Metodo para cambiar el estado del compensatorio
	public function estadoRechazado($ID_COMPENSATORIO){
		$this->intIdFuncionario = $ID_COMPENSATORIO;
		$estadoRechazado = 3;
	
		$sql = "UPDATE BIG_COMPENSATORIOS SET COM_ESTADO = :COM_ESTADO WHERE ID_COMPENSATORIO = :ID_COMPENSATORIO";

		$arrData = array(
			'COM_ESTADO' => $estadoRechazado,
			'ID_COMPENSATORIO' => $this->intIdFuncionario
		);

		$request = $this->update($sql, $arrData);
		
		return $request;
	}

    // ... otros métodos y funciones del modelo ...
	public function updateFuncionario(int $idFuncionario, string $identificacion, string $nombre, string $apellido, 
	string $usuario, string $email,int $tipoid, int $status){
			
			$this->intIdFuncionario = $idFuncionario;
			$this->strIdentificacion = $identificacion;
			$this->strNombre = $nombre;
			$this->strApellido = $apellido;
			$this->strUsuario = $usuario;
			$this->strEmail = $email;
			$this->intTipoId = $tipoid;
			$this->intStatus = $status;
			
			$sql = "SELECT * FROM BIG_FUNCIONARIOS
			WHERE 
			(FUN_CORREO = '{$this->strEmail}' OR FUN_USUARIO = '{$this->strUsuario}') 
			and ID_FUNCIONARIO!='{$this->intIdFuncionario}'
			";
		
		$request = $this->select_all($sql);

		if(empty($request)){
			$sql = "UPDATE BIG_FUNCIONARIOS SET FUN_IDENTIFICACION=:FUN_IDENTIFICACION, FUN_NOMBRES=:FUN_NOMBRES, FUN_APELLIDOS=:FUN_APELLIDOS, FUN_USUARIO=:FUN_USUARIO, 
			FUN_CORREO=:FUN_CORREO, ID_ROL=:ID_ROL, FUN_ESTADO=:FUN_ESTADO 
			WHERE ID_FUNCIONARIO = $this->intIdFuncionario 
			";
			
			$arrData = array(
				'FUN_IDENTIFICACION'=>$this->strIdentificacion,
				'FUN_NOMBRES'=>$this->strNombre,
				'FUN_APELLIDOS'=>$this->strApellido,
				'FUN_USUARIO'=>$this->strUsuario,
				'FUN_CORREO'=>$this->strEmail,
				'ID_ROL'=>$this->intTipoId,
				'FUN_ESTADO'=>$this->intStatus
			);
				
			$request = $this->update($sql,$arrData);
		}else{
			$request = "exist";
		}
		return $request;
	}

	public function deleteFuncionario(int $idFuncionario){
		$this->intIdFuncionario = $idFuncionario;
		$sql = "UPDATE BIG_FUNCIONARIOS SET FUN_ESTADO = ? WHERE ID_FUNCIONARIO = $this->intIdFuncionario ";
		$arrData = array(0);
		$request = $this->update($sql,$arrData);
		return $request;
	}

	public function estadoFuncionario(int $idFuncionario, string $estado){
		$this->intIdFuncionario = $idFuncionario;
		$sql = "UPDATE BIG_FUNCIONARIOS SET FUN_ESTADO = ? WHERE ID_FUNCIONARIO = $this->intIdFuncionario ";
		$arrData = array($estado);
		$request = $this->update($sql,$arrData);
		return $request;
	}

	public function resetPassFuncionario(int $idFuncionario,string $fun_password){
		$this->intIdFuncionario = $idFuncionario;
		$sql = "UPDATE BIG_FUNCIONARIOS SET FUN_PASSWORD = ? WHERE ID_FUNCIONARIO = $this->intIdFuncionario ";
		$arrData = array($fun_password);
		$request = $this->update($sql,$arrData);
		return $request;
	}

	public function selectUsuarios(){
		$whereAdmin = "";
		if ($_SESSION['idUser'] != 1 )
		{$whereAdmin = " and ID_FUNCIONARIO != 1 ";
		}
		// EXTRAE ROLES excluyendo el usuario con ID_FUNCIONARIO = 1
		$sql = "SELECT * FROM BIG_FUNCIONARIOS WHERE FUN_ESTADO != 0" . $whereAdmin;
		$request = $this->select_all($sql);
		return $request;
	}
	
	public function esAdministrador($ID_ROL) {

		$sql = "SELECT distinct ID_ROL FROM BIG_FUNCIONARIOS WHERE ID_ROL = ID_ROL";

		$arrData = array(
			':ID_ROL' => $ID_ROL
		);

		$request = $this->select($sql, $arrData);
		
		return $request['ID_ROL'] == 1;		
	}

	public function guardarEvidencia($COM_EVIDENCIAS, $ID_COMPENSATORIO){ // Esta definitivamente funciona
		$this->strEvidencia = $COM_EVIDENCIAS;

		$sql = "UPDATE BIG_COMPENSATORIOS SET COM_EVIDENCIAS = :COM_EVIDENCIAS WHERE ID_COMPENSATORIO = :ID_COMPENSATORIO";

		$arrData = array(
			'COM_EVIDENCIAS'=>$this->strEvidencia,
			'ID_COMPENSATORIO' => $ID_COMPENSATORIO
		);
		
		$request = $this->update($sql, $arrData);

		return $request;		
	}
	}
 ?>