<?php
require_once "../config/sesion.php";
require_once "../modelos/DetalleEvento.php";
require_once "../config/mail.php";
require_once "funciones_comunes.php";


Sesion::existeSesion();


$detalle_evento = new DetalleEvento();




switch ($_GET["op"])
{
	case 'cobros':
        $id_evento = $_GET['id'];
		$respuesta = $detalle_evento->selectCobrosPorMetodo($id_evento);
        $totalCobros = 0;
        foreach($respuesta as $dato){
            $totalCobros += $dato['monto'];
        }
        
        for($i=0;$i<sizeof($respuesta);$i++){
            $respuesta[$i]['total'] = $totalCobros;
        }
        echo json_encode($respuesta);
	break;


    case 'servicios':
        $id_evento = $_GET['id'];
		$respuesta = $detalle_evento->selectServiciosPorCategoria($id_evento);
        $totalServicios = 0;
        foreach($respuesta as $dato){
            $totalServicios += $dato['costoServicio'];
        }
        
        for($i=0;$i<sizeof($respuesta);$i++){
            $respuesta[$i]['total'] = $totalServicios;
        }
        echo json_encode($respuesta);
	break;

    case 'pagos':
        $id_evento = $_GET['id'];
		$respuesta = $detalle_evento->selectPagosPorCategoria($id_evento);
        $totalPagos = 0;
        foreach($respuesta as $dato){
            $totalPagos += $dato['monto'];
        }
        
        for($i=0;$i<sizeof($respuesta);$i++){
            $respuesta[$i]['total'] = $totalPagos;
        }
        echo json_encode($respuesta);
    break;

    case 'listarCategorias':
        $respuesta = $detalle_evento->listarCategorias();
        echo json_encode($respuesta);
    break;
    

}
?>
