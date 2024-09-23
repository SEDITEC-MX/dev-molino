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
            <h1 class="box-title">Listado de Eventos - Vista Negocio &nbsp; &nbsp; 
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
								<th style="text-align: center; vertical-align:middle;">Estado</th>
							</tfoot>
						</table> 
          </div>
					<!-- Fin Listado -->

<!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->

        </div><!-- /.box -->
      </div><!-- /.col -->
    </div><!-- /.row -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!--Fin-Contenido-->



<!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->  

<?php
//Añadimos el pie de página
require 'footer.php';
?>

<!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->  

<!-- Añadimos el javascript -->
<script type="text/javascript" src="scripts/vista-negocio.js?v=1.1"></script>

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