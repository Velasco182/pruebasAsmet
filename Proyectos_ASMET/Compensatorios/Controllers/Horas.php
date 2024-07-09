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

				$intIdHora = intval($_POST['idHora']);
				$intIdHora = intval($_POST['idHora']);
				$strMotivo = mb_convert_case(strClean($_POST['txtMotivo']),MB_CASE_TITLE, "UTF-8");
				$intEstado = intval($_POST['txtEstado']);
				$strFecha = $_POST['txtFecha'];
				$strHoras = floatval($_POST['txtHoras']);

				$idFuncionario = $_SESSION['userData']['ID_FUNCIONARIO'];
				$arrHoras = $this->model->getHorasDisponibles($idFuncionario);
				
				if($arrHoras){
					$horasDisponibles = floatval($arrHoras['HORAS_DISPONIBLES']);
					$horasAprobadas = floatval($arrHoras['HORAS_APROBADAS']);
					$horasAprobadasCompensatorios = floatval($arrHoras['HORAS_COMPENSATORIOS_APROBADAS']);
				}else{
					$horasDisponibles = 0;
					$horasAprobadas = 0;
					$horasAprobadasCompensatorios = 0;
				}

				if($horasDisponibles<=0){
					$msjResta="No tienes horas para tomar";
				}else{
					$msjResta="Solo tienes ".$horasDisponibles." hora/s para tomar";
				}

				if(($horasAprobadasCompensatorios && $strHoras)>0
					&& ($horasAprobadasCompensatorios-$horasAprobadas)>0
					&& ($horasAprobadasCompensatorios-$horasAprobadas)>=$strHoras){

					$request_user = "";
					if($intIdHora == 0){
						$option = 1;
						
						if($_SESSION['permisosMod']['PER_W']){
							$request_user = $this->model->insertHora(
								$strMotivo,
								$intEstado,
								$strFecha,
								$strHoras
							);
						}
					}

				$arrResponse = array('status' => false, 'msg' => 'Registro de horas existente!');
				
				if($request_user > 0){
					
					$remitente = 'estivenmendez550@gmail.com';
					$destinatario = 'aprendiz.bi@asmetsalud.com';
					$asunto = 'Solicitud de horas';
					
					$datos = [
							'Funcionario' 		=> 	$arrHoras['NOMBREFUNCIONARIO'],
							'MotivoSolicitud' 	=>	$_POST['txtMotivo'],
							'FechaSolicitud' 	=> 	$_POST['txtFecha'],
							'HorasSolicitar' 	=>	$_POST['txtHoras']
						];

						try {

							$enviarCorreo = enviarMail($remitente, $destinatario, $asunto, 'solicitud_horas', $datos);
							
							if ($enviarCorreo == "1"){
								$arrResponse = array('status' => true, 'msg' => 'Su solicitud fue procesada con éxito, espera que el administrador la apruebe');
							} else {
								$arrResponse = array('status' => true, 'msg' => 'Su solicitud fue enviada, pero no se pudo enviar el correo de confirmación.');
							}
							
						} catch (Exception $e) {
							$arrResponse = array('status' => false, 'msg' => 'Error al enviar el correo: ' . $e->getMessage());
						}
						
					} else {

						$request_user === "exist" ? $arrResponse : $arrResponse = array('status' => false, 'msg' =>'No es posible almacenar los datos.');
					
					}
				}else{
					$arrResponse = array('status' => false, 'msg' => $msjResta);
				}
			}
		}
		echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
	}

	//-----Funciones de recuperación de datos
	//Modulo para llenar datatable llamando al modelo selectHoras
	public function obtenerHoras(){
		if($_SESSION['permisosMod']['PER_R']){

			$idFuncionario = $_SESSION['userData']['ID_FUNCIONARIO'];

			$arrData = $this->model->selectHoras($idFuncionario);

			for ($i=0; $i < count($arrData); $i++) {
				
				$arrData[$i]['TOM_FECHA_SOLI']=formatearFechaUsuComparar($arrData[$i]['TOM_FECHA_SOLI'],"d/m/Y");

				$btnView = '';
				$btnAprobar = '';
				$btnRechazar = '';

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
					if($arrData[$i]['TOM_FECHA_SOLI']!="1"){
						$btnView = '<button class="btn btn-info btn-sm btnViewFuncionario" onClick="fntViewHora('.$arrData[$i]['ID_TOMA'].')" title="Ver Horas"><i class="far fa-eye"></i></button>';
					}else{
						$btnView = '<button class="btn btn-info btn-sm"><i class="far fa-eye"></i></button>';
					}
					
					if($comEstado == 3){
						$btnView = '<button class="btn btn-info btn-sm btnViewFuncionario" onClick="fntViewHora('.$arrData[$i]['ID_TOMA'].')" title="Ver Horas"><i class="far fa-eye"></i></button>';
					}
				}

				if ($_SESSION['permisosMod']['PER_U']) { // Botón de aprobaciones
					if ($comEstado == 1 || $comEstado == 3) {
						$btnAprobar = '<button class="btn btn-sm btn-primary" onClick="fntAprobar(' . $arrData[$i]['ID_TOMA'] . ')" title="Aprobar Horas"><i class="fas fa-check-circle"></i></button>';
					} else {
						$btnAprobar = '';//<button class="btn btn-secondary btn-sm" disabled><i class="fas fa-check-circle"></i></button>
					}
				}
				
				if ($_SESSION['permisosMod']['PER_D']) {
					if ($comEstado == 1 || $comEstado == 2){
						$btnRechazar = '<button class="btn btn-danger btn-sm btnDelFuncionario" onClick="fntRechazar('.$arrData[$i]['ID_TOMA'].')" title="Rechazar Horas"><i class="fas fa-ban"></i></button>';
					} else {
						$btnRechazar = '';//<button class="btn btn-secondary btn-sm" disabled><i class="fas fa-times-circle"></i></button>
					}
				}
				$arrData[$i]['ACCIONES'] = '<div class="text-center">'.$btnView.' '.$btnAprobar.' '.$btnRechazar.'</div>';
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
	public function obtenerHorasDisponibles(){

		$idFuncionario = $_SESSION['userData']['ID_FUNCIONARIO'];

		//Respuesta de la DB
		$arrHoras = $this->model->getHorasDisponibles($idFuncionario);

		if($arrHoras){
			$horasDisponibles = floatval($arrHoras['HORAS_DISPONIBLES']);
			$horasAprobadas = floatval($arrHoras['HORAS_APROBADAS']);
			$horasAprobadasCompensatorios = floatval($arrHoras['HORAS_COMPENSATORIOS_APROBADAS']);
		}else{
			$horasDisponibles = 0;
			$horasAprobadas = 0;
			$horasAprobadasCompensatorios = 0;
		}

		if($horasDisponibles<=0){
			$msjResta="No tienes horas para tomar";
			$arrResponse = array('status' => false, 'msg' => $msjResta);
		}else{
			$msjResta="Tienes ".$horasDisponibles." hora/s para tomar.,
			".$horasAprobadas." hora/s aprobadas y
			".$horasAprobadasCompensatorios." hora/s en compensatorios aprobadas.";
			$arrResponse = array('status' => true, 'msg' => $msjResta);
		}

		echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
	}
	//-----Funciones generales-----
	//Modulo para verificar rol
	public function getSelectRoles(){
		$htmlOptions = "";
		$arrData = $this->model->selectRoles();
		if(count($arrData) > 0 ){
			for ($i=0; $i < count($arrData); $i++) { 
				if($arrData[$i]['ROL_ESTADO'] == 1 ){
				$htmlOptions .= '<option value="'.$arrData[$i]['ID_ROL'].'">'.$arrData[$i]['ROL_NOMBRE'].'</option>';
				}
			}
		}
		return $htmlOptions;
	}
	//Modulo para aprobar la solicitud de horas
	public function aprobarSolicitud(){

		if ($_POST){

			if ($_SESSION['permisosMod']['PER_R']){
				$idToma = isset($_POST['ID_TOMA']) ? intval($_POST['ID_TOMA']) : 0;
				
				//Recuperacion de datos
				$datos = $this->model->correoAprobacionORechazo($idToma);
				
				$datos['TOM_FECHA_SOLI']=formatearFechaUsuComparar($datos['TOM_FECHA_SOLI'],"d/m/Y");
				
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
							$response = array('status' => true, 'msg' => 'Solicitud aprobada exitosamente y se envio un correo de confirmacion al solicitante');
						} else {
							$response = array('status' => false, 'msg' => 'Solicitud aprobada exitosamente, pero no se pudo enviar el correo de confirmacion');
						}
					} else {
						$response = array('status' => false, 'msg' => 'Error al aprobar la solicitud');
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

			$datos['TOM_FECHA_SOLI']=formatearFechaUsuComparar($datos['TOM_FECHA_SOLI'],"d/m/Y");

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
						$response = array('status' => true, 'msg' => 'La solicitud fue rechazada y se envio un correo de confirmacion al solicitante');
					} else {
						$response = array('status' => false, 'msg' => 'Solicitud rechazada, pero no se pudo enviar el correo de confirmacion');
					}
				} else {
					$response = array('status' => false, 'msg' => 'Error al rechazar la solicitud');
				}
				echo json_encode($response, JSON_UNESCAPED_UNICODE);
			exit;
			}
		}
	}
}
?>