<?php
require_once "../config/sesion.php";
require_once "../modelos/Proveedores.php";




Sesion::existeSesion();



// Creamos un nuevo objeto

$proveedor = new Proveedor();



// Nos traemos las variables del método POST del JS

$id_proveedor=isset($_POST["id_proveedor"])? limpiarCadena($_POST["id_proveedor"]):"";

$nombre_proveedor=isset($_POST["nombre_proveedor"])? limpiarCadena($_POST["nombre_proveedor"]):"";



switch ($_GET["op"])

{

	case 'guardaryeditar' :

	

		if (empty($id_proveedor))

		{

			$respuesta = $proveedor->insertar($nombre_proveedor);

			echo $respuesta ? "Proveedor Registrado" : "El Proveedor no se pudo registrar";

		}

		else {

			$respuesta = $proveedor->editar($nombre_proveedor, $id_proveedor);

			echo $respuesta ? "Proveedor Actualizado" : "Servicio no se pudo actualizar";

		}

		

	break;

	

	



	case 'desactivar' :

		$respuesta = $proveedor->desactivar($id_proveedor);

		echo $respuesta ? "Proveedor desactivado" : "El proveedor no se pudo desactivar";			

	break;



	case 'activar' :

		$respuesta = $proveedor->activar($id_proveedor);

		echo $respuesta ? "Proveedor activado" : "El proveedor no se pudo activar";			

	break;

		

	case 'mostrar' :

		$respuesta = $proveedor->mostrar($id_proveedor);

		echo json_encode($respuesta);

	break;

	

	case 'listar' :

		$respuesta = $proveedor->listar();

		$data= Array();

		// if (empty($data)){



		// }



		if (!empty(($respuesta))){



			foreach ($respuesta as $proveedor)

			{



				$data[]=array(

				"0"=>$proveedor['condicion_proveedor']== 1 ? '<botton class="btn btn-warning" onclick="mostrar('.$proveedor['id_proveedor'].')"><i class="fas fa-pencil-alt"></i></botton>'.

				' <botton class="btn btn-danger" onclick="desactivar('.$proveedor['id_proveedor'].')"><i class="fa fa-close"></i></botton>':'<botton class="btn btn-warning" onclick="mostrar('.$proveedor['id_proveedor'].')"><i class="fas fa-pencil-alt"></i></botton>'.

				' <botton class="btn btn-primary" onclick="activar('.$proveedor['id_proveedor'].')"><i class="fa fa-check"></i></botton>',

				"1"=>$proveedor['nombre_proveedor'],

				"2"=>$proveedor['condicion_proveedor'] == 1 ?'<span class="label bg-green">Activado</span>':

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

	

}



?>

