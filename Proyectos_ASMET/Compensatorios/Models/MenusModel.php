<?php 

	class MenusModel extends Oracle{
		public $intIdmenu;
		public $strTitulo;
		public $strCodigo;
		public $intEstado;
		public $strIcono;

		public function __construct(){
			parent::__construct();
		}

		public function selectMenus(){
			$sql = "SELECT * FROM BIG_MENU  
				ORDER BY MEN_TITULO ASC";
			$request = $this->select_all($sql);
			return $request;
		}

		public function selectMenu(int $ID_MENU){
			$this->intIdmenu = $ID_MENU;
			$sql = "SELECT * FROM BIG_MENU WHERE ID_MENU = $this->intIdmenu";
			$request = $this->select($sql);
			return $request;
		}

		public function insertMenu(string $TITULO, string $CODIGO, int $ESTADO, string $ICONO){ // Parametros que vamos insertar
			$return = "";
			$this->strTitulo = $TITULO;
			$this->strCodigo = $CODIGO;
			$this->intEstado = $ESTADO;
			$this->strIcono = $ICONO;

			$sql = "SELECT * FROM BIG_MENU WHERE MEN_CODIGO = '{$this->strCodigo}' OR MEN_TITULO = '{$this->strTitulo}'";
			$request = $this->select_all($sql); // Verifica que no se encuentren dupicados de codigo o titulo

			if(empty($request)){
				$query_insert  = "INSERT INTO BIG_MENU(MEN_TITULO,MEN_CODIGO,MEN_ESTADO,MEN_ICONO) 
				VALUES(:MEN_TITULO,:MEN_CODIGO,:MEN_ESTADO,:MEN_ICONO)";
			
			$arrData = array(
				'MEN_TITULO'=>$this->strTitulo, 'MEN_CODIGO'=>$this->strCodigo,
				'MEN_ESTADO'=>$this->intEstado, 'MEN_ICONO'=>$this->strIcono
			);
			$request_insert = $this->insert($query_insert,$arrData);
	        $return = $request_insert;
		}else{
			$return = "exist";
		}

		return $return;
			
		}	

		public function updateMenu(int $ID_MENU, string $TITULO, string $CODIGO, int $ESTADO,string $ICONO){
			$this->intIdmenu = $ID_MENU;
			$this->strTitulo = $TITULO;
			$this->strCodigo = $CODIGO;
			$this->intEstado = $ESTADO;
			$this->strIcono = $ICONO;

			$sql = "SELECT * FROM BIG_MENU WHERE (MEN_TITULO = '{$this->strTitulo}' OR MEN_CODIGO = '{$this->strCodigo}')
				AND ID_MENU != $this->intIdmenu";
			$request = $this->select_all($sql);

			if(empty($request)){
				$sql = "UPDATE BIG_MENU SET MEN_TITULO = :MENU_TITULO, MEN_CODIGO = :MEN_CODIGO, MEN_ESTADO = :MEND_ESTADO, MEN_ICONO = :MEN_ICONO 
					WHERE ID_MENU = $this->intIdmenu
					";
				$arrData = array(
					'MEN_TITULO'=>$this->strTitulo,
					'MEN_CODIGO'=>$this->strCodigo,
					'MEN_ESTADO'=>$this->intEstado,
					'MEN_ICONO'=>$this->strIcono
				);
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
		    return $request;			
		}

		public function deleteMenu(int $idMenu){
			$this->idMenu = $idMenu;

			$sql = "SELECT * FROM BIG_MODULOS WHERE ID_MENU = $this->idMenu";
			$request = $this->select_all($sql);

			if(empty($request)){
				$sqldel = "DELETE BIG_PERMISOS WHERE ID_MODULO = $this->idMenu ";
				$requestdel = $this->delete($sqldel);

				$sql = "DELETE BIG_MENU WHERE ID_MENU = $this->idMenu ";
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