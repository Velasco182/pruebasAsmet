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

	//------Función de Inserción de datos-------
	//Módulo para crear tipo de compensatorio llamando al modelo insertTipoCompensatorio
	public function setTipoCompensatorio(){
		
		if ($_POST) {
	
			if ($_POST['txtNombreTipoCompensatorio'] == '' 
			|| $_POST['txtDescripcionTipoCompensatorio'] == '' 
			|| $_POST['txtEstadoTipoCompensatorio'] == '') {

				$arrResponse = array("status" => false, "msg" => 'Ingrese todos los datos.');
			
			} else {
				
				$intIdTipoCompensatorio = intval($_POST['idTipoCompensatorio']);
				$strNombreTipoCompensatorio = mb_convert_case(strClean($_POST['txtNombreTipoCompensatorio']), MB_CASE_TITLE, "UTF-8");
				$strDescripcionTipoCompensatorio = mb_convert_case(strClean($_POST['txtDescripcionTipoCompensatorio']), MB_CASE_TITLE, "UTF-8");
				$intTipoCompensatorioEstado = intval($_POST['txtEstadoTipoCompensatorio']);
				
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
						);
						$option = 2; // Actualización
					}
				}

				$arrResponse = array('status' => false, 'msg' => 'El nombre del tipo de compensatorio ya existe!');

				if ($option == 1) {

					$request_user === "exist" ? $arrResponse : $arrResponse = array('status' => true, 'msg' => 'Insertado con éxito!');
					
				} else {

					$request_user === "exist" ? $arrResponse : $arrResponse = array('status' => true, 'msg' => 'Actualizado con éxito!');

				}
			}
		}
		echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
	}

	//-----Funciones de recuperación de datos----
	//Módulo para obtener tipos de compensatorios llamando al modelo selectTipoCompensatorios
	public function getTipoCompensatorios(){
		if($_SESSION['permisosMod']['PER_R']){
			$arrData = $this ->model->selectTipoCompensatorios();
			
			for ($i=0; $i < sizeof($arrData); $i++) {
				
				$btnEdit = '';
				$btnVer = '';
				$btnEliminar = '';

				if($arrData[$i]['TIP_COM_ESTADO'] == 1){
					$arrData[$i]['TIP_COM_ESTADO'] = '<span class="badge badge-success">Activo</span>';
				}else{
					$arrData[$i]['TIP_COM_ESTADO'] = '<span class="badge badge-danger">Inactivo</span>';
				}

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
				
				$arrData[$i]['ACCIONES'] = '<div class="text-center">'.$btnVer.' '.$btnEdit.' '.$btnEliminar.'</div>';
			}
			echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
		}
	}
	//Módulo para obtener tipo de compensatorios por ID llamando al modelo selectTipoCompensatorioVista
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

	//-----Funciones de actualización-----
	//Módulo para editar el tipo de compensatorio llamando al modelo selectTipoCompensatorioEdit
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
	//Módulo para eliminar el tipo de compensatorio llamando al modelo deleteTipoCompensatorio
	public function delTipoCompensatorio(){
		if($_POST){
			if($_SESSION['permisosMod']['PER_D']){
				$intIdTipoCompensatorio = intval($_POST['ID_TIPO_COMPENSATORIO']);
				$requestDelete = $this->model->deleteTipoCompensatorio($intIdTipoCompensatorio);
				
				if($requestDelete == 'ok'){
					$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado exitosamente!.');
				}else if($requestDelete == 'exist'){
					$arrResponse = array('status' => false, 'msg' => 'El tipo de compensatorio está relacionado con un registro, no es posible eliminarlo!');
				}else{
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);

			}
		}
	}

	//-----Funciones generales-----
}
 ?>


 