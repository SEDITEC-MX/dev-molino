<?php
require_once "../config/sesion.php";
require_once "../modelos/Indicadores.php";

Sesion::existeSesion();

$indicadores= new Indicadores();

//Nos traemos el rango de fechas
$fecha_inicio=isset($_POST["fecha_inicio"])? limpiarCadena($_POST["fecha_inicio"]):"";
$fecha_fin=isset($_POST["fecha_fin"])? limpiarCadena($_POST["fecha_fin"]):"";


//Pasamos el rango de fechas a tipo fecha
// $fecha_inicio = '2023-01-01';
// $fecha_fin = '2023-03-01';


//Pasamos el rango de fechas a tipo fecha
$fecha_inicio_aux = new DateTime($fecha_inicio);
$fecha_fin_aux = new DateTime($fecha_fin);

//Obtenemos la diferencia de fechas
$diferencia = $fecha_inicio_aux ->diff($fecha_fin_aux);

//Guardamos la diferencia en variables
$anios = $diferencia->y;
$meses = $diferencia->m;
$dias = $diferencia->d;

switch ($_GET["op"])

{
	case 'actualizar' :

    //Condiciones para tabla
     if ($fecha_fin >= $fecha_inicio){

        // Todos los ingresos
        $mostrarTodoIngresos_aux = $indicadores->mostrarTodoIngresos($fecha_inicio, $fecha_fin);
        $mostrarTodoIngresos = array_values($mostrarTodoIngresos_aux)[0];
     
        $mostrarIngresosHacienda_aux = $indicadores->mostrarIngresosHacienda($fecha_inicio, $fecha_fin);
        $mostrarIngresosHacienda = array_values($mostrarIngresosHacienda_aux)[0];
     
        $mostrarIngresosBanqueta_aux = $indicadores->mostrarIngresosBanqueta($fecha_inicio, $fecha_fin);
        $mostrarIngresosBanqueta = array_values($mostrarIngresosBanqueta_aux)[0];
     
        $mostrarIngresosHotel_aux = $indicadores->mostrarIngresosHotel($fecha_inicio, $fecha_fin);
        $mostrarIngresosHotel = array_values($mostrarIngresosHotel_aux)[0];
      
        $mostrarIngresosMobiliario_aux = $indicadores->mostrarIngresosMobiliario($fecha_inicio, $fecha_fin);
        $mostrarIngresosMobiliario = array_values($mostrarIngresosMobiliario_aux)[0];
      
        $mostrarIngresosProveedor_aux = $indicadores->mostrarIngresosProveedor($fecha_inicio, $fecha_fin);
        $mostrarIngresosProveedor = array_values($mostrarIngresosProveedor_aux)[0];
    
        $mostrarIngresosCasa_aux = $indicadores->mostrarIngresosCasa($fecha_inicio, $fecha_fin);
        $mostrarIngresosCasa = array_values($mostrarIngresosCasa_aux)[0];
      
      
        // Todos los pagos
        $mostrarTodoPagos_aux = $indicadores->mostrarPagos($fecha_inicio, $fecha_fin);
        $mostrarTodoPagos = array_values($mostrarTodoPagos_aux)[0];
      
        $mostrarPagosHacienda_aux = $indicadores->mostrarPagosHacienda($fecha_inicio, $fecha_fin);
        $mostrarPagosHacienda = array_values($mostrarPagosHacienda_aux)[0];
      
        $mostrarPagosBanquete_aux = $indicadores->mostrarPagosBanquete($fecha_inicio, $fecha_fin);
        $mostrarPagosBanquete = array_values($mostrarPagosBanquete_aux)[0];
      
        $mostrarPagosHotel_aux = $indicadores->mostrarPagosHotel($fecha_inicio, $fecha_fin);
        $mostrarPagosHotel = array_values($mostrarPagosHotel_aux)[0];
    
        $mostrarPagosMobi_aux = $indicadores->mostrarPagosMobi($fecha_inicio, $fecha_fin);
        $mostrarPagosMobi = array_values($mostrarPagosMobi_aux)[0];
      
        $mostrarPagosProveedor_aux = $indicadores->mostrarPagosProveedor($fecha_inicio, $fecha_fin);
        $mostrarPagosProveedor = array_values($mostrarPagosProveedor_aux)[0];
      
        $mostrarPagosCasa_aux = $indicadores->mostrarPagosCasa($fecha_inicio, $fecha_fin);
        $mostrarPagosCasa = array_values($mostrarPagosCasa_aux)[0];

        // Todas las utilidades
        $utilidadTotal = $mostrarTodoIngresos - $mostrarTodoPagos;
        $utilidadHacienda = $mostrarIngresosHacienda - $mostrarPagosHacienda;
        $utilidadBanquete = $mostrarIngresosBanqueta - $mostrarPagosBanquete;
        $utilidadHotel = $mostrarIngresosHotel - $mostrarPagosHotel;
        $utilidadMobi = $mostrarIngresosMobiliario - $mostrarPagosMobi;
        $utlidadPro = $mostrarIngresosProveedor - $mostrarPagosProveedor;
        $utlidadCasa = $mostrarIngresosCasa - $mostrarPagosCasa;

       
        // Se manda todo a un array
        $array = array($mostrarTodoIngresos, $mostrarIngresosHacienda,$mostrarIngresosBanqueta,$mostrarIngresosHotel, $mostrarIngresosMobiliario,
        $mostrarIngresosProveedor, $mostrarIngresosCasa, $mostrarTodoPagos, $mostrarPagosHacienda, $mostrarPagosBanquete, $mostrarPagosHotel, 
        $mostrarPagosMobi, $mostrarPagosProveedor, $mostrarPagosCasa, $utilidadTotal, $utilidadHacienda, $utilidadBanquete, $utilidadHotel,
        $utilidadMobi, $utlidadPro, $utlidadCasa );

      $json = json_encode($array);
      
      echo $json;

    } else {

      http_response_code(500);
      echo "La fecha inicio no puede ser posterior o igual a la fecha fin";

    }
        
	break;

  case 'graficar' :
    // echo "1";
    //Conciones para graficar
    if ($fecha_fin >= $fecha_inicio){
      
        //Se define el periodo de tiempo a agrupar
        if ($anios >= 1 OR $meses >= 3) {
          //Se grafica por meses

           //Se obtienen los ingresos por mes
           $indicadorTotal = $indicadores->indicadorTotalIngresosMes($fecha_inicio, $fecha_fin);
           $data= Array();
           $data['egresos'] = Array();
           $data['ingresos'] = Array();
           while ($reg=$indicadorTotal->fetch_object()){
             $arr = array(
               "anio"=>$reg->anio,
               "mes"=>$reg->mes,
               "tiempo"=>$reg->tiempo,
               "total"=>$reg->total
             );
             array_push($data['ingresos'], $arr);
           }

           //Se obtienen los egresos por mes
           $indicadorTotal1 = $indicadores->indicadorTotalEgresosMes($fecha_inicio, $fecha_fin);
           while ($reg=$indicadorTotal1->fetch_object()){
             $arr = array(
               "anio"=>$reg->anio,
               "mes"=>$reg->mes,
               "tiempo"=>$reg->tiempo,
               "total"=>$reg->total
             );
             array_push($data['egresos'], $arr);

           }
           // print_r($data);
           echo json_encode($data);

        } else {
          
          //Se grafica por semanas

           //Se obtienen los ingresos por semana
            $indicadorTotal = $indicadores->indicadorTotalIngresosSemanas($fecha_inicio, $fecha_fin);
            $data= Array();
            $data['egresos'] = Array();
            $data['ingresos'] = Array();
            while ($reg=$indicadorTotal->fetch_object()){
              $arr = array(
                "anio"=>$reg->anio,
                "semanas"=>$reg->semanas,
                "tiempo"=>$reg->tiempo,

                "total"=>$reg->total
              );
              array_push($data['ingresos'], $arr);
            }

            //Se obtienen los egresos por semana
            $indicadorTotal1 = $indicadores->indicadorTotalEgresosSemanas($fecha_inicio, $fecha_fin);
            while ($reg=$indicadorTotal1->fetch_object()){
              $arr = array(
                "anio"=>$reg->anio,
                "semanas"=>$reg->semanas,
                "tiempo"=>$reg->tiempo,

                "total"=>$reg->total
              );
              array_push($data['egresos'], $arr);

            }
            // print_r($data);
            echo json_encode($data);
        }
    }

    break;
 

}


?>

