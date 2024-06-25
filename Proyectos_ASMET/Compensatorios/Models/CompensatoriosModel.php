<?php 

class CompensatoriosModel extends Oracle{

	public $intIdFuncionario;

	public $intIdCompensatorio;

	public $strFechaInicio;
	public $strFechaFin;
	public $intIdTipoCompensatorio;
	public $strDescripcionActividad;
	public $listadoUsuarios;
	public $strTrabajoRequerido;
	public $intEstado;

	public $intIdUsuario;

	public $intIdRol;

	public function __construct(){
		parent::__construct();
	}
	
	public function insertCompensatorio(
		string $fechaInicio,
		string $fechaFin,
		int $idTipoCompensatorio,
		string $descripcionActividad,
		// string $ID_FUNCIONARIO,
		string $usuarios,
        string $usuarioFinal,
		int $estado) {
                
			$this->strFechaInicio = $fechaInicio;
			$this->strFechaFin = $fechaFin;
			$this->intIdTipoCompensatorio = $idTipoCompensatorio;//Antes strActividad
			$this->strDescripcionActividad = $descripcionActividad;
			// $this->ListadoUsuarios = $ID_FUNCIONARIO;
			$this->listadoUsuarios = $usuarios;
			$this->strTrabajoRequerido = $usuarioFinal;
			$this->intEstado = $estado;
			

			$return = 0;

			// Obtener el ID del funcionario de la sesión
			$idFuncionario = $_SESSION['userData']['ID_FUNCIONARIO'];

			$this->intIdFuncionario = $idFuncionario;

			// Tu consulta de inserción
			$query_insert  = "
			INSERT INTO BIG_COMPENSATORIOS
			(
				ID_FUNCIONARIO,
				COM_FECHA_INICIO,
				COM_FECHA_FIN,
				ID_TIPO_COMPENSATORIO,
        		COM_DESCRIPCION_ACTIVIDAD,
        		COM_ESTADO,
        		COM_USUARIO_FINAL
			) 
			VALUES
			(
				:ID_FUNCIONARIO,
				TO_TIMESTAMP(:COM_FECHA_INICIO, 'YYYY/MM/DD HH24:MI:SS'),
				TO_TIMESTAMP(:COM_FECHA_FIN, 'YYYY/MM/DD HH24:MI:SS'),
				:ID_TIPO_COMPENSATORIO,
        		:COM_DESCRIPCION_ACTIVIDAD,
        		:COM_ESTADO,
        		:COM_USUARIO_FINAL
			)";

			$arrData = array(
				'ID_FUNCIONARIO' => $this->intIdFuncionario, // Usar el ID del funcionario
        		'COM_FECHA_INICIO' => $this->strFechaInicio,
        		'COM_FECHA_FIN' => $this->strFechaFin,
				'ID_TIPO_COMPENSATORIO' => $this->intIdTipoCompensatorio,
        		'COM_DESCRIPCION_ACTIVIDAD' => $this->strDescripcionActividad,
        		'COM_ESTADO' => $this->intEstado,
        		'COM_USUARIO_FINAL' => $this->strTrabajoRequerido
			);

			$request_insert = $this->insert($query_insert, $arrData);

			// var_dump($arrData);

			$return = $request_insert;

			return $return; 
		}

		public function updateCompensatorio(int $idCompensatorio, string $fechainicio, string $fechafin, string $actividad, string  $descripcionActividad, string $usuario){


			$this->intIdCompensatorio = $idCompensatorio;
			$this->strFechaInicio = $fechainicio;
			$this->strFechaFin = $fechafin;
			$this->strDescripcionActividad = $descripcionActividad;
			$this->strActividad = $actividad;
			$this->strTrabajoRequerido = $usuario;

			if(empty($request)){
				$sql = "
    			UPDATE BIG_COMPENSATORIOS 
    			SET COM_FECHA_INICIO = TO_TIMESTAMP(:COM_FECHA_INICIO, 'YYYY/MM/DD HH24:MI:SS'),
        		COM_FECHA_FIN = TO_TIMESTAMP(:COM_FECHA_FIN, 'YYYY/MM/DD HH24:MI:SS'),
        		COM_DESCRIPCION_ACTIVIDAD = :COM_DESCRIPCION_ACTIVIDAD,
        		ID_TIPO_COMPENSATORIO = :ID_TIPO_COMPENSATORIO,
        		COM_USUARIO_FINAL = :COM_USUARIO_FINAL
    			WHERE ID_COMPENSATORIO = $this->intIdCompensatorio
				";


				$arrData = array(
					'COM_FECHA_INICIO'=>$this->strFechaInicio,
					'COM_FECHA_FIN'=>$this->strFechaFin,
					'COM_DESCRIPCION_ACTIVIDAD'=>$this->strDescripcionActividad,
					'ID_TIPO_COMPENSATORIO'=>$this->strActividad,
					'COM_USUARIO_FINAL'=>$this->strTrabajoRequerido
				);
				$request = $this->update($sql, $arrData);
			}else{
				$request = "exist";
			}
			return $request;

		}

