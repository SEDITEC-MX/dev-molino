<?php
require_once "../config/sesion.php";
require_once "funciones_comunes.php";
require_once "../config/mail.php";
require_once "../modelos/Registro_cobro.php";



Sesion::existeSesion();


$registro_cobro=new Registro_cobro();

/*
Columnas de la tabla 'cobros'
id_cobro int(11)
id_evento int(11)
fecha_cobro datetime(6)
monto_cobro decimal(15,2)
metodo_cobro varchar(45)
email_cobro varchar(200)
*/

// Recibimos la variables del formulario vía POST
$id_cobro=isset($_POST["id_cobro"])? limpiarCadena($_POST["id_cobro"]):"";
$id_evento=isset($_POST["id_evento"])? limpiarCadena($_POST["id_evento"]):"";
$fecha_cobro=isset($_POST["fecha_cobro"])? limpiarCadena($_POST["fecha_cobro"]):"";
$monto_cobro=isset($_POST["monto_cobro"])? limpiarCadena($_POST["monto_cobro"]):"";
$metodo_cobro=isset($_POST["metodo_cobro"])? limpiarCadena($_POST["metodo_cobro"]):"";
$email_cobro=isset($_POST["email_cobro"])? limpiarCadena($_POST["email_cobro"]):"";
$notas_cobro=isset($_POST["notas_cobro"])? limpiarCadena($_POST["notas_cobro"]):"";
$nombre_evento_seleccionado=isset($_POST["nombre_evento"])? limpiarCadena($_POST["nombre_evento"]):"";



