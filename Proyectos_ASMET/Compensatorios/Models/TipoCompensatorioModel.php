<?php 

class TipoCompensatoriosModel extends Oracle{

	public function __construct(){
		parent::__construct();
	}
	
	public function insertCompensatorio(
		string $COM_FECHA_INICIO,
		string $COM_FECHA_FIN,
		string $COM_ACTIVIDAD_DESARROLLAR,
		string $COM_DESCRIPCION_ACTIVIDAD,
		// string $ID_FUNCIONARIO,
		string $COM_USUARIOS,
        string $COM_USUARIO_FINAL,
		string $COM_ESTADO) {
                
			$this->strFechaInicio = $COM_FECHA_INICIO;
			$this->strFechaFin = $COM_FECHA_FIN;
			$this->strDescripcionActividad = $COM_ACTIVIDAD_DESARROLLAR;
			$this->strActividad = $COM_DESCRIPCION_ACTIVIDAD;
			// $this->ListadoUsuarios = $ID_FUNCIONARIO;
			$this->ListadoUsuarios = $COM_USUARIOS;
			$this->intEstado = $COM_ESTADO;
				
			$this->strTrabajoRequerido = $COM_USUARIO_FINAL;

			$return = 0;

			// Obtener el ID del funcionario de la sesión
			$idFuncionario = $_SESSION['userData']['ID_FUNCIONARIO'];

			// Tu consulta de inserción
			$query_insert  = "
			INSERT INTO BIG_COMPENSATORIOS
			(
				ID_FUNCIONARIO,
				COM_FECHA_INICIO,
				COM_FECHA_FIN,
				COM_ACTIVIDAD_DESARROLLAR,
        		COM_DESCRIPCION_ACTIVIDAD,
        		COM_ESTADO,
        		COM_USUARIO_FINAL
			) 
			VALUES
			(
				:ID_FUNCIONARIO,
        		TO_TIMESTAMP(:COM_FECHA_INICIO, 'YYYY/MM/DD HH24:MI:SS'),
        		TO_TIMESTAMP(:COM_FECHA_FIN, 'YYYY/MM/DD HH24:MI:SS'),
				:COM_ACTIVIDAD_DESARROLLAR,
        		:COM_DESCRIPCION_ACTIVIDAD,
        		:COM_ESTADO,
        		:COM_USUARIO_FINAL
			)";

			$arrData = array(
				'ID_FUNCIONARIO' => $idFuncionario, // Usar el ID del funcionario
        		'COM_FECHA_INICIO' => $this->strFechaInicio,
        		'COM_FECHA_FIN' => $this->strFechaFin,
				'COM_ACTIVIDAD_DESARROLLAR' => $this->strDescripcionActividad,
        		'COM_DESCRIPCION_ACTIVIDAD' => $this->strActividad,
        		'COM_ESTADO' => $this->intEstado,
				'ID_FUNCIONARIO'=>$this->ListadoUsuarios,
        		'COM_USUARIO_FINAL' => $this->strTrabajoRequerido
			);

			$request_insert = $this->insert($query_insert, $arrData);

			// var_dump($arrData);

			$return = $request_insert;

			return $return; 
		}

		public function updateCompensatorio(int $intIdCompensatorio, string $fechainicio, string $fechafin, string  $descripcionactividad, string $actividad, string $usuario){


			$this->intIdFuncionario = $intIdCompensatorio;
			$this->strFechaInicio = $fechainicio;
			$this->strFechaFin = $fechafin;
			$this->strDescripcionActividad = $descripcionactividad;
			$this->strActividad = $actividad;
			$this->strTrabajoRequerido = $usuario;

			// $sql = "SELECT * FROM BIG_COMPENSATORIOS WHERE (COM_DESCRIPCION_ACTIVIDAD = '{$this->strDescripcionActividad}' OR COM_ACTIVIDAD_DESARROLLAR = '{$this->strActividad}')
    		// AND ID_COMPENSATORIO != '{$this->intIdFuncionario}'
			// ";


			// $request = $this->select_all($sql);

			if(empty($request)){
				$sql = "
    			UPDATE BIG_COMPENSATORIOS 
    			SET COM_FECHA_INICIO = TO_DATE(:COM_FECHA_INICIO, 'YYYY/MM/DD HH24:MI:SS'),
        		COM_FECHA_FIN = TO_DATE(:COM_FECHA_FIN, 'YYYY/MM/DD HH24:MI:SS'),
        		COM_DESCRIPCION_ACTIVIDAD = :COM_DESCRIPCION_ACTIVIDAD,
        		COM_ACTIVIDAD_DESARROLLAR = :COM_ACTIVIDAD_DESARROLLAR,
        		COM_USUARIO_FINAL = :COM_USUARIO_FINAL
    			WHERE ID_COMPENSATORIO = $this->intIdFuncionario
				";


				$arrData = array(
					'COM_FECHA_INICIO'=>$this->strFechaInicio,
					'COM_FECHA_FIN'=>$this->strFechaFin,
					'COM_DESCRIPCION_ACTIVIDAD'=>$this->strDescripcionActividad,
					'COM_ACTIVIDAD_DESARROLLAR'=>$this->strActividad,
					'COM_USUARIO_FINAL'=>$this->strTrabajoRequerido
				);
				$request = $this->update($sql, $arrData);
			}else{
				$request = "exist";
			}
			return $request;

		}

