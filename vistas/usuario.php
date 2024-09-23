<?php
session_start();
// Incluye el archivo de cabecera
require 'header.php';
?>
<?php if (strpos($_SESSION['checkboxes'], 'j') !== false) { ?>
<!-- Contenido -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <!-- Título de la sección con botón para agregar nuevo usuario -->
            <h1 class="box-title">Usuarios
              <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button>
            </h1>
            <div class="box-tools pull-right">
            </div>
          </div>
          <!-- /.box-header -->
          <!-- Centro de listado de usuarios -->
          <div class="panel-body" id="listadoregistros">
            <!-- Tabla para mostrar la lista de usuarios -->
            <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
              <thead>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Celular</th>
                <th>Email</th>
                <th>Usuario</th>
                <th>Estado</th>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Celular</th>
                <th>Email</th>
                <th>Usuario</th>
                <th>Estado</th>
              </tfoot>
            </table>
          </div>
          <!-- Centro de formulario para agregar/editar usuarios -->
          <div class="panel-body table-responsive" id="formularioregistros">
            <!-- Formulario para agregar/editar usuarios -->
            <form name="formulario" id="formulario" method="POST">
              <!-- Campo para ingresar el nombre del usuario -->
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Nombre (*): </label>
                <input type="hidden" name="id_usuario" id="id_usuario">
                <input type="text" class="form-control" name="nombre_usuario" id="nombre_usuario" maxLength="145" placeholder="Nombre del usuario" required>
              </div>
              <!-- Campo para ingresar el celular del usuario -->
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Celular:</label>
                <input type="text" class="form-control" name="telefono_usuario" id="telefono_usuario" maxlength="20" placeholder="Celular" required>
              </div>
              <!-- Campo para ingresar el email del usuario -->
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Email:</label>
                <input type="email" class="form-control" name="email_usuario" id="email_usuario" maxlength="50" placeholder="Email del usuario" required>
              </div>
              <!-- Campo para ingresar el usuario del usuario -->
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Usuario (*):</label>
                <input type="text" class="form-control" name="usr_usuario" id="usr_usuario" maxlength="20" placeholder="Usuario" required>
              </div>
              <!-- Campo para seleccionar permisos del usuario -->
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Permisos:</label>
                <!-- Vamos a probar la propuesta de IA -->
                <!-- <ul style="list-style: none;" id="nombre_permiso">
                </ul> -->
                <?php
                // Lista de opciones de menú
                $menus = [
                  ['id' => 'a', 'nombre' => '  Eventos'],
                  ['id' => 'b', 'nombre' => '  Vista Clientes'],
                  ['id' => 'c', 'nombre' => '  Servicios'],
                  ['id' => 'd', 'nombre' => '  Cobros'],
                  ['id' => 'e', 'nombre' => '  Pagos'],
                  ['id' => 'f', 'nombre' => '  Proveedores'],
                  ['id' => 'g', 'nombre' => '  Empleados'],
                  ['id' => 'h', 'nombre' => '  Vista Negocio'],
                  ['id' => 'i', 'nombre' => '  Indicadores'],
                  ['id' => 'j', 'nombre' => '  Usuarios']
                  // Agrega más opciones de menú aquí...
                ];
                // Obtén los valores seleccionados por el usuario (si existen)
                $valoresSeleccionados = []; // Reemplaza con la forma en que obtienes los valores seleccionados
                echo '<table id="permisos-menu">';
                echo '<tr>';
                foreach ($menus as $i => $menu) {
                  if ($i % 2 == 0) {
                    echo '</tr><tr>'; // Crea una nueva fila cada 2 elementos
                  }
                  $checked = in_array($menu['id'], $valoresSeleccionados) ? 'checked' : '';
                  echo '<td><label><input type="checkbox" name="permisos[]" value="' . $menu['id'] . '" ' . $checked . '>' . $menu['nombre'] . '</label></td>';
                }
                echo '</tr>';
                echo '</table>';
                ?>
              </div>
              <!-- Campo para ingresar la contraseña del usuario -->
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Contraseña (*):</label>
                <input type="password" class="form-control" name="pass_usuario" id="pass_usuario" maxlength="64" placeholder="Contraseña" required>
              </div>
              <!-- Botones para guardar y cancelar -->
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- Botón para guardar el formulario -->
                <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                <!-- Botón para cancelar el formulario y regresar a la lista de usuarios -->
                <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
              </div>
            </form>
          </div>
          <!-- Fin del centro del formulario -->
        </div><!-- /.box -->
      </div><!-- /.col -->
    </div><!-- /.row -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!-- Fin del contenido de la página -->
<?php
// Incluye el archivo de pie de página
require 'footer.php';
?>
<!-- Incluye el archivo de script para la página de usuarios -->
<script type="text/javascript" src="scripts/usuario.js"></script>
<?php } ?>