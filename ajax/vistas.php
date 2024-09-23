<?php

require_once "../config/sesion.php";
require_once "../modelos/Evento.php";




Sesion::existeSesion();


$evento= new Evento();

/*
Columnas de la tabla 'evento'

id_evento 			int(11) AI PK 
fecha_evento 		datetime(6) 
nombre_evento 		varchar(250) 
tipo_evento 		varchar(45) 
cotizacion_evento 	varchar(45) 
notas_evento 		varchar(250) 
total_evento 		decimal(15,2) 
invitados_evento 	int(6) 
estado_evento 		varchar(45)
*/


// Recibimos la variables del formulario vía POST
$id_evento=isset($_POST["id_evento"])? limpiarCadena($_POST["id_evento"]):"";
$fecha_evento=isset($_POST["fecha_evento"])? limpiarCadena($_POST["fecha_evento"]):"";
$nombre_evento=isset($_POST["nombre_evento"])? limpiarCadena($_POST["nombre_evento"]):"";
$tipo_evento=isset($_POST["tipo_evento"])? limpiarCadena($_POST["tipo_evento"]):"";
$cotizacion_evento=isset($_POST["cotizacion_evento"])? limpiarCadena($_POST["cotizacion_evento"]):"";
$notas_evento=isset($_POST["notas_evento"])? limpiarCadena($_POST["notas_evento"]):"";
$total_evento=isset($_POST["total_evento"])? limpiarCadena($_POST["total_evento"]):"";
$invitados_evento=isset($_POST["invitados_evento"])? limpiarCadena($_POST["invitados_evento"]):"";
$estado_evento=isset($_POST["estado_evento"])? limpiarCadena($_POST["estado_evento"]):"";

