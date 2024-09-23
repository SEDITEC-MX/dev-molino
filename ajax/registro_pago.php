<?php
require_once "../config/sesion.php";
require_once "../modelos/Registro_pago.php";
require_once "../config/mail.php";
require_once "funciones_comunes.php";
require_once "../modelos/Archivos.php";

Sesion::existeSesion();

$base_dir = "./../archivos";

$registro_pago = new Registro_pago();

/*
Columnas de la tabla 'pagos'
id_pago int(11)
id_evento int(11)
id_proveedor int(11)
fecha_pago datetime(6)
monto_pago decimal(15,2)
metodo_pago varchar(45)
email_pago varchar(200)
*/

// Recibimos la variables del formulario vía POST
$id_pago = isset($_POST["id_pago"]) ? limpiarCadena($_POST["id_pago"]) : "";
$id_evento = isset($_POST["id_evento"]) ? limpiarCadena($_POST["id_evento"]) : "";
$id_proveedor = isset($_POST["id_proveedor"]) ? limpiarCadena($_POST["id_proveedor"]) : "";
$nombre_proveedor = isset($_POST["nombre_proveedor"]) ? limpiarCadena($_POST["nombre_proveedor"]) : "";
$fecha_pago = isset($_POST["fecha_pago"]) ? limpiarCadena($_POST["fecha_pago"]) : "";
$monto_pago = isset($_POST["monto_pago"]) ? limpiarCadena($_POST["monto_pago"]) : "";
$metodo_pago = isset($_POST["metodo_pago"]) ? limpiarCadena($_POST["metodo_pago"]) : "";
$email_pago = isset($_POST["email_pago"]) ? limpiarCadena($_POST["email_pago"]) : "";
$notas_pago = isset($_POST["notas_pago"]) ? limpiarCadena($_POST["notas_pago"]) : "";
$id_categoria_servicio = isset($_POST["id_categoria_servicio"]) ? limpiarCadena($_POST["id_categoria_servicio"]) : null;
$nombre_evento_seleccionado = isset($_POST["nombre_evento"]) ? limpiarCadena($_POST["nombre_evento"]) : "";
$id_usuario = isset($_POST["id_usuario"]) ? limpiarCadena($_POST["id_usuario"]) : "";
$nombre_evento = isset($_POST["nombre_evento"]) ? limpiarCadena($_POST["nombre_evento"]) : "";

