<?php
require_once "../config/sesion.php";
require_once "../modelos/Empleados.php";



Sesion::existeSesion();



// Creamos un nuevo objeto

$empleado = new Empleado();

// Nos traemos las variables del método POST del JS

$id_empleado=isset($_POST["id_empleado"])? limpiarCadena($_POST["id_empleado"]):"";

$nombre_empleado=isset($_POST["nombre_empleado"])? limpiarCadena($_POST["nombre_empleado"]):"";

$id_categoria_servicio=isset($_POST["id_categoria_servicio"])? limpiarCadena($_POST["id_categoria_servicio"]):"";

$notas_empleado=isset($_POST["notas_empleado"])? limpiarCadena($_POST["notas_empleado"]):"";

$condicion_empleado=isset($_POST["condicion_empleado"])? limpiarCadena($_POST["condicion_empleado"]):"";

switch ($_GET["op"])

{

	case 'guardaryeditar' :

	

		if (empty($id_empleado))

		{

			$respuesta = $empleado->insertar($nombre_empleado,$id_categoria_servicio,$notas_empleado);

			echo $respuesta ? "Empleado Registrado" : "El Empleado no se pudo registrar";

		}

		else {

			$respuesta = $empleado->editar($id_empleado,$nombre_empleado,$id_categoria_servicio,$notas_empleado);

			echo $respuesta ? "Empleado Actualizado" : "Empleado no se pudo actualizar";

		}

		

	break;

	

	



	case 'desactivar' :

		$respuesta = $empleado->desactivar($id_empleado);

		echo $respuesta ? "Empleado desactivado" : "El Empleado no se pudo desactivar";			

	break;



	case 'activar' :

		$respuesta = $empleado->activar($id_empleado);

		echo $respuesta ? "Empleado activado" : "El Empleado no se pudo activar";			

	break;

		

	case 'mostrar' :

		$respuesta = $empleado->mostrar($id_empleado);

		echo json_encode($respuesta);

	break;

	

	case 'listar' :

		$respuesta = $empleado->listar();
		$data= Array();

		// if (empty($data)){
		// }

		if (!empty(($respuesta))){
			foreach ($respuesta as $empleado)
			{

				$data[]=array(
				"0"=>$empleado['condicion_empleado']== 1 ? '<botton class="btn btn-warning" onclick="mostrar('.$empleado['id_empleado'].')"><i class="fas fa-pencil-alt"></i></botton>'.
				' <botton class="btn btn-danger" onclick="desactivar('.$empleado['id_empleado'].')"><i class="fa fa-close"></i></botton>':'<botton class="btn btn-warning" onclick="mostrar('.$empleado['id_empleado'].')"><i class="fas fa-pencil-alt"></i></botton>'.
				' <botton class="btn btn-primary" onclick="activar('.$empleado['id_empleado'].')"><i class="fa fa-check"></i></botton>',
				"1"=>$empleado['nombre_empleado'],
				"2"=>$empleado['id_categoria_servicio'],
				"3"=>$empleado['condicion_empleado'] == 1 ?'<span class="label bg-green">Activado</span>':
 				'<span class="label bg-red">Desactivado</span>'
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

	case 'selectCategoria' :
		require_once "../modelos/Categoria.php";
		$categoria = new Categoria();
		$respuesta = $categoria->select();
		foreach($respuesta as $servicio){
			echo '<option value=' . $servicio['id_categoria_servicio'] . '>' . $servicio['nombre_categoria_servicio'] . '</option>';
		}	
	break;


	
	case 'listarEmpleados':
		$respuesta = $empleado->listarActivos();
		// echo "hola";
		$data= Array();
		if (!empty(($respuesta))){
			foreach ($respuesta as $empleado)
			{	
				// $notas_empleado = trim(preg_replace('/\s\s+/', ' ', $empleado['notas_empleado']));
				// $notas_empleado = str_replace('\n',"\n",$notas_empleado);
				$breaks = array("\r\n", "\n", "\r");
				$notas_empleado = str_replace($breaks, "", $empleado['notas_empleado']);

				// $notas_empleado = mysql_real_escape_string($notas_empleado);
				$data[]=array(

				// "0"=>$empleado['id_empleado'],
				"0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$empleado['id_empleado'].',\''.$empleado['nombre_empleado'].'\',\''.$notas_empleado.'\')"><span class="fa fa-plus"></span></button>',
				"1"=>$empleado['nombre_empleado'],
				"2"=>$empleado['categoria'],
				//"3"=>$empleado['categoria'],
				"3"=>$empleado['notas_empleado']
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


}



?>