		// public function updateCompensatorio(int $ID_FUNCIONARIO, string $COM_FECHA_INICIO, string $COM_FECHA_FIN,
		// 	string $COM_DESCRIPCION_ACTIVIDAD, string $COM_ACTIVIDAD_DESARROLLAR, string $COM_USUARIO_FINAL){
				
		// 	$this->intIdFuncionario = $ID_FUNCIONARIO;
		// 	$this->strFechaInicio = $COM_FECHA_INICIO;
		// 	$this->strFechaFin = $COM_FECHA_FIN;
		// 	$this->strDescripcionActividad = $COM_DESCRIPCION_ACTIVIDAD;
		// 	$this->strActividad = $COM_ACTIVIDAD_DESARROLLAR;
		// 	$this->strTrabajoRequerido = $COM_USUARIO_FINAL;

		// 	$sql = "SELECT * FROM BIG_COMPENSATORIOS
		// 	WHERE (COM_DESCRIPCION_ACTIVIDAD = '{$this->strDescripcionActividad}' OR COM_USUARIO_FINAL = '{$this->strTrabajoRequerido}')
		// 	and ID_COMPENSATORIO!='{$this->intIdFuncionario}'
		// 	";

		// 	$request = $this->select_all($sql);

		// 	if(empty($request)){
		// 		$sql = "UPDATE BIG_COMPENSATORIOS SET COM_FECHA_INICIO = :COM_FECHA_INICIO,
		// 		COM_FECHA_FIN = :COM_FECHA_FIN, COM_DESCRIPCION_ACTIVIDAD = :COM_DESCRIPCION_ACTIVIDAD,
		// 		COM_ACTIVIDAD_DESARROLLAR = :COM_ACTIVIDAD_DESARROLLAR, COM_USUARIO_FINAL = :COM_USUARIO_FINAL

		// 		WHERE ID_FUNCIONARIO = $this->intIdFuncionario
		// 		";

		// 		$arrData = array(
		// 			'ID_FUNCIONARIO'=>$idFuncionario,
		// 			'COM_FECHA_INICIO'=>$this->strFechaInicio,
		// 			'COM_FECHA_FIN'=>$this->strFechaFin,
		// 			'COM_DESCRIPCION_ACTIVIDAD'=>$this->strDescripcionActividad,
		// 			'COM_ACTIVIDAD_DESARROLLAR'=>$this->strActividad,
		// 			'COM_USUARIO_FINAL'=>$this->strTrabajoRequerido
		// 		);

		// 		$request = $this->update($sql,$arrData);
		// 	}else{
		// 		$request = "exist";
		// 	}
		// 	return $request;
		// }

		public function selectEdit(int $ID_COMPENSATORIO){
			$this->intIdFuncionario = $ID_COMPENSATORIO;
			$sql = "SELECT 
				COM.ID_COMPENSATORIO,
				COM.ID_FUNCIONARIO,
				COM.COM_FECHA_FIN,
				COM.COM_FECHA_INICIO,
				COM.COM_ACTIVIDAD_DESARROLLAR,
				COM.COM_USUARIO_FINAL,
				COM.COM_DESCRIPCION_ACTIVIDAD,  
				COM.COM_ACTIVIDAD_DESARROLLAR
			FROM BIG_COMPENSATORIOS COM 
		
			WHERE COM.ID_COMPENSATORIO = $this->intIdFuncionario
			";
			$request = $this->select($sql);

			// var_dump($request);
			
			return $request;
		}

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
	
