<!-- Declaración del tipo de documento HTML5 -->
<!DOCTYPE html>
<html>

<!-- Encabezado del documento -->

<head>
  <!-- Configuración de la codificación de caracteres -->
  <meta charset="utf-8">
  <!-- Configuración de la compatibilidad con Internet Explorer -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Título del documento -->
  <title>Casa El Molino</title>
  <!-- Configuración de la respuesta a la anchura de pantalla -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Enlaces a hojas de estilo (CSS) -->

  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="../public/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../public/css/font-awesome.css">
  <link rel="stylesheet" href="../public/css/all.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
  <!-- AdminLTE Skins -->
  <link rel="stylesheet" href="../public/css/_all-skins.min.css">
  <!-- Iconos -->
  <link rel="apple-touch-icon" href="../public/img/apple-touch-icon.png">
  <link rel="shortcut icon" href="../public/img/favicon.ico">

  <!-- Enlaces a hojas de estilo (CSS) para Data Tables -->
  <link rel="stylesheet" type="text/css" href="../public/datatables/jquery.dataTables.min.css">
  <link href="../public/datatables/buttons.dataTables.min.css" rel="stylesheet" />
  <link href="../public/datatables/responsive.dataTables.min.css" rel="stylesheet" />

  <!-- Enlace a hoja de estilo (CSS) para Bootstrap Select -->
  <link rel="stylesheet" type="text/css" href="../public/css/bootstrap-select.min.css">

  <style>
    #permisos-menu {
      column-count: 2;
      column-gap: 20px;
      width: 400px;
    }

    #permisos-menu label {
      display: block;
      margin-bottom: 10px;
    }
  </style>

</head>

<!-- Cuerpo del documento -->

<body class="hold-transition skin-yellow-light sidebar-mini">

  <!-- Configuración regional para moneda -->
  <?php
  setlocale(LC_MONETARY, 'en_US');
  ?>

  <!-- Contenedor principal -->
  <div class="wrapper">

    <!-- Encabezado -->
    <header class="main-header">

      <!-- Logo -->
      <a href="index2.php" class="logo">
        <!-- Logo miniatura -->
        <span class="logo-mini"><b><img src="../public/img/logo-blanco-hacienda-casa-el-molino-small.png" alt="Descripción de la imagen" width="50px" height="40px"></b></span>
        <!-- Logo regular -->
        <span class="logo-lg"><b>Casa El Molino</b></span>
      </a>

      <!-- Barra de navegación -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Botón de toggle para la barra lateral -->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Navegación</span>
        </a>
      </nav>
    </header>

    <!-- Barra lateral -->
    <aside class="main-sidebar">

      <!-- Contenido de la barra lateral -->
      <section class="sidebar">

        <!-- Menú de la barra lateral -->
        <ul class="sidebar-menu">
          <li class="header"></li>

          <!-- Elementos de menú -->
          <?php if (strpos($_SESSION['checkboxes'], 'a') !== false) { ?>
            <li class="treeview">
              <a href="#">
                <i class="fas fa-calendar-alt"></i>
                <span> Eventos</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="evento.php"><i class="fas fa-play-circle"></i>&nbsp; Listado de Eventos </a></li>
              </ul>
            </li>
          <?php } ?>
          <?php if (strpos($_SESSION['checkboxes'], 'b') !== false) { ?>
            <li class="treeview">
              <a href="vista-clientes.php">
                <i class="fa fa-user" aria-hidden="true"></i>
                <span>Vista Clientes</span>
              </a>
            </li>
          <?php } ?>
          <?php if (strpos($_SESSION['checkboxes'], 'c') !== false) { ?>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-book"></i>
                <span>Servicios</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="servicio.php"><i class="fas fa-play-circle"></i>&nbsp;Cátalogo de Servicios </a></li>
                <li><a href="categoria.php"><i class="fas fa-play-circle"></i>&nbsp;Categorías</a></li>
              </ul>
            </li>
          <?php } ?>
          <?php if (strpos($_SESSION['checkboxes'], 'd') !== false) { ?>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-money"></i>
                <span>Cobros</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="cobro.php"><i class="fas fa-play-circle"></i>&nbsp;Listar Eventos </a></li>
                <li><a href="registro_cobro.php"><i class="fas fa-play-circle"></i>&nbsp;Registrar Cobro Evento </a></li>
              </ul>
            </li>
          <?php } ?>
          <?php if (strpos($_SESSION['checkboxes'], 'e') !== false) { ?>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-usd"></i>
                <span>Pagos</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="pago.php"><i class="fas fa-play-circle"></i>&nbsp;Listar Pagos </a></li>
                <li><a href="registro_pago.php"><i class="fas fa-play-circle"></i>&nbsp;Registrar Pago </a></li>
              </ul>
            </li>
          <?php } ?>
          <?php if (strpos($_SESSION['checkboxes'], 'f') !== false) { ?>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-group"></i>
                <span>Proveedores</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="proveedores.php"><i class="fas fa-play-circle"></i>&nbsp;Catálogo Proveedores </a></li>
              </ul>
            </li>
          <?php } ?>
          <?php if (strpos($_SESSION['checkboxes'], 'g') !== false) { ?>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-male"></i>
                <span>Empleados</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="empleados.php"><i class="fas fa-play-circle"></i>&nbsp;Listar Empleados </a></li>
                <li><a href="pagos_empleados.php"><i class="fas fa-play-circle"></i>&nbsp;Pagos a empleados </a></li>
              </ul>
            </li>
          <?php } ?>
          <li class="treeview">
            <a href="#">
              <i class="fas fa-cash-register"></i>
              <span>&nbsp;&nbsp;Caja Chica</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="caja.php"><i class="fas fa-play-circle"></i>&nbsp;Listar Caja Chica </a></li>
              <li><a href="registro_caja.php"><i class="fas fa-play-circle"></i>&nbsp;Registrar Caja Chica </a></li>
            </ul>
          </li>
          <hr>

          <?php if (strpos($_SESSION['checkboxes'], 'h') !== false) { ?>
            <li class="treeview">
              <a href="vista-negocio.php">
                <i class="fa fa-suitcase" aria-hidden="true"></i>
                <span>Vista Negocio</span>
              </a>
            </li>
          <?php } ?>
          <?php if (strpos($_SESSION['checkboxes'], 'i') !== false) { ?>
            <li class="treeview">
              <a href="indicadores.php">
                <i class="fa fa-bar-chart" aria-hidden="true"></i>
                <span>Indicadores</span>
              </a>
            </li>
          <?php } ?>
          <hr>

          <?php if (strpos($_SESSION['checkboxes'], 'j') !== false) { ?>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-user-secret" aria-hidden="true"></i>
                <span>Usuarios</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="usuario.php"><i class="fas fa-play-circle"></i>&nbsp;Listar Usuarios </a></li>
              </ul>
            </li>
          <?php } ?>

          <li>
            <a href="../ajax/usuario.php?op=salir">
              <i class="fa fa-power-off"></i>
              <span>Cerrar</span>
            </a>
          </li>
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>