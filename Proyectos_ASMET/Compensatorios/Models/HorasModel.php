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

		$idFuncionario = $_SESSION['userData']['ID_FUNCIONARIO'];
		$this -> intIdFuncionario = $idFuncionario;

		/*$sql = "SELECT * FROM BIG_TOMA 
			WHERE FUN_CORREO = '{$this->strEmail}'
			AND 
			AND FUN_USUARIO = '{$this->intUsuario}'";
			
		$request = $this->select_all($sql);*/

		//Verificación de inserción
		//if(empty($request)){

			$query_insert  = "INSERT INTO BIG_TOMA
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
					TO_DATE(:TOM_FECHA_SOLI, 'YYYY/MM/DD'),
					:TOM_HORAS_SOLI,
					:ID_FUNCIONARIO
				)";
			
			$arrData = array(
				'TOM_MOTIVO'		=>$this->strMotivo,
				'TOM_ESTADO'		=>$this->intEstado,
				'TOM_FECHA_SOLI'	=>$this->strFecha,
				'TOM_HORAS_SOLI'	=>$this->strHoras,
				'ID_FUNCIONARIO'	=>$this->intIdFuncionario
			);
			
			$request_insert = $this->insert($query_insert,$arrData);
			$return = $request_insert;
		/*}else{
			$return = "exist";
		}*/
		return $return;
	}


	//----Funciones de lectura----
	//Obtener horas para poder comparar las  que se van solicitar con las existentes
	public function getHoras(int $idFuncionario){

		$this -> intIdFuncionario = $idFuncionario;

		$sql = "SELECT
				T.FUN_NOMBRES,
				T.FUN_APELLIDOS,
				SUM(
				EXTRACT(HOUR FROM (I.COM_FECHA_FIN - I.COM_FECHA_INICIO))
				) AS HORAS_TOTALES
			FROM BIG_COMPENSATORIOS I
			INNER JOIN BIG_FUNCIONARIOS T ON I.ID_FUNCIONARIO = T.ID_FUNCIONARIO
			WHERE I.ID_FUNCIONARIO = '{$this->intIdFuncionario}'
			GROUP BY T.FUN_NOMBRES, T.FUN_APELLIDOS
			";

		$arrData = array(
			'ID_FUNCIONARIO' => $this->intIdFuncionario
		);

		$request = $this->select($sql, $arrData);

		return $request;
	}
	//Modulo para obtener y calcular las horas gastadas +
	public function getGastadas(int $idFuncionario){

		$this -> intIdFuncionario = $idFuncionario;

		$sql = "SELECT T.ID_FUNCIONARIO,
				TO_CHAR(SUM(T.TOM_HORAS_SOLI)) AS HORAS_GASTADAS
				FROM BIG_TOMA T
			WHERE T.ID_FUNCIONARIO = '{$this->intIdFuncionario}' AND T.TOM_ESTADO!=3
			GROUP BY T.ID_FUNCIONARIO";

		$arrData = array(
			'ID_FUNCIONARIO' => $this->intIdFuncionario
		);

		$request = $this->select($sql, $arrData);

		return $request;
	}
	//Modulo para llenar el DataTable +
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
	//Modulo para llenar el modal de detalles de horas +
	public function selectHoraVista(int $idToma){

		$this->intIdToma = $idToma;

		//Revisar forma de restar horas totales menos los compensatorios aprobados o pendientes

		$sql = "SELECT
				F.FUN_NOMBRES AS FUN_NOMBRES,
				F.FUN_APELLIDOS AS FUN_APELLIDOS,
				F.FUN_CORREO AS FUN_CORREO,
				T.TOM_ESTADO,
				TO_CHAR(T.TOM_FECHA_SOLI, 'DD/MM/YYYY') AS TOM_FECHA_SOLI,
				T.TOM_MOTIVO,
				T.TOM_HORAS_SOLI,
				ROUND(SUM(
				EXTRACT(HOUR FROM (I.COM_FECHA_FIN - I.COM_FECHA_INICIO)) +
				EXTRACT(MINUTE FROM (I.COM_FECHA_FIN - I.COM_FECHA_INICIO)) / 60
				), 2) AS HORAS_TOTALES
			FROM BIG_COMPENSATORIOS I
			INNER JOIN BIG_FUNCIONARIOS F ON I.ID_FUNCIONARIO = F.ID_FUNCIONARIO
			INNER JOIN BIG_TOMA T ON I.ID_FUNCIONARIO = T.ID_FUNCIONARIO
			WHERE T.ID_TOMA = $this->intIdToma
			GROUP BY I.ID_FUNCIONARIO, F.FUN_NOMBRES, F.FUN_APELLIDOS,
			F.FUN_CORREO, T.TOM_MOTIVO, T.TOM_FECHA_SOLI, T.TOM_HORAS_SOLI, T.TOM_ESTADO";

			$request = $this->select($sql);
			
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
	//Modulo para envío del correo de aprobación o rechazo de las horas +
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