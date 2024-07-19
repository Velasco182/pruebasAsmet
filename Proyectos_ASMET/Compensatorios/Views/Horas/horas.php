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
          <iframe title="Riesgo Cardio Vascular" width="800" height="600" src="https://app.powerbi.com/view?r=eyJrIjoiM2JhMmJhNjMtOWRiNC00NzhlLWI2ODYtNDM0Y2IyZjlkNGIzIiwidCI6Ijg2OWQxNmQwLTkyNTItNDJkZS1hZjYzLTBlNTI3MTVkYTZjNCJ9" frameborder="0" allowFullScreen="true"></iframe>    
        </main>
<?php footerAdmin($data); ?>
    