<?php 

class Compensatorios extends Controllers{
	public function __construct(){	
		parent::__construct();
		session_start();
		session_regenerate_id();

		if(empty($_SESSION['login'])){
			header('Location: '.base_url().'/login');
		}else{
			getPermisos(COD_MOD_COM);
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

	public function Compensatorios(){
		$data['page_tag'] = $_SESSION['permisosMod']['MOD_TITULO'];
		$data['page_title'] = $_SESSION['permisosMod']['MOD_TITULO'];
		$data['page_name'] = $_SESSION['permisosMod']['MOD_TITULO'];
		$data['page_icono'] = $_SESSION['permisosMod']['MOD_ICONO'];
		$data['page_acceso'] = $_SESSION['permisosMod']['MOD_ACCESO'];
		$data['page_functions_js'] = "functions_".$_SESSION['permisosMod']['MOD_ACCESO'].".js";
		$this->views->getView($this,$_SESSION['permisosMod']['MOD_ACCESO'],$data);
	}

	// public function setCompensatorio() {
	// 	if ($_POST) {
	// 		if (
	// 			$_POST['txtFechaInicio'] == '' ||
	// 			$_POST['txtFechaFin'] == '' ||
	// 			$_POST['txtActividad'] == '' ||
	// 			$_POST['txtTrabajoRequerido'] == '' ||
	// 			$_POST['txtEstado'] == '' ||
	// 			$_POST['ListaUsuarios'] == ''
	// 		) {
	// 			$arrResponse = array("status" => false, "msg" => 'Ingrese todos los datos.');
	// 		} else {
	// 			$txtFechaInicio = $_POST['txtFechaInicio'];
	// 			$txtFechaFin = $_POST['txtFechaFin'];
	
				
	// 				$intIdCompensatorio = intval($_POST['idCompensatorio']);
	// 				$strDescripcionActividad = mb_convert_case(strClean($_POST['txtDescripcionActividad']), MB_CASE_TITLE, "UTF-8");
	// 				$strActividad = mb_convert_case(strClean($_POST['txtActividad']), MB_CASE_TITLE, "UTF-8");
	// 				$strTrabajoRequerido = mb_convert_case(strClean($_POST['txtTrabajoRequerido']), MB_CASE_TITLE, "UTF-8");
	// 				$intEstado = intval(strClean($_POST['txtEstado']));
	// 				$strFechaInicio = date('Y-m-d H:i:s', strtotime($txtFechaInicio));
	// 				$strFechaFin = date('Y-m-d H:i:s', strtotime($txtFechaFin));
	// 				$ListadoUsuarios = intval(strClean($_POST['ListaUsuarios']));
	
	// 				$request_user = "";
	
	// 				if ($intIdCompensatorio == 0) {
	// 					if ($_SESSION['permisosMod']['PER_W']) {
	// 						$request_user = $this->model->insertCompensatorio(
	// 							$strFechaInicio,
	// 							$strFechaFin,
	// 							$strDescripcionActividad,
	// 							$strActividad,
	// 							$ListadoUsuarios,
	// 							$strTrabajoRequerido,
	// 							$intEstado
	// 						);
	// 					}
	// 					$option = 1;
	// 				} else {
						
	// 					if ($_SESSION['permisosMod']['PER_U']) {
	// 						$request_user = $this->model->updateCompensatorio(
	// 							$intIdCompensatorio,
	// 							$strFechaInicio,
	// 							$strFechaFin,
	// 							$strDescripcionActividad,
	// 							$strActividad,
	// 							$strTrabajoRequerido
	// 						);
							
	// 					}
	// 					$option = 2;
	// 				}

					

	
	// 				if($request_user > 0){
	// 					if($option == 1){
	// 						$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente');
	// 					}else{
	// 						$arrResponse = array('status' => true, 'msg' => 'Datos actualizados correctamente');
	// 					}
	// 				}elseif($request_user == 'exist'){
	// 					$arrResponse = array('status' => false, 'msg' => 'El usuario o correo ya existe, ingrese otro porfavor!');
	// 				}else{
	// 					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos');
	// 				}
				
	// 		}
	// 	}
	// 	echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
	// }
	public function parseToDB($fecha){
	
		// Format the date string before passing to strtotime()
		$formattedDateTime = DateTime::createFromFormat('d/m/Y h:i A', $fecha);
		$formattedDateString = $formattedDateTime->format('Y/m/d H:i:s');
		//
		$unixTimestamp = strtotime($formattedDateString);
		// Use the formatted Unix timestamp for further processing
		$strFecha = date('Y/m/d H:i:s', $unixTimestamp);

		return $strFecha;

	}

	public function setCompensatorio() {
		if ($_POST) {
	
			if ($_POST['txtFechaInicio'] == '' || $_POST['txtFechaFin'] == ''
				|| $_POST['txtActividad'] == '' || $_POST['txtTrabajoRequerido'] == ''
				|| $_POST['txtEstado'] == '' || $_POST['ListaUsuarios'] == '') {
				$arrResponse = array("status" => false, "msg" => 'Ingrese todos los datos.');
			} else {
				$txtFechaInicio = $_POST['txtFechaInicio'];
				$txtFechaFin = $_POST['txtFechaFin'];
	
				if ($txtFechaInicio == $txtFechaFin) {
					$arrResponse = array("status" => false, "msg" => 'Las horas no pueden ser las mismas');
				} else {
					$intIdCompensatorio = intval($_POST['idCompensatorio']);
					$strDescripcionActividad = mb_convert_case(strClean($_POST['txtDescripcionActividad']), MB_CASE_TITLE, "UTF-8");
					$strActividad = mb_convert_case(strClean($_POST['txtActividad']), MB_CASE_TITLE, "UTF-8");
					$strTrabajoRequerido = mb_convert_case(strClean($_POST['txtTrabajoRequerido']), MB_CASE_TITLE, "UTF-8");
					$intEstado = intval(strClean($_POST['txtEstado']));
	
					//Parseo en el back para datetimepicker
					$strFechaInicio = Compensatorios::parseToDB($txtFechaInicio);
					$strFechaFin = Compensatorios::parseToDB($txtFechaFin);

					$ListadoUsuarios = intval(strClean($_POST['ListaUsuarios']));
	
					$arrData = $this->model->recuperar($ListadoUsuarios); // Recuperar los datos insertados
	
					$request_user = 0;
					$option = 0; // Agregado para definir la operación (0: no definida, 1: inserción, 2: actualización)
	
					if ($intIdCompensatorio == 0) {
						if ($_SESSION['permisosMod']['PER_W']) {
							$request_user = $this->model->insertCompensatorio(
								$strFechaInicio,
								$strFechaFin,
								$strActividad,
								$strDescripcionActividad,
								$ListadoUsuarios,
								$strTrabajoRequerido,
								$intEstado
							);
							$option = 1; // Inserción
						}
					} else {
						if ($_SESSION['permisosMod']['PER_U']) {
							$request_user = $this->model->updateCompensatorio(
								$intIdCompensatorio,
								$strFechaInicio,
								$strFechaFin,
								$strActividad,
								$strDescripcionActividad,
								$strTrabajoRequerido
							);
							$option = 2; // Actualización
						}
					}
	
					if ($request_user > 0) {
						if ($option == 1) {
							// Bloque de envío de correo para inserción
							$remitente = 'estivenmendez550@gmail.com';
							$destinatario = 'aprendiz.bi@asmetsalud.com';
							$asunto = 'Solicitud de compensatorio';
							$tipoMensaje = 'solicitud';
	
							$txtFechaInicio = $_POST['txtFechaInicio'];
							$txtFechaFin = $_POST['txtFechaFin'];
							//Parseo de la fecha
							$fechaInicio = Compensatorios::parseToDB($txtFechaInicio);
							$fechaFin = Compensatorios::parseToDB($txtFechaFin);
							//Formato de fecha
							$fechaInicioFormateada = date('d/m/Y - h:i A', strtotime($fechaInicio));
							$fechaFinFormateada = date('d/m/Y - h:i A', strtotime($fechaFin));
	
							$datos = [
								'FechaInicio' => $fechaInicioFormateada,
								'Funcionario' => $arrData["NOMBREFUNCIONARIO"],
								'FechaFin' => $fechaFinFormateada,
								'Actividad' => $_POST['txtActividad'],
								'UsuarioTrabajo' => $_POST['txtTrabajoRequerido'],
								'DescripcionAc' => $_POST['txtDescripcionActividad']
							];
	
							$html = generarHTML($tipoMensaje, $datos);
	
							try {
								$enviarcorreo = enviarMail($remitente, $destinatario, $asunto, 'solicitud', $datos);
								$arrResponse = array('status' => true, 'msg' => 'Su solicitud fue procesada con éxito, espera que el admin apruebe tu compensatorio');
							} catch (Exception $e) {
								$arrResponse = array('status' => false, 'msg' => 'Error al enviar el correo: ' . $e->getMessage());
							}
						} else {
							// Bloque de actualización (puedes agregar un mensaje si deseas)
							$arrResponse = array('status' => true, 'msg' => 'Su compensatorio fue actualizado correctamente');
						}
					} else {
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
					}
				}
			}
		}
		echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);

	}

	public function getCompensatorios(){
		if($_SESSION['permisosMod']['PER_R']){

			$ID_FUNCIONARIO = $_SESSION['userData']['ID_FUNCIONARIO']; // ID del funcionario que deseas mostrar
			
			$arrData = $this ->model->selectCompensatorios($ID_FUNCIONARIO);
			// Procesa $resultado para mostrar los compensatorios del funcionario

			for ($i=0; $i < count($arrData); $i++) {

				$arrData[$i]['COM_FECHA_INICIO']=formatearFechaYHora($arrData[$i]['COM_FECHA_INICIO'],"d/m/Y - h:i A");
				$arrData[$i]['COM_FECHA_FIN']=formatearFechaYHora($arrData[$i]['COM_FECHA_FIN'],"d/m/Y - h:i A");

				// var_dump($arrData);

				$btnVer = '';
				$btnCancelar = '';
				$btnPendiente = '';
				$btnAprobar = '';
				$btnRechazar = '';
				$btnEdit = '';
				$btnReset = '';
				$btnDelete = '';
				$btnPendiente = '';
				$newStatus="";

				$comEstado = $arrData[$i]['COM_ESTADO'];
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

				$arrData[$i]['COM_ESTADO'] = '<span class="badge ' . $statusClass . '">' . $newStatus . '</span>'; // Error con los span

				// var_dump($comEstado);

				if ($_SESSION['permisosMod']['PER_F'] && $comEstado == 2) {
					$btnReset = '<button class="btn btn-success btn-sm btnResetPass" onClick="ftnEvidencias(' . $arrData[$i]['ID_COMPENSATORIO'] . ')" title="Cargar Evidencias"><i class="fas fa-cloud-upload-alt"></i></button>';
				} else {
					$btnReset = ''; // Botón vacío si no se cumple la condición
				}

				//Revisar
				if($_SESSION['permisosMod']['PER_R']){ // Icono de ver funcionario
					if($arrData[$i]['COM_USUARIO_FINAL']!="1"){
						$btnVer = '<button class="btn btn-info btn-sm btnViewFuncionario" onClick="fntViewFuncionario('.$arrData[$i]['ID_COMPENSATORIO'].')" title="Ver Funcionario"><i class="far fa-eye"></i></button>';
					}else{
						$btnVer = '<button class="btn btn-secondary btn-sm" disabled ><i class="far fa-eye"></i></button>';
					}
				}

				// if($_SESSION['permisosMod']['ID_ROL'] == '2' && $comEstado == 1){
				// 	if($arrData[$i]['COM_USUARIO_FINAL']!="1"){
				// 		$btnEdit = '<button class="btn btn-primary  btn-sm btnEditFuncionario" onClick="btnEditCompensatorio(this,'.$arrData[$i]['ID_COMPENSATORIO'].')" title="Editar Funcionario"><i class="fas fa-pencil-alt"></i></button>';
				// 	}else{
				// 		$btnEdit = '';
				// 	}
				// }

				if($_SESSION['permisosMod']['PER_U'] && $_SESSION['permisosMod']['ID_ROL'] !== '1') {
					if($arrData[$i]['COM_USUARIO_FINAL'] != "1" && ($comEstado == 1)) {
						$btnEdit = '<button class="btn btn-primary btn-sm btnEditFuncionario" onClick="btnEditCompensatorio(this,'.$arrData[$i]['ID_COMPENSATORIO'].')" title="Editar Funcionario"><i class="fas fa-pencil-alt"></i></button>';
					} else {
						$btnEdit = '';
					}
				}
				
				
				

				
				
				
				// if ($_SESSION['permisosMod']['PER_U']) { // Botón de aprobaciones
				// 	if ($comEstado == 1) {
				// 		$btnAprobar = '<button class="btn btn-sm btn-primary" onClick="ftnAprobarCompensatorio(' . $arrData[$i]['ID_COMPENSATORIO'] . ')" title="Aprobar Compensatorio"><i class="fas fa-check-double"></i></button>';
				// 	} else {
				// 		$btnAprobar = '';
				// 	}
				// }


				if ($_SESSION['permisosMod']['PER_U'] && $_SESSION['permisosMod']['ID_ROL'] === '1') {
					if ($comEstado == 1) {
						$btnAprobar = '<button class="btn btn-sm btn-primary" onClick="ftnAprobarCompensatorio(' . $arrData[$i]['ID_COMPENSATORIO'] . ')" title="Aprobar Compensatorio"><i class="fas fa-check-double"></i></button>';
					} else {
						$btnAprobar = '';
					}
				} else {
					$btnAprobar = '';
				}
				
				
				if ($_SESSION['permisosMod']['PER_D']) {
					if ($comEstado == 1) {
						$btnRechazar = '<button class="btn btn-danger btn-sm btnDelFuncionario" onClick="ftnRechazarCompensatorio(' . $arrData[$i]['ID_COMPENSATORIO'] . ')" title="Rechazar Compenstorio"><i class="fas fa-times-circle"></i></button>';
					} else {
						$btnRechazar = '';
					}
				}
				
				
				
				$arrData[$i]['ACCIONES'] = '<div class="text-center">'.$btnVer.' '.$btnEdit.' '.$btnAprobar.' '.$btnRechazar.' '.$btnReset.' '.$btnCancelar.'</div>';
			}
			echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
		}
	}

	public function editCompensatorio($ID_COMPENSATORIO) {
		if ($_SESSION['permisosMod']['PER_R']) {
			$ID_COMPENSATORIO = intval($ID_COMPENSATORIO);
			if ($ID_COMPENSATORIO > 0) {
				$arrData = $this->model->selectEdit($ID_COMPENSATORIO);

				// $arrData[$i]['COM_FECHA_INICIO']=formatearFechaYHora($arrData[$i]['COM_FECHA_INICIO'],"d/m/Y - h:i A");
				// $arrData[$i]['COM_FECHA_FIN']=formatearFechaYHora($arrData[$i]['COM_FECHA_FIN'],"d/m/Y - h:i A");

				///d/m/Y - h:i A
				/*$arrData['COM_FECHA_INICIO']=formatearFechaYHora($arrData['COM_FECHA_INICIO'],"Y-m-d\TH:i");
				$arrData['COM_FECHA_FIN']=formatearFechaYHora($arrData['COM_FECHA_FIN'],"Y-m-d\TH:i");*/
				$arrData['COM_FECHA_INICIO']=formatearFechaYHora($arrData['COM_FECHA_INICIO'],"d/m/Y h:i A");
				$arrData['COM_FECHA_FIN']=formatearFechaYHora($arrData['COM_FECHA_FIN'],"d/m/Y h:i A");
				// var_dump($arrData);
				
				if (empty($arrData)) {
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				} else {
					$arrResponse = array('status' => true, 'data' => $arrData);
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
		}
	}
	

	// public function editCompensatorio($idfuncionario){
	// 	if($_SESSION['permisosMod']['PER_R']){
	// 		$idfuncionario = intval($idfuncionario);
	// 		if($idfuncionario > 0){
	// 			$arrData = $this->model->selectEdit($idfuncionario);
	// 			$arrData["ROLES"]=$this->getSelectRoles();
	// 			$arrData["FUN_ACCESO"]=$arrData["FUN_USUARIO"]."<br>".$arrData["FUN_USUARIO"]."".SYS_PATRON_PASS;
	// 			if(empty($arrData)){
	// 				$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
	// 			}else{
	// 				$arrResponse = array('status' => true, 'data' => $arrData);
	// 			}
	// 			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
	// 		}
	// 	}
	// }

	public function getCompensatorio($ID_COMPENSATORIO) {
		if ($_SESSION['permisosMod']['PER_R'] && intval($ID_COMPENSATORIO) > 0) {
			$arrData = $this->model->selectCompensatorioVista($ID_COMPENSATORIO);
		
			if (!empty($arrData)) {
				// Comprobar y asignar la URL de la evidencia
				$arrData['url_portada'] = isset($arrData['COM_EVIDENCIAS']) && !empty($arrData['COM_EVIDENCIAS'])
				? 'archivos/' . $arrData['COM_EVIDENCIAS']
				: ''; // Puedes asignar una URL por defecto aquí
		
				// Calcular la diferencia en horas
				$fechaInicioObj = DateTime::createFromFormat('d/m/Y - h:i A', $arrData['COM_FECHA_INICIO']);
				$fechaFinObj = DateTime::createFromFormat('d/m/Y - h:i A', $arrData['COM_FECHA_FIN']);
		
				if ($fechaInicioObj !== false && $fechaFinObj !== false) {
					$intervalo = $fechaInicioObj->diff($fechaFinObj);
					$diferenciaHoras = $intervalo->days * 24 + $intervalo->h;
					$diferenciaMinutos = $intervalo->i;
		
					// Agregar la diferencia de horas al arreglo $arrData
					$arrData['horasrealizadas'] = $diferenciaHoras . ' Horas y ' . $diferenciaMinutos . ' Minutos';
		
					// Preparar la respuesta
					$arrResponse = array('status' => true, 'data' => $arrData);
				} else {
					$arrResponse = array('status' => false, 'msg' => 'Error al convertir fechas.');
				}
			} else {
				$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
			}
		
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE); // Devuelve la respuesta JSON
		}
	}
		
	//ControladorAprobacion.php
	public function aprobarCompensatorio() {
		if ($_POST) {
			if ($_SESSION['permisosMod']['PER_R']) {
				$ID_COMPENSATORIO = isset($_POST['ID_COMPENSATORIO']) ? intval($_POST['ID_COMPENSATORIO']) : 0;
				
				// Obtener los datos del propietario del compensatorio
				$datos = $this->model->correoAprobacion($ID_COMPENSATORIO);

				$datos['COM_FECHA_INICIO']=formatearFechaYHora($datos['COM_FECHA_INICIO'],"d/m/Y - h:i A");
				$datos['COM_FECHA_FIN']=formatearFechaYHora($datos['COM_FECHA_FIN'],"d/m/Y - h:i A");
	
				if ($ID_COMPENSATORIO > 0 && $datos) {
					$success = $this->model->estadoAprobado($ID_COMPENSATORIO);
	
					if ($success) {
						$correo = $datos['FUN_NOMBRES'];
						$correo = $datos['FUN_CORREO'];
						$remitente = 'estivenmendez550@gmail.com';
						$destinatario = 'aprendiz.bi@asmetsalud.com';
						$asunto = 'Aprobación de compensatorio';
	
						$tipoMensaje = 'aprobacion';

						// Generar HTML con los datos del propietario del compensatorio
						$html = generarHTML($tipoMensaje, $datos);
	
						// Enviar el correo electrónico
						$enviarcorreo = enviarMail($remitente, $correo, $asunto, 'aprobacion', $datos);
	
						if ($enviarcorreo) {
							$response = array('status' => true, 'msg' => 'Compensatorio aprobado exitosamente y se envió un correo de confirmación al solicitante.');
						} else {
							$response = array('status' => false, 'msg' => 'Compensatorio aprobado exitosamente, pero no se pudo enviar el correo de confirmación.');
						}
					} else {
						$response = array('status' => false, 'msg' => 'Error al aprobar el compensatorio');
					}
					echo json_encode($response, JSON_UNESCAPED_UNICODE);
					exit;
				}
			}
		}
	}
	
		
	// ControladorRechazo.php
	public function rechazarCompensatorio(){
		if ($_POST) {
			if ($_SESSION['permisosMod']['PER_R']);
			$ID_COMPENSATORIO = isset($_POST['ID_COMPENSATORIO']) ? intval($_POST['ID_COMPENSATORIO']) : 0;
			
			$datos = $this->model->CorreoRechazo($ID_COMPENSATORIO);

			$datos['COM_FECHA_INICIO']=formatearFechaYHora($datos['COM_FECHA_INICIO'],"d/m/Y - h:i A");
			$datos['COM_FECHA_FIN']=formatearFechaYHora($datos['COM_FECHA_FIN'],"d/m/Y - h:i A");

			if ($ID_COMPENSATORIO > 0) {
				$success = $this->model->estadoRechazado($ID_COMPENSATORIO);
		
				if ($success) {
					$nombre = $datos['FUN_NOMBRES'];
					$nombre = $datos['FUN_CORREO'];

					$remitente = 'estivenmendez550@gmail.com';
					$destinatario = 'aprendiz.bi@asmetsalud.com';
					$asunto = 'Rechazo de compensatorio';

					$tipoMensaje = 'rechazo';

					$html = generarHTML($tipoMensaje, $datos);

					$enviarcorreo = enviarMail($remitente, $nombre, $asunto, 'rechazo', $datos);

					if ($enviarcorreo){
						$response = array('status' => true, 'msg' => 'El compensatorio fue rechazado y se envio un correo de confirmacion al solicitante');
					} else {
						$response = array('status' => false, 'msg' => 'El compensatorio fue rechazado, pero no se pudo enviar el correo de confirmacion');
					}
				} else {
					$response = array('status' => false, 'msg' => 'El al rechazar el compensatorio');
				}
				echo json_encode($response, JSON_UNESCAPED_UNICODE);
				exit;
			}
		}
	}

	public function getSelectUsuarios(){
		$htmlOptions = "";
		$arrData = $this->model->selectUsuarios();
		if(count($arrData) > 0 ){
			// Obtener el nombre del usuario que inició sesión
			$loggedUserName = $_SESSION['userData']['FUN_NOMBRES'];
				
			// Agregar la opción del usuario que inició sesión
			$htmlOptions .= '<option value="'.$_SESSION['userData']['ID_FUNCIONARIO'].'">'.$loggedUserName.'</option>';
				
			// Agregar las opciones de los demás registros
			for ($i=0; $i < count($arrData); $i++) { 
				if($arrData[$i]['FUN_ESTADO'] == 1 && $arrData[$i]['ID_FUNCIONARIO'] != $_SESSION['userData']['ID_FUNCIONARIO']){
					$htmlOptions .= '<option value="'.$arrData[$i]['ID_FUNCIONARIO'].'">'.$arrData[$i]['FUN_NOMBRES'].' '.$arrData[$i]['FUN_APELLIDOS'].'</option>';
				}
			}
		}
		echo $htmlOptions;
	}
	public function getSelectTipoCompensatorio(){
		$htmlOptions = "";
		$arrData = $this->model->selectTipoCompensatorio();
		if(count($arrData) > 0 ){			
			// Agregar las opciones de los demás registros de tipo de compensatorios
			for ($i=0; $i < count($arrData); $i++) { 
				$htmlOptions .= '<option value="'.$arrData[$i]['ID_TIPO_COMPENSATORIO'].'">'.$arrData[$i]['TIP_COM_NOMBRE'].'</option>';
			}
		}
		echo $htmlOptions;
	}
		

	public function verificarRol() {
		
		// Verificar si el usuario tiene el rol de administrador
		$ID_ROL = $_SESSION['userData']['ID_ROL'];
		$esAdministrador = $this->model->esAdministrador($ID_ROL);
		
		$response = array(
			'esAdministrador' => $ID_ROL
		);
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}


	public function subirEvidencia($ID_COMPENSATORIO) {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$ID_COMPENSATORIO = isset($_POST['ID_COMPENSATORIO']) ? intval($_POST['ID_COMPENSATORIO']) : 0;

			// Verificar que se haya subido un archivo
			if (isset($_FILES['archivoEvidencia']) && $_FILES['archivoEvidencia']['error'] === UPLOAD_ERR_OK) {
				$archivo = $_FILES['archivoEvidencia']['tmp_name'];
				$name = $_FILES['archivoEvidencia']['name'];
		
				$directorio = "archivos/";
				$name = strtolower($name) . '_' . uniqid();
				$destino = $directorio . $name;
		
				// Intenta mover el archivo al directorio de destino
				if (move_uploaded_file($archivo, $destino)) {
					// Éxito: el archivo se cargó con éxito
		
					// Llama al método en tu modelo para guardar la evidencia en la base de datos
					$return = $this->model->guardarEvidencia($name, $ID_COMPENSATORIO);
		
					if ($return) {
						$response = array('status' => true, 'msg' => 'Subida con éxito');
					} else {
						$response = array('status' => false, 'msg' => 'Error al subir el archivo');
					}
				} else {
					// Error: no se pudo mover el archivo al directorio de destino
					$response = array('status' => false, 'msg' => 'No se pudo mover el archivo al directorio de destino');
				}
			} else {
				// Error: no se seleccionó ningún archivo o hubo un error al cargarlo
				$response = array('status' => false, 'msg' => 'No se seleccionó ningún archivo');
			}
		}
		echo json_encode($response, JSON_UNESCAPED_UNICODE);
	}
}//fin de la clase
 ?>


 