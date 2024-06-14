<?php headerAdmin($data); ?>
    <main class="app-content">
      <div class="app-title">
        <div>
        <h1>
          <i class="fa <?=$data['page_icono']?>"></i> <?= $data['page_title'] ?>
        </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-dashboard"></i></li>
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>/dashboard"><?= $data['page_title'] ?></a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body-dashboard"><?= $data['page_dashboard'] ?></div>
          </div>
        </div>
      </div>
      <div class="row listar_modulos">
        
      </div>
    </main>
<?php footerAdmin($data); ?>