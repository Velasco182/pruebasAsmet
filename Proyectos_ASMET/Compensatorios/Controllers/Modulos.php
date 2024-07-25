<?php 

	class Modulos extends Controllers{
		public function __construct(){
			parent::__construct();
			session_start();
			session_regenerate_id();

			if(empty($_SESSION['login'])){	
				header('Location: '.base_url().'/login');
			}else{
				getPermisos(COD_MOD_MOD);
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

		public function Modulos(){
			$data['page_tag'] = $_SESSION['permisosMod']['MOD_TITULO'];
			$data['page_title'] = $_SESSION['permisosMod']['MOD_TITULO'];
			$data['page_name'] = $_SESSION['permisosMod']['MOD_TITULO'];
			$data['page_icono'] = $_SESSION['permisosMod']['MOD_ICONO'];
			$data['page_acceso'] = $_SESSION['permisosMod']['MOD_ACCESO'];
			$data['page_functions_js'] = "functions_".$_SESSION['permisosMod']['MOD_ACCESO'].".js";
			$this->views->getView($this,$_SESSION['permisosMod']['MOD_ACCESO'],$data);
		}

		public function getModulos(){
			if($_SESSION['permisosMod']['PER_R']){
				$btnView = '';
				$btnEdit = '';
				$btnDelete = '';
				$arrData = $this->model->selectModulos();

				for ($i=0; $i < count($arrData); $i++) {

					if($arrData[$i]['MOD_ESTADO'] == 1){
						$arrData[$i]['MOD_ESTADO'] = '<span class="badge badge-success">Activo</span>';
					}else{
						$arrData[$i]['MOD_ESTADO'] = '<span class="badge badge-danger">Inactivo</span>';
					}

					if($arrData[$i]['MOD_ICONO'] != ""){
						$arrData[$i]['MOD_ICONO'] = '<i class="fa fa-lg '.$arrData[$i]['MOD_ICONO'].'"></i>';
					}else{
						$arrData[$i]['MOD_ICONO'] = '<i class="fa fa-lg fa-info"></i>';
					}

					//acciones
					if($_SESSION['permisosMod']['PER_U']){
						$btnEdit = '<button class="btn btn-primary btn-sm btnEditModulo" onClick="fntEditModulo('.$arrData[$i]['ID_MODULO'].')" title="Editar"><i class="fa fa-sm fa-pencil-alt"></i></button>';
					}
					if($_SESSION['permisosMod']['PER_R']){
						$btnView = '<button class="btn btn-info btn-sm btnViewModulo" onClick="fntViewModulo('.$arrData[$i]['ID_MODULO'].')" title="Ver Modulo"><i class="far fa-eye"></i></button>';
					}
					if($_SESSION['permisosMod']['PER_D']){
						if($arrData[$i]['MOD_CODIGO']!="2F" AND $arrData[$i]['MOD_CODIGO']!="3M" AND $arrData[$i]['MOD_CODIGO']!="4M"
							AND $arrData[$i]['MOD_CODIGO']!="5R" AND $arrData[$i]['MOD_CODIGO']!="6C" AND $arrData[$i]['MOD_CODIGO']!="7T" AND $arrData[$i]['MOD_CODIGO']!="8T"){
							$btnDelete = '<button class="btn btn-danger btn-sm btnDeleteModulo" onClick="fntDelModulo('.$arrData[$i]['ID_MODULO'].')" title="Eliminar"><i class="fa fa-sm fa-trash"></i></button>';
						}else{
							$btnDelete = '<button class="btn btn-danger btn-sm btnDeleteModulo" disabled><i class="fa fa-sm fa-trash"></i></button>';
						}
					}
					$arrData[$i]['options'] = '<div class="text-center">'.$btnEdit.' '.$btnView.' '.$btnDelete.'</div>';
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
		}

		public function getModulo(int $ID_MODULO){
			if($_SESSION['permisosMod']['PER_U']){
				$ID_MODULO = intval(strClean($ID_MODULO));
				if($ID_MODULO > 0){
					$arrData = $this->model->selectModulo($ID_MODULO);
					$arrData["MENUS"]=$this->getSelectMenus();

					if(empty($arrData)){
						$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
					}else{
						$arrResponse = array('status' => true, 'data' => $arrData);
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
		}

		public function getModuloView(int $ID_MODULO){
			if($_SESSION['permisosMod']['PER_U']){
				$ID_MODULO = intval(strClean($ID_MODULO));
				if($ID_MODULO > 0){
					$arrData = $this->model->selectModulo($ID_MODULO);
					$arrData["MENUS"]=$this->getSelectMenus();
					// print_r('Estoy ubicado aqui');
					if($arrData['MOD_ESTADO'] == 1){
						$arrData['MOD_ESTADO'] = '<span class="badge badge-success">Activo</span>';
					}else{
						$arrData['MOD_ESTADO'] = '<span class="badge badge-danger">Inactivo</span>';
					}

					if($arrData['MOD_LISTAR'] == 1){
						$arrData['MOD_LISTAR'] = '<span class="badge badge-success">Si</span>';
					}else{
						$arrData['MOD_LISTAR'] = '<span class="badge badge-danger">No</span>';
					}

					if($arrData['MOD_ICONO'] != ""){
						$arrData['MOD_ICONO'] = '<i class="fa fa-lg '.$arrData['MOD_ICONO'].'"></i>';
					}else{
						$arrData['MOD_ICONO'] = '<i class="fa fa-lg fa-info"></i>';
					}

					if(empty($arrData)){
						$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
					}else{
						$arrResponse = array('status' => true, 'data' => $arrData);
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
					// print_r($arrData);
				}
			}
		}
		
		public function setModulos(){
			if($_SESSION['permisosMod']['PER_W'] or $_SESSION['permisosMod']['PER_U']){
				$intIdModulo = intval($_POST['idModulo']);
				$intIdMenu = intval($_POST['listaMenus']);
				$strTitulo = strClean($_POST['txtNombre']);
				$strDesc = strClean($_POST['txtDescripcion']);
				$strCodigo = strClean($_POST['txtCodigo']);
				$strIcono = strClean($_POST['txtIcono']);
				$strAcceso = strClean($_POST['txtAcceso']);
				$intListar = intval($_POST['listaMostrar']);
				$intEstado = intval($_POST['listStatus']);
				
				$strTitulo = mb_convert_case($strTitulo,MB_CASE_TITLE, "UTF-8");
				$strDesc = mb_convert_case($strDesc,MB_CASE_TITLE, "UTF-8");
				$strCodigo = mb_convert_case($strCodigo,MB_CASE_UPPER, "UTF-8");
				$strIcono = mb_convert_case($strIcono,MB_CASE_LOWER, "UTF-8");
				$strAcceso = mb_convert_case($strAcceso,MB_CASE_LOWER, "UTF-8");

				$request_m="";
				if($intIdModulo == 0){
					if($_SESSION['permisosMod']['PER_W']){
						$request_m = $this->model->insertModulo($intIdMenu,$strTitulo,$strDesc,$strCodigo,
							$strIcono,$strAcceso,$intListar,$intEstado
						);
					}
					$option = 1;
				}else{
					if($_SESSION['permisosMod']['PER_U']){
						$request_m = $this->model->updateModulo($intIdModulo,$intIdMenu,$strTitulo,$strDesc,$strCodigo,
							$strIcono,$strAcceso,$intListar,$intEstado
						);
					}
					$option = 2;
				}

				if($request_m > 0 ){
					if($option == 1){
						$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
					}else{
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
					}
				}else if($request_m == 'exist'){
					$arrResponse = array('status' => false, 'msg' => '¡Atención! El titulo o el código ya existe.');
				}else{
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
		}

		public function delModulo(){
			if($_POST){
				if($_SESSION['permisosMod']['PER_D']){
					$idModulo = intval($_POST['ID_MODULO']);
					$requestDelete = $this->model->deleteModulo($idModulo);
					if($requestDelete == 'ok'){
						$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el registro');
					}else if($requestDelete == 'exist'){
						$arrResponse = array('status' => false, 'msg' => 'No es posible eliminar un registro asociado a otros registros.');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el registro.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
		}

		public function getMenus(){
			$arrData = $this->model->listarMenus();
			echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
		}

		public function getSelectMenus(){
			$htmlOptions = "";
			$arrData = $this->model->listarMenus();
			if(count($arrData) > 0 ){
				for ($i=0; $i < count($arrData); $i++) { 
					if($arrData[$i]['MEN_ESTADO'] == 1 ){
					$htmlOptions .= '<option value="'.$arrData[$i]['ID_MENU'].'">'.$arrData[$i]['MEN_TITULO'].'</option>';
					}
				}
			}
			return $htmlOptions;		
		}
	}
 ?>