		public function selectCompensatorios(int $ID_FUNCIONARIO) {
			$this->intIdUsuario = $ID_FUNCIONARIO;

			$ROL_CODIGO = $_SESSION['userData']['ROL_CODIGO'];

			if ($ROL_CODIGO == '1A') {
				//Consulta si es usuario administrador no habra limite de filtro y se mostrara todos los registros
			$sql = " 
			SELECT
				I.ID_COMPENSATORIO,
				I.ID_FUNCIONARIO,
    			TO_CHAR(I.COM_FECHA_INICIO) AS COM_FECHA_INICIO,
    			TO_CHAR(I.COM_FECHA_FIN) AS COM_FECHA_FIN,
    			I.COM_DESCRIPCION_ACTIVIDAD,
    			I.COM_ACTIVIDAD_DESARROLLAR,
    			I.COM_USUARIO_FINAL,
    			I.COM_ESTADO,
    			F.FUN_NOMBRES AS FUN_NOMBRES,
    			F.FUN_APELLIDOS AS FUN_APELLIDOS,
				F.FUN_CORREO AS FUN_CORREO,
				F.ID_ROL AS ID_ROL
			FROM BIG_COMPENSATORIOS I
			INNER JOIN BIG_FUNCIONARIOS F ON I.ID_FUNCIONARIO = F.ID_FUNCIONARIO
			";

			$request = $this->select_all($sql);
			return $request; 
		} 
		//Consulta si es usuario normal se filtrara solo para ese usuario
		$sql = " 
		SELECT
			I.ID_COMPENSATORIO,
			I.ID_FUNCIONARIO,
			TO_CHAR(I.COM_FECHA_INICIO) AS COM_FECHA_INICIO,
			TO_CHAR(I.COM_FECHA_FIN) AS COM_FECHA_FIN,
			I.COM_DESCRIPCION_ACTIVIDAD,
			I.COM_ACTIVIDAD_DESARROLLAR,
			I.COM_USUARIO_FINAL,
			I.COM_ESTADO,
			F.FUN_NOMBRES AS FUN_NOMBRES,
			F.FUN_APELLIDOS AS FUN_APELLIDOS,
			F.FUN_CORREO AS FUN_CORREO
		FROM BIG_COMPENSATORIOS I
		INNER JOIN BIG_FUNCIONARIOS F ON I.ID_FUNCIONARIO = F.ID_FUNCIONARIO
		WHERE I.ID_FUNCIONARIO = $this->intIdUsuario"; 
		
		$arrData = array(
			':ID_FUNCIONARIO'=> $this->intIdUsuario
		);
		
		$request = $this->select_all($sql, $arrData);

		return $request; 
	}
		
		public function selectCompensatorioVista(int $ID_COMPENSATORIO) {
			$this->intIdFuncionario = $ID_COMPENSATORIO;
			$sql = "
			SELECT
				I.ID_COMPENSATORIO,
				I.ID_FUNCIONARIO,
				TO_CHAR(I.COM_FECHA_INICIO, 'DD/MM/YYYY - HH:MI AM') AS COM_FECHA_INICIO,
				TO_CHAR(I.COM_FECHA_FIN, 'DD/MM/YYYY - HH:MI AM') AS COM_FECHA_FIN,
				I.COM_DESCRIPCION_ACTIVIDAD,
				I.COM_ACTIVIDAD_DESARROLLAR,
				I.COM_EVIDENCIAS,
				I.COM_USUARIO_FINAL,
				I.COM_ESTADO,
				F.FUN_NOMBRES AS FUN_NOMBRES,
				F.FUN_APELLIDOS AS FUN_APELLIDOS,
				F.FUN_CORREO AS FUN_CORREO
			FROM BIG_COMPENSATORIOS I
			INNER JOIN BIG_FUNCIONARIOS F ON I.ID_FUNCIONARIO = F.ID_FUNCIONARIO
			WHERE I.ID_COMPENSATORIO = $this->intIdFuncionario";

			$request = $this->select($sql);
		
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