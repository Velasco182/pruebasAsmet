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

	public $intResta;


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

		$sql = "SELECT * FROM BIG_TOMA
			WHERE (TOM_MOTIVO = '{$this->strMotivo}'
			AND TOM_FECHA_SOLI = TO_TIMESTAMP('{$this->strFecha}', 'DD/MM/YYYY'))
			AND TOM_HORAS_SOLI = '{$this->strHoras}'
			AND ID_FUNCIONARIO = '{$this->intIdFuncionario}'";
			
		$request = $this->select_all($sql);

		//Verificación de inserción
		if(empty($request)){

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
					TO_DATE(:TOM_FECHA_SOLI, 'DD/MM/YYYY'),
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
		}else{
			$return = "exist";
		}
		return $return;
	}

	//----Funciones de lectura----
	//Modulo para verificar horas disponibles
	public function getHorasDisponibles(int $idFuncionario){

		$this -> intIdFuncionario = $idFuncionario;

		$codigoRol = $_SESSION['userData']['ROL_CODIGO'];
		$this -> intCodigoRol = $codigoRol;

		if ($this -> intCodigoRol == '1A') {
			
			$sql = "SELECT
						FUN_NOMBRES || ' ' ||FUN_APELLIDOS AS NOMBREFUNCIONARIO,
						ROUND(SUM(TOM_HORAS_SOLI_SUBQUERY),2) AS HORAS_APROBADAS,
						ROUND(AVG(COMP_HORAS_SUBQUERY),2) AS HORAS_COMPENSATORIOS_APROBADAS,
						AVG(COMP_HORAS_SUBQUERY) - SUM(TOM_HORAS_SOLI_SUBQUERY) AS HORAS_DISPONIBLES
					FROM(
							SELECT
								F.FUN_NOMBRES,
								F.FUN_APELLIDOS,
								C.ID_FUNCIONARIO,
								T.TOM_HORAS_SOLI,
								C.COM_ESTADO,
								T.TOM_ESTADO,
								T.TOM_MOTIVO,
								T.TOM_FECHA_SOLI,
								T.TOM_HORAS_SOLI AS TOM_HORAS_SOLI_SUBQUERY,
								SUM(EXTRACT(HOUR FROM (C.COM_FECHA_FIN - C.COM_FECHA_INICIO)) +
									EXTRACT(MINUTE FROM (C.COM_FECHA_FIN - C.COM_FECHA_INICIO)) / 60) AS COMP_HORAS_SUBQUERY
							FROM BIG_COMPENSATORIOS C
							INNER JOIN BIG_TOMA T ON C.ID_FUNCIONARIO = T.ID_FUNCIONARIO
							INNER JOIN BIG_FUNCIONARIOS F ON C.ID_FUNCIONARIO = F.ID_FUNCIONARIO
							WHERE T.TOM_ESTADO = 2 AND C.COM_ESTADO = 2
							GROUP BY C.ID_FUNCIONARIO, T.TOM_HORAS_SOLI, C.COM_ESTADO, T.TOM_ESTADO, T.TOM_MOTIVO,
							T.TOM_FECHA_SOLI, F.FUN_NOMBRES, F.FUN_APELLIDOS
						)GROUP BY FUN_NOMBRES || ' ' ||FUN_APELLIDOS";//T.ID_FUNCIONARIO = '{$this->intIdFuncionario}' AND
	
			$request = $this->select_all($sql);
			return $request;

		}

		$sql = "SELECT
					FUN_NOMBRES || ' ' ||FUN_APELLIDOS AS NOMBREFUNCIONARIO,
					ROUND(SUM(TOM_HORAS_SOLI_SUBQUERY),2) AS HORAS_APROBADAS,
					ROUND(AVG(COMP_HORAS_SUBQUERY),2) AS HORAS_COMPENSATORIOS_APROBADAS,
					AVG(COMP_HORAS_SUBQUERY) - SUM(TOM_HORAS_SOLI_SUBQUERY) AS HORAS_DISPONIBLES
				FROM(
						SELECT
							F.FUN_NOMBRES,
							F.FUN_APELLIDOS,
							C.ID_FUNCIONARIO,
							T.TOM_HORAS_SOLI,
							C.COM_ESTADO,
							T.TOM_ESTADO,
							T.TOM_MOTIVO,
							T.TOM_FECHA_SOLI,
							T.TOM_HORAS_SOLI AS TOM_HORAS_SOLI_SUBQUERY,
							SUM(EXTRACT(HOUR FROM (C.COM_FECHA_FIN - C.COM_FECHA_INICIO)) +
								EXTRACT(MINUTE FROM (C.COM_FECHA_FIN - C.COM_FECHA_INICIO)) / 60) AS COMP_HORAS_SUBQUERY
						FROM BIG_COMPENSATORIOS C
						INNER JOIN BIG_TOMA T ON C.ID_FUNCIONARIO = T.ID_FUNCIONARIO
						INNER JOIN BIG_FUNCIONARIOS F ON C.ID_FUNCIONARIO = F.ID_FUNCIONARIO
						WHERE T.ID_FUNCIONARIO = '{$this->intIdFuncionario}' AND T.TOM_ESTADO = 2 AND C.COM_ESTADO = 2
						GROUP BY C.ID_FUNCIONARIO, T.TOM_HORAS_SOLI, C.COM_ESTADO, T.TOM_ESTADO, T.TOM_MOTIVO, 
						T.TOM_FECHA_SOLI, F.FUN_NOMBRES, F.FUN_APELLIDOS
					)GROUP BY FUN_NOMBRES || ' ' ||FUN_APELLIDOS";
	
		$arrData = array(
			'ID_FUNCIONARIO' => $this->intIdFuncionario
		);

		$request = $this->select_all($sql, $arrData);
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
				T.TOM_ESTADO AS TOM_ESTADO,
				TO_CHAR(T.TOM_FECHA_SOLI, 'DD/MM/YYYY') AS TOM_FECHA_SOLI,
				T.TOM_MOTIVO,
				T.TOM_HORAS_SOLI AS TOM_HORAS_SOLI
			FROM BIG_COMPENSATORIOS I
			INNER JOIN BIG_FUNCIONARIOS F ON I.ID_FUNCIONARIO = F.ID_FUNCIONARIO
			INNER JOIN BIG_TOMA T ON I.ID_FUNCIONARIO = T.ID_FUNCIONARIO
			WHERE T.ID_TOMA = $this->intIdToma
			GROUP BY I.ID_FUNCIONARIO, F.FUN_NOMBRES, F.FUN_APELLIDOS,
			F.FUN_CORREO, T.TOM_MOTIVO, T.TOM_FECHA_SOLI, T.TOM_HORAS_SOLI, T.TOM_ESTADO";

			/*
			,
				ROUND(SUM(
				CASE WHEN I.COM_ESTADO IN (2) THEN
					EXTRACT(HOUR FROM (I.COM_FECHA_FIN - I.COM_FECHA_INICIO)) +
					EXTRACT(MINUTE FROM (I.COM_FECHA_FIN - I.COM_FECHA_INICIO)) / 60
				ELSE
					0
				END), 2) -
				ROUND(
				CASE WHEN TOM_ESTADO IN (2) THEN
					TOM_HORAS_SOLI
				ELSE
					TOM_HORAS_SOLI
				END, 2) AS DIFERENCIA_HORAS

			SELECT
				F.FUN_NOMBRES AS FUN_NOMBRES,
				F.FUN_APELLIDOS AS FUN_APELLIDOS,
				F.FUN_CORREO AS FUN_CORREO,
				T.TOM_ESTADO,
				TO_CHAR(T.TOM_FECHA_SOLI, 'DD/MM/YYYY') AS TOM_FECHA_SOLI,
				T.TOM_MOTIVO,
				T.TOM_HORAS_SOLI,
				ROUND(SUM(
					EXTRACT(HOUR FROM (I.COM_FECHA_FIN - I.COM_FECHA_INICIO)) +
      				EXTRACT(MINUTE FROM (I.COM_FECHA_FIN - I.COM_FECHA_INICIO)) / 60) 
					- SUM(T.TOM_HORAS_SOLI)
				, 2) AS DIFERENCIA_HORAS
			FROM BIG_COMPENSATORIOS I
			INNER JOIN BIG_FUNCIONARIOS F ON I.ID_FUNCIONARIO = F.ID_FUNCIONARIO
			INNER JOIN BIG_TOMA T ON I.ID_FUNCIONARIO = T.ID_FUNCIONARIO
			WHERE T.ID_TOMA = $this->intIdToma --AND T.TOM_ESTADO != 3
			GROUP BY I.ID_FUNCIONARIO, F.FUN_NOMBRES, F.FUN_APELLIDOS,
			F.FUN_CORREO, T.TOM_MOTIVO, T.TOM_FECHA_SOLI, T.TOM_HORAS_SOLI, T.TOM_ESTADO*/
			
		$request = $this->select($sql);
			
		return $request;
	}
	//Modulo para llenar el modal de editar horas
	public function selectEditHora(int $idToma){
		
		$this->intIdToma = $idToma;

		$sql = "SELECT
			TOM.ID_FUNCIONARIO,
			TOM.ID_TOMA,
			TOM.TOM_ESTADO,
			TOM.TOM_MOTIVO,
			TO_CHAR(TOM.TOM_FECHA_SOLI, 'DD/MM/YYYY') AS TOM_FECHA_SOLI,
			TOM.TOM_HORAS_SOLI
		FROM BIG_TOMA TOM
		WHERE TOM.ID_TOMA = $this->intIdToma";

		$request = $this->select($sql);

		return $request;
	}

	//-----Funciones para actualización de datos-----
	//Modulo de actualización de horas
	public function updateHora(
	int $idToma,
	string $motivo,
	string $fecha,
	float $horas){

		$this->intIdToma = $idToma;
		$this->strMotivo = $motivo;
		$this->strFecha = $fecha;
		$this->strHoras = $horas;

		$sql = "SELECT * FROM BIG_TOMA
			WHERE TOM_MOTIVO = '{$this->strMotivo}'
			AND TOM_FECHA_SOLI = TO_TIMESTAMP('{$this->strFecha}', 'DD/MM/YYYY')
			AND TOM_HORAS_SOLI = '{$this->strHoras}'
			AND ID_TOMA != $this->intIdToma";

		$request = $this->select_all($sql);

		if(empty($request)){

			$sqlUpdate = "UPDATE BIG_TOMA
			SET TOM_MOTIVO = :TOM_MOTIVO,
			TOM_FECHA_SOLI = TO_DATE(:TOM_FECHA_SOLI, 'DD/MM/YYYY'),
			TOM_HORAS_SOLI = :TOM_HORAS_SOLI
			WHERE ID_TOMA = :ID_TOMA
			";

			$arrData = array(
				'ID_TOMA' 			=> $this->intIdToma,
				'TOM_MOTIVO' 		=> $this->strMotivo,
				'TOM_FECHA_SOLI' 	=> $this->strFecha,
				'TOM_HORAS_SOLI' 	=> $this->strHoras
			);
			///Ir borrando linea por linea hasta verificar en qué linea se queda
			//Posiblemente sea la de fecha

			$requestUpdate = $this->update($sqlUpdate, $arrData);

		}else{
			$requestUpdate = "exist";
		}

		return $requestUpdate;

	}
	//Modulo para cambiar el estado de horas aprobado
	public function estadoAprobado(int $idToma){

		$this->intIdToma = $idToma;
		$estadoAprobado = 2;
		
		$sql = "UPDATE BIG_TOMA
			SET TOM_ESTADO = :TOM_ESTADO
			WHERE ID_TOMA = :ID_TOMA";

		$arrData = array(
			'TOM_ESTADO' 	=> $estadoAprobado,
			'ID_TOMA' 		=> $this->intIdToma
		);

		$request = $this->update($sql, $arrData);//, $arrData antes update
	
		return $request;
	}
	//Modulo para cambiar el estado de horas rechazado
	public function estadoRechazado(int $idToma){
		$this->intIdToma = $idToma;
		$estadoRechazado = 3;
	
		$sql = "UPDATE BIG_TOMA 
			SET TOM_ESTADO = :TOM_ESTADO 
			WHERE ID_TOMA = :ID_TOMA";
	
		$arrData = array(
			'TOM_ESTADO' 	=> $estadoRechazado,
			'ID_TOMA' 		=> $this->intIdToma
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

	//----Funciones generales------
	//Modulo de verificación de rol
	public function esAdministrador($idRol) {

		$this->intIdRol = $idRol;

		$sql = "SELECT distinct ID_ROL
			FROM BIG_FUNCIONARIOS
			WHERE ID_ROL = ID_ROL";

		$arrData = array(
			':ID_ROL' 		=> $this->intIdRol
		);

		$request = $this->select($sql, $arrData);

		return $request['ID_ROL'] == 1;
	}

}
 ?>