switch ($_GET["op"]){

	case 'anular' :
		$respuesta = $evento->anular($id_evento);
		echo $respuesta ? "Evento finalizado" : "El evento no se pudo finalizar";
	break;

	case 'activar' :
		$respuesta = $evento->activar($id_evento);
		echo $respuesta ? "Evento programado" : "El evento no se pudo regresar a programado";
	break;

	case 'mostrar' :
		$respuesta = $evento->mostrar($id_evento);
		echo json_encode($respuesta);
	break;

	case 'listarDetalle':
		//Recibimos el idingreso
		$id=$_GET['id'];
		$rspta = $evento->listarDetalle($id);
		$total=0;
		echo "<thead style='background-color:#A9D0F5;'>
                <th style='text-align: center; vertical-align:middle;'>Opciones</th>
                <th style='text-align: center; vertical-align:middle;'>Servicio</th>
                <th style='text-align: center; vertical-align:middle;'>Categoría Servicio</th>
                <th style='text-align: center; vertical-align:middle;'>Cantidad</th>
               	<th style='text-align: center; vertical-align:middle;'>Precio</th>
				<th style='text-align: center; vertical-align:middle;'>Subtotal</th>
            </thead>";
		$cont = 0;
		while ($reg = $rspta->fetch_object()){
			$totalAux2 = $reg->precio_detalle_evento*$reg->cantidad_detalle_evento;
			$total2 = "$ ".number_format($totalAux2,0);
			$totalAux1 = $reg->precio_detalle_evento;
			$totalFor1 = "$ ".number_format($totalAux1,0);
			$totalAux2 = "$ ".number_format($totalAux2,2);
			// $reg->precio_detalle_evento = "$ ".number_format($totalAux2,2);
			// Botón de eliminar servicios
			// echo "<tr class='filas' style='text-align: right; vertical-align:middle;'>
			// <td><input type='button' class='borrar' value='Eliminar' /></td>
			// <td>".$reg->nombre_servicio."</td>
			// <td>".$reg->cantidad_detalle_evento."</td>
			// <td>".$totalFor1."</td>
			// <td>". $total2."</td>
			// </tr>";

		   echo "<tr class='filas' id=fila".$cont." style='text-align:; vertical-align:middle;'>
					<td><button type='button' class='btn btn-danger' onclick='eliminarDetalle(".$cont.")'>X</button></td>
					<td><input type='hidden' name='id_servicio[]' value='".$reg->id_servicio."'>".$reg->nombre_servicio."</td>
					<td><input type='hidden' name='nombre_categoria_servicio[]' id='nombre_categoria_servicio[]'>".$reg->nombre_categoria_servicio."</td>
					<td><input type='number' name='cantidad_detalle_evento[]' id='cantidad_detalle_evento[]' value='".$reg->cantidad_detalle_evento."'></td>
					<td><input type='number' name='precio_detalle_evento[]' id='precio_detalle_evento[]' value='".$reg->precio_detalle_evento."'></td>
					<td><span name='subtotal[]' id='subtotal".$cont."'>".$totalAux2."</span></td>
					<td><button type='button' onclick='modificarSubototales()' class='btn btn-info'><i class='fa fa-refresh'></i></button></td>
				</tr>";

			$total=$total+($reg->precio_detalle_evento*$reg->cantidad_detalle_evento);
			$cont++;
		}
		
		echo "<tfoot>
				<th style='text-align: center; vertical-align:middle;'>TOTAL</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th><h4 id='total' style='text-align: right; vertical-align:middle;' >$".number_format($total, 2).'</h4><input type="hidden" name="total_evento" id="total_evento"></th> 
            </tfoot>';
	break;

	case 'listarTablaAExportar':
		//Recibimos el idingreso
		$id=$_GET['id'];
		$rspta = $evento->listarDetalle($id);
		$total=0;
		echo "<thead style='background-color:#A9D0F5;'>
				<th style='text-align: center; vertical-align:middle;'>Servicio</th>
				<th style='text-align: center; vertical-align:middle;'>Categoría Servicio</th>
				<th style='text-align: center; vertical-align:middle;'>Cantidad</th>
				<th style='text-align: center; vertical-align:middle;'>Precio</th>
				<th style='text-align: center; vertical-align:middle;'>Subtotal</th>
			</thead>";
		$cont = 0;
		while ($reg = $rspta->fetch_object()){
			$totalAux2 = $reg->precio_detalle_evento*$reg->cantidad_detalle_evento;
			$total2 = "$ ".number_format($totalAux2,0);
			$totalAux1 = $reg->precio_detalle_evento;
			$totalFor1 = "$ ".number_format($totalAux1,0);
			$totalAux2 = "$ ".number_format($totalAux2,2);
			// $reg->precio_detalle_evento = "$ ".number_format($totalAux2,2);
			// Botón de eliminar servicios
			// echo "<tr class='filas' style='text-align: right; vertical-align:middle;'>
			// <td><input type='button' class='borrar' value='Eliminar' /></td>
			// <td>".$reg->nombre_servicio."</td>
			// <td>".$reg->cantidad_detalle_evento."</td>
			// <td>".$totalFor1."</td>
			// <td>". $total2."</td>
			// </tr>";
		   	echo "<tr class='filas' id=fila".$cont." style='text-align:; vertical-align:middle;'>
					<td>".$reg->nombre_servicio."</td>
					<td>".$reg->nombre_categoria_servicio."</td>
					<td>".$reg->cantidad_detalle_evento."</td>
					<td>$".number_format($reg->precio_detalle_evento,2)."</td>
					<td><span name='subtotal[]' id='subtotal'".$cont."'>".$totalAux2."</span></td>
				</tr>";

			$total=$total+($reg->precio_detalle_evento*$reg->cantidad_detalle_evento);
			$cont++;
		}

		echo "<tfoot>
				<th style='text-align: center; vertical-align:middle;'>TOTAL</th>
               	<th></th>
                <th></th>
                <th></th>
                <th><h4 id='total' style='text-align: right; vertical-align:middle;' >$".number_format($total, 2).'</h4><input type="hidden" name="total_evento" id="total_evento"></th> 
            </tfoot>';
	break;



	case 'listarVistaClientes' :
		$respuesta = $evento->listar();
		$data= Array();
		while ($reg=$respuesta->fetch_object()){
			$totalAux = $reg->total_evento ?? 0; //Si el valor es nulo, se asigna un 0 para evitar errores de compatibilidad.
			$totalFor = "$ ".number_format($totalAux,2);
			$data[]=array(
			"0"=>(($reg->estado_evento=='Programado') 
			? 	'<abbr title="Ver detalle">
					<a type="button" class="btn btn-primary" href="detalle-evento.php?evento=' . $reg->id_evento . '&vista=cliente">
						<i class="fa fa-eye"></i>
					</a>

				</abbr> '.
				'<abbr title="Finalizar evento">
					<button class="btn btn-danger" onclick="anular('.$reg->id_evento.')">
						<i class="fa fa-check-square-o"></i>
					</button>
				</abbr> '
			:	'<abbr title="Ver detalle">
					<a type="button" class="btn btn-primary" href="detalle-evento.php?evento=' . $reg->id_evento . '&vista=cliente">
						<i class="fa fa-eye"></i>
					</a>
				</abbr> '.
				'<abbr title="Regresar a Programado"><button class="btn btn-warning" onclick="activar('.$reg->id_evento.')"><i class="fa fa-undo"></i></button></abbr> '
			),
			"1"=>$reg->fecha_evento,
			"2"=>$reg->tipo_evento,
			"3"=>$reg->nombre_evento,
			"4"=>$reg->invitados_evento,
			"5"=>$reg->cotizacion_evento,
			"6"=>$totalFor,
			"7"=>($reg->estado_evento=='Programado')?'<span class="label bg-green">Programado</span>':
			'<span class="label bg-red">Finalizado</span>',
			"8" => $reg->timestamp
			);
		}

		$results = array(
			"sEcho"=>1,
			"iTotalRecords"=>count($data),
			"iTotalDisplayRecords"=>count($data),
			"aaData"=>$data
		);
		echo json_encode($results);
	break;


	case 'listarVistaNegocio' :
		$respuesta = $evento->listar();
		$data= Array();
		while ($reg=$respuesta->fetch_object()){
			$totalAux = $reg->total_evento ?? 0; //Si el valor es nulo, se asigna un 0 para evitar errores de compatibilidad.
			$totalFor = "$ ".number_format($totalAux,2);
			$data[]=array(
			"0"=>(($reg->estado_evento=='Programado') 
			? 	'<abbr title="Ver detalle">
					<a type="button" class="btn btn-primary" href="detalle-evento.php?evento=' . $reg->id_evento . '&vista=negocio">
						<i class="fa fa-eye"></i>
					</a>

				</abbr> '.
				'<abbr title="Finalizar evento">
					<button class="btn btn-danger" onclick="anular('.$reg->id_evento.')">
						<i class="fa fa-check-square-o"></i>
					</button>
				</abbr> '
			:	'<abbr title="Ver detalle">
					<a type="button" class="btn btn-primary" href="detalle-evento.php?evento=' . $reg->id_evento . '&vista=negocio">
						<i class="fa fa-eye"></i>
					</a>
				</abbr> '.
				'<abbr title="Regresar a Programado"><button class="btn btn-warning" onclick="activar('.$reg->id_evento.')"><i class="fa fa-undo"></i></button></abbr> '
			),
			"1"=>$reg->fecha_evento,
			"2"=>$reg->tipo_evento,
			"3"=>$reg->nombre_evento,
			"4"=>$reg->invitados_evento,
			"5"=>$reg->cotizacion_evento,
			"6"=>$totalFor,
			"7"=>($reg->estado_evento=='Programado')?'<span class="label bg-green">Programado</span>':
			'<span class="label bg-red">Finalizado</span>',
			"8" => $reg->timestamp
			);
		}

		$results = array(
			"sEcho"=>1,
			"iTotalRecords"=>count($data),
			"iTotalDisplayRecords"=>count($data),
			"aaData"=>$data
		);
		echo json_encode($results);
	break;


}

?>

