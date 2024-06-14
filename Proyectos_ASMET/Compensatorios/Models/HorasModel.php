<?php 

	class HorasModel extends Oracle{

		public function __construct(){
			parent::__construct();
		}

		public function getHoras($ID_FUNCIONARIO){
			$sql = "SELECT 
					T.FUN_NOMBRES,
					T.FUN_APELLIDOS,
					SUM(
					EXTRACT(HOUR FROM (I.COM_FECHA_FIN - I.COM_FECHA_INICIO)) 
					) AS HORAS_TOTALES
					FROM BIG_COMPENSATORIOS I
					INNER JOIN BIG_FUNCIONARIOS T ON I.ID_FUNCIONARIO = T.ID_FUNCIONARIO
					WHERE I.ID_FUNCIONARIO = ".$ID_FUNCIONARIO."
					GROUP BY T.FUN_NOMBRES, T.FUN_APELLIDOS
				";

				$request = $this->select($sql);

				// var_dump($request);

				return $request;
		}

		public function getGastadas($ID_FUNCIONARIO){
			$sql = "SELECT 
				ID_FUNCIONARIO,
				SUM(TOM_HORAS_SOLI) AS HORAS_GASTADAS
			FROM BIG_TOMA
			WHERE ID_FUNCIONARIO = ".$ID_FUNCIONARIO." AND TOM_ESTADO!=3
			GROUP BY ID_FUNCIONARIO
			";

			$request = $this->select($sql);

			return $request;
		}

		public function insertHora(string $TOM_MOTIVO, int $TOM_ESTADO, string $TOM_FECHA_SOLI,
			 string $TOM_HORAS_SOLI){

			$this->strMotivo = $TOM_MOTIVO;
			$this->intEstado = $TOM_ESTADO;
			$this->strFecha = $TOM_FECHA_SOLI;
			$this->strHoras = $TOM_HORAS_SOLI;

			$return = 0;

			$idHora = $_SESSION['userData']['ID_FUNCIONARIO'];

			// $sql = "SELECT * FROM BIG_TOMA 
			// 	WHERE FUN_CORREO = '{$this->strEmail}' or FUN_USUARIO = '{$this->intUsuario}'";
			// $request = $this->select_all($sql);

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
					'TOM_MOTIVO'=>$this->strMotivo,
					'TOM_ESTADO'=>$this->intEstado,
					'TOM_FECHA_SOLI'=>$this->strFecha,
					'TOM_HORAS_SOLI'=>$this->strHoras,
					'ID_FUNCIONARIO'=>$idHora
				);
				
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}

		public function selectHoras(int $ID_FUNCIONARIO){
			$this->intIdUsuario = $ID_FUNCIONARIO;

			$ROL_CODIGO = $_SESSION['userData']['ROL_CODIGO'];
			
			if ($ROL_CODIGO == '1A') {

				$sql = "
				SELECT 
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

			$sql = "
				SELECT 
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
				':ID_FUNCIONARIO'=>$this->intIdUsuario
			);
			$request = $this->select_all($sql, $arrData);
			return $request;
		}

		public function selectHoraVista(int $ID_TOMA){
			$this->intIdFuncionario = $ID_TOMA;
			$sql = "
				SELECT 
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
			//dep($sql);
			$request = $this->select_all($sql);

			if(empty($request)){
				$sql = "
				UPDATE BIG_FUNCIONARIOS SET FUN_IDENTIFICACION=:FUN_IDENTIFICACION,
				FUN_NOMBRES=:FUN_NOMBRES,
				FUN_APELLIDOS=:FUN_APELLIDOS,
				FUN_USUARIO=:FUN_USUARIO,
				FUN_CORREO=:FUN_CORREO,
				ID_ROL=:ID_ROL,
				FUN_ESTADO=:FUN_ESTADO 
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
			$sql = "UPDATE BIG_FUNCIONARIOS SET FUN_ESTADO = :FUN_ESTADO WHERE ID_FUNCIONARIO = $this->intIdFuncionario ";
			$arrData = array(
				'FUN_ESTADO'=>$estado
			);
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

		public function selectRoles(){
			$sql = "SELECT * FROM BIG_ROLES  
				where  ROL_ESTADO='1'
				ORDER BY ROL_NOMBRE ASC";
			$request = $this->select_all($sql);
			return $request;
		}

		public function EstadoAprobado($ID_TOMA){
			$this->intIdFuncionario = $ID_TOMA;
			$EstadoAprobado = 2;

			$sql = "UPDATE BIG_TOMA SET TOM_ESTADO = :TOM_ESTADO WHERE ID_TOMA = :ID_TOMA";

			$arrData = array(
				'TOM_ESTADO' => $EstadoAprobado,
				'ID_TOMA' => $this->intIdFuncionario
			);

			$request = $this->update($sql, $arrData);
		
			return $request;
		}

		public function CorreoAprobacion($ID_TOMA){

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
		WHERE TOM.ID_TOMA = ".$ID_TOMA."
			";

			$arrData = array(
				'ID_TOMA' => $ID_TOMA
			);

			$request = $this->select($sql, $arrData);

			return $request;
		}

		public function EstadoRechazado($ID_TOMA){
			$this->intIdFuncionario = $ID_TOMA;
			$EstadoRechazado = 3;

			$sql = "UPDATE BIG_TOMA SET TOM_ESTADO = :TOM_ESTADO WHERE ID_TOMA = :ID_TOMA";

			$arrData = array(
				'TOM_ESTADO' => $EstadoRechazado,
				'ID_TOMA' => $this->intIdFuncionario
			);

			$request = $this->update($sql, $arrData);
			
			return $request;
		}

		public function CorreoRechazo($ID_TOMA){
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
			WHERE TOM.ID_TOMA = ".$ID_TOMA."
			";

			$arrData = array(
				'ID_TOMA' => $ID_TOMA
			);

			$request = $this->select($sql, $arrData);

			return $request;
		}
	}
 ?>