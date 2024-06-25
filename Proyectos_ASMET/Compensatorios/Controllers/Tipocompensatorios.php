<?php 

class Tipocompensatorios extends Controllers{
	public function __construct(){
		parent::__construct();
		session_start();
		session_regenerate_id();

		if(empty($_SESSION['login'])){
			header('Location: '.base_url().'/login');
		}else{
			getPermisos(COD_MOD_TIPCOMP);
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

	public function Tipocompensatorios(){
		$data['page_tag'] = $_SESSION['permisosMod']['MOD_TITULO'];
		$data['page_title'] = $_SESSION['permisosMod']['MOD_TITULO'];
		$data['page_name'] = $_SESSION['permisosMod']['MOD_TITULO'];
		$data['page_icono'] = $_SESSION['permisosMod']['MOD_ICONO'];
		$data['page_acceso'] = $_SESSION['permisosMod']['MOD_ACCESO'];
		$data['page_functions_js'] = "functions_".$_SESSION['permisosMod']['MOD_ACCESO'].".js";
		$this->views->getView($this,$_SESSION['permisosMod']['MOD_ACCESO'],$data);
	}

	
	public function setTipoCompensatorio(){
		if ($_POST) {
	
			if ($_POST['txtNombreTipoCompensatorio'] == '' || $_POST['txtDescripcionTipoCompensatorio'] == '' || $_POST['txtEstadoTipoCompensatorio'] == '') {
				$arrResponse = array("status" => false, "msg" => 'Ingrese todos los datos.');
			} else {
				
				$intIdTipoCompensatorio = intval($_POST['idTipoCompensatorio']);
				$strNombreTipoCompensatorio = mb_convert_case(strClean($_POST['txtNombreTipoCompensatorio']), MB_CASE_TITLE, "UTF-8");
				$strDescripcionTipoCompensatorio = mb_convert_case(strClean($_POST['txtDescripcionTipoCompensatorio']), MB_CASE_TITLE, "UTF-8");
				$intTipoCompensatorioEstado = intval($_POST['txtEstadoTipoCompensatorio']);
				
				//Dep($strNombreTipoCompensatorio . "controller");

				$request_user = 0;
				$option = 0; // Agregado para definir la operación (0: no definida, 1: inserción, 2: actualización)

				if ($intIdTipoCompensatorio == 0) {
					if ($_SESSION['permisosMod']['PER_W']) {
						$request_user = $this->model->insertTipoCompensatorio(
							$strNombreTipoCompensatorio,
							$strDescripcionTipoCompensatorio,
							$intTipoCompensatorioEstado,
						);
						$option = 1; // Inserción
					}
				} else {
					if ($_SESSION['permisosMod']['PER_U']) {
						$request_user = $this->model->updateTipoCompensatorio(
							$intIdTipoCompensatorio,
							$strNombreTipoCompensatorio,
							$strDescripcionTipoCompensatorio,
							$intTipoCompensatorioEstado,
							/*$strDescripcionActividad,
							$strActividad,
							$strTrabajoRequerido*/
						);
						$option = 2; // Actualización
					}
				}

				//if ($request_user > 0) {
				if ($option == 1) {
					// Bloque de envío de correo para inserción

					/*$remitente = 'estivenmendez550@gmail.com';
					$destinatario = 'aprendiz.bi@asmetsalud.com';
					$asunto = 'Solicitud de compensatorio';
					$tipoMensaje = 'solicitud';

					$txtFechaInicio = $_POST['txtFechaInicio'];
					$txtFechaFin = $_POST['txtFechaFin'];

					$fechaInicioFormateada = date('d/m/Y - h:i A', strtotime($txtFechaInicio));
					$fechaFinFormateada = date('d/m/Y - h:i A', strtotime($txtFechaFin));

					$datos = [
						'FechaInicio' => $fechaInicioFormateada,
						'Funcionario' => $arrData["NOMBREFUNCIONARIO"],
						'FechaFin' => $fechaFinFormateada,
						'Actividad' => $_POST['txtActividad'],
						'UsuarioTrabajo' => $_POST['txtTrabajoRequerido'],
						'DescripcionAc' => $_POST['txtDescripcionActividad']
					];

					$html = generarHTML($tipoMensaje, $datos);*/

					//try {
						
						$arrResponse = array('status' => true, 'msg' => 'Insertado con éxito!');
						
						//$enviarcorreo = enviarMail($remitente, $destinatario, $asunto, 'solicitud', $datos);
						//$arrResponse = array('status' => true, 'msg' => 'Su solicitud fue procesada con éxito, espera que el admin apruebe tu compensatorio');
					/*} catch (Exception $e) {
						$arrResponse = array('status' => false, 'msg' => 'Error al enviar el correo: ' . $e->getMessage());
					}*/
				} else {
					// Bloque de actualización (puedes agregar un mensaje si deseas)
					$arrResponse = array('status' => true, 'msg' => 'Actualizado con éxito!');
				}
				/*} else {
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
			}*/
			}
		}
		echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
	}
	
	public function getTipoCompensatorios(){
		if($_SESSION['permisosMod']['PER_R']){
			$arrData = $this ->model->selectTipoCompensatorios();
			// Procesa $resultado para mostrar los compensatorios del funcionario

			

			for ($i=0; $i < sizeof($arrData); $i++) {
				

				/*$arrData[$i]['COM_FECHA_INICIO']=formatearFechaYHora($arrData[$i]['COM_FECHA_INICIO'],"d/m/Y - h:i A");
				$arrData[$i]['COM_FECHA_FIN']=formatearFechaYHora($arrData[$i]['COM_FECHA_FIN'],"d/m/Y - h:i A");*/

				// var_dump($arrData);

				$btnEdit = '';
				$btnVer = '';
				$btnEliminar = '';

				if($arrData[$i]['TIP_COM_ESTADO'] == 1){
					$arrData[$i]['TIP_COM_ESTADO'] = '<span class="badge badge-success">Activo</span>';
				}else{
					$arrData[$i]['TIP_COM_ESTADO'] = '<span class="badge badge-danger">Inactivo</span>';
				}


				// $arrData[$i]['COM_ESTADO'] = '<span class="badge ' . $statusClass . '">' . $newStatus . '</span>'; // Error con los span

				// var_dump($comEstado);

				/*if ($_SESSION['permisosMod']['PER_F'] && $comEstado == 2) {
					$btnReset = '<button class="btn btn-success btn-sm btnResetPass" onClick="ftnEvidencias(' . $arrData[$i]['ID_COMPENSATORIO'] . ')" title="Cargar Evidencias"><i class="fas fa-cloud-upload-alt"></i></button>';
				} else {
					$btnReset = ''; // Botón vacío si no se cumple la condición
				}*/

				//PER_R -> LEEER
				//PER_W -> ESCRIBIR
				//PER_U -> ACTUALIZAR
				//PER_D -> ELIMINAR

				if($_SESSION['permisosMod']['PER_R']){ // Icono de ver funcionario
					$btnVer = '<button class="btn btn-info btn-sm btnViewFuncionario" onClick="fntViewTipoCompensatorio('.$arrData[$i]['ID_TIPO_COMPENSATORIO'].')" title="Ver Tipo Compensatorio"><i class="far fa-eye"></i></button>';
				}
				
				if($_SESSION['permisosMod']['PER_U']){ // Icono de Editar Tipo Compensatorio
					$btnEdit = '<button class="btn btn-primary btn-sm btnEditFuncionario" onClick="ftnEditTipoCompensatorio(this,'.$arrData[$i]['ID_TIPO_COMPENSATORIO'].')" title="Editar Tipo Compensatorio"><i class="fas fa-pencil-alt"></i></button>';
				}

				if ($_SESSION['permisosMod']['PER_D']) {
					$btnEliminar = '<button class="btn btn-danger btn-sm btnDelFuncionario" onClick="ftnDeleteTipoCompensatorio('.$arrData[$i]['ID_TIPO_COMPENSATORIO'].')" title="Eliminar Tipo Compenstorio"><i class="fa fa-sm fa-trash"></i></button>';
				}

				/*if($_SESSION['permisosMod']['ID_ROL'] == '2' && $comEstado == 1){
				 	if($arrData[$i]['COM_USUARIO_FINAL']!="1"){
				 		$btnEdit = '<button class="btn btn-primary  btn-sm btnEditFuncionario" onClick="btnEditCompensatorio(this,'.$arrData[$i]['ID_COMPENSATORIO'].')" title="Editar Funcionario"><i class="fas fa-pencil-alt"></i></button>';
				 	}else{
				 		$btnEdit = '';
				 	}
				}

				if($_SESSION['permisosMod']['PER_U'] && $_SESSION['permisosMod']['ID_ROL'] !== '1') {
					if($arrData[$i]['COM_USUARIO_FINAL'] != "1" && ($comEstado == 1)) {
						$btnEdit = '<button class="btn btn-primary btn-sm btnEditFuncionario" onClick="btnEditCompensatorio(this,'.$arrData[$i]['ID_COMPENSATORIO'].')" title="Editar Funcionario"><i class="fas fa-pencil-alt"></i></button>';
					} else {
						$btnEdit = '';
					}
				}
				
				if ($_SESSION['permisosMod']['PER_U']) { // Botón de aprobaciones
					if ($comEstado == 1) {
				 		$btnAprobar = '<button class="btn btn-sm btn-primary" onClick="ftnAprobarCompensatorio(' . $arrData[$i]['ID_COMPENSATORIO'] . ')" title="Aprobar Compensatorio"><i class="fas fa-check-double"></i></button>';
				 	} else {
				 		$btnAprobar = '';
				 	}
				}

				if ($_SESSION['permisosMod']['PER_U'] && $_SESSION['permisosMod']['ID_ROL'] === '1') {
					if ($comEstado == 1) {
						$btnAprobar = '<button class="btn btn-sm btn-primary" onClick="ftnAprobarCompensatorio(' . $arrData[$i]['ID_COMPENSATORIO'] . ')" title="Aprobar Compensatorio"><i class="fas fa-check-double"></i></button>';
					} else {
						$btnAprobar = '';
					}
				} else {
					$btnAprobar = '';
				}*/
				
				if ($_SESSION['permisosMod']['PER_D']) {
					if ($comEstado == 1) {
						$btnRechazar = '<button class="btn btn-danger btn-sm btnDelFuncionario" onClick="ftnRechazarCompensatorio(' . $arrData[$i]['ID_COMPENSATORIO'] . ')" title="Rechazar Compenstorio"><i class="fas fa-times-circle"></i></button>';
					} else {
						$btnRechazar = '';
					}
				}*/
				
				$arrData[$i]['ACCIONES'] = '<div class="text-center">'.$btnVer.' '.$btnEdit.' '.$btnEliminar.'</div>';
			}
			echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
		}
	}

	public function editTipoCompensatorio($idTipoCompensatorio) {

		//Duda, los permisos deberían ser de Update?, entiendo que está haciendo una lectura de igual forma
		if ($_SESSION['permisosMod']['PER_U']) {
			$idTipoCompensatorio = intval($idTipoCompensatorio);
			if ($idTipoCompensatorio > 0) {

				$arrData = $this->model->selectTipoCompensatorioEdit($idTipoCompensatorio);
				
				if (empty($arrData)) {
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				} else {
					$arrResponse = array('status' => true, 'data' => $arrData);
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
		}
	}

	public function getTipoCompensatorio($idTipoCompensatorio) {
		if ($_SESSION['permisosMod']['PER_R'] && intval($idTipoCompensatorio) > 0) {
			$arrData = $this->model->selectTipoCompensatorioVista($idTipoCompensatorio);
		
			if (!empty($arrData)) {
				// Preparar la respuesta
				$arrResponse = array('status' => true, 'data' => $arrData);

			} else {
				$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
			}
		
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE); // Devuelve la respuesta JSON
		}
	}

	public function delTipoCompensatorio(){
		if($_POST){
			if($_SESSION['permisosMod']['PER_D']){
				$intIdTipoCompensatorio = intval($_POST['ID_TIPO_COMPENSATORIO']);
				$requestDelete = $this->model->deleteTipoCompensatorio($intIdTipoCompensatorio);
				
				if($requestDelete == 'ok'){
					$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado Exitosamente!.');
				}else if($requestDelete == 'exist'){
					$arrResponse = array('status' => false, 'msg' => 'No es posible.');
				}else{
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);

			}
		}
	}
		
	//ControladorAprobacion.php
	/*public function aprobarCompensatorio() {
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
	}*/
}//fin de la clase
 ?>


 