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
	
	//----Función de inserción----
	//Modulo de inserción de compensatorios
	public function insertCompensatorio(
	string $fechaInicio,
	string $fechaFin,
	string $idTipoCompensatorio,
	string $descripcionActividad,
	string $usuarios,
	string $usuarioFinal,
	string $estado) {
			
		$this->strFechaInicio = $fechaInicio;
		$this->strFechaFin = $fechaFin;
		$this->intIdTipoCompensatorio = $idTipoCompensatorio;//Antes strActividad
		$this->strDescripcionActividad = $descripcionActividad;
		$this->listadoUsuarios = $usuarios;
		$this->strTrabajoRequerido = $usuarioFinal;
		$this->intEstado = $estado;

		$return = 0;

		// Obtener el ID del funcionario de la sesión
		$idFuncionario = $_SESSION['userData']['ID_FUNCIONARIO'];
		$this->intIdFuncionario = $idFuncionario;

		//Validar duplicidad de datos
		$sql = "SELECT * FROM BIG_COMPENSATORIOS 
			WHERE ID_FUNCIONARIO = '{$this->intIdFuncionario}' 
			AND COM_FECHA_INICIO = TO_TIMESTAMP('{$this->strFechaInicio}', 'YYYY/MM/DD HH24:MI:SS') 
			AND COM_FECHA_FIN = TO_TIMESTAMP('{$this->strFechaFin}', 'YYYY/MM/DD HH24:MI:SS') 
			AND ID_TIPO_COMPENSATORIO = '{$this->intIdTipoCompensatorio}' 
			AND COM_USUARIO_FINAL = '{$this->strTrabajoRequerido}'
			AND COM_DESCRIPCION_ACTIVIDAD = '{$this->strDescripcionActividad}'";

		$request = $this->select_all($sql);

		if(empty($request)){

			$query_insert  = "INSERT INTO BIG_COMPENSATORIOS
			(
				ID_FUNCIONARIO,
				COM_FECHA_INICIO,
				COM_FECHA_FIN,
				ID_TIPO_COMPENSATORIO,
				COM_DESCRIPCION_ACTIVIDAD,
				COM_USUARIO_FINAL,
				COM_ESTADO
			) 
			VALUES
			(
				:ID_FUNCIONARIO,
				TO_TIMESTAMP(:COM_FECHA_INICIO, 'YYYY/MM/DD HH24:MI:SS'),
				TO_TIMESTAMP(:COM_FECHA_FIN, 'YYYY/MM/DD HH24:MI:SS'),
				:ID_TIPO_COMPENSATORIO,
				:COM_DESCRIPCION_ACTIVIDAD,
				:COM_USUARIO_FINAL,
				:COM_ESTADO
			)";
	
			$arrData = array(
				'ID_FUNCIONARIO' 			=> $this->intIdFuncionario, // Usar el ID del funcionario
				'COM_FECHA_INICIO' 			=> $this->strFechaInicio,
				'COM_FECHA_FIN' 			=> $this->strFechaFin,
				'ID_TIPO_COMPENSATORIO' 	=> $this->intIdTipoCompensatorio,
				'COM_DESCRIPCION_ACTIVIDAD' => $this->strDescripcionActividad,
				'COM_USUARIO_FINAL' 		=> $this->strTrabajoRequerido,
				'COM_ESTADO' 				=> $this->intEstado,
				'ID_FUNCIONARIO'			=> $this->listadoUsuarios
			);
	
			$request_insert = $this->insert($query_insert, $arrData);
			$return = $request_insert;

		}else{
			$return = "exist";
		}

		return $return;
	}

	//---Funciones de lectura----
	//Modulo de lectura de datos para editar compensatorio
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
	//Modulo para recolectar información del usuario
	public function recuperar(int $idFuncionario){

		$this->intIdFuncionario = $idFuncionario;

		$sql = "SELECT
			FUN.ID_FUNCIONARIO,
			FUN.FUN_NOMBRES || ' ' ||FUN.FUN_APELLIDOS NOMBREFUNCIONARIO,
			FUN.FUN_CORREO,
			FUN.FUN_USUARIO
		FROM BIG_FUNCIONARIOS FUN
		WHERE FUN.ID_FUNCIONARIO = $this->intIdFuncionario
		";

		$arrData = array(
			'ID_FUNCIONARIO' => $this->intIdFuncionario
		);

		$request = $this->select($sql, $arrData);

		return $request;
	}
	//Modulo de llenar datatable, de admin y usuario normal
	public function selectCompensatorios(int $idFuncionario) {

		$this->intIdUsuario = $idFuncionario;

		$rolCodigo = $_SESSION['userData']['ROL_CODIGO'];
		//Consulta para admin
		if ($rolCodigo == '1A') {

			$sql = "SELECT
				I.ID_COMPENSATORIO,
				I.ID_FUNCIONARIO,
				TO_CHAR(I.COM_FECHA_INICIO) AS COM_FECHA_INICIO,
				TO_CHAR(I.COM_FECHA_FIN) AS COM_FECHA_FIN,
				TC.TIP_COM_NOMBRE AS TIP_COM_NOMBRE,
				I.COM_DESCRIPCION_ACTIVIDAD,
				I.COM_USUARIO_FINAL,
				I.COM_ESTADO,
				F.FUN_NOMBRES AS FUN_NOMBRES,
				F.FUN_APELLIDOS AS FUN_APELLIDOS,
				F.FUN_CORREO AS FUN_CORREO,
				F.ID_ROL AS ID_ROL
			FROM BIG_COMPENSATORIOS I
			INNER JOIN BIG_TIPO_COMPENSATORIO TC ON I.ID_TIPO_COMPENSATORIO = TC.ID_TIPO_COMPENSATORIO
			INNER JOIN BIG_FUNCIONARIOS F ON I.ID_FUNCIONARIO = F.ID_FUNCIONARIO
			";

			$request = $this->select_all($sql);

			return $request; 
		} 

		//Consulta si es usuario normal se filtrara solo para ese usuario
		$sql = "SELECT
			I.ID_COMPENSATORIO,
			I.ID_FUNCIONARIO,
			TO_CHAR(I.COM_FECHA_INICIO) AS COM_FECHA_INICIO,
			TO_CHAR(I.COM_FECHA_FIN) AS COM_FECHA_FIN,
			TC.TIP_COM_NOMBRE AS TIP_COM_NOMBRE,
			I.COM_DESCRIPCION_ACTIVIDAD,
			I.COM_USUARIO_FINAL,
			I.COM_ESTADO,
			F.FUN_NOMBRES AS FUN_NOMBRES,
			F.FUN_APELLIDOS AS FUN_APELLIDOS,
			F.FUN_CORREO AS FUN_CORREO
		FROM BIG_COMPENSATORIOS I
		INNER JOIN BIG_FUNCIONARIOS F ON I.ID_FUNCIONARIO = F.ID_FUNCIONARIO
		INNER JOIN BIG_TIPO_COMPENSATORIO TC ON I.ID_TIPO_COMPENSATORIO = TC.ID_TIPO_COMPENSATORIO
		WHERE I.ID_FUNCIONARIO = $this->intIdUsuario"; 
		
		$arrData = array(
			':ID_FUNCIONARIO'=> $this->intIdUsuario
		);
		
		$request = $this->select_all($sql, $arrData);

		return $request; 
	}
	//Modulo para llenar el modal de detalles del compensatorio
	public function selectCompensatorioVista(int $idCompensatorio) {

		$this->intIdCompensatorio = $idCompensatorio;

		$sql = "SELECT
			I.ID_COMPENSATORIO,
			I.ID_FUNCIONARIO,
			TO_CHAR(I.COM_FECHA_INICIO, 'DD/MM/YYYY - HH:MI AM') AS COM_FECHA_INICIO,
			TO_CHAR(I.COM_FECHA_FIN, 'DD/MM/YYYY - HH:MI AM') AS COM_FECHA_FIN,
			TC.TIP_COM_NOMBRE AS TIP_COM_NOMBRE,
			I.COM_DESCRIPCION_ACTIVIDAD,
			I.COM_EVIDENCIAS,
			I.COM_USUARIO_FINAL,
			I.COM_ESTADO,
			F.FUN_NOMBRES AS FUN_NOMBRES,
			F.FUN_APELLIDOS AS FUN_APELLIDOS,
			F.FUN_CORREO AS FUN_CORREO
		FROM BIG_COMPENSATORIOS I
		INNER JOIN BIG_FUNCIONARIOS F ON I.ID_FUNCIONARIO = F.ID_FUNCIONARIO
		INNER JOIN BIG_TIPO_COMPENSATORIO TC ON I.ID_TIPO_COMPENSATORIO = TC.ID_TIPO_COMPENSATORIO
		WHERE I.ID_COMPENSATORIO = $this->intIdCompensatorio";

		$request = $this->select($sql);
	
		return $request;
	}
	//Modulo para llenar el select de usuario en el modal de registro de compensatorios 
	public function selectUsuarios(){
		
		$whereAdmin = "";
		if ($_SESSION['idUser'] != 1 ){

			$whereAdmin = " and ID_FUNCIONARIO != 1 ";

		}
		// EXTRAE ROLES excluyendo el usuario con ID_FUNCIONARIO = 1
		$sql = "SELECT * FROM BIG_FUNCIONARIOS WHERE FUN_ESTADO != 0" . $whereAdmin;
		$request = $this->select_all($sql);

		return $request;
	}
	//Modulo para llenar el select de tipo de compensatorio
	public function selectTipoCompensatorio(){
		// Hago un select a la db para recuperar todos los tipos de compensatorios activos.
		$sql = "SELECT ID_TIPO_COMPENSATORIO, TIP_COM_NOMBRE FROM BIG_TIPO_COMPENSATORIO TC WHERE TC.TIP_COM_ESTADO='1'";
		$request = $this->select_all($sql);
		return $request;
	}

	//-----Funciones para actualización de datos-----
	//Modulo de actualización para el compensatorio
	public function updateCompensatorio(int $idCompensatorio, string $fechainicio, string $fechafin, string $idTipoCompensatorio, string  $descripcionActividad, string $usuarioFinal){

		$this->intIdCompensatorio = $idCompensatorio;
		$this->strFechaInicio = $fechainicio;
		$this->strFechaFin = $fechafin;
		$this->intIdTipoCompensatorio = $idTipoCompensatorio;
		$this->strDescripcionActividad = $descripcionActividad;
		$this->strTrabajoRequerido = $usuarioFinal;

		//Validar duplicidad de datos
		$sql = "SELECT * FROM BIG_COMPENSATORIOS
			WHERE ID_FUNCIONARIO = '{$this->intIdFuncionario}' 
			AND COM_FECHA_INICIO = TO_TIMESTAMP('{$this->strFechaInicio}', 'YYYY/MM/DD HH24:MI:SS') 
			AND COM_FECHA_FIN = TO_TIMESTAMP('{$this->strFechaFin}', 'YYYY/MM/DD HH24:MI:SS') 
			AND ID_TIPO_COMPENSATORIO = '{$this->intIdTipoCompensatorio}' 
			AND COM_DESCRIPCION_ACTIVIDAD = '{$this->strDescripcionActividad}'
			AND COM_USUARIO_FINAL = '{$this->strTrabajoRequerido}'
			AND ID_COMPENSATORIO != $this->intIdCompensatorio";
			
		$request = $this->select_all($sql);

		if(empty($request)){
			$sqlUpdate = "UPDATE BIG_COMPENSATORIOS 
				SET COM_FECHA_INICIO = TO_TIMESTAMP(:COM_FECHA_INICIO, 'YYYY/MM/DD HH24:MI:SS'),
				COM_FECHA_FIN = TO_TIMESTAMP(:COM_FECHA_FIN, 'YYYY/MM/DD HH24:MI:SS'),
				ID_TIPO_COMPENSATORIO = :ID_TIPO_COMPENSATORIO,
				COM_DESCRIPCION_ACTIVIDAD = :COM_DESCRIPCION_ACTIVIDAD,
				COM_USUARIO_FINAL = :COM_USUARIO_FINAL
				WHERE ID_COMPENSATORIO = $this->intIdCompensatorio
			";

			$arrData = array(
				'ID_COMPENSATORIO'				=>$this->intIdCompensatorio,
				'COM_FECHA_INICIO'				=>$this->strFechaInicio,
				'COM_FECHA_FIN'					=>$this->strFechaFin,
				'ID_TIPO_COMPENSATORIO'			=>$this->intIdTipoCompensatorio,
				'COM_DESCRIPCION_ACTIVIDAD'		=>$this->strDescripcionActividad,
				'COM_USUARIO_FINAL'				=>$this->strTrabajoRequerido
			);
			$requestUpdate = $this->update($sqlUpdate, $arrData);
		}else{
			$requestUpdate = "exist";
		}
		return $requestUpdate;

	}
	//Modulo para cambiar el estado del compensatorio aprobado
	public function estadoAprobado(int $idCompensatorio){

		$this->intIdCompensatorio = $idCompensatorio;
		$estadoAprobado = 2;

		$sql = "UPDATE BIG_COMPENSATORIOS SET COM_ESTADO = :COM_ESTADO WHERE ID_COMPENSATORIO = :ID_COMPENSATORIO";

		$arrData = array(
			'COM_ESTADO' 		=> $estadoAprobado,
			'ID_COMPENSATORIO' 	=> $this->intIdCompensatorio
		);

		$request = $this->update($sql, $arrData);
	
		return $request;
	}
	//Modulo para cambiar el estado del compensatorio rechazado
	public function estadoRechazado(int $idCompensatorio){

		$this->intIdCompensatorio = $idCompensatorio;
		$estadoRechazado = 3;
	
		$sql = "UPDATE BIG_COMPENSATORIOS SET COM_ESTADO = :COM_ESTADO WHERE ID_COMPENSATORIO = :ID_COMPENSATORIO";

		$arrData = array(
			'COM_ESTADO' 		=> $estadoRechazado,
			'ID_COMPENSATORIO' 	=> $this->intIdCompensatorio
		);

		$request = $this->update($sql, $arrData);
		
		return $request;
	}		
	//Modulo para envío del correo de aprobación o rechazo del compensatorio
	public function correoAprobacionORechazo(int $idCompensatorio){

		$this->intIdFuncionario = $idCompensatorio;

		$sql = "SELECT
			FUN.ID_FUNCIONARIO,
			FUN.FUN_NOMBRES,
			FUN.FUN_APELLIDOS,
			FUN.FUN_CORREO,
			FUN.FUN_USUARIO,
			COM.COM_FECHA_INICIO,  -- Reemplaza 'OtroCampo1' con el nombre del campo que deseas seleccionar
			COM.COM_FECHA_FIN,  -- Reemplaza 'OtroCampo2' con el nombre del campo que deseas seleccionar
			TC.TIP_COM_NOMBRE,
			COM.COM_DESCRIPCION_ACTIVIDAD,
			COM.COM_USUARIO_FINAL
		FROM BIG_COMPENSATORIOS COM
		INNER JOIN BIG_FUNCIONARIOS FUN ON FUN.ID_FUNCIONARIO = COM.ID_FUNCIONARIO
		INNER JOIN BIG_TIPO_COMPENSATORIO TC ON COM.ID_TIPO_COMPENSATORIO = TC.ID_TIPO_COMPENSATORIO
		WHERE COM.ID_COMPENSATORIO = $this->intIdFuncionario
		";

		$arrData = array(
			'ID_COMPENSATORIO' => $this->intIdFuncionario
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
	//Modulo para guardar la evidencia del compensatorio
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