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
  <?php if (strpos($_SESSION['checkboxes'], 'd') !== false) { ?>
    <!--Contenido-->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Main content -->
      <section class="content" style="width: 100% !important;">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <!-- Inicio del encabezado de la sección -->
              <div class="box-header with-border">
                <h1 class="box-title">Registrar Cobro &nbsp; &nbsp;
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
                  <input type="hidden" name="id_cobro" id="estado_evento">
                  <input type="hidden" name="estado_evento" id="estado_evento">
                  <!-- Solicitamos evento -->
                  <div class="form-group row-fluid col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <!-- <label>Seleccionar evento (*):</label> -->
                    <label>Seleccionar evento (*):</label>
                    <select class="form-control selectpicker" data-show-subtext="true" data-live-search="true" name="id_evento" id="id_evento" required="">
                      <!-- <option value="">Seleccione el evento</option> -->
                      <option value="">Cargando...</option>
                    </select>
                  </div>
                  <!-- Solicitamos fecha del cobro -->
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Fecha del cobro (*):</label>
                    <input type="date" class="form-control" name="fecha_cobro" id="fecha_cobro" required="true">
                  </div>
                  <!-- Monto del cobro -->
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Monto del cobro (*):</label>
                    <input type="number" class="form-control" name="monto_cobro" id="monto_cobro" maxLength="6" step="any" placeholder="Monto del cobro" required="true">
                  </div>
                  <!-- Solicitamos método de pago -->
                  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label>Método de cobro (*):</label>
                    <select name="metodo_cobro" id="metodo_cobro" class="form-control selectpicker" required="true">
                      <option value="">Seleccione uno</option>
                      <option value="EV">Cuenta EVENTOS</option>
                      <option value="FE">Cuenta FERCON</option>
                      <option value="FV">Cuenta FVG</option>
                      <option value="AV">Cuenta AVU</option>
                      <option value="EF">Efectivo</option>
                      <option value="Otro">Otro</option>
                    </select>
                  </div>
                  <!-- Solicitamos email del cliente -->
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Email del cliente(s): </label>
                    <input type="email" class="form-control" name="email_cobro" id="email_cobro" maxLength="250" placeholder="Email del cliente(s)">
                  </div>
                  <!-- Solicitamos notas del cliente -->
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Notas: </label>
                    <textarea class="form-control" maxLength="250" name="notas_cobro" id="notas_cobro" placeholder="Notas del cobro"></textarea>
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
                    <th style="text-align: center; vertical-align:middle;">Fecha del <br> cobro</th>
                    <th style="text-align: center; vertical-align:middle;">Monto del cobro</th>
                    <th style="text-align: center; vertical-align:middle;">Método del cobro</th>
                    <th style="text-align: center; vertical-align:middle;">Email</th>
                    <th style="text-align: center; vertical-align:middle;">Notas</th>
                  </thead>

                  <tbody style="text-align: center; vertical-align:middle;">
                  </tbody>

                  <tfoot>
                    <th style="text-align: center; vertical-align:middle;">Opciones</th>
                    <th style="text-align: center; vertical-align:middle;">Fecha del <br> cobro</th>
                    <th style="text-align: center; vertical-align:middle;">Monto del cobro</th>
                    <th style="text-align: center; vertical-align:middle;">Método del cobro</th>
                    <th style="text-align: center; vertical-align:middle;">Email</th>
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
    <script type="text/javascript" src="scripts/registro_cobro.js"></script>
    <!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->
  <?php
  }
  // Cerramos buffer
  ob_end_flush();
  ?>
  <style type="text/css">
    #listadoregistros button {
      margin: 0 3px;
    }
  </style>
<?php } ?>