switch ($_GET["op"])
{
	case 'registrar_cobro' :
		
		$sumaEventos = $registro_cobro->verificarTotalEventos($id_evento);
		$cobros = $registro_cobro->verificarTotalCobros($id_evento);
		$totalCobros = $cobros[0]['cobrado'] ?? 0;
		$totalEvento = $sumaEventos[0]['total'] ?? 0;
		$sumaCobros = $totalCobros + $monto_cobro;
		if ($totalEvento < $sumaCobros){
			echo "El cobro excede a la cantidad del evento. <br> 
			Total del evento: "."$ ".number_format($totalEvento,2).
			"<br>Total cobrado: "."$ ".number_format($totalCobros,2).
			"<br>Se intenta cobrar: "."$ ".number_format($monto_cobro,2).
			"<br>Se excede por: "."$ ".number_format($totalEvento - $sumaCobros,2);
			return;
		}
		$respuesta = $registro_cobro->insertar(
		$id_evento,
		$fecha_cobro,
		$monto_cobro,
		$metodo_cobro,
		$email_cobro,
		$notas_cobro
		);
		$correo = new Mail();
		$aux = new FuncionesComunes();
		$metodo_cobro = $aux->asignarMetodoCobro($metodo_cobro);
		$monto_cobro = "$ ".number_format($monto_cobro,2);
		$resultado;
		if ($respuesta){
			$resultado = "Cobro registrado"; 
			if ( $correo->enviarCorreoCobro($email_cobro, $_SESSION['email_usuario'], $fecha_cobro, 
				$monto_cobro, $metodo_cobro, $notas_cobro, $nombre_evento_seleccionado) ){
				$resultado .= " y correo enviado correctamente.";
			} else {
				$resultado .= ". El correo no pudo ser enviado.";
			}
		} else {
			$resultado = "El cobro no se pudo registrar.";
		}
		echo $resultado;



	break;

	case 'reenviar_comprobante' :
		$correo = new Mail();
		$resultado;
		if ( $correo->enviarCorreoCobro($email_cobro, $_SESSION['email_usuario'], $fecha_cobro, 
			$monto_cobro, $metodo_cobro, $notas_cobro, $nombre_evento_seleccionado) ){
			$resultado = "Correo enviado correctamente.";
		} else {
			$resultado = "El correo no pudo ser enviado.";
		}
		echo $resultado;

	break;

	case 'listar' :

	case 'listarEventos' :
		$listaEventos = $registro_cobro->listarNombreEventos();
		$contador = 0;
        $data = "<option value=''>Seleccione el evento</option>";

		foreach($listaEventos as $evento){
			$id_evento = $evento['id_evento'];
			$nombre_evento = $evento['nombre_evento'];
			$data .= "<option value=". $id_evento. ">".$nombre_evento."</option>";
		}
		echo $data;
	break;


	case 'listarCobros' :
		$id_evento = $_POST['id_evento'];
		$listaCobros = $registro_cobro->listarCobros($id_evento);
		$cont = 0;
		$data= Array();
		$total=0;

		if (count($listaCobros) == 0 ){ //Si no hay historial de
			// echo "<tr class='filas'>
			// 		<td colspan='6'>No hay historial de cobros para este evento</td>
			// 	 </tr>";
			$data[]=array(
				// "0" => "No hay historial de cobros para este evento",
				// "1" => ""
			);
			$results = array(
				"aaData"=>[]
			);
			echo json_encode($results);
		} else{
			$aux = new FuncionesComunes();
			foreach($listaCobros as $cobro){
				$cont++;
				$total+= $cobro['monto_cobro'];
				$id_cobro = $cobro['id_cobro'];
				$fecha_cobro = $cobro['fecha_cobro'];
				$monto_cobro = $cobro['monto_cobro'];
				$metodo_cobro = $cobro['metodo_cobro'];
				$email_cobro = $cobro['email_cobro'];
				$notas_cobro = $cobro['notas_cobro'];
				$metodo_cobro = $aux->asignarMetodoCobro($metodo_cobro);
				$data[]=array(
				"0"=>('<button class="btn btn-primary reenviar">Reenviar Comprobante</button>'.
					'<button class="btn btn-danger eliminar">Borrar Cobro</button>'
				),
				//"0"=>strstr($pago['fecha_pago'],' ',true),
				"1"=>strstr($fecha_cobro,' ',true),
				"2"=>"$ ".number_format($monto_cobro,2),
				"3"=>$metodo_cobro,
				"4"=>$email_cobro,
				"5"=>$notas_cobro,
				"6"=>$id_cobro,
				"7"=> "$ ".number_format($total, 2)

				);
			}
			$results = array(
				"sEcho"=>1,
				"iTotalRecords"=>count($data),
				"iTotalDisplayRecords"=>count($data),
				"aaData"=>$data
			);
			echo json_encode($results);
		}
	break;

	case 'mostrar' :
		$respuesta = $cobro->mostrar($id_evento);
		echo json_encode($respuesta);
	break;

	case 'pruebaCorreo' :
		// $correo = new Mail();
		// echo json_encode($correo->enviarCorreo("jorgealbertoci@hotmail.com", "Jorge", "Mensaje prueba", "Esta es una prueba"));
		echo json_encode($_SESSION);
	break;


	case 'listarDetalle':
		//Recibimos el idingreso
		$id=$_GET['id'];
		echo $id;
		$rspta = $registro_cobro->listarDetalle($id);
		$total=0;
		echo "<thead style='background-color:#A9D0F5;'>
                                    <th style='text-align: center; vertical-align:middle;'>Opciones</th>
                                    <th style='text-align: center; vertical-align:middle;'>Fecha del cobro</th>
                                    <th style='text-align: center; vertical-align:middle;'>Monto del cobro</th>
                                    <th style='text-align: center; vertical-align:middle;'>Método del cobro</th>
                                    <th style='text-align: center; vertical-align:middle;'>Email</th>
                                    <th style='text-align: center; vertical-align:middle;'>Notas</th>
                                </thead>";

		$cont = 0;
		while ($reg = $rspta->fetch_object())
				{
		   echo "<tr class='filas' id=fila".$cont." style='text-align:; vertical-align:middle;'>
					<td><button type='button' class='btn btn-danger' onclick='eliminarDetalle(".$cont.")'>Reenviar comprobante</button>
					<button type='button' class='btn btn-danger' onclick='eliminarDetalle(".$cont.")'>X</button></td>
					<td><input type='hidden' name='id_cobro[]' value='".$reg->id_cobro."'>".$reg->fecha_cobro."</td>
					<td><input type='number' name='monto_cobro[]' id='monto_cobro[]' value='".$reg->monto_cobro."'></td>
					<td><input type='number' name='metodo_cobro[]' id='metodo_cobro[]' value='".$reg->metodo_cobro."'></td>
					<td><input type='number' name='email_cobro[]' id='email_cobro[]' value='".$reg->email_cobro."'></td>
					<td><input type='number' name='notas_cobro[]' id='notas_cobro[]' value='".$reg->notas_cobro."'></td>
				</tr>";
					$total=$total+($reg->monto_cobro);
					$cont++;
				}
		  echo "<tfoot>
					<th style='text-align: center; vertical-align:middle;'>TOTAL</th>
                	<th></th>
                    <th></th>
                    <th></th>
                    <th><h4 id='total' style='text-align: right; vertical-align:middle;' >$".$total.'</h4><input type="hidden" name="total_cobro" id="total_cobro"></th> 
                </tfoot>';
	break;
	
	case 'eliminar_cobro' :
		$id_cobro=isset($_POST["id_cobro"])? limpiarCadena($_POST["id_cobro"]):"";
		$fecha_cobro = $_POST['fecha_cobro'];
		$monto_cobro = $_POST['monto_cobro'];
		$metodo_cobro = $_POST['metodo_cobro'];
		$email_cobro = $_POST['email_cobro'];
		$nombre_evento_seleccionado = $_POST['nombre_evento'];
		$correo = new Mail();

		$respuesta = $registro_cobro->eliminar($id_cobro);

		if ($respuesta){
			$resultado = "Cobro eliminado con éxito"; 
			if ( $correo->enviarCorreoCobroEliminado($email_cobro, $_SESSION['email_usuario'], $fecha_cobro, 
				$monto_cobro, $metodo_cobro, $nombre_evento_seleccionado) ){
				$resultado .= " y correo enviado correctamente.";
			} else {
				$resultado .= ". El correo no pudo ser enviado.";
			}
		} else {
			$resultado = "El cobro no se pudo eliminar.";
		}
		echo $resultado;
	break;

}
?>
