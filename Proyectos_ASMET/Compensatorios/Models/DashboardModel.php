<?php
class DashboardModel extends Oracle{

    public function __construct(){
        parent::__construct();
    }

    public function listarModulos(){
        $rol = $_SESSION['userData']['ROL_CODIGO'];    
        $sql = "
            SELECT 
                P.ID_ROL,
                P.ID_MODULO,
                M.MOD_TITULO,
                M.MOD_CODIGO,
                M.MOD_ACCESO,
                M.MOD_ICONO,
                P.PER_R,
                P.PER_W,
                P.PER_U,
                P.PER_D
            FROM BIG_PERMISOS P
            INNER JOIN BIG_MODULOS M ON P.ID_MODULO = M.ID_MODULO
            INNER JOIN BIG_ROLES br ON BR.ID_ROL = P.ID_ROL 
            WHERE BR.ROL_CODIGO = '".$rol."' AND M.MOD_CODIGO !='".COD_MOD_DAS."'
                AND MOD_LISTAR='1' AND P.PER_R=1 AND M.MOD_ESTADO=1 
            ORDER BY M.MOD_TITULO ASC
        ";

        // dep($sql);

        $request = $this->select_all($sql);
        return $request;
    }
}
?>