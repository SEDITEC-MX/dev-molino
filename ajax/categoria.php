<?php
require_once "../config/sesion.php";
require_once "../modelos/Categoria.php";




Sesion::existeSesion();





$categoria= new Categoria();



$id_categoria_servicio=isset($_POST["id_categoria_servicio"])? limpiarCadena($_POST["id_categoria_servicio"]):"";

$nombre_categoria_servicio=isset($_POST["nombre_categoria_servicio"])? limpiarCadena($_POST["nombre_categoria_servicio"]):"";

$condicio_categoria_servicio=isset($_POST["condicio_categoria_servicio"])? limpiarCadena($_POST["condicio_categoria_servicio"]):"";



switch ($_GET["op"])

{

	case 'guardaryeditar' :

		if (empty($id_categoria_servicio))

		{

				$respuesta = $categoria->insertar($nombre_categoria_servicio);

				echo $respuesta ? "Categoría Registrada" : "Categoría no se pudo registrar";

		}

		elseif ($condicio_categoria_servicio=="1") {

				$respuesta = $categoria->editar($id_categoria_servicio,$nombre_categoria_servicio);

				echo $respuesta ? "Categoría Actualizada" : "Categoría no se pudo actualizar";

		}

		else {

			echo $respuesta="Categoría no se pudo actualizar porque está desactivada";

		} 

	break;

	

	

	case 'desactivar' :

		$respuesta = $categoria->desactivar($id_categoria_servicio);

		echo $respuesta ? "Categoría desactivada" : "Categoría no se pudo desactivar";			

	break;

	

	

	case 'activar' :

		$respuesta = $categoria->activar($id_categoria_servicio);

		echo $respuesta ? "Categoría activada" : "Categoría no se pudo activar";		

	break;

	

	

	case 'mostrar' :

		$respuesta = $categoria->mostrar($id_categoria_servicio);

		echo json_encode($respuesta);

	break;

	

	

	case 'listar' :

		$respuesta = $categoria->listar();

		$data= Array();

		

		while ($reg=$respuesta->fetch_object())

		{

			$data[]=array(

			"0"=>($reg->condicio_categoria_servicio)?'<botton class="btn btn-warning" onclick="mostrar('.$reg->id_categoria_servicio.')"><i class="fas fa-pencil-alt"></i></botton>'.

			' <botton class="btn btn-danger" onclick="desactivar('.$reg->id_categoria_servicio.')"><i class="fa fa-close"></i></botton>':'<botton class="btn btn-warning" onclick="mostrar('.$reg->id_categoria_servicio.')"><i class="fas fa-pencil-alt"></i></botton>'.

			' <botton class="btn btn-primary" onclick="activar('.$reg->id_categoria_servicio.')"><i class="fa fa-check"></i></botton>',

			"1"=>$reg->nombre_categoria_servicio,

			"2"=>($reg->condicio_categoria_servicio)?'<span class="label bg-green">Activado</span>':

 				'<span class="label bg-red">Desactivado</span>'

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

