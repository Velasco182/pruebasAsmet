<?php 

	class PermisosModel extends Oracle{
		public $intIdpermiso;
		public $intRolid;
		public $strRolCodigo;
		public $intModuloid;
		public $r;
		public $w;
		public $u;
		public $d;
		public $f;

		public function __construct(){
			parent::__construct();
		}

		public function selectModulos(int $ID_ROL){
			$this->intRolid=$ID_ROL;
			$sql = "SELECT 
				BM.MEN_TITULO,BMO.* 
				FROM BIG_MODULOS BMO
				LEFT JOIN BIG_MENU BM ON BM.ID_MENU = BMO.ID_MENU
				WHERE MOD_ESTADO != 0
				ORDER BY BM.MEN_TITULO ASC,BMO.MOD_TITULO ASC 
			";
			$request = $this->select_all($sql);
			return $request;
		}

		public function selectPermisosRol(int $ID_ROL, int $ID_MODULO){
			$this->intRolid = $ID_ROL;
			$this->intModuloid = $ID_MODULO;
			$sql = "SELECT P.*
				FROM BIG_PERMISOS P
				INNER JOIN BIG_MODULOS M ON P.ID_MODULO = M.ID_MODULO
				INNER JOIN BIG_MENU BM ON BM.ID_MENU = M.ID_MENU 
				WHERE P.ID_ROL=$this->intRolid AND P.ID_MODULO=$this->intModuloid AND rownum<=1
				ORDER BY BM.MEN_TITULO ASC,M.MOD_TITULO ASC
			";
			$request = $this->select_all($sql);
			return $request;
		}

		public function deletePermisos(int $ID_ROL){
			$this->intRolid = $ID_ROL;
			$sql = "DELETE FROM BIG_PERMISOS WHERE ID_ROL = $this->intRolid";
			$request = $this->delete($sql);
			return $request;
		}

		public function insertPermisos(int $ID_ROL, int $ID_MODULO, int $r, int $w, int $u, int $d, $f){
			$this->intRolid = $ID_ROL;
			$this->intModuloid = $ID_MODULO;
			$this->r = $r;
			$this->w = $w;
			$this->u = $u;
			$this->d = $d;
			$this->f = $f;
			$query_insert  = "INSERT INTO BIG_PERMISOS
				(
					ID_ROL,ID_MODULO,PER_R,PER_W,PER_U,PER_D,PER_F
				) VALUES(
					:ID_ROL,:ID_MODULO,:PER_R,:PER_W,:PER_U,:PER_D,:PER_F)
				";

			$arrData = array(
				'ID_ROL'=>$this->intRolid,'ID_MODULO'=>$this->intModuloid,'PER_R'=>$this->r,
				'PER_W'=>$this->w,'PER_U'=>$this->u,'PER_D'=>$this->d, 'PER_F'=>$this->f
			);
					
        	$request_insert = $this->insert($query_insert,$arrData);	
	        return $request_insert;
		}

		//se listan los modulos a los que el rol tiene acceso
		public function permisosModulo(string $CODIGO_ROL){
			$this->strRolCodigo = $CODIGO_ROL;
			$sql = "
			SELECT 
				P.ID_ROL,
				P.ID_MODULO,
				MEN.MEN_TITULO,
				MEN.MEN_CODIGO,
				M.MOD_TITULO,
				M.MOD_CODIGO,
				P.PER_R,
				P.PER_W,
				P.PER_U,
				P.PER_D,
				P.PER_F,
				M.MOD_ICONO,
				M.MOD_ACCESO
			FROM BIG_PERMISOS P 
			INNER JOIN BIG_MODULOS M ON P.ID_MODULO = M.ID_MODULO
			INNER JOIN BIG_MENU MEN ON MEN.ID_MENU = M.ID_MENU
			INNER JOIN BIG_ROLES br ON BR.ID_ROL = P.ID_ROL 
			WHERE BR.ROL_CODIGO ='".$this->strRolCodigo."'
			ORDER BY MEN.MEN_TITULO ASC,M.MOD_TITULO ASC";
			
			$request = $this->select_all($sql);
			$arrPermisos = array();
			for ($i=0; $i < count($request); $i++) { 
				$arrPermisos[$request[$i]['MOD_CODIGO']] = $request[$i];
			}
			return $arrPermisos;
		}
	}
 ?>