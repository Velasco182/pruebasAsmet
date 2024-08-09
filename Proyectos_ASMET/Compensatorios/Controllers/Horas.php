<?php 

class Horas extends Controllers{
		
	public function __construct(){
		parent::__construct();
		session_start();
		session_regenerate_id();

		if(empty($_SESSION['login'])){
			header('Location: '.base_url().'/login');
		}else{
			getPermisos(COD_MOD_HOR);
			if(isset($_SESSION['permisosMod']['PER_R'])){
				if($_SESSION['permisosMod']['PER_R']==1){
					//ingresa al modulo
				}else{
					header('Location: '.base_url().'/dashboard');
				}
			}else{
				header('Location: '.base_url().'/dashboard');
			}
		}
	}

	public function Horas(){
		$data['page_tag'] = $_SESSION['permisosMod']['MOD_TITULO'];
		$data['page_title'] = $_SESSION['permisosMod']['MOD_TITULO'];
		$data['page_name'] = $_SESSION['permisosMod']['MOD_TITULO'];
		$data['page_icono'] = $_SESSION['permisosMod']['MOD_ICONO'];
		$data['page_acceso'] = $_SESSION['permisosMod']['MOD_ACCESO'];
		$data['page_functions_js'] = "functions_".$_SESSION['permisosMod']['MOD_ACCESO'].".js";
		$this->views->getView($this,$_SESSION['permisosMod']['MOD_ACCESO'],$data);
	}

