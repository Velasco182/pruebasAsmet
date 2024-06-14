<?php 

	class Menus extends Controllers{
		public function __construct(){
			parent::__construct();
			session_start();
			session_regenerate_id();

			if(empty($_SESSION['login'])){	
				header('Location: '.base_url().'/login');
			}else{
				getPermisos(COD_MOD_MENU);
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

		public function Menus(){
			$data['page_tag'] = $_SESSION['permisosMod']['MOD_TITULO'];
			$data['page_title'] = $_SESSION['permisosMod']['MOD_TITULO'];
			$data['page_name'] = $_SESSION['permisosMod']['MOD_TITULO'];
			$data['page_icono'] = $_SESSION['permisosMod']['MOD_ICONO'];
			$data['page_acceso'] = $_SESSION['permisosMod']['MOD_ACCESO'];
			$data['page_functions_js'] = "functions_".$_SESSION['permisosMod']['MOD_ACCESO'].".js";
			$this->views->getView($this,$_SESSION['permisosMod']['MOD_ACCESO'],$data);
		}

		public function getMenus(){
			if($_SESSION['permisosMod']['PER_R']){
				$btnView = '';
				$btnEdit = '';
				$btnDelete = '';
				$arrData = $this->model->selectMenus();

				for ($i=0; $i < count($arrData); $i++) {

					if($arrData[$i]['MEN_ESTADO'] == 1){
						$arrData[$i]['MEN_ESTADO'] = '<span class="badge badge-success">Activo</span>';
					}else{
						$arrData[$i]['MEN_ESTADO'] = '<span class="badge badge-danger">Inactivo</span>';
					}

					if($arrData[$i]['MEN_ICONO'] != ""){
						$arrData[$i]['MEN_ICONO'] = '<i class="fa fa-lg '.$arrData[$i]['MEN_ICONO'].'"></i>';
					}else{
						$arrData[$i]['MEN_ICONO'] = '<i class="fa fa-lg fa-info"></i>';
					}

					if($_SESSION['permisosMod']['PER_U']){
						$btnEdit = '<button class="btn btn-primary btn-sm btnEditMenu" onClick="fntEditMenu('.$arrData[$i]['ID_MENU'].')" title="Editar"><i class="fa fa-sm fa-pencil-alt"></i></button>';
					}
					if($_SESSION['permisosMod']['PER_D']){
						if($arrData[$i]['MEN_CODIGO']=="1A"){
							$btnDelete = '<button class="btn btn-danger btn-sm" disabled title="Eliminar"><i class="fa fa-sm fa-trash"></i></button>';
						}else{
							$btnDelete = '<button class="btn btn-danger btn-sm btnDelMenu" onClick="fntDelMenu('.$arrData[$i]['ID_MENU'].')" title="Eliminar"><i class="fa fa-sm fa-trash"></i></button>';
						}
					}

					$arrData[$i]['options'] = '<div class="text-center">'.$btnEdit.' '.$btnDelete.'</div>';
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
		}
		public function getMenu(int $ID_MENU){
			if($_SESSION['permisosMod']['PER_R']){
				$intIdmenu = intval(strClean($ID_MENU));
				if($intIdmenu > 0){
					$arrData = $this->model->selectMenu($intIdmenu);
					if(empty($arrData))
					{
						$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
					}else{
						$arrResponse = array('status' => true, 'data' => $arrData);
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
		}
		
		public function setMenu(){
			if($_SESSION['permisosMod']['PER_W'] or $_SESSION['permisosMod']['PER_U']){
				$intIdmenu = intval($_POST['idMenu']);
				$strTitulo = strClean($_POST['txtNombre']);
				$strCodigo = strClean($_POST['txtCodigo']);
				$intEstado = intval($_POST['listStatus']);
				$strIcono = strClean($_POST['txtIcono']);

				$strTitulo = mb_convert_case($strTitulo,MB_CASE_TITLE, "UTF-8");
				$strCodigo = mb_convert_case($strCodigo,MB_CASE_UPPER, "UTF-8");
				$strIcono = mb_convert_case($strIcono,MB_CASE_LOWER, "UTF-8");

				$request_rol="";
				if($intIdmenu == 0){
					if($_SESSION['permisosMod']['PER_W']){
						$request_rol = $this->model->insertMenu($strTitulo,$strCodigo,$intEstado,$strIcono);
					}
					$option = 1;
				}else{
					if($_SESSION['permisosMod']['PER_U']){
						$request_rol = $this->model->updateMenu($intIdmenu,$strTitulo,$strCodigo,$intEstado,$strIcono);
					}
					$option = 2;
				}

				if($request_rol > 0 ){
					if($option == 1){
						$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
					}else{
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
					}
				}else if($request_rol == 'exist'){
					$arrResponse = array('status' => false, 'msg' => '¡Atención! El titulo o el código ya existe.');
				}else{
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
		}

		public function delMenu(){
			if($_POST){
				if($_SESSION['permisosMod']['PER_D']){
					$idMenu = intval($_POST['ID_MENU']);
					$requestDelete = $this->model->deleteMenu($idMenu);
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
	}
 ?>