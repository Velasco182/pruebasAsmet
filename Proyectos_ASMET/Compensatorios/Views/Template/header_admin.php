<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="description" content="Asmet Salud">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Asmet Salud">
    <meta name="theme-color" content="#009688">
    <link rel="shortcut icon" href="<?= media();?>/images/favicon.ico">
    <title><?= $data['page_tag'] ?></title>
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/main.css">
    <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/bootstrap-select.min.css"> 
    <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/style.css">

    <!-- Tempus Dominus - DateTimePicker - CSS -->
    <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/tempusdominus-bootstrap-4.min.css">
    
  </head>
  <body class="app sidebar-mini sidenav-toggled pace-done">
    <div id="divLoading" >
      <div>
        <img src="<?= media(); ?>/images/loading.svg" alt="Loading">
      </div>
    </div>
    <!-- Navbar-->
    <header class="app-header"><a class="app-header__logo" href="<?= base_url(); ?>/dashboard"><?= TITULO_APP ?></a> 
      <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"><i class="fas fa-bars"></i></a>
        <!-- Navbar Right Menu-->
        <ul class="app-nav">
          <!-- User Menu-->
          <li class="dropdown">
            <a class="app-nav__item" href="<?= base_url(); ?>/logout">
              <i class="fa fa-sign-out fa-lgg"></i> Salir
            </a>
          </li>
        </ul>
      </a>
    </header>
    <?php require_once("nav_admin.php"); ?> 