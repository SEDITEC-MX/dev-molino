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
  <?php if (strpos($_SESSION['checkboxes'], 'h') !== false) { ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content" style="width: 100% !important;">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <!-- Inicio del encabezado de la sección -->
            <div class="box-header with-border">
              <h1 class="box-title">Pagos a empleados &nbsp; &nbsp; </h1>
              <div class="box-tools pull-right"></div>
            </div>
            <!-- Fin del encabezado de la sección -->

            <!-- codigo de prueba -->

            <!-- codigo de prueba -->

            <div class="row">
              <div class="col-md-12">
                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12" id="abrirModal">
                  <a data-toggle="modal" href="#myModal">


                    <button id="btnAgregarArt" onclick="mostrarForm(true)" type="button" class="btn btn-primary">

                      <span class="fa fa-plus"></span> Agregar Empleados
                </div>

              </div>

              </button>
              </a>
            </div>


            <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
            <!-- Inicio Listado -->
            <div class="panel-body" id="listadoregistros">
              <table id="tbllistado_empleados_pagos" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th>Opciones</th>
                  <th>Fecha</th>
                  <th>Evento</th>
                  <th>Nombre</th>
                  <th>Concepto</th>
                  <th>Monto</th>
                  <th>Descuento</th>
                  <th>Total</th>
                  <!-- <th>Comentarios</th> -->
                </thead>

                <tbody>
                </tbody>

                <tfoot>
                  <th>Opciones</th>
                  <th>Fecha</th>
                  <th>Evento</th>
                  <th>Nombre</th>
                  <th>Concepto</th>
                  <th>Monto</th>
                  <th>Descuento</th>
                  <th>Total</th>
                  <!-- <th>Comentarios</th> -->
                </tfoot>
              </table>
            </div>
            <!-- Fin Listado -->
          </div>
          <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
          <div class="row" id="detallesPago">
            <div class="col-md-12">
              <div class="box">

                <!-- Solicitamos fecha del pagoo -->
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-4">
                  <br>
                  <label>Fecha del pago al evento (*): </label>
                  <input type="date" class="form-control" name="fecha_pago_empleado" id="fecha_pago_empleado" required="true">
                </div>
                <!-- Solicitamos evento -->
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-4">
                  <br>

                  <label>Relacionar a un evento (Opcional):</label>
                  <select class="form-control selectpicker" data-show-subtext="true" data-live-search="true" name="id_evento" id="id_evento" required="">
                    <!--<select name="tipo_evento" id="tipo_evento" class="form-control" required="">-->
                    <!-- <option value="">Seleccione el evento</option> -->
                    <option value="0">Ninguno</option>
                  </select>
                </div>

                <!-- Solicitamos empleados -->

                <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                  <thead style="background-color:#A9D0F5">
                    <th style='text-align: center; vertical-align:middle;'>Nombre</th>
                    <!--  <th style='text-align: center; vertical-align:middle;'>Evento</th> -->
                    <th style='text-align: center; vertical-align:middle;'>Concepto</th>
                    <th style='text-align: center; vertical-align:middle;'>Monto</th>
                    <th style='text-align: center; vertical-align:middle;'>Descuento</th>
                    <th style='text-align: center; vertical-align:middle;'>Total</th>
                    <th style='text-align: center; vertical-align:middle;'>Comentarios</th>
                    <th style='text-align: center; vertical-align:middle;'>Opciones</th>
                  </thead>


                  <tfoot>

                  </tfoot>
                  <tbody>
                  </tbody>

                </table>

                &nbsp; &nbsp; &nbsp;<button class="btn btn-primary" type="button" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                <button id="btnCancelar" class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                <!-- <button id="btnExport" class="btn btn-info"  type="button"><i class="fa fa-download"></i> Exportar a Excel</button>  -->
                <div>&nbsp;</div>

              </div>
              <br>
              <!-- Fin del encabezado de la sección -->
            </div>


          </div>

          <!-- Fin Formulario -->
        </div><!-- /.box -->

    </section><!-- /.content -->

  </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
  <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
  <!-- Inicio Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80% !important;">
      <div class="modal-content">

        <!-- Inicio Encabezado Modal -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Seleccione los empleados</h4>
        </div>
        <!-- Fin Encabezado Modal -->
        <!-- Inicio Contenido Modal -->
        <div class="modal-body table-responsive">
          <table id="tblempleados" style="width: 100% !important;" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
              <th>Opciones</th>
              <th>Nombre</th>
              <th>Categoría</th>
              <th>Comentarios</th>
            </thead>

            <tbody>
            </tbody>

            <tfoot>
              <th>Opciones</th>
              <th>Nombre</th>
              <th>Categoría</th>
              <th>Comentarios</th>
            </tfoot>
          </table>
        </div>
        <!-- Inicio Contenido Modal -->
        <!-- Inicio Pie Modal -->
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
        <!-- Fin Pie Modal -->
      </div>
    </div>
  </div>
  <!-- Fin Modal -->
  <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
  <?php
  //Añadimos el pie de página
  require 'footer.php';
  ?>
  <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
  <!-- Añadimos el javascript -->
  <script type="text/javascript" src="scripts/pagos_empleados.js"></script>
  <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
<?php
}
// Cerramos buffer
ob_end_flush();
?>
<style type="text/css">
  body {
    padding-right: 0 !important;
  }

  input[name="precio_detalle_evento"]:before {
    content: '$';
  }
</style>
<?php } ?>