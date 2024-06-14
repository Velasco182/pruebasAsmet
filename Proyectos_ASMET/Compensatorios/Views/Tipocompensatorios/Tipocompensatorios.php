<?php 
    headerAdmin($data); 
    getModal('modalTipocompensatorios',$data);
?>
  <main class="app-content">    
      <div class="app-title">
        <div>
          <h1><i class="fa <?=$data['page_icono']?>"></i> <?= $data['page_title'] ?>
            <?php if($_SESSION['permisosMod']['PER_W']){ ?>
              <button class="btn btn-primary" type="button" onclick="openModal();" ><i class="fas fa-plus-circle"></i> Nuevo</button>
            <?php } ?>
          </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>/<?= $data['page_acceso'];?>"><?= $data['page_title'] ?></a></li>
        </ul>
      </div>
        <div class="row">
            <div class="col-md-12">
              <div class="tile">
                <div class="tile-body">
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="tablePrestamos">
                      <thead>
                        <tr>
                          <th>Titulo Libro</th>
                          <th>Usuario</th>
                          <th>Fecha de Prestamo</th>
                          <th>Fecha de Devolución</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </main>
<?php footerAdmin($data); ?>
    