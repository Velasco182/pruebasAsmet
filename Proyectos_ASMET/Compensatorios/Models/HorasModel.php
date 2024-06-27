<?php 

class HorasModel extends Oracle{

	public $intIdFuncionario;

	public $intIdToma;

	public $intIdHora;
	public $strMotivo;
	public $intEstado;
	public $strFecha;
	public $strHoras;

	public $intIdUsuario;

	public $intCodigoRol;


	public function __construct(){
		parent::__construct();
	}

	//----Función de inserción----
	//Modulo de inserción de horas
	public function insertHora(
		string $tomMotivo, 
		int $tomEstado, 
		string $tomFechaSolicitada,
		string $tomHorasSolicitadas){

		$this->strMotivo = $tomMotivo;
		$this->intEstado = $tomEstado;
		$this->strFecha = $tomFechaSolicitada;
		$this->strHoras = $tomHorasSolicitadas;

		$return = 0;

		$idHora = $_SESSION['userData']['ID_FUNCIONARIO'];
		$this -> intIdHora = $idHora;

		// $sql = "SELECT * FROM BIG_TOMA 
		// 	WHERE FUN_CORREO = '{$this->strEmail}' or FUN_USUARIO = '{$this->intUsuario}'";
		// $request = $this->select_all($sql);

		//Verificación de inserción
		if(empty($request)){
			$query_insert  = "
			INSERT INTO BIG_TOMA
			(
				TOM_MOTIVO,
				TOM_ESTADO,
				TOM_FECHA_SOLI,
				TOM_HORAS_SOLI,
				ID_FUNCIONARIO
			) 
			VALUES
			(
				:TOM_MOTIVO,
				:TOM_ESTADO,
				TO_TIMESTAMP(:TOM_FECHA_SOLI, 'YYYY/MM/DD HH24:MI:SS'),
				:TOM_HORAS_SOLI,
				:ID_FUNCIONARIO
			)";
			
			$arrData = array(
				'TOM_MOTIVO'		=>$this->strMotivo,
				'TOM_ESTADO'		=>$this->intEstado,
				'TOM_FECHA_SOLI'	=>$this->strFecha,
				'TOM_HORAS_SOLI'	=>$this->strHoras,
				'ID_FUNCIONARIO'	=>$this->intIdHora
			);
			
			$request_insert = $this->insert($query_insert,$arrData);
			$return = $request_insert;
		}else{
			$return = "exist";
		}
		return $return;
	}

	//----Funciones de lectura----
	//Modulo de lectura de datos para modal de ver
	public function getHoras(int $idFuncionario){
		
		$this -> intIdFuncionario = $idFuncionario;

		/*$sql = "SELECT T.FUN_NOMBRES, T.FUN_APELLIDOS,
				SUM(EXTRACT(HOUR FROM (I.COM_FECHA_FIN - I.COM_FECHA_INICIO))) 
				AS HORAS_TOTALES
				FROM BIG_COMPENSATORIOS I
				INNER JOIN BIG_FUNCIONARIOS T ON I.ID_FUNCIONARIO = T.ID_FUNCIONARIO
			WHERE I.ID_FUNCIONARIO = $this->intIdFuncionario
			GROUP BY T.FUN_NOMBRES, T.FUN_APELLIDOS
			";*/
		$sql = "SELECT T.FUN_NOMBRES, T.FUN_APELLIDOS,
				SUM(
					EXTRACT(HOUR FROM (I.COM_FECHA_FIN - I.COM_FECHA_INICIO)) +
					EXTRACT(MINUTE FROM (I.COM_FECHA_FIN - I.COM_FECHA_INICIO)) / 60
				) AS HORAS_TOTALES
				FROM BIG_COMPENSATORIOS I
				INNER JOIN BIG_FUNCIONARIOS T ON I.ID_FUNCIONARIO = T.ID_FUNCIONARIO
			WHERE I.ID_FUNCIONARIO = $this->intIdFuncionario
			GROUP BY T.FUN_NOMBRES, T.FUN_APELLIDOS
			";

			$request = $this->select($sql);

			// var_dump($request);

			return $request;
	}

	public function getGastadas(int $idFuncionario){

		$this -> intIdFuncionario = $idFuncionario;

		$sql = "SELECT ID_FUNCIONARIO,
				SUM(TOM_HORAS_SOLI) AS HORAS_GASTADAS
				FROM BIG_TOMA
		WHERE ID_FUNCIONARIO = $this->intIdFuncionario AND TOM_ESTADO!=3
		GROUP BY ID_FUNCIONARIO
		";

		$request = $this->select($sql);

		return $request;
	}

