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
<?php if (strpos($_SESSION['checkboxes'], 'c') !== false) { ?>
<!--Contenido-->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <!-- Inicio del encabezado de la sección -->
          <div class="box-header with-border">
            <h1 class="box-title">Servicios &nbsp; &nbsp; 
            <!-- Botón para mostrar formulario -->
            <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button>
						</h1>
            <div class="box-tools pull-right"></div>
          </div>
          <!-- Fin del encabezado de la sección -->

<!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->

          <!-- Inicio Listado -->
          <div class="panel-body" id="listadoregistros">
						<table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
							<thead>
								<th>Opciones</th>
								<th>Categoría</th>
								<th>Nombre</th>
								<th>Descripción</th>
								<th>Imagen</th>
								<th>Estado</th>
							</thead>
							
							<tbody>
							</tbody>
							
							<tfoot>
								<th>Opciones</th>
								<th>Categoría</th>
								<th>Nombre</th>
								<th>Descripción</th>
								<th>Imagen</th>
								<th>Estado</th>
							</tfoot>
						</table> 
          </div>
					<!-- Fin Listado -->

<!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->

          <!-- Inicio Formulario -->
          <div class="panel-body table-responsive" id="formularioregistros">
            <form name="formulario" id="formulario" method="POST">
							<!-- Solicitamos el id del servicio -->
              <input type="hidden" name="id_servicio" id="id_servicio">
              <input type="hidden" name="condicion_servicio" id="condicion_servicio">
              
              <!-- Solicitamos el nombre del servicio -->
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
							  <label>Nombre (*): </label>
                <input type="text" class="form-control" name="nombre_servicio" id="nombre_servicio" maxLength="60" placeholder="Nombre del servicio" required>
							</div>
							
              <!-- Solicitamos la categoría del servicio -->
							<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Categoría(*): </label>
                <select id="id_categoria_servicio" name="id_categoria_servicio" class="form-control selectpicker" data-live-search="true" required></select>
							</div>
							
              <!-- Solicitamos la descripción del servicio -->
							<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Descripción: </label>
                <input type="text" class="form-control" name="descripcion_servicio" id="descripcion_servicio" maxlength="256" placeholder="Descripción">
              </div>

              <!-- Solicitamos la imagen del servicio -->
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Imagen (Opcional):</label>
                <input type="file" class="form-control" name="imagen_servicio" id="imagen_servicio" accept="image/x-png,image/gif,image/jpeg">
                <input type="hidden" name="imagenactual" id="imagenactual">
							  <img src="" width="50px" height="50px" id="imagenmuestra">
              </div>
							
              <!-- Botones para guardar y cancelar -->
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
              </div>
            </form>
          </div>
          <!-- Fin Formulario -->
        </div><!-- /.box -->
      </div><!-- /.col -->
    </div><!-- /.row -->
  <!-- Fin Main content -->
  </section>
</div><!-- /.content-wrapper -->
<!--Fin-Contenido-->

<!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->  

<?php
//Añadimos el pie de página
require 'footer.php';
?>

<!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->  

<!-- Añadimos el javascript -->
<script type="text/javascript" src="scripts/servicio.js"></script>

<!-- --------------------------------------------------------------------------------------------------------------------------------------------- -->  
<?php
}
// Cerramos buffer
ob_end_flush();
?>

<?php } ?>