switch ($_GET["op"]) {
	case 'registrar_pago':
		$respuesta = $registro_pago->insertar(
			$id_evento,
			$id_proveedor,
			$fecha_pago,
			$monto_pago,
			$metodo_pago,
			$email_pago,
			$notas_pago,
			$id_categoria_servicio,
			$id_usuario
		);
		$correo = new Mail();
		$aux = new FuncionesComunes();
		$metodo_pago = $aux->asignarMetodoCobro($metodo_pago);
		$monto_pago = "$ " . number_format($monto_pago, 2);
		$resultado;
		if ($respuesta) {
			$resultado = "Pago registrado";
			if ($correo->enviarCorreoPago(
				$email_pago,
				$_SESSION['email_usuario'],
				$fecha_pago,
				$monto_pago,
				$metodo_pago,
				$nombre_proveedor,
				$notas_pago,
				$nombre_evento_seleccionado
			)) {
				$resultado .= " y correo enviado correctamente.";
			} else {
				$resultado .= ". El pago no pudo ser enviado.";
			}
		} else {
			$resultado = "El pago no se pudo registrar.";
		}
		echo $resultado;
		break;

	case 'reenviar_comprobante':
		$correo = new Mail();
		$resultado;
		if ($correo->enviarCorreoPago(
			$email_pago,
			$_SESSION['email_usuario'],
			$fecha_pago,
			$monto_pago,
			$metodo_pago,
			$nombre_proveedor,
			$notas_pago,
			$nombre_evento_seleccionado
		)) {
			$resultado = "Correo enviado correctamente.";
		} else {
			$resultado = "El correo no pudo ser enviado.";
		}
		echo $resultado;
		break;

	//case 'listar':

	case 'listarEventos':
		$listaEventos = $registro_pago->listarNombreEventos();
		$contador = 0;
		$data = "<option value=''>Seleccione el evento</option>";

		foreach ($listaEventos as $evento) {
			$id_evento = $evento['id_evento'];
			$nombre_evento = $evento['nombre_evento'];
			$data .= "<option value=" . $id_evento . ">" . $nombre_evento . "</option>";
		}
		echo $data;
		break;

	case 'listarProveedores':
		$listaProveedores = $registro_pago->listarNombreProveedores();
		$contador = 0;
		$data = "<option value=''>Seleccione el proveedor</option>";
		foreach ($listaProveedores as $evento) {
			$id_proveedor = $evento['id_proveedor'];
			$nombre_evento = $evento['nombre_proveedor'];
			$data .= "<option value=" . $id_proveedor . ">" . $nombre_evento . "</option>";
		}
		echo $data;
		break;

	case 'listarPagos':
		$id_evento = $_POST['id_evento'];
		$listaPagos = $registro_pago->listarPagos($id_evento);
		$cont = 0;
		$total = 0;

		$data = array();
		if (count($listaPagos) == 0) { //Si no hay historial de
			// echo "<tr class='filas'>
			// 		<td colspan='6'>No hay historial de cobros para este evento</td>
			// 	 </tr>";
			$data[] = array(
				// "0" => "No hay historial de cobros para este evento",
				// "1" => ""
			);
			$results = array(
				"aaData" => []
			);
			echo json_encode($results);
		} else {
			$aux = new FuncionesComunes();
			foreach ($listaPagos as $pago) {
				$cont++;
				$id_pago = $pago['id_pago'];
				$fecha_pago = strstr($pago['fecha_pago'], ' ', true);
				$monto_pago = $pago['monto_pago'];
				$total += $pago['monto_pago'];
				$nombre_proveedor = $pago['nombre_proveedor'];
				$metodo_pago = $pago['metodo_pago'];
				$email_pago = $pago['email_pago'];
				$notas_pago = $pago['notas_pago'];
				$nombre_categoria_servicio = $pago['nombre_categoria_servicio'];
				$id_categoria_servicio = $pago['id_categoria_servicio'];
				$metodo_pago = $aux->asignarMetodoCobro($metodo_pago);

				if (isset($_GET['variacion'])) {
					$data[] = array(
						"0" => ('<button class="btn btn-primary reenviar">Reenviar Comprobante</button>' .
							'<button class="btn btn-danger eliminar">Borrar Pago</button>'
						),
						"1" => $fecha_pago,
						"2" => $nombre_proveedor,
						"3" => $metodo_pago,
						"4" => "$ " . number_format($monto_pago, 2),
						"5" => $email_pago,
						"6" => $nombre_categoria_servicio,
						"7" => $notas_pago,
						"8" => $id_pago,
						"9" => "$ " . number_format($total, 2),
						"10" => $id_categoria_servicio
					);
				} else {
					$data[] = array(
						"0" => ('<button class="btn btn-primary reenviar">Reenviar Comprobante</button>' .
							'<button class="btn btn-danger eliminar">Borrar Pago</button>' .
							'<button class="btn btn-info editar-pago">Editar Categoría del pago</button>'
						),
						"1" => $fecha_pago,
						"2" => $nombre_proveedor,
						"3" => $metodo_pago,
						"4" => "$ " . number_format($monto_pago, 2),
						"5" => $email_pago,
						"6" => $nombre_categoria_servicio,
						"7" => $notas_pago,
						"8" => $id_pago,
						"9" => "$ " . number_format($total, 2),
						"10" => $id_categoria_servicio
					);
				}
			}
			$results = array(
				"sEcho" => 1,
				"iTotalRecords" => count($data),
				"iTotalDisplayRecords" => count($data),
				"aaData" => $data
			);
			echo json_encode($results);
		}
		break;

	case 'mostrar':
		$respuesta = $pago->mostrar($id_evento);
		echo json_encode($respuesta);
		break;


	case 'actualizarCategoriaServicio':
		$respuesta = $registro_pago->actualizarCategoriaServicio($id_pago, $id_categoria_servicio);
		if ($respuesta) {
			$resultado = "Categoría actualizada correctamente";
		} else {
			$resultado = "La categoría no pudo ser actualizada";
		}
		echo $resultado;
		break;

	case 'listarDetalle':
		//Recibimos el idingreso
		$id = $_GET['id'];

		$rspta = $registro_pago->listarDetalle($id);
		$total = 0;
		echo "<thead style='background-color:#A9D0F5;'>
                                    <th style='text-align: center; vertical-align:middle;'>Opciones</th>
                                    <th style='text-align: center; vertical-align:middle;'>Fecha del pago</th>
                                    <th style='text-align: center; vertical-align:middle;'>Monto del pago</th>
                                    <th style='text-align: center; vertical-align:middle;'>Método del pago</th>
                                    <th style='text-align: center; vertical-align:middle;'>Email</th>
                                    <th style='text-align: center; vertical-align:middle;'>Notas</th>
                                </thead>";

		$cont = 0;
		while ($reg = $rspta->fetch_object()) {


			echo "<tr class='filas' id=fila" . $cont . " style='text-align:; vertical-align:middle;'>
					<td><button type='button' class='btn btn-danger' onclick='eliminarDetalle(" . $cont . ")'>Reenviar comprobante</button>
					<button type='button' class='btn btn-danger' onclick='eliminarDetalle(" . $cont . ")'>X</button></td>
					<td><input type='hidden' name='id_pago[]' value='" . $reg->id_pago . "'>" . $reg->fecha_pago . "</td>
					<td><input type='number' name='monto_pago[]' id='monto_pago[]' value='" . $reg->monto_pago . "'></td>
					<td><input type='number' name='metodo_pago[]' id='metodo_pago[]' value='" . $reg->metodo_pago . "'></td>
					<td><input type='number' name='email_pago[]' id='email_pago[]' value='" . $reg->email_pago . "'></td>
					<td><input type='number' name='notas_pago[]' id='notas_pago[]' value='" . $reg->notas_pago . "'></td>
				</tr>";
			$total = $total + ($reg->monto_pago);
			$cont++;
		}
		echo "<tfoot>
					<th style='text-align: center; vertical-align:middle;'>TOTAL</th>
                	<th></th>
                    <th></th>
                    <th></th>
                    <th><h4 id='total' style='text-align: right; vertical-align:middle;' >$" . $total . '</h4><input type="hidden" name="total_pago" id="total_pago"></th> 
                </tfoot>';
		break;

	case 'eliminar_pago':
		$id_pago = isset($_POST["id_pago"]) ? limpiarCadena($_POST["id_pago"]) : "";
		$fecha_pago = $_POST['fecha_pago'];
		$monto_pago = $_POST['monto_pago'];
		$metodo_pago = $_POST['metodo_pago'];
		$email_pago = $_POST['email_pago'];
		$nombre_proveedor = $_POST['nombre_proveedor'];
		$correo = new Mail();

		$respuesta = $registro_pago->eliminar($id_pago);
		if ($respuesta) {
			$resultado = "Pago eliminado con éxito";
			if ($correo->enviarCorreoPagoEliminado(
				$email_pago,
				$_SESSION['email_usuario'],
				$fecha_pago,
				$monto_pago,
				$nombre_proveedor,
				$metodo_pago,
				$nombre_evento_seleccionado
			)) {
				$resultado .= " y correo enviado correctamente.";
			} else {
				$resultado .= ". El pago no pudo ser enviado.";
			}
		} else {
			$resultado = "El pago no se pudo eliminar.";
		}
		echo $resultado;
		break;

	case 'registrar_caja':
		$correo = "julio.garces@outlook.com";
		$respuesta = $registro_pago->insertar(
			$id_evento,
			$id_proveedor,
			$fecha_pago,
			$monto_pago,
			"EF",
			$correo,
			$notas_pago,
			$id_categoria_servicio,
			$id_usuario
		);
		$correo = new Mail();
		$aux = new FuncionesComunes();
		$metodo_pago = $aux->asignarMetodoCobro($metodo_pago);
		$monto_pago = "$ " . number_format($monto_pago, 2);
		$resultado;
		if ($respuesta) {
			$resultado = "Pago registrado";
			if ($correo->enviarCorreoPago(
				$email_pago,
				$_SESSION['email_usuario'],
				$fecha_pago,
				$monto_pago,
				$metodo_pago,
				$nombre_proveedor,
				$notas_pago,
				$nombre_evento_seleccionado
			)) {
				$resultado .= " y correo enviado correctamente.";
			} else {
				$resultado .= ". El pago no pudo ser enviado.";
			}
		} else {
			$resultado = "El pago no se pudo registrar.";
		}
		echo $resultado;
		break;
	
		case 'listarPagosCaja':
			//$id_usuario = 1;
			$id_usuario = $_POST['id_usuario'];
			//echo $id_usuario;
			$listaPagos = $registro_pago->listarPagosCaja($id_usuario);
			$cont = 0;
			$total = 0;
	
			$data = array();
			if (count($listaPagos) == 0) { //Si no hay historial de
				// echo "<tr class='filas'>
				// 		<td colspan='6'>No hay historial de cobros para este evento</td>
				// 	 </tr>";
				$data[] = array(
					// "0" => "No hay historial de cobros para este evento",
					// "1" => ""
				);
				$results = array(
					"aaData" => []
				);
				echo json_encode($results);
			} else {
				$aux = new FuncionesComunes();
				foreach ($listaPagos as $pago) {
					$cont++;
					$id_pago = $pago['id_pago'];
					$fecha_pago = strstr($pago['fecha_pago'], ' ', true);
					$monto_pago = $pago['monto_pago'];
					$total += $pago['monto_pago'];
					$nombre_evento  = $pago['nombre_evento'];
					$nombre_proveedor = $pago['nombre_proveedor'];
					$metodo_pago = $pago['metodo_pago'];
					$email_pago = $pago['email_pago'];
					$notas_pago = $pago['notas_pago'];
					$nombre_categoria_servicio = $pago['nombre_categoria_servicio'];
					$id_categoria_servicio = $pago['id_categoria_servicio'];
					$metodo_pago = $aux->asignarMetodoCobro($metodo_pago);
	
					if (isset($_GET['variacion'])) {
						$data[] = array(
							"0" => ('<button class="btn btn-danger eliminar">Borrar Pago</button>'
							),
							"1" => $fecha_pago,
							"2" => "$ " . number_format($monto_pago, 2),
							"3" => $nombre_evento,
							"4" => $nombre_proveedor,
							"5" => $notas_pago,
							"6" => "boton comprobante"
						);
					} else {
						$data[] = array(
	
							"0" => ('<button class="btn btn-danger eliminar">Borrar Pago</button>'
							),
							"1" => $fecha_pago,
							"2" => "$ " . number_format($monto_pago, 2),
							"3" => $nombre_evento,
							"4" => $nombre_proveedor,
							"5" => $notas_pago,
							"6" => "boton comprobante"
	
						);
					}
				}
				$results = array(
					"sEcho" => 1,
					"iTotalRecords" => count($data),
					"iTotalDisplayRecords" => count($data),
					"aaData" => $data
				);
				echo json_encode($results);
			}
			break;
}
