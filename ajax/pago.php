<?php

require_once "../config/sesion.php";
require_once "funciones_comunes.php";
require_once "../modelos/Pago.php";
require_once "../modelos/Evento.php";

Sesion::existeSesion();
$pago=new Pago();

/*
Columnas de la tabla 'cobros'
id_pago int(11)
id_evento int(11)
id_proveedor int(11)
fecha_pago datetime(6)
monto_pago decimal(15,2)
metodo_pago varchar(45)
email_pago varchar(200)
*/

// Recibimos la variables del formulario vía POST
$id_pago=isset($_POST["id_pago"])? limpiarCadena($_POST["id_pago"]):"";
$id_evento=isset($_POST["id_evento"])? limpiarCadena($_POST["id_evento"]):"";
$id_proveedor=isset($_POST["id_proveedor"])? limpiarCadena($_POST["id_proveedor"]):"";
$fecha_pago=isset($_POST["fecha_pago"])? limpiarCadena($_POST["fecha_pago"]):"";
$monto_pago=isset($_POST["monto_pago"])? limpiarCadena($_POST["monto_pago"]):"";
$metodo_pago=isset($_POST["metodo_pago"])? limpiarCadena($_POST["metodo_pago"]):"";
$email_pago=isset($_POST["email_pago"])? limpiarCadena($_POST["email_pago"]):"";
$id_usuario = isset($_POST["id_usuario"]) ? limpiarCadena($_POST["id_usuario"]) : "";

switch ($_GET["op"]){
	case 'guardaryeditar' :
		if (empty($id_pago)){
			$respuesta = $pago->insertar(
			$id_evento,
			$fecha_pago,
			$monto_pago,
			$metodo_pago,
			$notas_evento,
			$email_pago,
			$id_usuario
		);
			echo $respuesta ? "Cobro registrado" : "El cobro no se pudo registrar";
		}else{
			$respuesta = $pago->editarCobros(
				$id_pago,
				$id_evento,
				$fecha_pago,
				$monto_pago,
				$metodo_pago,
				$notas_evento,
				$email_pago	
			);
			echo $respuesta ? "Evento editado" : "El evento no se pudo editadar";
		}
	break;

	case 'mostrar' :
		$respuesta = $pago->mostrar($id_evento);
		echo json_encode($respuesta);
	break;

	case 'listar' :
		$respuesta = $pago->listarEventos();
		$data= Array();
		foreach($respuesta as $evento){
			$totalAux = $evento['total_evento'];
			$totalFor = "$ ".number_format($totalAux,0);
			$pendiente = $evento['total'] - $evento['pagado'];
			$eventoTotal = $evento['total'] ?? 0;  //Si el valor es nulo, se asigna un 0 para evitar errores de compatibilidad.
			$eventoPagado = $evento['pagado'] ?? 0;
			$data[]=array(
				"0"=>(($evento['estado_evento']=='Programado')?'<abbr title="Ver detalle"><button class="btn btn-primary" onclick="mostrar('.$evento['id_evento'].')"><i class="fa fa-eye"></i></button></abbr> ':
				'<abbr title="Ver detalle"><button class="btn btn-primary" onclick="mostrar('.$evento['id_evento'].')"><i class="fa fa-eye"></i></button></abbr> '
				),
				"1"=>$evento['fecha_evento'],
				"2"=>$evento['tipo_evento'],
				"3"=>$evento['nombre_evento'],
				"4"=>$evento['cotizacion_evento'],
				"5"=>"$ ".number_format($eventoTotal,2),
				"6"=>"$ ".number_format($eventoPagado,2),
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
		$respuesta = $pago->listarDetalle($id);
		$total=0;
		$aux = new FuncionesComunes();
		$cont = 0;
		$data= Array();
		foreach($respuesta as $pago){
			$total+= $pago['monto_pago'];
			// $totalAux = $pago['total_evento'];
			// $totalFor = "$ ".number_format($totalAux,0);
			// $pendiente = $pago['total'] - $pago['pagado'];
			$metodo_pago = $aux->asignarMetodoCobro($pago['metodo_pago']);
			$data[]=array(
			"0"=>strstr($pago['fecha_pago'],' ',true),
			"1"=>$pago['nombre_proveedor'],
			"2"=>"$ ".number_format($pago['monto_pago'],2),
			"3"=>$metodo_pago,
			"4"=>$pago['email_pago'],
			"5"=>$pago['nombre_categoria_servicio'],
			"6"=>$pago['notas_pago'],
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
	break;

	case 'listarServicios':
		require_once "../modelos/Servicio.php";
		$servicio=new Servicio();
		$respuesta = $servicio->listarActivos();
		$data= Array();
		while ($reg=$respuesta->fetch_object())
		{
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