		public function selectEdit(int $idCompensatorio){

			$this->intIdCompensatorio = $idCompensatorio;

			$sql = "SELECT 
				COM.ID_COMPENSATORIO,
				COM.ID_FUNCIONARIO,
				COM.COM_FECHA_FIN,
				COM.COM_FECHA_INICIO,
				COM.ID_TIPO_COMPENSATORIO,
				COM.COM_USUARIO_FINAL,
				COM.COM_DESCRIPCION_ACTIVIDAD,  
				COM.ID_TIPO_COMPENSATORIO
			FROM BIG_COMPENSATORIOS COM 
		
			WHERE COM.ID_COMPENSATORIO = $this->intIdCompensatorio
			";
			$request = $this->select($sql);

			// var_dump($request);
			
			return $request;
		}

		public function recuperar(int $idFuncionario){

			$this->intIdFuncionario = $idFuncionario;

			$sql = "SELECT
				FUN.ID_FUNCIONARIO,
				FUN.FUN_NOMBRES || ' ' ||FUN.FUN_APELLIDOS NOMBREFUNCIONARIO,
				FUN.FUN_CORREO,
				FUN.FUN_USUARIO
			FROM BIG_FUNCIONARIOS FUN
			WHERE FUN.ID_FUNCIONARIO = $this->$intIdFuncionario
			";

			$arrData = array(
				'ID_FUNCIONARIO' => $this->intIdFuncionario
			);

			$request = $this->select($sql, $arrData);

			return $request;
		}
	
