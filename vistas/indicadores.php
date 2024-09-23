<?php
//Activamos el almacenamiento en el buffer
ob_start();
//Iniciamos la sesión
session_start();
//Válidamos si hay un usuario con sesión
if (!isset($_SESSION["nombre_usuario"])) {
  //Si está vacío lo mandamos a autenticarse
  header("Location: login.html");
}
//Si está lleno el campo imprimimos el contanido de la página
else {
  //Añadimos el encabezado
  require 'header.php';
?>
  <!-- PRUEBAS DE GRAFICAS --------------------------------------------------------------------------------------------------------------------- -->
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <!-- PRUEBAS DE GRAFICAS --------------------------------------------------------------------------------------------------------------------- -->
  <!--Contenido-->
  <?php if (strpos($_SESSION['checkboxes'], 'i') !== false) { ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" id="contenido-evento">
      <!-- Main content -->
      <section class="content" style="width: 100% !important;">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <!-- Inicio del encabezado de la sección -->
              <div class="box-header with-border">
                <h1 class="box-title">Indicadores: &nbsp; &nbsp;
                </h1>
                <div class="box-tools pull-right"></div>
              </div>
              <!-- Fin del encabezado de la sección -->
              <!-- Inicio Formulario -->
              <div class="panel-body table-responsive" id="formularioregistros1">
                <form name="formulario" id="formulario" method="POST">
                  <div class="row">
                    <!-- Solicitamos fecha inicio -->
                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                      <label>Fecha Inicio (*): </label>
                      <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" required=""
                        value="">
                    </div>
                    <!-- Solicitamos fecha fin -->
                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                      <label>Fecha Fin (*): </label>
                      <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" required=""
                        value="">
                    </div>
                  </div>
                  <div class="row">
                  </div>
                  <div class="row" style="margin-bottom: 40px">
                    <div class="col-lg-12">
                      <button id="actualizar" type="button" class="btn btn-primary actualizar_datos" style="margin-top: 5px">Obtener información</button>
                    </div>
                    <div class="col-lg-12">
                      <button id="graficar" type="button" class="btn btn-primary actualizar_datos" style="margin-top: 5px">Actualizar gráfica</button>
                    </div>
                  </div>
                </form>
                </br>
                </br>
                </br>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6 table-responsive" style="display: flex">
                  <table id="cobrosPorMetodo" class="table table-striped table-bordered table-condensed table-hover"
                    style="float:left;">
                    <tbody style="text-align: center;">
                      <tr>
                        <td style="text-align: right; color: #ffffff; background-color: rgb(54, 127, 169)">Total de Ingresos (Servicios Contratados):</td>
                        <td style="text-align: right;" id="ingresos"></td>
                      </tr>
                      <tr>
                        <td style="text-align: right; color: #ffffff; background-color:rgb(54, 127, 169)">Total de Egresos:</td>
                        <td style="text-align: right;" id="egresos"></td>
                      </tr>
                      <tr>
                        <td style="text-align: right; color: #ffffff; background-color:rgb(54, 127, 169);">Utilidad o Pérdida:</td>
                        <td style="text-align: right;" id="utilidad"></td>
                      </tr>
                    </tbody>
                  </table>
                  </br>
                  </br>
                  </br>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6 table-responsive" style="display: flex">
                  <table id="cobrosPorMetodo1" class="table table-striped table-bordered table-condensed table-hover"
                    style="float:left;">
                    <tr>
                      <th style="text-align: center; color: #ffffff; background-color:rgb(54, 127, 169);">Categorías</th>
                      <th style="text-align: center; color: #ffffff; background-color:rgb(54, 127, 169);">Total de Ingresos </br> (Servicios Contratados)</th>
                      <th style="text-align: center; color: #ffffff; background-color:rgb(54, 127, 169);">Total de Egresos</th>
                      <th style="text-align: center; color: #ffffff; background-color:rgb(54, 127, 169);">Utilidad o Pérdida</th>
                    </tr>
                    <tr>
                      <td style="text-align: right; color: #ffffff; background-color:rgb(54, 127, 169);">HACIENDA</td>
                      <td style="text-align: right;" id="Hacienda_ingresos"></td>
                      <td style="text-align: right;" id="Hacienda_egresos"></td>
                      <td style="text-align: right;" id="Hacienda_utilidad"></td>
                    </tr>
                    <tr>
                      <td style="text-align: right; color: #ffffff; background-color:rgb(54, 127, 169);">BANQUETE</td>
                      <td style="text-align: right;" id="Banquete_ingresos"></td>
                      <td style="text-align: right;" id="Banquete_egresos"></td>
                      <td style="text-align: right;" id="Banquete_utilidad"></td>
                    </tr>
                    <tr>
                      <td style="text-align: right; color: #ffffff; background-color:rgb(54, 127, 169);">HOTEL</td>
                      <td style="text-align: right;" id="Hotel_ingresos"></td>
                      <td style="text-align: right;" id="Hotel_egresos"></td>
                      <td style="text-align: right;" id="Hotel_utilidad"></td>
                    </tr>
                    <tr>
                      <td style="text-align: right; color: #ffffff; background-color:rgb(54, 127, 169);">MOBILIARIO</td>
                      <td style="text-align: right;" id="Mobi_ingresos"></td>
                      <td style="text-align: right;" id="Mobi_egresos"></td>
                      <td style="text-align: right;" id="Mobi_utilidad"></td>
                    </tr>
                    <tr>
                      <td style="text-align: right; color: #ffffff; background-color:rgb(54, 127, 169);">PROVEEDOR ADICIONAL</td>
                      <td style="text-align: right;" id="Proveedor_ingresos"></td>
                      <td style="text-align: right;" id="Proveedor_egresos"></td>
                      <td style="text-align: right;" id="Proveedor_utilidad"></td>
                    </tr>
                    <tr>
                      <td style="text-align: right; color: #ffffff; background-color:rgb(54, 127, 169);">CASA MONTESPEJO</td>
                      <td style="text-align: right;" id="Casa_ingresos"></td>
                      <td style="text-align: right;" id="Casa_egresos"></td>
                      <td style="text-align: right;" id="Casa_utilidad"></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <!-- <div class="box"> -->
                <div id="linechart_material"></div>
                <!-- </div> -->
              </div>
            </div>
          </div>
          <!-- Fin Formulario -->
        </div>
        <!-- Fin Box -->
    </div>
    <!-- Fin col-md-12 -->
    </div>
    <!-- Fin row -->
    </section>
    <!-- Fin section  -->
    </div>
    <!-- Fin contenido-evento -->
    <!-- Fin Modal -->
    <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
    <?php
    //Añadimos el pie de página
    require 'footer.php';
    ?>
    <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
    <!-- Añadimos el javascript -->
    <script type="text/javascript" src="../public/plugins/numero-letras.js"></script>
    <script type="text/javascript" src="scripts/indicadores.js"></script>
    <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
  <?php
  }
  // Cerramos buffer
  ob_end_flush();
  ?>
  <style type="text/css">
    input[name="precio_detalle_evento"]:before {
      content: '$';
    }
  </style>
<?php } ?>