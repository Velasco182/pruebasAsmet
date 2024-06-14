<!-- Sidebar menu-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="<?= media();?>/images/avatar.png" alt="User Image">
        <div>
        <p class="app-sidebar__user-name"><?= $_SESSION['userData']['FUN_USUARIO']; ?></p>
        <p class="app-sidebar__user-designation"><?= $_SESSION['userData']['ROL_NOMBRE']; ?></p>
        </div>
    </div>
    <ul class="app-menu">
        <?php cargarMenu();?>
    </ul>
</aside>
<?php
function cargarMenu(){
    require_once "Models/NavAdminModel.php";
    $objNavAdmin = new NavAdminModel();
    $arrData = $objNavAdmin->listarMenus();
    
    $menu="";
    $item="";
    $codMenu="";
    $conteo=0;

    //echo "<div style='color:white;'>";
    foreach($arrData as $clave => $valor){
        $dato=0;
        if($codMenu!=$valor["MEN_CODIGO"]){
            if($codMenu!=""){
                echo "</ul>";
                echo "</li>";
            }

            echo '
                <li class="treeview">
                    <a class="app-menu__item" href="#" data-toggle="treeview">
                        <i class="app-menu__icon fa '.$valor["MEN_ICONO"].'" aria-hidden="true"></i>
                        <span class="app-menu__label">'.$valor["MEN_TITULO"].'</span>
                        <i class="treeview-indicator fa fa-angle-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a class="treeview-item" href="'.base_url().'/'.$valor["MOD_ACCESO"].'">
                                <i class="fa '.$valor["MOD_ICONO"].'"></i>&nbsp;&nbsp;'.$valor["MOD_TITULO"].'
                            </a>
                        </li>
            ';
            $codMenu=$valor["MEN_CODIGO"];
            $dato=1;
        }
        if($dato==0){ // Si el dato es igual a 0 imprima 
            echo '
                <li>
                    <a class="treeview-item" href="'.base_url().'/'.$valor["MOD_ACCESO"].'">
                        <i class="fa '.$valor["MOD_ICONO"].'"></i>&nbsp;&nbsp;'.$valor["MOD_TITULO"].'
                    </a>
                </li>
            ';
        }
    }
    //echo "</div>";
}

function itemListaMenu(){
    $plantillaPadre="";
}
?>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        var ancho = window.innerWidth;
        if(ancho<=748){
            document.querySelector(".app-sidebar__toggle").click();   
        }
    });
</script>