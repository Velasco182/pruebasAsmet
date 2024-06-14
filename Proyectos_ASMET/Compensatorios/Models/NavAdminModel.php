<?php
class NavAdminModel extends Oracle{

    public function __construct(){
        parent::__construct();
    }

    public function listarMenus(){
        $rol = $_SESSION['userData']['ROL_CODIGO'];    
        $sql = "
            SELECT 
                P.ID_ROL,
                br.ROL_CODIGO,
                br.ROL_NOMBRE,
                BM.MEN_CODIGO,
                BM.MEN_TITULO,
                M.MOD_CODIGO,
                M.MOD_TITULO,
                M.MOD_ICONO,
                M.MOD_ACCESO,
                BM.MEN_ICONO
            FROM BIG_PERMISOS P
            INNER JOIN BIG_MODULOS M ON P.ID_MODULO = M.ID_MODULO
            INNER JOIN BIG_MENU BM ON BM.ID_MENU = M.ID_MENU 
            INNER JOIN BIG_ROLES br ON BR.ID_ROL = P.ID_ROL 
            WHERE BR.ROL_CODIGO = '".$rol."' AND M.MOD_CODIGO != '".COD_MOD_DAS."' AND P.PER_R = 1
                AND BM.MEN_ESTADO=1 AND M.MOD_ESTADO=1
            ORDER BY BM.MEN_TITULO,M.MOD_TITULO ASC
        ";
        $request = $this->select_all($sql);
        return $request;
    }
}
?>