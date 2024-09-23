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
  <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
  <!--Contenido-->
  <?php if (strpos($_SESSION['checkboxes'], 'e') !== false) { ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content" style="width: 100% !important;">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <!-- Inicio del encabezado de la sección -->
            <div class="box-header with-border">
              <h1 class="box-title">Pagos &nbsp; &nbsp;
                <!-- Botón para mostrar formulario
            <button class="btn btn-success" id="boton_verde_agregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button> -->
              </h1>
              <div class="box-tools pull-right"></div>
            </div>
            <!-- Fin del encabezado de la sección -->
            <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
            <!-- Inicio Listado -->
            <div class="panel-body" id="listadoregistros">
              <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover" style="width: 100% !important;">
                <thead>
                  <th style="text-align: center; vertical-align:middle;">Opciones</th>
                  <th style="text-align: center; vertical-align:middle;">Fecha del <br> evento</th>
                  <th style="text-align: center; vertical-align:middle;">Tipo de <br>evento</th>
                  <th style="text-align: center; vertical-align:middle;">Nombre de <br>cliente</th>
                  <th style="text-align: center; vertical-align:middle;">Tipo de <br>cotización</th>
                  <th style="text-align: center; vertical-align:middle;">Total</th>
                  <th style="text-align: center; vertical-align:middle;">Pagado</th>
                  <th style="text-align: center; vertical-align:middle;">Ganancia</th>
                  <th style="text-align: center; vertical-align:middle;">Estado</th>
                </thead>

                <tbody style="text-align: center; vertical-align:middle;">
                </tbody>

                <tfoot>
                  <th style="text-align: center; vertical-align:middle;">Opciones</th>
                  <th style="text-align: center; vertical-align:middle;">Fecha del <br> evento</th>
                  <th style="text-align: center; vertical-align:middle;">Tipo de <br>evento</th>
                  <th style="text-align: center; vertical-align:middle;">Nombre de <br>cliente</th>
                  <th style="text-align: center; vertical-align:middle;">Tipo de <br>cotización</th>
                  <th style="text-align: center; vertical-align:middle;">Total</th>
                  <th style="text-align: center; vertical-align:middle;">Pagado</th>
                  <th style="text-align: center; vertical-align:middle;">Ganancia</th>
                  <th style="text-align: center; vertical-align:middle;">Estado</th>
                </tfoot>
              </table>
            </div>
            <!-- Fin Listado -->
            <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
            <!-- Inicio Formulario -->
            <div class="panel-body table-responsive" id="formularioregistros">
              <form name="formulario" id="formulario" method="POST">

                <!-- Listamos cobros del evento -->
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                  <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                    <thead style="background-color:#A9D0F5">
                      <th style='text-align: center; vertical-align:middle;'>Fecha del pago</th>
                      <th style='text-align: center; vertical-align:middle;'>Nombre del proveedor</th>
                      <th style='text-align: center; vertical-align:middle;'>Monto del pago</th>
                      <th style='text-align: center; vertical-align:middle;'>Método de pago</th>
                      <th style='text-align: center; vertical-align:middle;'>Email</th>
                      <th style='text-align: center; vertical-align:middle;'>Categoría</th>
                      <th style='text-align: center; vertical-align:middle;'>Notas</th>
                    </thead>

                    <tfoot>
                      <th>TOTAL</th>
                      <th></th>
                      <th id="total" style="text-align: center;"></th>
                      <th></th>
                      <th></th>
                      <th></th>
                    </tfoot>

                    <tbody style="text-align: center;">
                    </tbody>
                  </table>
                </div>
                <!-- Botones para guardar y cancelar -->
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <button id="btnCancelar" class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>
                </div>
              </form>
            </div>
            <!-- Fin Formulario -->
          </div><!-- /.box -->
        </div><!-- /.col -->
      </div><!-- /.row -->
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
  <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
  <!-- Inicio Modal -->
  <!-- Inicio Contenido Modal -->
  <!-- Inicio Pie Modal -->
  <!-- Fin Pie Modal -->
  <!-- Fin Modal -->
  <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
  <?php
  //Añadimos el pie de página
  require 'footer.php';
  ?>
  <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
  <!-- Añadimos el javascript -->
  <script type="text/javascript" src="scripts/pago.js"></script>
  <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
<?php
}
// Cerramos buffer
ob_end_flush();
?>
<?php } ?>