	public function selectHoras(int $idFuncionario){

		$this -> intIdUsuario = $idFuncionario;

		$codigoRol = $_SESSION['userData']['ROL_CODIGO'];
		$this -> intCodigoRol = $codigoRol;

		
		if ($this -> intCodigoRol == '1A') {

			$sql = "SELECT 
				TOM.ID_TOMA,
				TOM.TOM_ESTADO,
				TO_CHAR(TOM.TOM_FECHA_SOLI) AS TOM_FECHA_SOLI,
				TOM.TOM_MOTIVO,
				TOM.TOM_HORAS_SOLI,
				FUN.FUN_NOMBRES AS FUN_NOMBRES,
				FUN.FUN_APELLIDOS AS FUN_APELLIDOS,
				FUN.FUN_CORREO AS FUN_CORREO
			FROM BIG_TOMA TOM
			INNER JOIN BIG_FUNCIONARIOS FUN ON TOM.ID_FUNCIONARIO = FUN.ID_FUNCIONARIO";

			$request = $this->select_all($sql);
			return $request;
		}

		$sql = "SELECT 
				TOM.ID_TOMA,
				TOM.TOM_ESTADO,
				TO_CHAR(TOM.TOM_FECHA_SOLI) AS TOM_FECHA_SOLI,
				TOM.TOM_MOTIVO,
				TOM.TOM_HORAS_SOLI,
				FUN.FUN_NOMBRES AS FUN_NOMBRES,
				FUN.FUN_APELLIDOS AS FUN_APELLIDOS,
				FUN.FUN_CORREO AS FUN_CORREO
			FROM BIG_TOMA TOM
			INNER JOIN BIG_FUNCIONARIOS FUN ON TOM.ID_FUNCIONARIO = FUN.ID_FUNCIONARIO
			WHERE TOM.ID_FUNCIONARIO = $this->intIdUsuario";

		$arrData = array(
			':ID_FUNCIONARIO'	=>$this	->intIdUsuario
		);

		$request = $this->select_all($sql, $arrData);

		return $request;
	}
	//Modulo para llenar el modal de detalles de horas
	public function selectHoraVista(int $idToma){

		$this->intIdFuncionario = $idToma;

		$sql = "SELECT 
				I.ID_TOMA,
				I.TOM_ESTADO,
				TO_CHAR(I.TOM_FECHA_SOLI, 'DD/MM/YYYY') AS TOM_FECHA_SOLI,
				I.TOM_MOTIVO,
				I.TOM_HORAS_SOLI,
				F.FUN_NOMBRES AS FUN_NOMBRES,
				F.FUN_APELLIDOS AS FUN_APELLIDOS,
				F.FUN_CORREO AS FUN_CORREO
			FROM BIG_TOMA I 
			INNER JOIN BIG_FUNCIONARIOS F ON I.ID_FUNCIONARIO = F.ID_FUNCIONARIO
			WHERE I.ID_TOMA = $this->intIdFuncionario";

			$request = $this->select($sql);
			// print_r($request);
			return $request;
	}

	//-----Funciones para actualización de datos-----
	//Modulo para cambiar el estado de horas aprobado
	public function estadoAprobado(int $idToma){

		$this->intIdFuncionario = $idToma;
		$estadoAprobado = 2;

		$sql = "UPDATE BIG_TOMA 
				SET TOM_ESTADO = :TOM_ESTADO 
			WHERE ID_TOMA = :ID_TOMA";

		$arrData = array(
			'TOM_ESTADO' 	=> $estadoAprobado,
			'ID_TOMA' 		=> $this->intIdFuncionario
		);

		$request = $this->update($sql, $arrData);
	
		return $request;
	}
	//Modulo para cambiar el estado de horas rechazado
	public function estadoRechazado(int $idToma){
		$this->intIdFuncionario = $idToma;
		$estadoRechazado = 3;
	
		$sql = "UPDATE BIG_TOMA SET TOM_ESTADO = :TOM_ESTADO WHERE ID_TOMA = :ID_TOMA";
	
		$arrData = array(
			'TOM_ESTADO' 	=> $estadoRechazado,
			'ID_TOMA' 		=> $this->intIdFuncionario
		);
	
		$request = $this->update($sql, $arrData);
		
		return $request;
	}
	//Modulo para envío del correo de aprobación o rechazo de las horas
	public function correoAprobacionORechazo(int $idToma){

		$this->	intIdToma = $idToma;

		$sql = "SELECT 
			FUN.FUN_NOMBRES,
			FUN.FUN_APELLIDOS,
			FUN.FUN_CORREO,
			FUN.FUN_USUARIO,
			TOM.TOM_MOTIVO,
			TOM.TOM_FECHA_SOLI,
			TOM.TOM_HORAS_SOLI
		FROM BIG_TOMA TOM
		INNER JOIN BIG_FUNCIONARIOS FUN ON FUN.ID_FUNCIONARIO = TOM.ID_FUNCIONARIO
		WHERE TOM.ID_TOMA = $this->intIdToma
		";

		$arrData = array(
			'ID_TOMA' 		=> $this->intIdToma
		);

		$request = $this->select($sql, $arrData);

		return $request;
	}
}
 ?>