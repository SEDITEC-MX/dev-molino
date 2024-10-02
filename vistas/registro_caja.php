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

  <!-- Validador de privilegios -->
  <?php if (strpos($_SESSION['checkboxes'], 'e') !== false) { ?>

    <!--Contenido-->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

      <!-- Main content -->
      <section class="content" style="width: 100% !important;">
        <div class="row">
          <div class="col-lg-12">
            <div class="box">
              <!-- Inicio del encabezado de la sección -->
              <div class="box-header with-border">
                <h1 class="box-title">Registrar gasto de Caja Chica </h1>
              </div>
              <!-- Fin del encabezado de la sección -->

              <!-- Inicio Formulario -->
              <div class="panel-body table-responsive" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">

                  <!-- Solicitamos id del evento -->
                  <input type="hidden" name="id_pago" id="id_pago">
                  <input type="hidden" name="estado_evento" id="estado_evento">
                  <input type="hidden" id="id_usuario"
                    data-id-usuario="<?php echo $_SESSION['id_usuario']; ?>" value="<?php echo $_SESSION['id_usuario']; ?>" />

                  <!-- Solicitamos fecha del pago -->
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Fecha del pago (*):</label>
                    <input type="date" class="form-control" name="fecha_pago" id="fecha_pago" required="true">
                  </div>

                  <!-- Monto del pago -->
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Monto del pago (*):</label>
                    <input type="number" class="form-control" name="monto_pago" id="monto_pago" maxLength="6"
                      step="any" placeholder="Monto del pago" required="true">
                  </div>

                  <!-- Solicitamos evento -->
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Seleccionar evento (Opcional):</label>
                    <select class="form-control selectpicker" data-show-subtext="true" data-live-search="true" name="id_evento"
                      id="id_evento">
                      <option value="">Cargando...</option>
                    </select>
                  </div>

                  <!-- Solicitamos categoría -->
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Categoría(*): </label>
                    <select id="id_categoria_servicio" name="id_categoria_servicio" class="form-control selectpicker" required></select>
                  </div>

                  <!-- Solicitamos proveedor -->
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Seleccionar proveedor (*):</label>
                    <select class="form-control selectpicker" data-show-subtext="true" data-live-search="true"
                      name="id_proveedor" id="id_proveedor" required="">
                      <option value="">Cargando...</option>
                    </select>
                  </div>

                  <!-- Solicitamos comprobante -->
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Comprobante (*):</label>
                    <div class="input-group">
                      <input type="file" class="form-control" aria-label="..." accept=".pdf, .jpeg, .jpg, .png" id="archivo" name="archivo">
                    </div><!-- /input-group -->
                  </div>

                  <!-- Solicitamos notas -->
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Notas: </label>
                    <textarea class="form-control" maxLength="250" name="notas_pago" id="notas_pago" placeholder="Notas del pago"></textarea>
                  </div>

                  <!-- Botones para guardar y cancelar -->
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button class="btn btn-primary btnGuardar" data-category="comprobante"
                      type="submit" id="btnGuardar">Registrar</button>
                    <a href="index2.php">
                      <button id="btnCancelar" class="btn btn-danger" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>
                    </a>
                    <div style="margin: 10px 0;" class="hidden loading-gif">
                      <img src="../public/img/loading.gif" class="img-responsive"
                        style="display: block; margin-right: auto; margin-left: auto; width: 80px">
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
                    <th style="text-align: center; vertical-align:middle;">Monto del pago</th>
                    <th style="text-align: center; vertical-align:middle;">Evento</th>
                    <th style="text-align: center; vertical-align:middle;">Proveedor</th>
                    <th style="text-align: center; vertical-align:middle;" class="hide-on-small">Notas</th>
                    <th style="text-align: center; vertical-align:middle;">Status</th>
                  </thead>

                  <tbody style="text-align: center; vertical-align:middle;">
                  </tbody>

                  <tfoot>
                    <th style="text-align: center; vertical-align:middle;">Opciones</th>
                    <th style="text-align: center; vertical-align:middle;">Fecha del <br> pago</th>
                    <th style="text-align: center; vertical-align:middle;">Monto del pago</th>
                    <th style="text-align: center; vertical-align:middle;">Evento</th>
                    <th style="text-align: center; vertical-align:middle;">Proveedor</th>
                    <th style="text-align: center; vertical-align:middle;" class="hide-on-small">Notas</th>
                    <th style="text-align: center; vertical-align:middle;">Status</th>
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

    <?php
    //Añadimos el pie de página
    require 'footer.php';
    ?>

    <!-- Añadimos el javascript -->
    <script type="text/javascript" src="scripts/registro_caja.js"></script>
    <script type="text/javascript" src="scripts/archivos.js"></script>

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

    @media only screen and (max-width: 768px) {
      .hide-on-small {
        display: none;
      }
    }
  </style>
<?php } ?>