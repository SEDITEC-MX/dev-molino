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
              <h1 class="box-title">Registrar Pago &nbsp; &nbsp;
                <!-- Botón para mostrar formulario
            <button class="btn btn-success" id="boton_verde_agregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button> -->
              </h1>
              <div class="box-tools pull-right"></div>
            </div>
            <!-- Fin del encabezado de la sección -->
            <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
            <!-- Inicio Listado -->
            <!-- Fin Listado -->
            <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
            <!-- Inicio Formulario -->
            <div class="panel-body table-responsive" id="formularioregistros">
              <form name="formulario" id="formulario" method="POST">
                <!-- Solicitamos id del evento -->
                <input type="hidden" name="id_pago" id="id_pago">
                <input type="hidden" name="estado_evento" id="estado_evento">
                <input type="hidden" id="id_usuario"
                    data-id-usuario="<?php echo $_SESSION['id_usuario']; ?>" value="<?php echo $_SESSION['id_usuario']; ?>" />
                <!-- Solicitamos evento -->
                <div class="form-group row-fluid col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <!-- <label>Seleccionar evento (*):</label> -->
                  <label>Seleccionar evento (*):</label>
                  <select class="form-control selectpicker" data-show-subtext="true" data-live-search="true" name="id_evento" id="id_evento" required="">
                    <!-- <option value="">Seleccione el evento</option> -->
                    <option value="">Cargando...</option>
                  </select>
                </div>
                <!-- Solicitamos evento -->
                <div class="form-group row-fluid col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <!-- <label>Seleccionar evento (*):</label> -->
                  <label>Seleccionar proveedor (*):</label>
                  <select class="form-control selectpicker" data-show-subtext="true" data-live-search="true" name="id_proveedor" id="id_proveedor" required="">
                    <!-- <option value="">Seleccione el evento</option> -->
                    <option value="">Cargando...</option>
                  </select>
                </div>
                <!-- Solicitamos fecha del pago -->
                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <label>Fecha del pago (*):</label>
                  <input type="date" class="form-control" name="fecha_pago" id="fecha_pago" required="true">
                </div>
                <!-- Monto del pago -->
                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <label>Monto del pago (*):</label>
                  <input type="number" class="form-control" name="monto_pago" id="monto_pago" maxLength="6" step="any" placeholder="Monto del pago" required="true">
                </div>
                <!-- Solicitamos método de pago -->
                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <label>Método de pago (*):</label>
                  <select name="metodo_pago" id="metodo_pago" class="form-control selectpicker" required="true">
                    <option value="">Seleccione uno</option>
                    <option value="EV">Cuenta EVENTOS</option>
                    <option value="FE">Cuenta FERCON</option>
                    <option value="FV">Cuenta FVG</option>
                    <option value="AV">Cuenta AVU</option>
                    <option value="EF">Efectivo</option>
                    <option value="Otro">Otro</option>
                  </select>
                </div>
                <!-- Solicitamos la categoría del servicio -->
                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <label>Categoría(*): </label>
                  <select id="id_categoria_servicio" name="id_categoria_servicio" class="form-control selectpicker" data-live-search="true" required></select>
                </div>
                <!-- Solicitamos email del cliente -->
                <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                  <label>Email del proveedor(es): </label>
                  <input type="email" class="form-control" name="email_pago" id="email_pago" maxLength="250" placeholder="Email del cliente(s)">
                </div>
                <!-- Solicitamos notas del cliente -->
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <label>Notas: </label>
                  <textarea class="form-control" maxLength="250" name="notas_pago" id="notas_pago" placeholder="Notas del pago"></textarea>
                </div>
                <!-- Botones para guardar y cancelar -->
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <button class="btn btn-primary" type="submit" id="btnGuardar">Registrar</button>
                  <a href="index2.php"><button id="btnCancelar" class="btn btn-danger" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button></a>
                  <div style="margin: 10px 0;" class="hidden loading-gif">
                    <img src="../public/img/loading.gif" class="img-responsive" style="display: block; margin-right: auto; margin-left: auto; width: 80px">
                  </div>
                </div>
              </form>
            </div>
            <!-- Fin Formulario -->
            <!-- Inicio Listado -->
            <div class="panel-body" id="listadoregistros">
              <table id="detalles" class="table table-striped table-bordered table-condensed table-hover" style="width: 100% !important;">
                <thead>
                  <th style="text-align: center; vertical-align:middle;">Opciones</th>
                  <th style="text-align: center; vertical-align:middle;">Fecha del <br> pago</th>
                  <th style="text-align: center; vertical-align:middle;">Proveedor</th>
                  <th style="text-align: center; vertical-align:middle;">Método del pago</th>
                  <th style="text-align: center; vertical-align:middle;">Monto del pago</th>
                  <th style="text-align: center; vertical-align:middle;">Email</th>
                  <th style="text-align: center; vertical-align:middle;">Categoría servicio</th>
                  <th style="text-align: center; vertical-align:middle;">Notas</th>
                </thead>

                <tbody style="text-align: center; vertical-align:middle;">
                </tbody>

                <tfoot>
                  <th style="text-align: center; vertical-align:middle;">Opciones</th>
                  <th style="text-align: center; vertical-align:middle;">Fecha del <br> pago</th>
                  <th style="text-align: center; vertical-align:middle;">Proveedor</th>
                  <th style="text-align: center; vertical-align:middle;">Método del pago</th>
                  <th style="text-align: center; vertical-align:middle;">Monto del pago</th>
                  <th style="text-align: center; vertical-align:middle;">Email</th>
                  <th style="text-align: center; vertical-align:middle;">Categoría servicio</th>
                  <th style="text-align: center; vertical-align:middle;">Notas</th>
                </tfoot>
              </table>
            </div>
            <!-- Fin Listado -->
          </div><!-- /.box -->
        </div><!-- /.col -->
      </div><!-- /.row -->
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
  <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
  <div class="modal fade" id="modal-pago" tabindex="-1" role="dialog" aria-labelledby="modal-pago" aria-hidden="true">
    <div class="modal-dialog" style="width: 80% !important;">
      <div class="modal-content">

        <!-- Inicio Encabezado Modal -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Editar Categoría de pago</h4>
        </div>
        <!-- Fin Encabezado Modal -->
        <!-- Inicio Contenido Modal -->
        <div class="modal-body table-responsive">
          <input type="hidden" name="modal_editar_pago_id_pago"
            id="modal_editar_pago_id_pago" readonly>
          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <label>Categoría(*): </label>
            <select id="modal_editar_pago_id_categoria_servicio"
              name="modal_editar_pago_id_categoria_servicio"
              class="form-control selectpicker" data-live-search="true">
              <option class="disabled" value="">Choose option...</option>
            </select>
          </div>

          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <label>Fecha del pago:</label>
            <input type="text" class="form-control" name="modal_editar_pago_fecha_pago"
              id="modal_editar_pago_fecha_pago" readonly>
          </div>
          <!-- Proveedor -->
          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <label>Proveedor:</label>
            <input type="text" class="form-control" name="modal_editar_pago_proveedor_pago"
              id="modal_editar_pago_proveedor_pago" readonly>
          </div>
          <!-- Monto del pago -->
          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <label>Monto del pago:</label>
            <input type="text" class="form-control" name="modal_editar_pago_monto_pago"
              id="modal_editar_pago_monto_pago" readonly>
          </div>

          <div class="form-group col-12">
            <button class="btn btn-primary pull-right" type="submit"
              id="btnGuardarCategoriaServicio">
              Actualizar Categoría Servicio
            </button>
          </div>

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
  <script type="text/javascript" src="scripts/registro_pago.js"></script>
  <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
<?php
}
// Cerramos buffer
ob_end_flush();
?>
<style type="text/css">
  #listadoregistros button {
    display: block;
    margin: 5px 3px;
  }

  #modal-pago .modal-body {
    min-height: 300px;

  }
</style>
<?php } ?>