	//-----Funciones de isnserción-----
	//Modulo para creación y actualización de horas
	public function setHora(){

		if($_POST){

			if($_POST['txtMotivo']=='' || $_POST['txtEstado']==''
			|| $_POST['txtFecha']=='' || $_POST['txtHoras']==''){
				
				$arrResponse = array("status" => false, "msg" => 'Ingrese todos los datos.');
			
			}else{

				$intIdToma = intval($_POST['idToma']);

				$strMotivo = mb_convert_case(strClean($_POST['txtMotivo']), MB_CASE_TITLE, "UTF-8");
				$intEstado = intval($_POST['txtEstado']);
				$strFecha = $_POST['txtFecha'];
				$strHoras = floatval($_POST['txtHoras']);

				$listadoUsuarios = intval(strClean($_POST['listaUsuarios']));

				if(!empty($listadoUsuarios)){

					$idFuncionario = $listadoUsuarios;

				}else{

					$idFuncionario = $_SESSION['userData']['ID_FUNCIONARIO'];

				}

				$option = 0;
				$request_user = 0;
				
				$horasExistentes = $this->model->getHorasExistentes($idFuncionario);

				$rolCodigo = $_SESSION['userData']['ROL_CODIGO'];
				
				if((in_array($rolCodigo, ROLES_ADMIN)) || !empty($horasExistentes) && is_array($horasExistentes)){

					if(!empty($horasExistentes)){

						$horas = $horasExistentes[0];
		
						if(array_key_exists('HORAS_APROBADAS_SIN_TOMA', $horas)) {

							if($horasExistentes){
								$funcionario = $horasExistentes[0]['NOMBREFUNCIONARIO'];
								$horasAprobadasCompensatorios = floatval($horasExistentes[0]['HORAS_APROBADAS_SIN_TOMA']);
								$horasAprobadas = 0;
								$horasDisponibles = 0;
							}else{
								$funcionario = "";
								$horasAprobadasCompensatorios = 0;
								$horasAprobadas = 0;
								$horasDisponibles = 0;
							}
			
							if($horasAprobadasCompensatorios<=0){
								$msjResta="No tiene horas para tomar";
							}else{
								$msjResta="Tiene ".$horasAprobadasCompensatorios." hora/s para tomar.";
							}

						}else{

							if($horasExistentes){
								$horasDisponibles = floatval($horasExistentes[0]['HORAS_DISPONIBLES']);
								$horasAprobadas = floatval($horasExistentes[0]['HORAS_APROBADAS']);
								$horasAprobadasCompensatorios = floatval($horasExistentes[0]['HORAS_COMPENSATORIOS_APROBADAS']);
								$funcionario = $horasExistentes[0]['NOMBREFUNCIONARIO'];
							}else{
								$horasDisponibles = 0;
								$horasAprobadas = 0;
								$horasAprobadasCompensatorios = 0;
								$funcionario = "";
							}
							
							if($horasDisponibles<=0){
								$msjResta="No tiene horas para tomar.";
							}else{
								$msjResta="Tiene ".$horasDisponibles." hora/s para tomar.";
							}

						}

					}else{
						$arrResponse = array('status' => false, 'msg' => 'No tienes horas disponibles.');
						$msjResta = "No tiene horas para tomar.";
						$horasAprobadasCompensatorios = 0;
					}
				}

				if(($horasAprobadasCompensatorios && $strHoras) > 0
					&& ($horasAprobadasCompensatorios - $horasAprobadas) > 0
					&& ($horasAprobadasCompensatorios - $horasAprobadas) >= $strHoras){

					if($intIdToma == 0){
						
						if($_SESSION['permisosMod']['PER_W']){

							$request_user = $this->model->insertHora(
								$strMotivo,
								$intEstado,
								$strFecha,
								$strHoras,
								$listadoUsuarios
							);
							$option = 1; //Inserción

						}

					}else{

						if($_SESSION['permisosMod']['PER_U']){

							$request_user = $this->model->updateHora(
								$intIdToma,
								$strMotivo,
								$strFecha,
								$strHoras
							);
							$option = 2; //Actualización

						}
					}

					$arrResponse = array('status' => false, 'msg' => 'Registro de horas existente!');
					$diferencia = array('status' => false, 'msg' => 'Deben ser por lo menos 30 minutos.');
					
					if($option == 1){
						
						$remitente = 'estivenmendez550@gmail.com';
						$destinatario = 'aprendiz.bi@asmetsalud.com';
						$asunto = 'Solicitud de horas';
						
						$datos = [
								'Funcionario' 		=> 	$funcionario,
								'MotivoSolicitud' 	=>	$_POST['txtMotivo'],
								'FechaSolicitud' 	=> 	$_POST['txtFecha'],
								'HorasSolicitar' 	=>	$_POST['txtHoras']
							];

							try {

								if($request_user === "time_error"){
									$arrResponse = $diferencia;
								}elseif ($request_user === "exist"){
									$arrResponse;
								}else{

									if(in_array($rolCodigo, ROLES_ADMIN)){
										$arrResponse = array('status' => true, 'msg' => 'La solicitud de horas fue procesada con éxito, ya la puedes aprobar!');
									}else{
										$arrResponse = array('status' => true, 'msg' => 'Su solicitud de horas fue procesada con éxito, espera que un admin la apruebe!');
									}

									$enviarcorreo = enviarMail($remitente, $destinatario, $asunto, 'solicitud_horas', $datos);
								}
								
							} catch (Exception $e) {
								$arrResponse = array('status' => false, 'msg' => 'Error al enviar el correo: ' . $e->getMessage());
							}
							
					} else {

						if($request_user === "time_error"){
							$arrResponse = $diferencia;
						}else{
							$request_user === "exist" ? $arrResponse : $arrResponse = array('status' => true, 'msg' =>'Registro de horas actualizado correctamente!');
						}
					}

				}else{

					$arrResponse = array('status' => false, 'msg' => $msjResta);
					
				}
			}
		}

		echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
	}

