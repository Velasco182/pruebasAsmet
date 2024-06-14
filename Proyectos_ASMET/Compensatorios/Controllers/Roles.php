<?php 

	class Roles extends Controllers{
		public function __construct(){
			parent::__construct();
			session_start();
			session_regenerate_id();

			if(empty($_SESSION['login'])){	
				header('Location: '.base_url().'/login');
			}else{
				getPermisos(COD_MOD_ROLES);
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

		public function Roles(){
			$data['page_tag'] = $_SESSION['permisosMod']['MOD_TITULO'];
			$data['page_title'] = $_SESSION['permisosMod']['MOD_TITULO'];
			$data['page_name'] = $_SESSION['permisosMod']['MOD_TITULO'];
			$data['page_icono'] = $_SESSION['permisosMod']['MOD_ICONO'];
			$data['page_acceso'] = $_SESSION['permisosMod']['MOD_ACCESO'];
			$data['page_functions_js'] = "functions_".$_SESSION['permisosMod']['MOD_ACCESO'].".js";
			$this->views->getView($this,$_SESSION['permisosMod']['MOD_ACCESO'],$data);
		}

		public function getRoles(){
			if($_SESSION['permisosMod']['PER_R']){
				$btnView = '';
				$btnEdit = '';
				$btnDelete = '';
				$arrData = $this->model->selectRoles();

				for ($i=0; $i < count($arrData); $i++) {

					if($arrData[$i]['ROL_ESTADO'] == 1){
						$arrData[$i]['ROL_ESTADO'] = '<span class="badge badge-success">Activo</span>';
					}else{
						$arrData[$i]['ROL_ESTADO'] = '<span class="badge badge-danger">Inactivo</span>';
					}

					if($_SESSION['permisosMod']['PER_U']){
						$btnView = '<button class="btn btn-secondary btn-sm btnPermisosRol" onClick="fntPermisos('.$arrData[$i]['ID_ROL'].')" title="Permisos"><i class="fas fa-key"></i></button>';
						$btnEdit = '<button class="btn btn-primary btn-sm btnEditRol" onClick="fntEditRol('.$arrData[$i]['ID_ROL'].')" title="Editar"><i class="fas fa-pencil-alt"></i></button>';
					}
					if($_SESSION['permisosMod']['PER_D']){
						$btnDelete = '<button class="btn btn-danger btn-sm btnDelRol" onClick="fntDelRol('.$arrData[$i]['ID_ROL'].')" title="Eliminar"><i class="far fa-trash-alt"></i></button>
					</div>';
					}
					$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
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
			echo $htmlOptions;		
		}

		public function getRol(int $ID_ROL){
			if($_SESSION['permisosMod']['PER_R']){
				$intIdrol = intval(strClean($ID_ROL));
				if($intIdrol > 0)
				{
					$arrData = $this->model->selectRol($intIdrol);
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
		
		public function setRol(){
			if($_SESSION['permisosMod']['PER_W']){
				$intIdrol = intval($_POST['idRol']);
				$strRol =  strClean($_POST['txtNombre']);
				$strDescipcion = strClean($_POST['txtDescripcion']);
				$intStatus = intval($_POST['listStatus']);
				$strCodigo =  strClean($_POST['txtCodigo']);

				if($intIdrol == 0){
					//Crear
					$request_rol = $this->model->insertRol($strRol,$strDescipcion,$intStatus,$strCodigo);
					$option = 1;
				}else{
					//Actualizar
					$request_rol = $this->model->updateRol($intIdrol, $strRol, $strDescipcion, $intStatus,$strCodigo);
					$option = 2;
				}

				if($request_rol > 0 ){
					if($option == 1){
						$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
					}else{
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
					}
				}else if($request_rol == 'exist'){
					$arrResponse = array('status' => false, 'msg' => '¡Atención! El Rol ya existe.');
				}else{
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
		}

		public function delRol(){
			if($_POST){
				if($_SESSION['permisosMod']['PER_D']){
					$intIdrol = intval($_POST['ID_ROL']);
					$requestDelete = $this->model->deleteRol($intIdrol);
					if($requestDelete == 'ok'){
						$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Rol');
					}else if($requestDelete == 'exist'){
						$arrResponse = array('status' => false, 'msg' => 'No es posible eliminar un Rol asociado a usuarios.');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Rol.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
		}
	}
 ?>