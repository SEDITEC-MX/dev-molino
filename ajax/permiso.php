<?php
require_once "../config/sesion.php";
require_once "../modelos/Permiso.php";



Sesion::existeSesion();

$permiso= new Permiso();



switch ($_GET["op"])

{

	case 'listar' :

		$respuesta = $permiso->listar();

		$data= Array();

		

		while ($reg=$respuesta->fetch_object())

		{

			$data[]=array(

			"0"=>$reg->nombre_permiso

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

