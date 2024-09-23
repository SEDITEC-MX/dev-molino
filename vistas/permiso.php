<?php
require 'header.php';
?>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title">Listado de Privilegios
						  <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button>
						  </h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro listado -->
                    <div class="panel-body" id="listadoregistros">
						<table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
							<thead>
								
								<th>Nombre</th>
								
							</thead>
							
							<tbody>
							</tbody>
							
							<tfoot>
								
								<th>Nombre</th>
								
							</tfoot>
						</table> 
                    </div>
					<!-- centro formulario -->
					
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
  
  <script type="text/javascript" src="scripts/permiso.js"></script>
  