		public function selectCompensatorios(int $idFuncionario) {

			$this->intIdUsuario = $idFuncionario;

			$ROL_CODIGO = $_SESSION['userData']['ROL_CODIGO'];

			if ($ROL_CODIGO == '1A') {

				$sql = "SELECT
					I.ID_COMPENSATORIO,
					I.ID_FUNCIONARIO,
					TO_CHAR(I.COM_FECHA_INICIO) AS COM_FECHA_INICIO,
					TO_CHAR(I.COM_FECHA_FIN) AS COM_FECHA_FIN,
					I.ID_TIPO_COMPENSATORIO,
					I.COM_DESCRIPCION_ACTIVIDAD,
					I.COM_USUARIO_FINAL,
					I.COM_ESTADO,
					F.FUN_NOMBRES AS FUN_NOMBRES,
					F.FUN_APELLIDOS AS FUN_APELLIDOS,
					F.FUN_CORREO AS FUN_CORREO,
					F.ID_ROL AS ID_ROL
				FROM BIG_COMPENSATORIOS I
				INNER JOIN BIG_FUNCIONARIOS F ON I.ID_FUNCIONARIO = $this->intIdUsuario
				";
				//F.ID_FUNCIONARIO

				$request = $this->select_all($sql);

				return $request; 
			} 
		//Consulta si es usuario normal se filtrara solo para ese usuario
			$sql = "SELECT
				I.ID_COMPENSATORIO,
				I.ID_FUNCIONARIO,
				TO_CHAR(I.COM_FECHA_INICIO) AS COM_FECHA_INICIO,
				TO_CHAR(I.COM_FECHA_FIN) AS COM_FECHA_FIN,
				I.ID_TIPO_COMPENSATORIO,
				I.COM_DESCRIPCION_ACTIVIDAD,
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
		
		public function selectCompensatorioVista(int $idCompensatorio) {

			$this->intIdCompensatorio = $idCompensatorio;

			$sql = "SELECT
				I.ID_COMPENSATORIO,
				I.ID_FUNCIONARIO,
				TO_CHAR(I.COM_FECHA_INICIO, 'DD/MM/YYYY - HH:MI AM') AS COM_FECHA_INICIO,
				TO_CHAR(I.COM_FECHA_FIN, 'DD/MM/YYYY - HH:MI AM') AS COM_FECHA_FIN,
				I.ID_TIPO_COMPENSATORIO,
				I.COM_DESCRIPCION_ACTIVIDAD,
				I.COM_EVIDENCIAS,
				I.COM_USUARIO_FINAL,
				I.COM_ESTADO,
				F.FUN_NOMBRES AS FUN_NOMBRES,
				F.FUN_APELLIDOS AS FUN_APELLIDOS,
				F.FUN_CORREO AS FUN_CORREO
			FROM BIG_COMPENSATORIOS I
			INNER JOIN BIG_FUNCIONARIOS F ON I.ID_FUNCIONARIO = F.ID_FUNCIONARIO
			WHERE I.ID_COMPENSATORIO = $this->intIdCompensatorio";

			$request = $this->select($sql);
		
			return $request;
		}
		
		// Método para cambiar el estado del compensatorio 
		
		public function estadoAprobado(int $idCompensatorio){

			$this->intIdCompensatorio = $idCompensatorio;

			$estadoAprobado = 2;

			$sql = "UPDATE BIG_COMPENSATORIOS SET COM_ESTADO = :COM_ESTADO WHERE ID_COMPENSATORIO = :ID_COMPENSATORIO";

			$arrData = array(
				'COM_ESTADO' => $estadoAprobado,
				'ID_COMPENSATORIO' => $this->intIdCompensatorio
			);

			$request = $this->update($sql, $arrData);
		
			return $request;
		}		

		public function correoAprobacion(int $idCompensatorio){

			$this->intIdFuncionario = $idCompensatorio;

			$sql = "SELECT
				FUN.ID_FUNCIONARIO,
				FUN.FUN_NOMBRES,
				FUN.FUN_APELLIDOS,
				FUN.FUN_CORREO,
				FUN.FUN_USUARIO,
				COM.COM_FECHA_INICIO,  -- Reemplaza 'OtroCampo1' con el nombre del campo que deseas seleccionar
				COM.COM_FECHA_FIN,  -- Reemplaza 'OtroCampo2' con el nombre del campo que deseas seleccionar
				COM.ID_TIPO_COMPENSATORIO,
				COM.COM_DESCRIPCION_ACTIVIDAD,
				COM.COM_USUARIO_FINAL
			FROM BIG_COMPENSATORIOS COM
			INNER JOIN BIG_FUNCIONARIOS FUN ON FUN.ID_FUNCIONARIO = COM.ID_FUNCIONARIO
			WHERE COM.ID_COMPENSATORIO = $this->intIdFuncionario
			";

			$arrData = array(
				'ID_COMPENSATORIO' => $this->intIdFuncionario
			);

			$request = $this->select($sql, $arrData);

			return $request;
		}

		public function CorreoRechazo(int $idCompensatorio){

			$this->intIdCompensatorio = $idCompensatorio;

			$sql = "SELECT 
			FUN.ID_FUNCIONARIO,
			FUN.FUN_NOMBRES, 
			FUN.FUN_APELLIDOS, 
			FUN.FUN_CORREO,
			FUN.FUN_USUARIO, 
			COM.COM_FECHA_INICIO,
			COM.COM_FECHA_FIN,
			COM.ID_TIPO_COMPENSATORIO,
			COM.COM_DESCRIPCION_ACTIVIDAD,
			COM.COM_USUARIO_FINAL
			FROM BIG_COMPENSATORIOS COM
			INNER JOIN BIG_FUNCIONARIOS FUN ON FUN.ID_FUNCIONARIO = COM.ID_FUNCIONARIO
			WHERE COM.ID_COMPENSATORIO = $this->$intIdCompensatorio
			";

			$arrData = array(
				'ID_COMPENSATORIO' => $this->$intIdCompensatorio
			);

			$request = $this->select($sql, $arrData);

			return $request;
		}

		// Metodo para cambiar el estado del compensatorio

		public function estadoRechazado(int $idCompensatorio){
			$this->intIdCompensatorio = $idCompensatorio;
			$this->$estadoRechazado = 3;
		
			$sql = "UPDATE BIG_COMPENSATORIOS SET COM_ESTADO = :COM_ESTADO WHERE ID_COMPENSATORIO = :ID_COMPENSATORIO";

			$arrData = array(
				'COM_ESTADO' => $this->$estadoRechazado,
				'ID_COMPENSATORIO' => $this->intIdCompensatorio
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

		public function resetPassFuncionario(int $idFuncionario, string $fun_password){
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

		public function selectTipoCompensatorio(){
			// Hago un select a la db para recuperar todos los tipos de compensatorios.
			$sql = "SELECT ID_TIPO_COMPENSATORIO, TIP_COM_NOMBRE FROM BIG_TIPO_COMPENSATORIO TC WHERE TC.TIP_COM_ESTADO='1'";
			$request = $this->select_all($sql);
			return $request;
		}

		//Estado para el tipo de comp
		
		public function esAdministrador(int $idRol) {

			$this->intIdRol = $idRol;
	
			$sql = "SELECT distinct ID_ROL FROM BIG_FUNCIONARIOS WHERE ID_ROL = ID_ROL";
	
			$arrData = array(
				':ID_ROL' 		=> $this->intIdRol
			);

			$request = $this->select($sql, $arrData);
			
			return $request['ID_ROL'] == 1;		
		}

		public function guardarEvidencia(string $evidencia, int $idCompensatorio){ // Esta definitivamente funciona
			$this->strEvidencia = $evidencia;
			$this->intIdCompensatorio = $idCompensatorio;

			$sql = "UPDATE BIG_COMPENSATORIOS SET COM_EVIDENCIAS = :COM_EVIDENCIAS WHERE ID_COMPENSATORIO = :ID_COMPENSATORIO";

			$arrData = array(
				'COM_EVIDENCIAS'		=>$this->strEvidencia,
				'ID_COMPENSATORIO' 		=>$this->intIdCompensatorio
			);
			
			$request = $this->update($sql, $arrData);

			return $request;		
		}
	}
 ?>