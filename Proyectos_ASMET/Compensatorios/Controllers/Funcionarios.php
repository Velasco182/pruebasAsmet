<?php 

	class Funcionarios extends Controllers{
		public function __construct(){
			parent::__construct();
			session_start();
			session_regenerate_id();

			if(empty($_SESSION['login'])){
				header('Location: '.base_url().'/login');
			}else{
				getPermisos(COD_MOD_FUN);
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

		public function Funcionarios(){
			$data['page_tag'] = $_SESSION['permisosMod']['MOD_TITULO'];
			$data['page_title'] = $_SESSION['permisosMod']['MOD_TITULO'];
			$data['page_name'] = $_SESSION['permisosMod']['MOD_TITULO'];
			$data['page_icono'] = $_SESSION['permisosMod']['MOD_ICONO'];
			$data['page_acceso'] = $_SESSION['permisosMod']['MOD_ACCESO'];
			$data['page_functions_js'] = "functions_".$_SESSION['permisosMod']['MOD_ACCESO'].".js";
			$this->views->getView($this,$_SESSION['permisosMod']['MOD_ACCESO'],$data);
		}

		public function setFuncionario(){
			if($_POST){	
				if($_POST['txtIdentificacion']=='' || $_POST['txtNombre']=='' || $_POST['txtApellido']==''
					|| $_POST['txtUsuario']=='' || $_POST['txtEmail']=='' || $_POST['listRolid']=='' 
					|| $_POST['listStatus']==''){
					$arrResponse = array("status" => false, "msg" => 'Ingrese todos los datos.');
				}else{
					$idFuncionario = intval($_POST['idFuncionario']);
					$strIdentificacion = strClean($_POST['txtIdentificacion']);
					$strNombre = mb_convert_case(strClean($_POST['txtNombre']),MB_CASE_TITLE, "UTF-8");
					$strApellido = mb_convert_case(strClean($_POST['txtApellido']),MB_CASE_TITLE, "UTF-8");
					$strUsuario = mb_convert_case(strClean($_POST['txtUsuario']),MB_CASE_LOWER, "UTF-8");
					$strEmail = mb_convert_case(strClean($_POST['txtEmail']),MB_CASE_LOWER, "UTF-8");
					$intTipoId = intval(strClean($_POST['listRolid']));
					$intStatus = intval(strClean($_POST['listStatus']));
					$request_user = "";
					if($idFuncionario == 0){
						$option = 1;
						$strPassword = hash("SHA256",$strUsuario.".23");

						if($_SESSION['permisosMod']['PER_W']){
							$request_user = $this->model->insertFuncionario(
								$strIdentificacion,
								$strNombre, 
								$strApellido, 
								$strUsuario, 
								$strEmail,
								$strPassword, 
								$intTipoId, 
								$intStatus
							);
						}
					}else{
						$option = 2;
						if($_SESSION['permisosMod']['PER_U']){
							$request_user = $this->model->updateFuncionario(
								$idFuncionario,
								$strIdentificacion, 
								$strNombre,
								$strApellido, 
								$strUsuario, 
								$strEmail,
								$intTipoId, 
								$intStatus
							);
						}
					}

					// var_dump();

					if($request_user > 0){
						if($option == 1){
							$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
						}else{
							$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
						}
					}else if($request_user == 'exist'){
						$arrResponse = array('status' => false, 'msg' => 'El usuario o correo ya existe, ingrese otro por favor!');		
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
		}

		public function getFuncionarios(){
			if($_SESSION['permisosMod']['PER_R']){
				$arrData = $this->model->selectFuncionarios();
				for ($i=0; $i < count($arrData); $i++) {
					$btnView = '';
					$btnEdit = '';
					$btnReset = '';
					$btnDelete = '';
					$newStatus="";

					if($arrData[$i]['FUN_ESTADO'] == 1){
						$newStatus="0";
						$arrData[$i]['FUN_ESTADO'] = '<span class="badge badge-success">Activo</span>';
					}else{
						$newStatus="1";
						$arrData[$i]['FUN_ESTADO'] = '<span class="badge badge-danger">Inactivo</span>';
					}

					if($_SESSION['permisosMod']['PER_R']){
						if($arrData[$i]['FUN_ADMIN']!="1"){
							$btnView = '<button class="btn btn-info btn-sm btnViewFuncionario" onClick="fntViewFuncionario('.$arrData[$i]['ID_FUNCIONARIO'].')" title="Ver Funcionario"><i class="far fa-eye"></i></button>';
						}else{
							$btnView = '<button class="btn btn-secondary btn-sm" disabled ><i class="far fa-eye"></i></button>';
						}
					}

					if($_SESSION['permisosMod']['PER_U']){
						if($arrData[$i]['FUN_ADMIN']!="1"){
							$btnEdit = '<button class="btn btn-primary  btn-sm btnEditFuncionario" onClick="fntEditFuncionario(this,'.$arrData[$i]['ID_FUNCIONARIO'].')" title="Editar Funcionario"><i class="fas fa-pencil-alt"></i></button>';
						}else{
							$btnEdit = '<button class="btn btn-secondary btn-sm" disabled ><i class="fas fa-pencil-alt"></i></button>';
						}
					}

					if($_SESSION['permisosMod']['PER_D']){
						if($arrData[$i]['FUN_ADMIN']!="1"){
							$btnDelete = '<button class="btn btn-danger btn-sm btnDelFuncionario" onClick="fntDelFuncionario('.$arrData[$i]['ID_FUNCIONARIO'].','.$newStatus.')" title="Cambiar Estado"><i class="fa fa-refresh"></i></button>';
						}else{
							$btnDelete = '<button class="btn btn-secondary btn-sm" disabled ><i class="fa fa-refresh"></i></button>';
						}
					}

					if($_SESSION['permisosMod']['PER_U']){
						if($arrData[$i]['FUN_ADMIN']!="1"){
							$btnReset = '<button class="btn btn-warning btn-sm btnResetPass" onClick="fntReserPass('.$arrData[$i]['ID_FUNCIONARIO'].')" title="Reestablecer Contraseña"><i class="fa fa-lock"></i></button>';
						}else{
							$btnReset = '<button class="btn btn-secondary btn-sm" disabled ><i class="fa fa-lock"></i></button>';
						}
					}

					$arrData[$i]['ACCIONES'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.' '.$btnReset.'</div>';
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
		}

		public function getFuncionario($idfuncionario){
			if($_SESSION['permisosMod']['PER_R']){
				$idfuncionario = intval($idfuncionario);
				if($idfuncionario > 0){
					$arrData = $this->model->selectFuncionario($idfuncionario);
					$arrData["ROLES"]=$this->getSelectRoles();
					$arrData["FUN_ACCESO"]=$arrData["FUN_USUARIO"]."<br>".$arrData["FUN_USUARIO"]."".SYS_PATRON_PASS;
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
	}//fin de la clase
 ?>