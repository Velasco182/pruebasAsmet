<?php 
    headerAdmin($data); 
    getModal('modalHoras',$data);
?>
  <main class="app-content">    
      <div class="app-title">
        <div>
            <h1><i class="fa <?=$data['page_icono']?>"></i> <?= $data['page_title'] ?>
                <?php if($_SESSION['permisosMod']['PER_W']){ ?>
                  <button class="btn btn-primary" type="button" onclick="openModal();" ><i class="fas fa-plus-circle"></i>&nbsp;&nbsp;&nbsp;&nbsp;Nuevo</button>
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
                    <table class="table table-hover table-bordered" id="tableHoras">
                      <thead>
                        <tr>
                          <th>Nombres</th>
                          <th>Apellidos</th>
                          <th>Correo</th>
                          <th>Motivo</th>
                          <th>Fecha de la solicitud</th>
                          <th>Horas a solicitar</th>
                          <th>Estado</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                       
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </main>
<?php footerAdmin($data); ?>
    