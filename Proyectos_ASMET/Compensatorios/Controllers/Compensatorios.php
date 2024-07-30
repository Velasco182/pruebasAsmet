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

	//Modulo para parsear fechas
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

	//-----Funciones de inserción-----
	//Modulo de creación y actualización de compensatorios
	public function setCompensatorio() {

		if ($_POST) {
			//|| $_POST['archivoEvidencia'] == ''
			if ($_POST['txtFechaInicio'] == '' || $_POST['txtFechaFin'] == ''
			|| $_POST['txtActividad'] == '' || $_POST['txtTrabajoRequerido'] == ''
			|| $_POST['txtEstado'] == '' || $_POST['listaUsuarios'] == '') {
				
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

					$listadoUsuarios = intval(strClean($_POST['listaUsuarios']));
					// Recuperar los datos insertados
					$arrData = $this->model->recuperar($listadoUsuarios);

					$request_user = 0;
					$option = 0; // Agregado para definir la operación (0: no definida, 1: inserción, 2: actualización)

					if (isset($_FILES['archivoEvidencia']) && $_FILES['archivoEvidencia']['error'] === UPLOAD_ERR_OK) {
						$archivo = $_FILES['archivoEvidencia']['tmp_name'];
						$name = $_FILES['archivoEvidencia']['name'];
				
						$directorio = "archivos/";
						$name = strtolower($name) . '_' . uniqid();
						$destino = $directorio . $name;
				
						// Intenta mover el archivo al directorio de destino
						if (move_uploaded_file($archivo, $destino)) {

							// Éxito: el archivo se cargó con éxito
							if ($intIdCompensatorio == 0) {

								if ($_SESSION['permisosMod']['PER_W']) {

									$request_user = $this->model->insertCompensatorio(
										$strFechaInicio,
										$strFechaFin,
										$strActividad, //ID_TIPO_COMPENSATORIO
										$strDescripcionActividad,
										$listadoUsuarios,
										$strTrabajoRequerido,
										$name, //COM_EVIDENCIAS
										$intEstado
									);
									$option = 1;//Inserción

								}
							} else {
								if ($_SESSION['permisosMod']['PER_U']) {

									$request_user = $this->model->updateCompensatorio(
										$intIdCompensatorio,
										$strFechaInicio,
										$strFechaFin,
										$strActividad, //ID_TIPO_COMPENSATORIO
										$strDescripcionActividad,
										$name, //COM_EVIDENCIAS
										$strTrabajoRequerido
									);
									$option = 2;//Actualización

								}
							}

						} else {
							// Error: no se pudo mover el archivo al directorio de destino
							$arrResponse = array('status' => false, 'msg' => 'No se pudo mover el archivo al directorio de destino');
						}
					} else {
						// Error: no se seleccionó ningún archivo o hubo un error al cargarlo
						$arrResponse = array('status' => false, 'msg' => 'No se seleccionó ningún archivo');

						if ($_SESSION['permisosMod']['PER_U']) {

							$request_user = $this->model->updateCompensatorioSinEvidencia(
								$intIdCompensatorio,
								$strFechaInicio,
								$strFechaFin,
								$strActividad, //ID_TIPO_COMPENSATORIO
								$strDescripcionActividad,
								$strTrabajoRequerido
							);
							$option = 2; // Actualización

						}
					}
	
					$arrResponse = array('status' => false, 'msg' => 'El compensatorio ya existe!');
					$diferencia = array('status' => false, 'msg' => 'La diferencia debe ser de por lo menos 30 minutos.');

					if($option == 1) {
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

						$idTipoCompensatorio = $_POST['txtActividad'];
						//Recuperar nombre del tipo de compensatorio
						$tipoCompensatorioNombre = $this->model->selectTipoCompensatorioVista($idTipoCompensatorio); 
						
						$datos = [
							'Funcionario' => $arrData["NOMBREFUNCIONARIO"],
							'FechaInicio' => $fechaInicioFormateada,
							'FechaFin' => $fechaFinFormateada,
							'Actividad' => $tipoCompensatorioNombre[0]["TIP_COM_NOMBRE"],
							'UsuarioTrabajo' => $_POST['txtTrabajoRequerido'],
							'DescripcionAc' => $_POST['txtDescripcionActividad']
						];
						
						try {
							
							if($request_user === "time_error"){
								$arrResponse = $diferencia;
							}elseif($request_user === "exist"){
								$arrResponse;
							}else{
								$arrResponse = array('status' => true, 'msg' => 'Su solicitud fue procesada con éxito, espera que el admin apruebe tu compensatorio!');
								$enviarcorreo = enviarMail($remitente, $destinatario, $asunto, 'solicitud', $datos);
							}

						} catch (Exception $e) {
							
							$arrResponse = array('status' => false, 'msg' => 'Error al enviar el correo: ' . $e->getMessage());
						
						}

					} else {

						if($request_user === "time_error"){
							$arrResponse = $diferencia;
						}else{
							$request_user === "exist" ? $arrResponse : $arrResponse = array('status' => true, 'msg' => 'Su compensatorio fue actualizado correctamente!');
						}
					
					}
				}
			}
		}
		
		echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);

	}

	//-----Funciones de recuperación de datos-----
	//Modulo para obtener todos los compensatorios
	public function getCompensatorios(){
		if($_SESSION['permisosMod']['PER_R']){

			$idFuncionario = $_SESSION['userData']['ID_FUNCIONARIO']; // ID del funcionario que deseas mostrar
			$idFuncionario = intval($idFuncionario);
			
			$arrData = $this->model->selectCompensatorios($idFuncionario);
			// Procesa $resultado para mostrar los compensatorios del funcionario

			for ($i=0; $i < count($arrData); $i++) {

				$arrData[$i]['COM_FECHA_INICIO']=formatearFechaYHora($arrData[$i]['COM_FECHA_INICIO'],"d/m/Y - h:i A");
				$arrData[$i]['COM_FECHA_FIN']=formatearFechaYHora($arrData[$i]['COM_FECHA_FIN'],"d/m/Y - h:i A");

				$arrData[$i]['HORAS_REALIZADAS'] = floatval($arrData[$i]['HORAS_REALIZADAS']);

				$btnVer = '';
				$btnCancelar = '';
				$btnPendiente = '';
				$btnAprobar = '';
				$btnRechazar = '';
				$btnEdit = '';
				$btnReset = '';
				$btnDelete = '';
				$newStatus= '';

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

				//Revisar
				if($_SESSION['permisosMod']['PER_R']){ // Icono de ver funcionario
					
					if($arrData[$i]['COM_USUARIO_FINAL']!="1"){
						$btnVer = '<button class="btn btn-info btn-sm btnViewFuncionario" onClick="ftnViewCompensatorio('.$arrData[$i]['ID_COMPENSATORIO'].')" title="Ver Compensatorio"><i class="far fa-eye"></i></button>';
					}else{
						$btnVer = '';
					}

					if($comEstado == 3){
						$btnVer = '<button class="btn btn-info btn-sm btnViewFuncionario" onClick="ftnViewCompensatorio('.$arrData[$i]['ID_COMPENSATORIO'].')" title="Ver Compensatorio"><i class="far fa-eye"></i></button>';
					}

				}

				if($_SESSION['permisosMod']['PER_U'] && $_SESSION['permisosMod']['ID_ROL'] !== '1') {
					
					if(($arrData[$i]['COM_USUARIO_FINAL'] != "1") && ($comEstado == 1) && (intval($arrData[$i]['ID_FUNCIONARIO']) === $idFuncionario)) {
						$btnEdit = '<button class="btn btn-primary btn-sm btnEditFuncionario" onClick="ftnEditCompensatorio(this,'.$arrData[$i]['ID_COMPENSATORIO'].')" title="Editar Funcionario"><i class="fas fa-pencil-alt"></i></button>';
					} else {
						$btnEdit = '';
					}

				}

				if($_SESSION['permisosMod']['ID_ROL'] !== '2'){

					if ($_SESSION['permisosMod']['PER_U']) {
						if ($comEstado == 1) {
							$btnAprobar = '<button class="btn btn-sm btn-primary" onClick="ftnAprobarCompensatorio(' . $arrData[$i]['ID_COMPENSATORIO'] . ')" title="Aprobar Compensatorio"><i class="fas fa-check-circle"></i></button>';
						} else {
							$btnAprobar = '';
						}
					} else {
						$btnAprobar = '';
					}
					
					if ($_SESSION['permisosMod']['PER_U']) {
						if ($comEstado == 1) {
							$btnRechazar = '<button class="btn btn-danger btn-sm" onClick="ftnRechazarCompensatorio(' . $arrData[$i]['ID_COMPENSATORIO'] . ')" title="Rechazar Compensatorio"><i class="fas fa-times-circle"></i></button>';
						} else {
							$btnRechazar = '';
						}
					}

				}

				
				$arrData[$i]['ACCIONES'] = '<div class="text-center">'.$btnVer.' '.$btnEdit.' '.$btnAprobar.' '.$btnRechazar.' '.$btnReset.' '.$btnCancelar.'</div>';
			}
			echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
		}
	}
	//Modulo para seleccionar compensatorio por id para después actualizar el mismo llamando al modelo selectEdit
	public function editCompensatorio($idCompensatorio) {
		if ($_SESSION['permisosMod']['PER_R']) {
			$idCompensatorio = intval($idCompensatorio);
			if ($idCompensatorio > 0) {
				$arrData = $this->model->selectEdit($idCompensatorio);

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
	//Modulo paraa obtener compensatorio por id para ver el contenido llamando al modelo selectCompensatorioVista
	public function getCompensatorio($idCompensatorio) {
		if ($_SESSION['permisosMod']['PER_R'] && intval($idCompensatorio) > 0) {
			$arrData = $this->model->selectCompensatorioVista($idCompensatorio);
		
			if (!empty($arrData)) {
				// Comprobar y asignar la URL de la evidencia
				$arrData['url_portada'] = isset($arrData['COM_EVIDENCIAS']) && !empty($arrData['COM_EVIDENCIAS'])
				? 'archivos/' . $arrData['COM_EVIDENCIAS']
				: ''; // Puedes asignar una URL por defecto aquí

				// Preparar la respuesta
				$arrResponse = array('status' => true, 'data' => $arrData);
			
			} else {
				$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
			}
		
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE); // Devuelve la respuesta JSON
		}
	}
	//Modulo para aprovación del compensatorio llamando al modelo correoAprobacionORechazo para el correo y el modelo estadoAprobado
	public function aprobarCompensatorio() {
		if ($_POST) {
			if ($_SESSION['permisosMod']['PER_R']) {
				$idCompensatorio = isset($_POST['ID_COMPENSATORIO']) ? intval($_POST['ID_COMPENSATORIO']) : 0;
				
				// Obtener los datos del propietario del compensatorio
				$datos = $this->model->correoAprobacionORechazo($idCompensatorio);

				$datos['COM_FECHA_INICIO']=formatearFechaYHora($datos['COM_FECHA_INICIO'],"d/m/Y - h:i A");
				$datos['COM_FECHA_FIN']=formatearFechaYHora($datos['COM_FECHA_FIN'],"d/m/Y - h:i A");

				if ($idCompensatorio > 0 && $datos) {
					$success = $this->model->estadoAprobado($idCompensatorio);
	
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
							$response = array('status' => true, 'msg' => 'Se envió un correo de confirmación al solicitante.');
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
	//Modulo para rechazo del compensatorio llamando al modelo correoAprobacionORechazo para el correo y el modelo estadoRechazado
	public function rechazarCompensatorio(){
		if ($_POST) {
			if ($_SESSION['permisosMod']['PER_R']);
			$idCompensatorio = isset($_POST['ID_COMPENSATORIO']) ? intval($_POST['ID_COMPENSATORIO']) : 0;
			// Obtener los datos del propietario del compensatorio
			$datos = $this->model->correoAprobacionORechazo($idCompensatorio);
			
			$datos['COM_FECHA_INICIO']=formatearFechaYHora($datos['COM_FECHA_INICIO'],"d/m/Y - h:i A");
			$datos['COM_FECHA_FIN']=formatearFechaYHora($datos['COM_FECHA_FIN'],"d/m/Y - h:i A");
			
			if ($idCompensatorio > 0 && $datos) {

				$success = $this->model->estadoRechazado($idCompensatorio);

				if ($success) {
					$nombre = $datos['FUN_NOMBRES'];
					$correo = $datos['FUN_CORREO'];
					$remitente = 'estivenmendez550@gmail.com';
					$destinatario = 'aprendiz.bi@asmetsalud.com';
					$asunto = 'Rechazo de compensatorio';

					$tipoMensaje = 'rechazo';
					// Generar HTML con los datos del propietario del compensatorio
					$html = generarHTML($tipoMensaje, $datos);

					$enviarcorreo = enviarMail($remitente, $correo, $asunto, 'rechazo', $datos);

					if ($enviarcorreo){
						$response = array('status' => true, 'msg' => 'Se envio un correo de confirmacion al solicitante');
					} else {
						$response = array('status' => false, 'msg' => 'El compensatorio fue rechazado, pero no se pudo enviar el correo de confirmacion');
					}
				} else {
					$response = array('status' => false, 'msg' => 'Error al rechazar el compensatorio');
				}
				echo json_encode($response, JSON_UNESCAPED_UNICODE);
				exit;
			}
		}
	}
	//Modulo para obetener los usuarios en el select, haciendo llamado al modelo selectUsuarios
	public function getSelectUsuarios(){
		$htmlOptions = "";
		$arrData = $this->model->selectUsuarios();

		if(count($arrData) > 0 ){
			// Obtener el nombre del usuario que inició sesión
			$loggedUserName = $_SESSION['userData']['FUN_NOMBRES'];
				
			// Agregar la opción del usuario que inició sesión
			$htmlOptions .= '<option value="'.$_SESSION['userData']['ID_FUNCIONARIO'].'">'.$loggedUserName.'</option>';
				
			// Agregar las opciones de los demás registros && $arrData[0]['ID_FUNCIONARIO'] != $_SESSION['userData']['ID_FUNCIONARIO']
			for ($i=0; $i < count($arrData); $i++) {
				if($arrData[0]['FUN_ESTADO'] == 1 ){
					$htmlOptions .= '<option value="'.$arrData[$i]['ID_FUNCIONARIO'].'">'.$arrData[$i]['FUN_NOMBRES'].' '.$arrData[$i]['FUN_APELLIDOS'].'</option>';
				}
			}
		}
		echo $htmlOptions;
	}
	//Modulo para obetener los tipos de compensatorios en el select, haciendo llamado al modelo selectTipoCompensatorio
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
	//Módulo paraa verificación de rol llamando al modelo esAdministrador
	public function verificarRol() {
		
		// Verificar si el usuario tiene el rol de administrador
		$idRol = $_SESSION['userData']['ID_ROL'];
		$esAdministrador = $this->model->esAdministrador($idRol);
		
		$response = array(
			'esAdministrador' => $idRol
		);
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	//Módulo para subir evidencia llamando al modelo guardarEvidencia
	public function subirEvidencia($idCompensatorio) {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$idCompensatorio = isset($_POST['ID_COMPENSATORIO']) ? intval($_POST['ID_COMPENSATORIO']) : 0;

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
					$return = $this->model->guardarEvidencia($name, $idCompensatorio);
		
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
}
 ?>


 