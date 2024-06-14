<?php 

	class Permisos extends Controllers{
		public function __construct(){
			parent::__construct();
		}

		public function getPermisosRol(int $ID_ROL){
			$ID_ROL = intval($ID_ROL);
			if($ID_ROL > 0){
				$arrModulos = $this->model->selectModulos($ID_ROL);
				$arrPermisos = array('PER_R' => 0, 'PER_W' => 0, 'PER_U' => 0, 'PER_D' => 0,  'PER_F' => 0); // r => 0, w => 0, u => 0, d => 0
				
				//dep($arrModulos);				
				
				$arrPermisoRol = array('ID_ROL' => $ID_ROL );
				for ($i=0; $i < count($arrModulos); $i++) {
					$arrPermisos = array('PER_R' => 0, 'PER_W' => 0, 'PER_U' => 0, 'PER_D' => 0, 'PER_F' => 0);
					$arrPermisosRol = $this->model->selectPermisosRol($ID_ROL,$arrModulos[$i]['ID_MODULO']);
					//dep($arrPermisosRol);
					if(!empty($arrPermisosRol)){
						$arrPermisos = array(
							'PER_R' => $arrPermisosRol[0]['PER_R'],
							'PER_W' => $arrPermisosRol[0]['PER_W'],
							'PER_U' => $arrPermisosRol[0]['PER_U'],
							'PER_D' => $arrPermisosRol[0]['PER_D'],
							'PER_F' => $arrPermisosRol[0]['PER_F']
						);
					}
					$arrModulos[$i]['permisos'] = $arrPermisos;
				}
				$arrPermisoRol['MODULOS'] = $arrModulos;
				//dep($arrPermisoRol);
				$html = getModal("modalPermisos",$arrPermisoRol);
			}
		}

		public function setPermisos(){
			if($_POST){
				$intIdrol = intval($_POST['idrol']);
				$modulos = $_POST['modulos'];
				
				$this->model->deletePermisos($intIdrol);
				
				foreach ($modulos as $modulo) {
					$idModulo = $modulo['idmodulo'];
					$r = empty($modulo['r']) ? 0 : 1;
					$w = empty($modulo['w']) ? 0 : 1;
					$u = empty($modulo['u']) ? 0 : 1;
					$d = empty($modulo['d']) ? 0 : 1;
					$f = empty($modulo['f']) ? 0 : 1;
					$requestPermiso = $this->model->insertPermisos($intIdrol, $idModulo, $r, $w, $u, $d, $f);
				}
				
				if($requestPermiso > 0){
					$arrResponse = array('status' => true, 'msg' => 'Permisos asignados correctamente.');
				}else{
					$arrResponse = array("status" => false, "msg" => 'No es posible asignar los permisos.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
		}
	}
 ?>