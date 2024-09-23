<?php

//Activamos el almacenamiento en el buffer
ob_start();

//Iniciamos la sesión
session_start();

//Válidamos si hay un usuario con sesión
if (!isset($_SESSION["nombre_usuario"]))
{
  //Si está vacío lo mandamos a autenticarse
  header("Location: login.html");
}
//Si está lleno el campo imprimimos el contanido de la página
else
{

//Añadimos el encabezado
require 'header.php';
?>

<?php if (strpos($_SESSION['checkboxes'], 'a') !== false) { ?>
<!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->

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
            <h1 class="box-title">Listado de Eventos &nbsp; &nbsp; 
            <!-- Botón para mostrar formulario -->
            <button class="btn btn-success" id="boton_verde_agregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button>
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
								<th style="text-align: center; vertical-align:middle;">Tipo de  <br>evento</th>
								<th style="text-align: center; vertical-align:middle;">Nombre de  <br>cliente</th>
								<th style="text-align: center; vertical-align:middle;">Número de  <br>invitados</th>
								<th style="text-align: center; vertical-align:middle;">Tipo de  <br>cotización</th>
								<th style="text-align: center; vertical-align:middle;">Total</th>
								<th style="text-align: center; vertical-align:middle;">Estado</th>
							</thead>
							
							<tbody style="text-align: center; vertical-align:middle;">
							</tbody>
							
							<tfoot>
							  <th style="text-align: center; vertical-align:middle;">Opciones</th>
								<th style="text-align: center; vertical-align:middle;">Fecha del <br> evento</th>
								<th style="text-align: center; vertical-align:middle;">Tipo de  <br>evento</th>
								<th style="text-align: center; vertical-align:middle;">Nombre de  <br>cliente</th>
								<th style="text-align: center; vertical-align:middle;">Número de  <br>invitados</th>
								<th style="text-align: center; vertical-align:middle;">Tipo de  <br>cotización</th>
								<th style="text-align: center; vertical-align:middle;">Total</th>
								<th style="text-align: center; vertical-align:middle;">Estado</th>
							</tfoot>
						</table> 
          </div>
					<!-- Fin Listado -->

<!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->

          <!-- Inicio Formulario -->
					<div class="panel-body table-responsive" id="formularioregistros">
            <form name="formulario" id="formulario" method="POST">
              <!-- Solicitamos id del evento -->
              <input type="hidden" name="id_evento" id="id_evento">
              <input type="hidden" name="estado_evento" id="estado_evento">

              <!-- Solicitamos fecha del evento -->
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Fecha del evento (*): </label>
                <input type="date" class="form-control" name="fecha_evento" id="fecha_evento" required="">
              </div>

              <!-- Solicitamos tipo de evento -->
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-4">
                <label>Tipo de evento (*):</label>
                <select name="tipo_evento" id="tipo_evento" class="form-control selectpicker" required="">
                  <option value="">Seleccione uno</option>
                  <option value="Boda">Boda</option>
                  <option value="XV">XV's</option>
                  <option value="Bautizo">Bautizo</option>
                  <option value="Empresarial">Empresarial</option>
                  <option value="Otro">Otro</option>
                </select>
              </div>

              <!-- Solicitamos nombres de los clientes de evento -->
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label>Nombre(s) del cliente(s) (*): </label>
							  <input type="text" class="form-control" name="nombre_evento" id="nombre_evento" maxLength="250" placeholder="Nombre(s) del cliente(s)" required>
							</div>

              <!-- Solicitamos número de invitados del evento -->
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
							  <label>Número de invitados:</label>
							  <input type="number" class="form-control" name="invitados_evento" id="invitados_evento" maxLength="6" placeholder="Número de invitados">
							</div>

              <!-- Solicitamos tipo de cotización del evento -->
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Tipo de cotización (*):</label>
                <select name="cotizacion_evento" id="cotizacion_evento" class="form-control selectpicker" required="">
                  <option value="">Seleccione uno</option>
				  <option value="BASICA">BASICA</option>
				  <option value="SOLO LUGAR">SOLO LUGAR</option>
				  <option value="LUGAR Y MOBILIARIO">LUGAR Y MOBILIARIO</option>
				  <option value="EMPRESARIAL">EMPRESARIAL</option>
				  <option value="FAMILIAR">FAMILIAR</option>
                  <option value="OTRO">OTRO</option>
                </select>
              </div>

              <!-- Solicitamos notas del evento -->
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
							  <label>Especificaciones adicionales: </label>
                <textarea class="form-control" name="notas_evento" id="notas_evento" maxLength="250" placeholder="Especificaciones adicionales"></textarea>
							</div>
              
              <!-- Solicitamos servicios del evento -->
              <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12" id="abrirModal">
                <a data-toggle="modal" href="#myModal">           
                  <button id="btnAgregarArt" type="button" class="btn btn-primary">
                    <span class="fa fa-plus"></span> Agregar Servicios
                  </button>
                </a>
              </div>

              <!-- Listamos servicios del evento -->
              <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                  <thead style="background-color:#A9D0F5">
                    <th style='text-align: center; vertical-align:middle;'>Opciones</th>
                    <th style='text-align: center; vertical-align:middle;'>Servicio</th>
                    <th style='text-align: center; vertical-align:middle;'>Categoría Servicio</th>
                    <th style='text-align: center; vertical-align:middle;'>Cantidad</th>
                    <th style='text-align: center; vertical-align:middle;'>Precio</th>
                    <th style='text-align: center; vertical-align:middle;'>Subtotal</th>
                  </thead>
                  
                  <tfoot>
                    <th>TOTAL</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><h4 id="total"> </h4><input type="hidden" name="total_evento" id="total_evento"></th> 
                  </tfoot>
                  
                  <tbody>
                  </tbody>
                </table>

                <table id="sheetjs" style="display: none;">
                  <thead style="background-color:#A9D0F5">
                    <th style='text-align: center; vertical-align:middle;'>Servicio</th>
                    <th style='text-align: center; vertical-align:middle;'>Categoría Servicio</th>
                    <th style='text-align: center; vertical-align:middle;'>Cantidad</th>
                    <th style='text-align: center; vertical-align:middle;'>Precio</th>
                    <th style='text-align: center; vertical-align:middle;'>Subtotal</th>
                  </thead>
                  
                  <tfoot>
                    <th>TOTAL</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><h4 id="total"> </h4><input type="hidden" name="total_evento" id="total_evento"></th> 
                  </tfoot>
                  
                  <tbody>
                  </tbody>
                </table>
              </div>

              <!-- Botones para guardar y cancelar -->
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                <button id="btnCancelar" class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                <button id="btnExport" class="btn btn-info"  type="button"><i class="fa fa-download"></i> Exportar a Excel</button>
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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 80% !important;">
    <div class="modal-content">
      
      <!-- Inicio Encabezado Modal -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Seleccione un Servicio</h4>
      </div>
      <!-- Fin Encabezado Modal -->

      <!-- Inicio Contenido Modal -->
      <div class="modal-body table-responsive">
        <table id="tblarticulos" style="width: 100% !important;" class="table table-striped table-bordered table-condensed table-hover">
          <thead>
            <th>Opciones</th>
            <th>Categoría</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Imagen</th>
          </thead>
    
          <tbody>
          </tbody>
    
          <tfoot>
            <th>Opciones</th>
            <th>Categoría</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Imagen</th>
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
<script type="text/javascript" src="scripts/evento.js?v=1.1"></script>

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