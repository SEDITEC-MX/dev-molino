<?php
require_once "../config/sesion.php";
require_once "../modelos/Servicio.php";




Sesion::existeSesion();



// Creamos un nuevo objeto

$servicio= new Servicio();



// Nos traemos las variables del método POST del JS

$id_servicio=isset($_POST["id_servicio"])? limpiarCadena($_POST["id_servicio"]):"";

$id_categoria_servicio=isset($_POST["id_categoria_servicio"])? limpiarCadena($_POST["id_categoria_servicio"]):"";

$nombre_servicio=isset($_POST["nombre_servicio"])? limpiarCadena($_POST["nombre_servicio"]):"";

$descripcion_servicio=isset($_POST["descripcion_servicio"])? limpiarCadena($_POST["descripcion_servicio"]):"";

$imagen_servicio=isset($_POST["imagen_servicio"])? limpiarCadena($_POST["imagen_servicio"]):"";

$condicion_servicio=isset($_POST["condicion_servicio"])? limpiarCadena($_POST["condicion_servicio"]):"";



switch ($_GET["op"])

{

	case 'guardaryeditar' :

		if (!file_exists($_FILES['imagen_servicio']['tmp_name']) || !is_uploaded_file($_FILES['imagen_servicio']['tmp_name']))

		{

			$imagen_servicio=$_POST["imagenactual"];

		}

		else 

		{

			$ext = explode(".", $_FILES["imagen_servicio"]["name"]);

			if ($_FILES['imagen_servicio']['type'] == "image/jpg" || $_FILES['imagen_servicio']['type'] == "image/jpeg" || $_FILES['imagen_servicio']['type'] == "image/png")

			{

				$imagen_servicio = round(microtime(true)) . '.' . end($ext);

				move_uploaded_file($_FILES["imagen_servicio"]["tmp_name"], "../files/servicios/" . $imagen_servicio);

			}

		}

	

		if (empty($id_servicio))

		{

				$respuesta = $servicio->insertar($id_categoria_servicio,$nombre_servicio,$descripcion_servicio,$imagen_servicio);

				echo $respuesta ? "Servicio Registrado" : "Servicio no se pudo registrar";

		}

		elseif ($condicion_servicio=="1") {

				$respuesta = $servicio->editar($id_servicio,$id_categoria_servicio,$nombre_servicio,$descripcion_servicio,$imagen_servicio);

				echo $respuesta ? "Servicio Actualizado" : "Servicio no se pudo actualizar";

		}

		else {

			echo $respuesta="Servicio no se pudo actualizar porque está desactivado";

		} 

	break;

	



	case 'desactivar' :

		$respuesta = $servicio->desactivar($id_servicio);

		echo $respuesta ? "Servicio desactivado" : "Servicio no se pudo desactivar";			

	break;

	

	

	case 'activar' :

		$respuesta = $servicio->activar($id_servicio);

		echo $respuesta ? "Servicio activado" : "Servicio no se pudo activar";		

	break;

	

	

	case 'mostrar' :

		$respuesta = $servicio->mostrar($id_servicio);

		echo json_encode($respuesta);

	break;

	

	

	case 'listar' :

		$respuesta = $servicio->listar();

		$data= Array();

		

		while ($reg=$respuesta->fetch_object())

		{

			$data[]=array(

			"0"=>($reg->condicion_servicio)?'<botton class="btn btn-warning" onclick="mostrar('.$reg->id_servicio.')"><i class="fas fa-pencil-alt"></i></botton>'.

			' <botton class="btn btn-danger" onclick="desactivar('.$reg->id_servicio.')"><i class="fa fa-close"></i></botton>':

			'<botton class="btn btn-warning" onclick="mostrar('.$reg->id_servicio.')"><i class="fas fa-pencil-alt"></i></botton>'.

			' <botton class="btn btn-primary" onclick="activar('.$reg->id_servicio.')"><i class="fa fa-check"></i></botton>',

			"1"=>$reg->categoria,

			"2"=>$reg->nombre_servicio,

			"3"=>$reg->descripcion_servicio,

			"4"=>($reg->imagen_servicio)?"<img src='../files/servicios/".$reg->imagen_servicio."' height='50px' width='50px' >": 'Sin imagen' ,

			"5"=>($reg->condicion_servicio)?'<span class="label bg-green">Activado</span>':

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

	

	case 'selectCategoria' :

		require_once "../modelos/Categoria.php";

		$categoria = new Categoria();

				

		$respuesta = $categoria->select();

		

		while ($reg = $respuesta->fetch_object())

				{

					echo '<option value=' . $reg->id_categoria_servicio . '>' . $reg->nombre_categoria_servicio . '</option>';

				}

		

	break;

	

}



?>

