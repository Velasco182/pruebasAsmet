<?php 

	class RolesModel extends Oracle{
		public $intIdrol;
		public $strRol;
		public $strDescripcion;
		public $intStatus;
		public $strCodigo;

		public function __construct(){
			parent::__construct();
		}

		public function selectRoles(){
			$sql = "SELECT * FROM BIG_ROLES  
				where  ROL_ESTADO='1'
				ORDER BY ROL_NOMBRE ASC";
			$request = $this->select_all($sql);
			return $request;
		}

		public function selectRol(int $ID_ROL){
			$this->intIdrol = $ID_ROL;
			$sql = "SELECT * FROM BIG_ROLES WHERE ID_ROL = $this->intIdrol";
			$request = $this->select($sql);
			return $request;
		}

		public function insertRol(string $ROL, string $ROL_DESCRIPCION, int $ROL_ESTADO, string $CODIGO){
			$return = "";
			$this->strRol = $ROL;
			$this->strDescripcion = $ROL_DESCRIPCION;
			$this->intStatus = $ROL_ESTADO;
			$this->strCodigo = $CODIGO;

			$sql = "SELECT * FROM BIG_ROLES WHERE ROL_NOMBRE = '{$this->strRol}' ";
			$request = $this->select_all($sql);

			if(empty($request)){
				$query_insert  = "
				INSERT INTO BIG_ROLES
			(
				ROL_NOMBRE,
				ROL_DESCRIPCION,
				ROL_ESTADO,
				ROL_CODIGO
			)
			VALUES
			(
				:ROL_NOMBRE,
				:ROL_DESCRIPCION,
				:ROL_ESTADO,
				:ROL_CODIGO
			)";
	        	$arrData = array(
				'ROL_NOMBRE'=>$this->strRol,
				'ROL_DESCRIPCION'=>$this->strDescripcion,
				'ROL_ESTADO'=>$this->intStatus,
				'ROL_CODIGO'=>$this->strCodigo
			);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
			return $return;
		}	

		public function updateRol(int $ID_ROL, string $ROL, string $ROL_DESCRIPCION, int $ROL_ESTADO,string $CODIGO){
			$this->intIdrol = $ID_ROL;
			$this->strRol = $ROL;
			$this->strDescripcion = $ROL_DESCRIPCION;
			$this->intStatus = $ROL_ESTADO;
			$this->strCodigo = $CODIGO;

			$sql = "SELECT * FROM BIG_ROLES WHERE ROL_NOMBRE = '$this->strRol' AND ID_ROL != $this->intIdrol";
			$request = $this->select_all($sql);

			if(empty($request)){
				$sql = "
				UPDATE BIG_ROLES SET ROL_NOMBRE = :ROL_NOMBRE,
				ROL_DESCRIPCION = :ROL_DESCRIPCION,
				ROL_ESTADO = :ROL_ESTADO,
				ROL_CODIGO = :ROL_CODIGO WHERE ID_ROL = $this->intIdrol ";

				$arrData = array(
				'ROL_NOMBRE'=>$this->strRol,
				'ROL_DESCRIPCION'=>$this->strDescripcion,
				'ROL_ESTADO'=>$this->intStatus,
				'ROL_CODIGO'=>$this->strCodigo);

				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
		    return $request;			
		}

		public function deleteRol(int $ID_ROL){
			$this->intIdrol = $ID_ROL;
			$sql = "SELECT * FROM BIG_FUNCIONARIOS WHERE ID_ROL = $this->intIdrol";
			$request = $this->select_all($sql);

			if(empty($request)){
				$sqldel = "DELETE BIG_PERMISOS WHERE ID_ROL = $this->intIdrol";
				$requestdel = $this->delete($sqldel);

				$sql = "DELETE BIG_ROLES WHERE ID_ROL = $this->intIdrol";
				$request = $this->delete($sql);

				if($request){
					$request = 'ok';	
				}else{
					$request = 'error';
				}
			}else{
				$request = 'exist';
			}
			return $request;
		}
	}
 ?>