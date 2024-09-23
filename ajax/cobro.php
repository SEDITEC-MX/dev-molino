<?php

require_once "../config/sesion.php";
require_once "funciones_comunes.php";
require_once "../modelos/Cobro.php";
require_once "../modelos/Evento.php";
Sesion::existeSesion();

$cobro=new Cobro();

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

switch ($_GET["op"]){
	case 'guardaryeditar' :
		if (empty($id_cobro)){
			$respuesta = $cobro->insertar(
				$id_evento,
				$fecha_cobro,
				$monto_cobro,
				$metodo_cobro,
				$notas_evento,
				$email_cobro
			);
			echo $respuesta ? "Cobro registrado" : "El cobro no se pudo registrar";
		}else{
			$respuesta = $cobro->editarCobros(
				$id_cobro,
				$id_evento,
				$fecha_cobro,
				$monto_cobro,
				$metodo_cobro,
				$notas_evento,
				$email_cobro	
			);
			echo $respuesta ? "Evento editado" : "El evento no se pudo editadar";
		}
	break;



	case 'mostrar' :
		$respuesta = $cobro->mostrar($id_evento);
		echo json_encode($respuesta);
	break;

	case 'listar' :
		$respuesta = $cobro->listarEventos();
		$data= Array();
		foreach($respuesta as $evento){
			$totalAux = $evento['total_evento'] ?? 0;
			$totalFor = "$ ".number_format($totalAux,0);
			$pendiente = $evento['total'] - $evento['cobrado'];
			$eventoTotal = $evento['total'] ?? 0;  //Si el valor es nulo, se asigna un 0 para evitar errores de compatibilidad.
			$eventoCobrado = $evento['cobrado'] ?? 0;
			$data[]=array(
			"0"=>(($evento['estado_evento']=='Programado')?'<abbr title="Ver detalle"><button class="btn btn-primary" onclick="mostrar('.$evento['id_evento'].')"><i class="fa fa-eye"></i></button></abbr> ':
			'<abbr title="Ver detalle"><button class="btn btn-primary" onclick="mostrar('.$evento['id_evento'].')"><i class="fa fa-eye"></i></button></abbr> '
			),
			"1"=>$evento['fecha_evento'],
			"2"=>$evento['tipo_evento'],
			"3"=>$evento['nombre_evento'],
			"4"=>$evento['cotizacion_evento'],
			"5"=>"$ ".number_format($eventoTotal,2),
			"6"=>"$ ".number_format($eventoCobrado,2),
			"7"=>"$ ".number_format($pendiente,2),
			"8"=>($evento['estado_evento']=='Programado')?'<span class="label bg-green">Programado</span>':
			'<span class="label bg-red">Finalizado</span>'
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



	case 'listarDetalle':
		//Recibimos el idingreso
		$id=$_GET['id'];
		$respuesta = $cobro->listarDetalle($id);
		$total=0;
		$aux = new FuncionesComunes();
		$cont = 0;
		$data= Array();
		foreach($respuesta as $cobro){
			$total+= $cobro['monto_cobro'];
			// $totalAux = $cobro['total_evento'];
			// $totalFor = "$ ".number_format($totalAux,0);
			// $pendiente = $cobro['total'] - $cobro['cobrado'];
			$metodo_cobro = $aux->asignarMetodoCobro($cobro['metodo_cobro']);
			$data[]=array(
				"0"=>strstr($cobro['fecha_cobro'],' ',true),
				"1"=>"$ ".number_format($cobro['monto_cobro'],2),
				"2"=>$metodo_cobro,
				"3"=>$cobro['email_cobro'],
				"4"=>$cobro['notas_cobro'],
				"5"=> "$ ".number_format($total, 2)
			);
		}

		$results = array(
			"sEcho"=>1,
			"iTotalRecords"=>count($data),
			"iTotalDisplayRecords"=>count($data),
			"aaData"=>$data
		);
		echo json_encode($results);

		// echo "<thead style='background-color:#A9D0F5;'>

  //                                   <th style='text-align: center; vertical-align:middle;'>Fecha del cobro</th>

  //                                   <th style='text-align: center; vertical-align:middle;'>Método del cobro</th>

  //                                   <th style='text-align: center; vertical-align:middle;'>Correo</th>

  //                                   <th style='text-align: center; vertical-align:middle;'>Notas</th>

  //                                   <th style='text-align: center; vertical-align:middle;'>Subtotal</th>

  //                               </thead>";



		// $cont = 0;

		// $aux = new FuncionesComunes();

		

		// foreach($rspta as $cobro){

		// 	$monto = "$ ".number_format($cobro['monto_cobro'],2);

		// 	// Botón de eliminar servicios

		// 	// echo "<tr class='filas' style='text-align: right; vertical-align:middle;'>

		// 	// <td><input type='button' class='borrar' value='Eliminar' /></td>

		// 	// <td>".$cobro['nombre_servicio']."</td>

		// 	// <td>".$cobro['cantidad_detalle_evento']."</td>

		// 	// <td>".$totalFor1."</td>

		// 	// <td>". $total2."</td>

		// 	// </tr>";

		// 	$metodo_cobro = $aux->asignarMetodoCobro($cobro['metodo_cobro']);

		//    echo "<tr class='filas' id=fila".$cont." style='text-align: center; vertical-align:middle;'>

		// 			<td>".$cobro['fecha_cobro']."</td>

		// 			<td>".$metodo_cobro."</td>

		// 			<td>".$cobro['email_cobro']."</td>

		// 			<td>".$cobro['notas_cobro']."</td>

		// 			<td>".$monto."</td>

		// 		</tr>";

		// 		$total+=($cobro['monto_cobro']);

		// 		$cont++;

		// 	}

			

		// 	$total = "$ ".number_format($total,2);

		//   	echo "<tfoot>

		// 			<th style='text-align: center; vertical-align:middle;'>TOTAL</th>

  //               	<th></th>

  //                   <th></th>

  //                   <th></th>

  //                   <th><h4 id='total' style='text-align: center; vertical-align:middle;' >".$total.'</h4><input type="hidden" name="total_evento" id="total_evento"></th> 

  //               </tfoot>';

	break;


	case 'listarServicios':
		require_once "../modelos/Servicio.php";
		$servicio=new Servicio();
		$respuesta = $servicio->listarActivos();
		$data= Array();
		while ($reg=$respuesta->fetch_object()){
			$data[]=array(
				"0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->id_servicio.',\''.$reg->nombre_servicio.'\')"><span class="fa fa-plus"></span></button>',
				"1"=>$reg->categoria,
				"2"=>$reg->nombre_servicio,
				"3"=>$reg->descripcion_servicio,
				"4"=>($reg->imagen_servicio)?"<img src='../files/servicios/".$reg->imagen_servicio."' height='50px' width='50px' >": 'Sin imagen'
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

