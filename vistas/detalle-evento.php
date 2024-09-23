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
<?php if ((strpos($_SESSION['checkboxes'], 'b') !== false) || (strpos($_SESSION['checkboxes'], 'h') !== false)) { ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper hidden" id="not-found">
  <div class="content">

    <h1>Evento no encontrado</h1>
  </div>
</div>
<div class="content-wrapper" id="contenido-evento">
  <!-- Main content -->
  <section class="content" style="width: 100% !important;">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <!-- Inicio del encabezado de la sección -->
          <div class="box-header with-border">
            <h1 class="box-title">Evento: &nbsp; &nbsp; 
            </h1>
            <div class="box-tools pull-right"></div>
          </div>
          <!-- Fin del encabezado de la sección -->
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

              <div class="col-lg-12 " style="margin-bottom: 40px">
                <div class="col-lg-4">
                  <h4>Cotización</h4>
                  <div id="existeCotizacion" class="hidden">
                    <a type="button" class="btn btn-primary ver-archivo" data-category="cotizacion" id="archivoCotizacion">Descargar archivo</a>
                    <button type="button" class="btn btn-danger borrar-archivo" data-category="cotizacion" id="archivoCotizacion">Borrar archivo</button>      
                  </div>
                  <div id="noExisteCotizacion">
                    <div class="input-group">
                      <input type="file" class="form-control" aria-label="..." accept="application/pdf">
                    </div><!-- /input-group -->
                    <button type="button" class="btn btn-primary documentacion-subir" data-category="cotizacion" style="margin-top: 5px">Subir archivo</button>
                  </div>
                </div>
                <div class="col-lg-4">
                  <h4>Documentación</h4>
                  <div id="existeDocumentacion" class="hidden">
                    <a type="button" class="btn btn-primary ver-archivo" data-category="documentacion" id="archivoDocumentacion">Descargar archivo</a>
                    <button type="button" class="btn btn-danger borrar-archivo" data-category="documentacion" id="archivoDocumentacion">Borrar archivo</button>      
                  </div>
                  <div id="noExisteDocumentacion">
                    <div class="input-group">
                      <input type="file" class="form-control" aria-label="...">
                    </div><!-- /input-group -->
                    <button type="button" class="btn btn-primary documentacion-subir" data-category="documentacion" style="margin-top: 5px">Subir archivo</button>
                  </div>
                </div>
                <div class="col-lg-4">
                  <h4>INE</h4>
                  <div id="existeINE" class="hidden">
                    <a type="button" class="btn btn-primary ver-archivo" data-category="ine" id="archivoINE">Descargar archivo</a>
                    <button type="button" class="btn btn-danger borrar-archivo" data-category="ine" id="archivoINE">Borrar archivo</button>
                  </div>
                  <div id="noExisteINE">
                    <div class="input-group" >
                      <input type="file" class="form-control" aria-label="...">                  
                    </div><!-- /input-group -->
                    <button type="button" class="btn btn-primary documentacion-subir" data-category="ine" style="margin-top: 5px">Subir archivo</button>
                  </div>
                </div>
              </div>
              
              <!-- Solicitamos servicios del evento -->
              <div class="form-group col-lg-12 col-md-3 col-sm-6 col-xs-12" id="abrirModal">
                <input type="hidden" name="total_evento" id="total_evento">
                <div class="panel panel-primary">
                  <div class="panel-heading">
                    <div style="display: flex; justify-content:space-between; align-items:baseline; flex-wrap:wrap">
                      <h4>Servicios</h4>
                      <div >
                        <h4 style="display:inline">Total del evento: </h4><h4 id="total" style="margin-bottom: 10px; display:inline; text-align:center; font-size: 2rem"></h4>
                        <br>
                        <a class="btn btn-primary btn-sm pull-right" data-toggle="collapse" href="#detalleServicios" role="button" aria-expanded="false" aria-controls="collapseExample">
                          Detalles
                        </a>
                      </div>       
                    </div>
                  </div>
                  <div class="panel-body">
                    <!-- Listamos servicios del evento -->
                    <div class="collapse" id="detalleServicios">
                      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                        <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                          <thead style="background-color:#A9D0F5">
                            <th style='text-align: center; vertical-align:middle;'>Opciones</th>
                            <th style='text-align: center; vertical-align:middle;'>Servicio</th>
                            <th style='text-align: center; vertical-align:middle;'>Categoría</th>
                            <th style='text-align: center; vertical-align:middle;'>Cantidad</th>
                            <th style='text-align: center; vertical-align:middle;'>Precio</th>
                            <th style='text-align: center; vertical-align:middle;'>Subtotal</th>
                          </thead>
                          <tfoot>
                            <th>TOTAL</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th> 
                          </tfoot>
                          <tbody>
                          </tbody>
                      </table>

                        <table id="sheetjs" style="display: none;">
                          <thead style="background-color:#A9D0F5">
                            <th style='text-align: center; vertical-align:middle;'>Servicio</th>
                            <th style='text-align: center; vertical-align:middle;'>Categoría</th>
                            <th style='text-align: center; vertical-align:middle;'>Cantidad</th>
                            <th style='text-align: center; vertical-align:middle;'>Precio</th>
                            <th style='text-align: center; vertical-align:middle;'>Subtotal</th>
                          </thead>
                          
                          <tfoot>
                            <th>TOTAL</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th> 
                          </tfoot>
                          
                          <tbody>
                          </tbody>
                        </table>
                      </div>

                      
                    </div>

                  </div>
                </div> 
                <!-- Botones para guardar y cancelar -->
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <a data-toggle="modal" href="#myModal">           
                          <button id="btnAgregarArt" type="button" class="btn btn-primary">
                            <span class="fa fa-plus"></span> Agregar Servicios
                          </button>
                        </a>
                        <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                        
                        <button id="btnExport" class="btn btn-info"  type="button"><i class="fa fa-download"></i> Exportar a Excel</button>
                      </div>             
              </div>
              <!-- Fin de servicios -->
            </form>
          </div>
          <!-- Fin Formulario -->
              
              
              <!-- Inicio de cobros -->
              <div class="panel-body">
                <div class="form-group col-lg-12 col-md-3 col-sm-6 col-xs-12" id="cobros">
                  <div class="panel panel-primary">
                    <div class="panel-heading">

                      <div style="display: flex; justify-content:space-between; align-items:baseline; flex-wrap:wrap">
                        <h4>Cobros</h4>
                        <div>
                        <h4 style="display:inline">Total cobrado: </h4><h4 id="totalCobros" style="margin-bottom: 10px; display:inline; text-align:center; font-size: 2rem"></h4>
                        <br>
                          <a class="btn btn-primary btn-sm pull-right" data-toggle="collapse" href="#detalleCobros" role="button" aria-expanded="false" aria-controls="collapseExample">
                            Detalles
                          </a>
                        </div>       
                      </div>
                    </div>
                    <div class="panel-body" id="listadoregistrosCobros">
                      
  
                      <!-- Listamos cobros del evento -->
                      <div class="collapse " id="detalleCobros">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                          <table id="detallesCobros" class="table table-striped table-bordered table-condensed table-hover">
                          <thead style="background-color:#A9D0F5">
                      			<th style="text-align: center; vertical-align:middle;">Opciones</th>
                            <th style='text-align: center; vertical-align:middle;'>Fecha del cobro</th>
                            <th style='text-align: center; vertical-align:middle;'>Monto del cobro</th>
                            <th style='text-align: center; vertical-align:middle;'>Método de cobro</th>
                            <th style='text-align: center; vertical-align:middle;'>Email</th>
                            <th style='text-align: center; vertical-align:middle;'>Notas</th>
                          </thead>
                            <tfoot>
                              <th>TOTAL</th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th><h4 id="totalCobros"> </h4><input type="hidden" name="total_evento_cobros" id="total_evento_cobros"></th> 
                            </tfoot>
                            <tbody>
                            </tbody>
                        </table>
  
                          <table id="sheetjs" style="display: none;">
                            <thead style="background-color:#A9D0F5">
                              <th style='text-align: center; vertical-align:middle;'>Servicio</th>
                              <th style='text-align: center; vertical-align:middle;'>Categoría</th>
                              <th style='text-align: center; vertical-align:middle;'>Cantidad</th>
                              <th style='text-align: center; vertical-align:middle;'>Precio</th>
                              <th style='text-align: center; vertical-align:middle;'>Subtotal</th>
                            </thead>
                            
                         
                            
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a data-toggle="modal" href="#modalCobros">           
                      <button id="btnAgregarArt" type="button" class="btn btn-primary">
                        <span class="fa fa-plus"></span> Registrar Cobro
                      </button>
                    </a>
                  </div>            
                </div>
                
              </div>
              <!-- Fin de cobros -->

              <div class="panel-body">
                <div class="form-group col-lg-12 col-md-3 col-sm-6 col-xs-12"  style="display:flex; justify-content:space-between; align-items:center">
                  <div></div>
                  <div style="display: flex; align-items:baseline">
                    
                    <h2>Saldo pendiente:</h2>
                    <p id="saldoPendiente" style="font-size: 2.5rem; margin-left: 10px"></p>
                  </div>
                </div>
              </div>
              
              <div class="panel-body">
                <div class="form-group col-lg-12 col-md-3 col-sm-6 col-xs-12"  style="display:flex; justify-content:space-between; align-items:center">
                  <div></div>
                  <div style="display: flex; align-items:baseline">
                  <button class="btn btn-primary" id="imprimirPDF" type="button">Imprimir PDF</button>
                  </div>
                </div>
              </div>
        

              <!-- Inicio de pagos -->
              <div class="panel-body">
                <div class="form-group col-lg-12 col-md-3 col-sm-6 col-xs-12" id="pagos">
                  <div class="panel panel-primary">
                    <div class="panel-heading">
                      <div style="display: flex; justify-content:space-between; align-items:baseline; flex-wrap:wrap">
                        <h4>Pagos</h4>
                        <div>
                          <h4 style="display:inline">Total pagado: </h4><h4 id="totalPagos" style="margin-bottom: 10px; display:inline; text-align:center; font-size: 2rem">0</h4>
                          <br>
                          <a class="btn btn-primary btn-sm pull-right" data-toggle="collapse" href="#detallePagos" role="button" aria-expanded="false" aria-controls="collapseExample">
                            Detalles
                          </a>
                        </div>       
                      </div>
                    </div>
                    <div class="panel-body">
                      
  
                      <!-- Listamos pagos del evento -->
                      <div class="collapse" id="detallePagos">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                          <table id="detallesPagos" class="table table-striped table-bordered table-condensed table-hover">
                            <thead style="background-color:#A9D0F5">
                              <th style="text-align: center; vertical-align:middle;">Opciones</th>
                              <th style="text-align: center; vertical-align:middle;">Fecha del <br> pago</th>
                              <th style="text-align: center; vertical-align:middle;">Proveedor</th>
                              <th style="text-align: center; vertical-align:middle;">Método del pago</th>
                              <th style="text-align: center; vertical-align:middle;">Monto del pago</th>
                              <th style="text-align: center; vertical-align:middle;">Email</th>
			                        <th style="text-align: center; vertical-align:middle;">Categoría</th>
                              <th style="text-align: center; vertical-align:middle;">Notas</th>
                            </thead>
                            <tfoot>
                              <th>TOTAL</th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th><input type="hidden" name="total_evento_pagos" id="total_evento_pagos"></th> 
                            </tfoot>
                            <tbody>
                            </tbody>
                        </table>
  
                          <table id="sheetjs" style="display: none;">
                            <thead style="background-color:#A9D0F5">
                              <th style='text-align: center; vertical-align:middle;'>Servicio</th>
                              <th style='text-align: center; vertical-align:middle;'>Categoría</th>
                              <th style='text-align: center; vertical-align:middle;'>Cantidad</th>
                              <th style='text-align: center; vertical-align:middle;'>Precio</th>
                              <th style='text-align: center; vertical-align:middle;'>Subtotal</th>
                            </thead>
                            
                            <tfoot>
                              <th>TOTAL</th>
                              <th></th>
                              <th></th>
                              <th></th>
                            </tfoot>
                            
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>
  
                    </div>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a data-toggle="modal" href="#modalPagos">           
                      <button id="btnAgregarArt" type="button" class="btn btn-primary">
                        <span class="fa fa-plus"></span> Registrar Pago
                      </button>
                    </a>
                  </div>               
                </div>
              </div>
              <!-- Fin de pagos -->
              
              
              <!-- Inicio de utilidad -->
              <div class="panel-body">
                <div class="form-group col-lg-12 col-md-3 col-sm-6 col-xs-12" id="utilidad">
                  <div class="panel panel-primary">
                    <div class="panel-heading">
                      <div style="display: flex; justify-content:space-between; align-items:baseline; flex-wrap:wrap">
                        <h4>Utilidad</h4>
                        <div>
                          <h4 style="display:inline">Utilidad: </h4><h4 id="totalUtilidad" style="margin-bottom: 10px; display:inline; text-align:center; font-size: 2rem">0</h4>
                          <br>
                          <a class="btn btn-primary btn-sm pull-right" data-toggle="collapse" href="#detalleUtilidad" role="button" aria-expanded="false" aria-controls="collapseExample">
                            Detalles
                          </a>
                        </div>       
                      </div>
                    </div>
                    <div class="panel-body">
                      
  
                      <!-- Listamos  del evento -->
                      <div class="collapse" id="detalleUtilidad">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive" style="display: flex">
                          <table id="serviciosPorCategoria" class="table table-striped table-bordered table-condensed table-hover"
                          style="float:left;">
                            <thead style="background-color:#A9D0F5">
                              <th></th>
                              <th style="text-align: center; vertical-align:middle;">Servicios (Por Categoría)</th>
                            </thead>
                            <tbody style="text-align: center;">
                            </tbody>
                            <tfoot>
                              <th style="vertical-align: middle; text-align:center;">TOTAL</th>
                              <th style="text-align: center;">
                                <h4 id="totalSumaServicios"> </h4>
                                <input type="hidden" id="totalSumaServiciosSinFormato">
                              </th> 
                            </tfoot>
                            
                        </table>
                        <table id="pagosPorMetodo" class="table table-striped table-bordered table-condensed table-hover"
                        style="float:left; margin-left: 5px">
                            <thead style="background-color:#A9D0F5">
                              <th></th>
                              <th style="text-align: center; vertical-align:middle;">Pagos (Por Método de Pago)</th>
                            </thead>
                            <tbody style="text-align: center;">
                            </tbody>
                            <tfoot>
                              <th style="vertical-align: middle; text-align:center;">TOTAL</th>
                              <th style="text-align: center;"><h4 id="totalSumaPagos"> </h4>
                              <input type="hidden" id="totalSumaPagosSinFormato"></th> 
                            </tfoot>
                            
                        </table>
                        <table id="tablaUtilidad" class="table table-striped table-bordered table-condensed table-hover"
                        style="float:left; margin-left: 5px">
                          <thead>
                            <tr>
                              <th>Categorías</th>
                              <th>Total de Ingresos </br> (Servicios Contratados)</th>
                              <th>Total de Egresos</th>
                              <th>Utilidad o Pérdida</th>
                            </tr>
                          </thead>
                          <tbody>

                          </tbody>

                        </table>
                        </div>
                      </div>
  
                    </div>
                  </div>              
                </div>
              </div>
              <!-- Fin de utilidad -->
            
          
        </div><!-- /.box -->
        <a type="button" href="vista-negocio.php" class="btn btn-danger" id="regresar">
          <i class="fa fa-arrow-circle-left"></i> 
          Regresar
        </a>
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

<!-- Inicio Modal Cobros -->
<div class="modal fade" id="modalCobros" tabindex="-1" role="dialog">
  <div class="modal-dialog" style="width: 80% !important;">
    <div class="modal-content">
      
      <!-- Inicio Encabezado Modal -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Registrar Cobro</h4>
      </div>
      <!-- Fin Encabezado Modal -->

      <!-- Inicio Contenido Modal -->
      <div class="modal-body table-responsive">
        <form name="formularioCobros" id="formularioCobros" method="POST">
              <!-- Solicitamos id del evento -->
              <input type="hidden" name="id_cobro" id="estado_evento">
              <!-- Solicitamos evento -->
              <div class="form-group row-fluid col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- <label>Seleccionar evento (*):</label> -->
                <label>Evento:</label>
                <input type="text" class="form-control nombre_evento_modal" id="nombreEventoCobro" placeholder="Nombre evento" readonly>
              </div>

              <!-- Solicitamos fecha del cobro -->
              <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label>Fecha del cobro (*):</label>
                <input type="datetime-local" class="form-control" name="fecha_cobro" id="fecha_cobro" required="true">
              </div>

              <!-- Monto del cobro -->
              <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
							  <label>Monto del cobro (*):</label>
							  <input type="number" class="form-control" name="monto_cobro" id="monto_cobro" maxLength="6" placeholder="Monto del cobro" required="true">
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
							  <input type="email" class="form-control" name="email_cobro" id="email_cobro" maxLength="250" placeholder="Email del cliente(s)"  >
							</div>

              <!-- Solicitamos notas del cliente -->
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label>Notas: </label>
							  <textarea class="form-control" maxLength="250" name="notas_cobro" id="notas_cobro" placeholder="Notas del cobro" ></textarea>
							</div>

              <!-- Botones para guardar y cancelar -->
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button class="btn btn-primary" type="submit" id="btnGuardar">Registrar</button>
                <div style="margin: 10px 0;" class="hidden loading-gif">
                  <img src="../public/img/loading.gif" class="img-responsive" style="display: block; margin-right: auto; margin-left: auto; width: 80px">
                </div>
              </div>
            </form>
      </div>
      <!-- Inicio Contenido Modal -->

      <!-- Inicio Pie Modal -->
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
      <!-- Fin Pie Modal Cobros -->        
    </div>
  </div>
</div>  
<!-- Fin Modal -->


<!-- Inicio Modal Pagos -->
<div class="modal fade" id="modalPagos" tabindex="-1" role="dialog">
  <div class="modal-dialog" style="width: 80% !important;">
    <div class="modal-content">
      
      <!-- Inicio Encabezado Modal -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Registrar Pago</h4>
      </div>
      <!-- Fin Encabezado Modal -->

      <!-- Inicio Contenido Modal -->
      <div class="modal-body table-responsive">
        <form name="formularioPagos" id="formularioPagos" method="POST">
              <!-- Solicitamos id del evento -->
              <input type="hidden" name="id_cobro" id="estado_evento">
              <!-- Solicitamos evento -->
              <div class="form-group row-fluid col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- <label>Seleccionar evento (*):</label> -->
                <label>Evento:</label>
                <input type="text" class="form-control nombre_evento_modal" id="nombreEventoPago" placeholder="Nombre evento" readonly>
              </div>

              <!-- Solicitamos proveedor -->
              <div class="form-group row-fluid col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- <label>Seleccionar evento (*):</label> -->
                <label>Seleccionar proveedor (*):</label>
                <select class="form-control selectpicker" data-show-subtext="true" data-live-search="true" 
                name="id_proveedor" id="id_proveedor" required="">
                  <!-- <option value="">Seleccione el evento</option> -->
                  <option value="">Cargando...</option>
                </select>
              </div>

              <!-- Solicitamos fecha del pago -->
              <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label>Fecha del pago (*):</label>
                <input type="datetime-local" class="form-control" name="fecha_pago" id="fecha_pago" required="true">
              </div>

              <!-- Monto del pago -->
              <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
							  <label>Monto del pago (*):</label>
							  <input type="number" class="form-control" name="monto_pago" id="monto_pago" maxLength="6" 
                placeholder="Monto del pago" required="true">
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
							  <input type="email" class="form-control" name="email_pago" id="email_pago" 
                maxLength="250" placeholder="Email del cliente(s)">
							</div>

              <!-- Solicitamos notas del cliente -->
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label>Notas: </label>
							  <textarea class="form-control" maxLength="250" name="notas_pago" 
                id="notas_pago" placeholder="Notas del pago" ></textarea>
							</div>

              <!-- Botones para guardar y cancelar -->
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button class="btn btn-primary" type="submit" id="btnGuardar">Registrar</button>
                <div style="margin: 10px 0;" class="hidden loading-gif">
                  <img src="../public/img/loading.gif" class="img-responsive" 
                  style="display: block; margin-right: auto; margin-left: auto; width: 80px">
                </div>
              </div>
            </form>
      </div>
      <!-- Inicio Contenido Modal -->

      <!-- Inicio Pie Modal -->
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
      <!-- Fin Pie Modal Cobros -->        
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
<script>
    var checkboxes = '<?php echo $_SESSION['checkboxes']; ?>';
</script>
<script type="text/javascript" src="../public/plugins/numero-letras.js"></script>
<script type="text/javascript" src="scripts/detalle-evento.js"></script>
<script type="text/javascript" src="scripts/archivos.js"></script>
<script type="text/javascript" src="scripts/pdf.js"></script>

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

  #detallesPagos button{
    display: block;
    margin: 5px 3px;
  }

  #tablaUtilidad tr{
    text-align: right;
  }

  #tablaUtilidad th{
    text-align: center; 
    color: #ffffff; 
    background-color:rgb(54, 127, 169);
  }

  #tablaUtilidad .categoria{
    text-align: right; 
    color: #ffffff; 
    background-color:rgb(54, 127, 169);
  }

  #tablaUtilidad .categoria-total{
    text-align: right; 
    color: #ffffff; 
    background-color:rgb(54, 127, 169);
    font-weight: bold;
  }

  #serviciosPorCategoria,#pagosPorMetodo{
    display: none;
  }
  

  #tablaUtilidad .totales{
    font-weight: bold;
    font-size: 1.4rem;
  }
</style>
<?php } ?>