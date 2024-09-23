<?php

require_once "../config/sesion.php";
require_once "../modelos/FechaEvento.php";



Sesion::existeSesion();


$fechaEvento= new FechaEvento();


switch ($_GET["op"]){

	
	case 'revisarFecha':
		$fechaEvento=new FechaEvento();
		$fecha = $_POST['fecha'];
		$respuesta = $fechaEvento->selectEventoExistenteEnFecha($fecha);
		if ($respuesta){
			$resp = array("estatus" => 1, "nombre_evento" => $respuesta['nombre_evento']);
			echo json_encode($resp);
		} else{
			$resp = array("estatus" => 0, "nombre_evento" => "");
			echo json_encode($resp);
		}
		return;

	case 'verEventosEnFecha':
		$fechaEvento=new FechaEvento();
		$fecha = $_GET['fecha'];
		$respuesta = $fechaEvento->selectEventosEnFecha($fecha);
		echo json_encode($respuesta);
		return;

}

?>

