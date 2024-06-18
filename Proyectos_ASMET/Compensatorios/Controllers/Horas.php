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

		public function setHora(){
			if($_POST){	
				if($_POST['txtMotivo']=='' || $_POST['txtEstado']=='' || $_POST['txtFecha']=='' || $_POST['txtHoras']==''){
					$arrResponse = array("status" => false, "msg" => 'Ingrese todos los datos.');
				}else{
					$intIdHora = intval($_POST['idHora']);
					$intIdHora = intval($_POST['idHora']);
					$strMotivo = mb_convert_case(strClean($_POST['txtMotivo']),MB_CASE_TITLE, "UTF-8");
					$intEstado = intval($_POST['txtEstado']);
					$strFecha = $_POST['txtFecha']; 
					$strHoras = intval($_POST['txtHoras']);

					$ID_FUNCIONARIO = $_SESSION['userData']['ID_FUNCIONARIO'];
					//sumo las horas de los compensatorios
					$arrHoras = $this->model->getHoras($ID_FUNCIONARIO);
					if($arrHoras){
						$horasRegis = intval($arrHoras["HORAS_TOTALES"]);
					}else{
						$horasRegis = 0;
					}
					//sumo las horas consumidas
					$arrGastadas = $this->model->getGastadas($ID_FUNCIONARIO);
					if($arrGastadas){
						$horasGas = intval($arrGastadas["HORAS_GASTADAS"]);
					}else{
						$horasGas = 0;
					}
					//resto las horas registradas menos las horas gastadas
					$resta=($horasRegis-$horasGas);
					if($resta<=0){
						$msjResta="Error, no tienes horas para tomar";
					}else{
						$msjResta="Error, solo tienes ".$resta." hora para tomar";
					}

					if(($horasRegis>0 && $strHoras>0) && ($horasRegis-$horasGas)>0 && ($horasRegis-$horasGas)>=$strHoras){
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
						
						if($request_user > 0){

							$remitente = 'estivenmendez550@gmail.com';
							$destinatario = 'aprendiz.bi@asmetsalud.com';
							$asunto = 'Solicitud de horas';

							$datos = [
								'MotivoSolicitud' => $_POST['txtMotivo'],
								'FechaSolicitud' => $_POST['txtFecha'],
								'HorasSolicitar' =>$_POST['txtHoras']
							];

							$tipoMensaje = 'solicitud_horas';

							$html = generarHTML($tipoMensaje, $datos);

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
							$arrResponse = array('status' => false, 'msg' =>'No es posible almacenar los datos.');
						}
					}else{
						$arrResponse = array("status" => false, "msg" => $msjResta);
					}
					// ... (código anterior)
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
	

		public function getHoras(){
			if($_SESSION['permisosMod']['PER_R']){

				$ID_FUNCIONARIO = $_SESSION['userData']['ID_FUNCIONARIO'];

				$arrData = $this->model->selectHoras($ID_FUNCIONARIO);

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
							$btnView = '<button class="btn btn-info btn-sm btnViewFuncionario" onClick="fntViewHora('.$arrData[$i]['ID_TOMA'].')" title="Ver Funcionario"><i class="far fa-eye"></i></button>';
						}else{
							$btnView = '<button class="btn btn-secondary btn-sm" disabled ><i class="far fa-eye"></i></button>';
						}
					}

                    if ($_SESSION['permisosMod']['PER_U']) { // Botón de aprobaciones
						if ($comEstado == 1 || $comEstado == 3) {
							$btnAprobar = '<button class="btn btn-sm btn-primary" onClick="ftnAprobar(' . $arrData[$i]['ID_TOMA'] . ')" title="Aprobar Compensatorio"><i class="fas fa-check-circle"></i></button>';
						} else {
							$btnAprobar = '<button class="btn btn-secondary btn-sm" disabled><i class="fas fa-check-circle"></i></button>';
						}
					}
					
					if ($_SESSION['permisosMod']['PER_D']) {
						if ($comEstado == 1 || $comEstado == 2){
							$btnRechazar = '<button class="btn btn-danger btn-sm btnDelFuncionario" onClick="ftnRechazar('.$arrData[$i]['ID_TOMA'].')" title="Rechazar Compenstorio"><i class="fas fa-ban"></i></button>';
						} else {
							$btnRechazar = '<button class="btn btn-secondary btn-sm" disabled><i class="fas fa-times-circle"></i></button>';
						}
					}
					$arrData[$i]['ACCIONES'] = '<div class="text-center">'.$btnView.' '.$btnAprobar.' '.$btnRechazar.'</div>';
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
		}

		public function getHora($ID_TOMA){
			if($_SESSION['permisosMod']['PER_R']){
				$ID_TOMA = intval($ID_TOMA);
				if($ID_TOMA > 0){
					$arrData = $this->model->selectHoraVista($ID_TOMA);
					if(empty($arrData)){
						$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
					}else{
						$arrResponse = array('status' => true, 'data' => $arrData);
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
		}
		

		public function delFuncionario(){
			if($_POST){
				if($_SESSION['permisosMod']['PER_D']){
					$idFuncionario = intval($_POST['idFuncionario']);
					$requestDelete = $this->model->deleteFuncionario($idFuncionario);
					if($requestDelete)
					{
						$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el usuario');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el usuario.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
		}

		public function statusFuncionario(){
			if($_POST){
				if($_SESSION['permisosMod']['PER_D']){
					$idFuncionario = intval($_POST['idFuncionario']);
					$status = $_POST['status'];
					$requestDelete = $this->model->estadoFuncionario($idFuncionario,$status);
					if($requestDelete)
					{
						$arrResponse = array('status' => true, 'msg' => 'Se ha cambiado el estado');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al cambiar el estado.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
		}

		public function resetPassFuncionario(){
			if($_POST){
				if($_SESSION['permisosMod']['PER_U']){
					$this->intIdFuncionario = intval($_POST['idFuncionario']);
					
					$dataFuncionario=$this->model->selectFuncionario($this->intIdFuncionario);
					$strPassword = hash("SHA256",$dataFuncionario["FUN_USUARIO"]."".SYS_PATRON_PASS);
					$requestPass = $this->model->resetPassFuncionario($this->intIdFuncionario,$strPassword);
					if($requestPass){
						$arrResponse = array('status' => true, 'msg' => 'Se ha reestablecido la contraseña');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error en el reestablecimiento.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
		}

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

		public function aprobarSolicitud(){
			if ($_POST){
				if ($_SESSION['permisosMod']['PER_R']){
					$ID_TOMA = isset($_POST['ID_TOMA']) ? intval($_POST['ID_TOMA']) : 0;
					
					//Recuperacion de datos
					$datos = $this->model->CorreoAprobacion($ID_TOMA);
					
					$datos['TOM_FECHA_SOLI']=formatearFechaUsuComparar($datos['TOM_FECHA_SOLI'],"d/m/Y");
					
					if ($ID_TOMA > 0 && $datos){
						$arrAprobado = $this->model->EstadoAprobado($ID_TOMA);

						if ($arrAprobado) {
							$correo = $datos['FUN_NOMBRES'];
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

		public function RechazarSolicitud(){
			if ($_POST){
				if ($_SESSION['permisosMod']['PER_R']);
				$ID_TOMA = isset($_POST['ID_TOMA']) ? intval($_POST['ID_TOMA']) : 0;

				$datos = $this->model->CorreoRechazo($ID_TOMA);

				$datos['TOM_FECHA_SOLI']=formatearFechaUsuComparar($datos['TOM_FECHA_SOLI'],"d/m/Y");

				if ($ID_TOMA > 0){
					$success = $this->model->EstadoRechazado($ID_TOMA);

					if ($success){
						$correo = $datos['FUN_NOMBRES'];
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
}//fin de la clase
?>