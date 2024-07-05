<?php 

	class ModulosModel extends Oracle{
		public $intIdModulo;
		public $intIdMenu;
		public $strTitulo;
		public $strDesc;
		public $strCodigo;
		public $strIcono;
		public $strAcceso;
		public $intListar;
		public $intEstado;

		public function __construct(){
			parent::__construct();
		}

		public function selectModulos(){
			$sql = "SELECT 
				BM.MEN_TITULO,
				BMO.* 
			FROM BIG_MODULOS BMO 
			INNER JOIN BIG_MENU BM ON BMO.ID_MENU = BM.ID_MENU
			ORDER BY BM.MEN_TITULO,MOD_TITULO ASC";
			$request = $this->select_all($sql);
			return $request;
		}

		public function selectModulo(int $ID_MENU){
			$this->intIdmenu = $ID_MENU;
			$sql = "SELECT BM.MEN_TITULO, MO.*
				FROM BIG_MODULOS MO
				INNER JOIN BIG_MENU bm ON MO.ID_MENU  = BM.ID_MENU
				WHERE ID_MODULO = '".$this->intIdmenu."'
				ORDER BY BM.MEN_TITULO ASC, MO.MOD_TITULO ASC";
			$request = $this->select($sql);
			return $request;
		}

		public function insertModulo(int $intIdMenu, string $strTitulo, string $strDesc, string $strCodigo,
			string $strIcono, string $strAcceso, int $intListar, int $intEstado){
			$return = "";
			$this->intIdMenu = $intIdMenu;
			$this->strTitulo = $strTitulo;
			$this->strDesc = $strDesc;
			$this->strCodigo = $strCodigo;
			$this->strIcono = $strIcono;
			$this->strAcceso = $strAcceso;
			$this->intListar = $intListar;
			$this->intEstado = $intEstado;

			$sql = "SELECT * FROM BIG_MODULOS WHERE MOD_CODIGO = '{$this->strCodigo}' OR MOD_TITULO = '{$this->strTitulo}'";
			$request = $this->select_all($sql);

		
			if(empty($request)){
				$query_insert  = "INSERT INTO BIG_MODULOS(ID_MENU,MOD_TITULO,MOD_DESCRIPCION,MOD_CODIGO,MOD_ICONO,MOD_ACCESO,MOD_LISTAR,MOD_ESTADO)
				VALUES(:ID_MENU, :MOD_TITULO, :MOD_DESCRIPCION, :MOD_CODIGO, :MOD_ICONO, :MOD_ACCESO, :MOD_LISTAR, :MOD_ESTADO)";

				//dep($query_insert);
				
	        	$arrData = array(
					'ID_MENU'=>$this->intIdMenu,'MOD_TITULO'=>$this->strTitulo,'MOD_DESCRIPCION'=>$this->strDesc,
					'MOD_CODIGO'=>$this->strCodigo,'MOD_ICONO'=>$this->strIcono,'MOD_ACCESO'=>$this->strAcceso,
					'MOD_LISTAR'=>$this->intListar,'MOD_ESTADO'=>$this->intEstado
				);
				//dep($arrData);

	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
			return $return;
		}	

		public function updateModulo(int $intIdModulo,int $intIdMenu, string $strTitulo, string $strDesc, string $strCodigo,
			string $strIcono, string $strAcceso, int $intListar, int $intEstado){
			$this->intIdModulo = $intIdModulo;
			$this->intIdMenu = $intIdMenu;
			$this->strTitulo = $strTitulo;
			$this->strDesc = $strDesc;
			$this->strCodigo = $strCodigo;
			$this->strIcono = $strIcono;
			$this->strAcceso = $strAcceso;
			$this->intListar = $intListar;
			$this->intEstado = $intEstado;

			$sql = "SELECT * FROM BIG_MODULOS WHERE (MOD_TITULO = '{$this->strTitulo}' OR MOD_CODIGO = '{$this->strCodigo}')
				AND ID_MODULO != $this->intIdModulo";
			$request = $this->select_all($sql);

			if(empty($request)){
				$sql = "
				UPDATE BIG_MODULOS SET ID_MENU = :ID_MENU,
				MOD_TITULO = :MOD_TITULO,
				MOD_DESCRIPCION = :MOD_ESTADO,
				MOD_CODIGO = :MOD_CODIGO,
				MOD_ICONO = :MOD_ICONO,
				MOD_ACCESO = :MOD_ACCESO,
				MOD_LISTAR = :MOD_LISTAR,
				MOD_ESTADO = :MOD_ESTADO
				WHERE ID_MODULO = $this->intIdModulo
				";
				$arrData = array(
					'ID_MENU'=>$this->intIdMenu,'MOD_TITULO'=>$this->strTitulo,'MOD_DESCRIPCION'=>$this->strDesc,'MOD_CODIGO'=>$this->strCodigo,
					'MOD_ICONO'=>$this->strIcono,'MOD_ACCESO'=>$this->strAcceso,'MOD_LISTAR'=>$this->intListar,'MOD_ESTADO'=>$this->intEstado
				);
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
		    return $request;
		}

		public function deleteModulo(int $idModulo){
			$this->intIdModulo = $idModulo;

			$sqldel = "DELETE BIG_PERMISOS WHERE ID_MODULO = $this->intIdModulo ";
			$requestdel = $this->delete($sqldel);

			$sql = "SELECT * FROM BIG_PERMISOS WHERE ID_MODULO = $this->intIdModulo";
			$request = $this->select_all($sql);

			if(empty($request)){
				$sql = "DELETE BIG_MODULOS WHERE ID_MODULO = $this->intIdModulo ";
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

		public function listarMenus(){
			$sql = "SELECT * 
				FROM BIG_MENU 
				WHERE MEN_ESTADO='1' 
				ORDER BY MEN_TITULO ASC";
			$request = $this->select_all($sql);
			return $request;
		}
	}
 ?>