<?php
require_once "../config/sesion.php";
require_once "../modelos/Pagos_Empleados.php";

Sesion::existeSesion();

// Creamos un nuevo objeto
$pago_empleado = new Empleado_Pagos();

// Nos traemos las variables del método POST del JS
$id_empleado=isset($_POST["id_empleado"])? limpiarCadena($_POST["id_empleado"]):"";
$nombre_empleado=isset($_POST["nombre_empleado"])? limpiarCadena($_POST["nombre_empleado"]):"";
$id_categoria_servicio=isset($_POST["id_categoria_servicio"])? limpiarCadena($_POST["id_categoria_servicio"]):"";
$notas_empleado=isset($_POST["notas_empleado"])? limpiarCadena($_POST["notas_empleado"]):"";
$condicion_empleado=isset($_POST["condicion_empleado"])? limpiarCadena($_POST["condicion_empleado"]):"";
$id_pago_empleado=isset($_POST["id_pago_empleado"])? limpiarCadena($_POST["id_pago_empleado"]):"";
$id_evento=isset($_POST["id_evento"])? limpiarCadena($_POST["id_evento"]):"";
$fecha_pago_empleado=isset($_POST["fecha_pago_empleado"])? limpiarCadena($_POST["fecha_pago_empleado"]):"";
$monto_pago=isset($_POST["monto_pago"])? limpiarCadena($_POST["monto_pago"]):"";
$descuento=isset($_POST["descuento"])? limpiarCadena($_POST["descuento"]):"";
$total=isset($_POST["total"])? limpiarCadena($_POST["total"]):"";
$concepto=isset($_POST["concepto"])? limpiarCadena($_POST["concepto"]):"";

switch ($_GET["op"])
{
	case 'listarPagosEmpleados':
		$respuesta = $pago_empleado->listar();
		// echo "hola";
		$data= Array();
		if (!empty(($respuesta))){
			foreach ($respuesta as $pago_empleado)
			{
				$data[]=array(
				"0"=>'<botton class="btn btn-danger" onclick="eliminar('.$pago_empleado['id_pago_empleado'].')"><i class="fa fa-close"></i></botton>',
				//"0"=>$pago_empleado['id_pago_empleado'],
				"1"=>$pago_empleado['fecha_pago_empleado'],
				"2"=>$pago_empleado['id_evento'],
				"3"=>$pago_empleado['categoria'],
				"4"=>$pago_empleado['id_empleado'],
				"5"=>$pago_empleado['concepto'],
				"6"=>$pago_empleado['monto_pago'],
				"7"=>$pago_empleado['descuento'],
				"8"=>$pago_empleado['total']
				
				);
			}
		}
		$results = array(
			"sEcho"=>1,
			"iTotalRecords"=>count($data),
			"iTotalDisplayRecords"=>count($data),
			"aaData"=>$data
		);
		echo json_encode($results);
	break;
	case 'eliminar' :
		$respuesta = $pago_empleado->eliminar($id_pago_empleado);
		echo $respuesta ? "Pago eliminado" : "Pago no se pudo eliminar";			
	break;

	case 'guardar' :
		$empleados = $_POST;
		$arregloEmp = array();
		foreach($empleados as $empleado){
			$obj = json_decode($empleado,true);
			$nombre = $obj['nombre'];
			$idEmpleado = $obj['idEmpleado'];
			$fecha = $obj['fecha'];
			$monto = $obj['monto'];
			$descuento = $obj['descuento'];
			$total = $obj['total'];
			$concepto = $obj['concepto'];
			$notas = $obj['notas'];
			array_push($arregloEmp, $obj);
		}
		// $_POST 
		$respuesta = $pago_empleado->insertarMultiple($arregloEmp);
		echo $respuesta ? "Pagos a empleados registrados" : "Pagos a empleados NO registrados";
	break;
	
}

?>
