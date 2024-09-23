<?php

//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre_usuario"]))
{
  header("Location: login.html");
}
else
{

require 'header.php';
?>

<?php if (strpos($_SESSION['checkboxes'], 'c') !== false) { ?>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title">Categorías
                          <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button>
                          </h1>
                             <!-- <button class="btn btn-success" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button> -->
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro listado -->
                    <div class="panel-body" id="listadoregistros">
						<table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
							<thead>
								<th>Opciones</th>
								<th>Nombre</th>
								<th>Estado</th>
							</thead>
							
							<tbody>
							</tbody>
							
							<tfoot>
								<th>Opciones</th>
								<th>Nombre</th>
								<th>Estado</th>
							</tfoot>
						</table> 
                    </div>
					<!-- centro formulario -->
					<div class="panel-body table-responsive" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
							<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<label>Nombre: </label>
							<input type="hidden" name="id_categoria_servicio" id="id_categoria_servicio">
							<input type="hidden" name="condicio_categoria_servicio" id="condicio_categoria_servicio">
							<input type="text" class="form-control" name="nombre_categoria_servicio" id="nombre_categoria_servicio" maxLength="50" placeholder="Nombre de la categoría del servicio" required>
							</div>

          
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                            <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                          </div>
                        </form>
                    </div>
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
  <?php
  require 'footer.php';
  ?>
  
  <script type="text/javascript" src="scripts/categoria.js"></script>

  <?php
  
}
ob_end_flush();
  
  ?>
<?php } ?>