	//-----Funciones de recuperación de datos------
	//Modulo para llenar datatable llamando al modelo selectHoras
	public function obtenerHoras(){
		if($_SESSION['permisosMod']['PER_R']){

			$idFuncionario = $_SESSION['userData']['ID_FUNCIONARIO'];
			$idFuncionario = intval($idFuncionario);

			$rolCodigo = $_SESSION['userData']['ROL_CODIGO'];

			$arrData = $this->model->selectHoras($idFuncionario);

			for ($i=0; $i < count($arrData); $i++) {
				
				$arrData[$i]['TOM_FECHA_SOLI']=formatearFechaUsuComparar($arrData[$i]['TOM_FECHA_SOLI'],"d/m/Y");
				$arrData[$i]['TOM_HORAS_SOLI']=floatval($arrData[$i]['TOM_HORAS_SOLI']);

				$btnView = '';
				$btnAprobar = '';
				$btnRechazar = '';
				$btnEdit = '';

				$newStatus="";
					
				$comEstado = $arrData[$i]['TOM_ESTADO'];
				if ($comEstado == 1) {
					$statusClass = 'badge-warning';
					$newStatus = 'Pendiente';
				} elseif ($comEstado == 2) {
					$statusClass = 'badge-success';
					$newStatus = 'Aprobado';
				} elseif ($comEstado == 3) {
					$statusClass = 'badge-danger';
					$newStatus = 'Rechazado';
				} else {
					$statusClass = 'badge-secondary';
					$newStatus = 'Estado Desconocido';
				}
				
				$arrData[$i]['TOM_ESTADO'] = '<span class="badge ' . $statusClass . '">' . $newStatus . '</span>';

				if($_SESSION['permisosMod']['PER_R']){
					if($arrData[$i]['TOM_FECHA_SOLI'] !="1"){
						$btnView = '<button class="btn btn-info btn-sm btnViewFuncionario" onClick="fntViewHora('.$arrData[$i]['ID_TOMA'].')" title="Ver horas"><i class="far fa-eye"></i></button>';
					}else{
						$btnView = '<button class="btn btn-info btn-sm"><i class="far fa-eye"></i></button>';
					}
					
					if($comEstado == 3){
						$btnView = '<button class="btn btn-info btn-sm btnViewFuncionario" onClick="fntViewHora('.$arrData[$i]['ID_TOMA'].')" title="Ver horas"><i class="far fa-eye"></i></button>';
					}
				}

				//Botón para editar
				if ($_SESSION['permisosMod']['PER_U']) {
					if($arrData[$i]['TOM_FECHA_SOLI'] != "1" && $comEstado == 1 && intval($arrData[$i]['ID_FUNCIONARIO']) === $idFuncionario) {
						$btnEdit = '<button class="btn btn-primary btn-sm btnEditFuncionario" onClick="fntEditToma(this,'.$arrData[$i]['ID_TOMA'].')" title="Editar toma de horas"><i class="fas fa-pencil-alt"></i></button>';
					} else {
						$btnEdit = '';
					}
				}

				if(in_array($rolCodigo, ROLES_ADMIN)){
					
					//Botón para aprobar de solicitud
					if ($_SESSION['permisosMod']['PER_U']) {
						if ($comEstado == 1) {//|| $comEstado == 3
							$btnAprobar = '<button class="btn btn-sm btn-primary" onClick="fntAprobar('. $arrData[$i]['ID_TOMA'] .')" title="Aprobar Horas"><i class="fas fa-check-circle"></i></button>';
						} else {
							$btnAprobar = '';//<button class="btn btn-secondary btn-sm" disabled><i class="fas fa-check-circle"></i></button>
						}
					}
					
					//Botón para rechazo de solicitud
					if ($_SESSION['permisosMod']['PER_U']) {
						if ($comEstado == 1){//|| $comEstado == 2
							$btnRechazar = '<button class="btn btn-danger btn-sm btnDelFuncionario" onClick="fntRechazar('.$arrData[$i]['ID_TOMA'].')" title="Rechazar Horas"><i class="fas fa-ban"></i></button>';
						} else {
							$btnRechazar = '';//<button class="btn btn-secondary btn-sm" disabled><i class="fas fa-times-circle"></i></button>
						}
					}

				}

				$arrData[$i]['ACCIONES'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnAprobar.' '.$btnRechazar.'</div>';
			}
			echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
		}
	}
	//Modulo para obtener horas por id
	public function getHora($idToma){
		if($_SESSION['permisosMod']['PER_R']){
			$idToma = intval($idToma);
			if($idToma > 0){
				$arrData = $this->model->selectHoraVista($idToma);
				if(empty($arrData)){
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				}else{
					$arrResponse = array('status' => true, 'data' => $arrData);
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
		}
	}
	//Modulo para obtener horas disponibles por id
	public function obtenerHorasDisponibles($idFuncionario){

		$idFuncionario = intval($idFuncionario);
		$rolCodigo = $_SESSION['userData']['ROL_CODIGO'];

		if($idFuncionario > 0){
			
			$horasExistentes = $this->model->getHorasExistentes($idFuncionario);

			$horas = 0;

			if((in_array($rolCodigo, ROLES_ADMIN)) || (!empty($horasExistentes) && is_array($horasExistentes))){

				if(!empty($horasExistentes)){

					$horas = $horasExistentes[0];
	
					if(array_key_exists('HORAS_APROBADAS_SIN_TOMA', $horas)) {
			
						if($horasExistentes){
							$horasAprobadasCompensatoriosSinToma = floatval($horasExistentes[0]['HORAS_APROBADAS_SIN_TOMA']);
						}else{
							$horasAprobadasCompensatoriosSinToma = 0;
						}
			
						if($horasAprobadasCompensatoriosSinToma <= 0){
							$msjResta="No tiene horas para tomar";
							$arrResponse = array('status' => false, 'msg' => $msjResta);
						}else{
							$msjResta="Tiene ".$horasAprobadasCompensatoriosSinToma." hora/s para tomar.";
							$arrResponse = array('status' => true, 'msg' => $msjResta);
						}
		
					}else{
					
						if($horasExistentes){
							$horasDisponibles = floatval($horasExistentes[0]['HORAS_DISPONIBLES']);
							$horasAprobadas = floatval($horasExistentes[0]['HORAS_APROBADAS']);
							$horasAprobadasCompensatorios = floatval($horasExistentes[0]['HORAS_COMPENSATORIOS_APROBADAS']);
						}else{
							$horasDisponibles = 0;
							$horasAprobadas = 0;
							$horasAprobadasCompensatorios = 0;
						}
			
						if($horasDisponibles<=0){
							$msjResta="No tiene horas para tomar";
							$arrResponse = array('status' => false, 'msg' => $msjResta);
						}else{
							$msjResta="Tiene ".$horasDisponibles." hora/s para tomar.";
							$arrResponse = array('status' => true, 'msg' => $msjResta);
						}
					}

				}else{
					$arrResponse = array('status' => false, 'msg' => 'No tiene horas disponibles.');
					$msjResta = "No tiene horas para tomar.";
				}

			}else{
				
				$arrResponse = array('status' => false, 'msg' => 'No se encontraron datos');

			}
			
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
	}
	//Modulo para obtener horas disponibles
	public function obtenerHorasDisponiblesSinId(){

		$idFuncionario = $_SESSION['userData']['ID_FUNCIONARIO'];
		$idFuncionario = intval($idFuncionario);

		$rolCodigo = $_SESSION['userData']['ROL_CODIGO'];

		if($idFuncionario > 0){
			
			$horasExistentes = $this->model->getHorasExistentes($idFuncionario);

			$horas = 0;

			if((in_array($rolCodigo, ROLES_ADMIN)) || (!empty($horasExistentes) && is_array($horasExistentes))){
				
				if(!empty($horasExistentes)){
					
					$horas = $horasExistentes[0];

					if(array_key_exists('HORAS_APROBADAS_SIN_TOMA', $horas)) {
			
						if($horasExistentes){
							$horasAprobadasCompensatoriosSinToma = floatval($horasExistentes[0]['HORAS_APROBADAS_SIN_TOMA']);
						}else{
							$horasAprobadasCompensatoriosSinToma = 0;
						}
			
						if($horasAprobadasCompensatoriosSinToma <= 0){
							$msjResta="No tiene horas para tomar";
							$arrResponse = array('status' => false, 'msg' => $msjResta);
						}else{
							$msjResta="Tiene ".$horasAprobadasCompensatoriosSinToma." hora/s para tomar.";
							$arrResponse = array('status' => true, 'msg' => $msjResta);
						}
		
					}else{
					
						if($horasExistentes){
							$horasDisponibles = floatval($horasExistentes[0]['HORAS_DISPONIBLES']);
							$horasAprobadas = floatval($horasExistentes[0]['HORAS_APROBADAS']);
							$horasAprobadasCompensatorios = floatval($horasExistentes[0]['HORAS_COMPENSATORIOS_APROBADAS']);
						}else{
							$horasDisponibles = 0;
							$horasAprobadas = 0;
							$horasAprobadasCompensatorios = 0;
						}
			
						if($horasDisponibles<=0){
							$msjResta="No tiene horas para tomar";
							$arrResponse = array('status' => false, 'msg' => $msjResta);
						}else{
							$msjResta="Tiene ".$horasDisponibles." hora/s para tomar.";
							$arrResponse = array('status' => true, 'msg' => $msjResta);
						}
					}

				}else{
					$arrResponse = array('status' => true, 'msg' => '¡Bienvenido Administrador!');
				}


			}else{
				
				$arrResponse = array('status' => false, 'msg' => 'No se encontraron horas para tomar');

			}
		
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
	}
	//Modulo para obetener los usuarios en el select, haciendo llamado al modelo selectUsuarios
	public function getSelectUsuarios(){
		$htmlOptions = "";
		$arrData = $this->model->selectUsuarios();

		if(!empty($arrData)){
			// Obtener el nombre del usuario que inició sesión
			//$loggedUserName = $_SESSION['userData']['FUN_NOMBRES'];
				
			// Agregar la opción del usuario que inició sesión
			//$htmlOptions .= '<option value="'.$_SESSION['userData']['ID_FUNCIONARIO'].'">'.$loggedUserName.'</option>';
				
			// Agregar las opciones de los demás registros && $arrData[0]['ID_FUNCIONARIO'] != $_SESSION['userData']['ID_FUNCIONARIO']
			for ($i=0; $i < count($arrData); $i++) { 
				if($arrData[0]['FUN_ESTADO'] == 1){
					$htmlOptions .= '<option value="'.$arrData[$i]['ID_FUNCIONARIO'].'">'.$arrData[$i]['FUN_NOMBRES'].' '.$arrData[$i]['FUN_APELLIDOS'].'</option>';
				}
			}
		}
		echo $htmlOptions;
	}
	//Modulo para seleccionar horas por id para obtener el contenido llamando al modelo selectEditHora y posteriormente editarlo
	public function editHora($idToma){
		if($_SESSION['permisosMod']['PER_R']){
			$idToma = intval($idToma);

			if($idToma>0){
				$arrData = $this->model->selectEditHora($idToma);

				if (empty($arrData)) {
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				} else {
					$arrResponse = array('status' => true, 'data' => $arrData);
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
		}
	}
	
	//-----Funciones de actualización-----
	//Modulo para aprobar la solicitud de horas
	public function aprobarSolicitud(){

		if ($_POST){

			if ($_SESSION['permisosMod']['PER_R']){
				$idToma = isset($_POST['ID_TOMA']) ? intval($_POST['ID_TOMA']) : 0;
				
				//Recuperacion de datos
				$datos = $this->model->correoAprobacionORechazo($idToma);
				
				$datos['TOM_FECHA_SOLI'] = formatearFechaUsuComparar($datos['TOM_FECHA_SOLI'],"d/m/Y");
				$datos['TOM_HORAS_SOLI'] = floatval($datos['TOM_HORAS_SOLI']);

				$horasSolicitadas = $datos['TOM_HORAS_SOLI'];
				
				$idFuncionario = $datos['ID_FUNCIONARIO'];

				if($idFuncionario > 0){
			
					$horasExistentes = $this->model->getHorasExistentes($idFuncionario);
					$horas = 0;
		
					if(!empty($horasExistentes) && is_array($horasExistentes)){
		
						$horas = $horasExistentes[0];
		
						if(array_key_exists('HORAS_APROBADAS_SIN_TOMA', $horas)) {
				
							if($horasExistentes){
								$horasDisponiblesSinToma = floatval($horasExistentes[0]['HORAS_APROBADAS_SIN_TOMA']);
								$horasDisponibles = null;
								$horasAprobadas = null;
								$horasAprobadasCompensatorios = null;
							}else{
								$horasDisponiblesSinToma = 0;
							}
			
						}else{
						
							if($horasExistentes){
								$horasDisponibles = floatval($horasExistentes[0]['HORAS_DISPONIBLES']);
								$horasAprobadas = floatval($horasExistentes[0]['HORAS_APROBADAS']);
								$horasAprobadasCompensatorios = floatval($horasExistentes[0]['HORAS_COMPENSATORIOS_APROBADAS']);
								$horasDisponiblesSinToma = null;
							}else{
								$horasDisponibles = 0;
								$horasAprobadas = 0;
								$horasAprobadasCompensatorios = 0;
							}

						}
		
					}else{
						
						$arrResponse = array('status' => false, 'msg' => 'No se encontraron datos');
		
					}
				
					if(($horasDisponibles>=$horasSolicitadas) || ($horasDisponiblesSinToma>=$horasSolicitadas)){
						
						if ($idToma > 0 && $datos){
		
							$arrAprobado = $this->model->estadoAprobado($idToma);
		
							if ($arrAprobado) {
								$nombres = $datos['FUN_NOMBRES'];
								$correo = $datos['FUN_CORREO'];
								
								$remitente = 'estivenmendez550@gmail.com';
								$destinatario = 'aprendiz.bi@asmetsalud.com';
								$asunto = 'Aprobacion de horas';
		
								$tipoMensaje = 'Aprobacion de horas';
		
								$html = generarHTML($tipoMensaje, $datos);
		
								$enviarMail = enviarMail($remitente, $destinatario, $asunto, 'Aprobacion de horas', $datos);
		
								if ($enviarMail){
									$response = array('status' => true, 'msg' => 'Se envió un correo de confirmación al solicitante.');
								} else {
									$response = array('status' => false, 'msg' => 'No se pudo enviar el correo de confirmación.');
								}
							} else {
								$response = array('status' => false, 'msg' => 'Al aprobar la solicitud');
							}
						}
						
					}else{
						$response = array('status' => false, 'msg' => 'Tiempo insuficiente.
						Disponibles: '.$horasDisponibles.' hora/s.');
					}
				
					echo json_encode($response, JSON_UNESCAPED_UNICODE);
					exit;
				}
			}
		}
	}
	//Modulo para rechazar la solicitud de horas
	public function rechazarSolicitud(){
		if ($_POST){
			if ($_SESSION['permisosMod']['PER_R']);
			$idToma = isset($_POST['ID_TOMA']) ? intval($_POST['ID_TOMA']) : 0;

			$datos = $this->model->correoAprobacionORechazo($idToma);

			$datos['TOM_FECHA_SOLI'] = formatearFechaUsuComparar($datos['TOM_FECHA_SOLI'],"d/m/Y");
			$datos['TOM_HORAS_SOLI'] = floatval($datos['TOM_HORAS_SOLI']);

			
			if ($idToma > 0){
				$success = $this->model->estadoRechazado($idToma);

				if ($success){
					$nombres = $datos['FUN_NOMBRES'];
					$correo = $datos['FUN_CORREO'];
					
					$remitente = 'estivenmendez550@gmail.com';
					$destinatario = 'aprendiz.bi@asmetsalud.com';
					$asunto = 'Solicitud rechazada';
					
					$tipoMensaje = 'Rechazo de horas';
					
					$html = generarHTML($tipoMensaje, $datos);

					$enviarMail = enviarMail($remitente, $destinatario, $asunto, 'Rechazo de horas', $datos);
					
					if ($enviarMail){
						$response = array('status' => true, 'msg' => 'Se envió un correo de confirmación al solicitante.');
					} else {
						$response = array('status' => false, 'msg' => 'No se pudo enviar el correo de confirmación.');
					}
				} else {
					$response = array('status' => false, 'msg' => 'Al rechazar la solicitud.');
				}
				echo json_encode($response, JSON_UNESCAPED_UNICODE);
			exit;
			}
		}
	}

	//-----Funciones generales-----
	//Módulo paraa verificación de rol llamando al modelo obtenerRol
	public function getRol() {

		// Verificar si el usuario tiene el rol de administrador
		$rol = $this->model->obtenerRol();
				
		$response = array('Rol' => $rol);
		
		header('Content-Type: application/json');

		echo json_encode($response, JSON_UNESCAPED_UNICODE);
	}
}
?>