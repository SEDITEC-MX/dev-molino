<?php

// Conexión a la BD
require "../config/conec.php";


Class Indicadores
{
	public function __construct()
	{
		
	}
	
	//TODOS LOS INGRESOS (Servicios Contratados) en un lapso de tiempo
	public function mostrarTodoIngresos($fecha_inicio, $fecha_fin)
	{
		$sql="SELECT SUM(cantidad_detalle_evento * precio_detalle_evento)
		FROM detalle_evento
		WHERE detalle_evento.id_evento in 
		(SELECT id_evento FROM evento WHERE fecha_evento BETWEEN '$fecha_inicio' AND '$fecha_fin')";
		return ejecutarConsultaSimpleFila($sql);
	}

	//TODOS LOS INGRESOS (Servicios Contratados) en un lapso de tiempo de la CATEGORIAS 
	public function mostrarIngresosHacienda($fecha_inicio, $fecha_fin)
	{
		$sql="SELECT SUM(cantidad_detalle_evento * precio_detalle_evento)
		FROM detalle_evento
		WHERE detalle_evento.id_evento in (SELECT id_evento FROM evento WHERE fecha_evento BETWEEN '$fecha_inicio' AND '$fecha_fin')
		AND id_servicio in (SELECT id_servicio FROM catalogo_servicio WHERE id_categoria_servicio = 11)";
		return ejecutarConsultaSimpleFila($sql);
	}
	
	public function mostrarIngresosBanqueta($fecha_inicio, $fecha_fin)
	{
		$sql="SELECT SUM(cantidad_detalle_evento * precio_detalle_evento)
		FROM detalle_evento
		WHERE detalle_evento.id_evento in (SELECT id_evento FROM evento WHERE fecha_evento BETWEEN '$fecha_inicio' AND '$fecha_fin')
		AND id_servicio in (SELECT id_servicio FROM catalogo_servicio WHERE id_categoria_servicio = 12)";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function mostrarIngresosHotel($fecha_inicio, $fecha_fin)
	{
		$sql="SELECT SUM(cantidad_detalle_evento * precio_detalle_evento)
		FROM detalle_evento
		WHERE detalle_evento.id_evento in (SELECT id_evento FROM evento WHERE fecha_evento BETWEEN '$fecha_inicio' AND '$fecha_fin')
		AND id_servicio in (SELECT id_servicio FROM catalogo_servicio WHERE id_categoria_servicio = 13)";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function mostrarIngresosMobiliario($fecha_inicio, $fecha_fin)
	{
		$sql="SELECT SUM(cantidad_detalle_evento * precio_detalle_evento)
		FROM detalle_evento
		WHERE detalle_evento.id_evento in (SELECT id_evento FROM evento WHERE fecha_evento BETWEEN '$fecha_inicio' AND '$fecha_fin')
		AND id_servicio in (SELECT id_servicio FROM catalogo_servicio WHERE id_categoria_servicio = 14)";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function mostrarIngresosProveedor($fecha_inicio, $fecha_fin)
	{
		$sql="SELECT SUM(cantidad_detalle_evento * precio_detalle_evento)
		FROM detalle_evento
		WHERE detalle_evento.id_evento in (SELECT id_evento FROM evento WHERE fecha_evento BETWEEN '$fecha_inicio' AND '$fecha_fin')
		AND id_servicio in (SELECT id_servicio FROM catalogo_servicio WHERE id_categoria_servicio = 16)";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function mostrarIngresosCasa($fecha_inicio, $fecha_fin)
	{
		$sql="SELECT SUM(cantidad_detalle_evento * precio_detalle_evento)
		FROM detalle_evento
		WHERE detalle_evento.id_evento in (SELECT id_evento FROM evento WHERE fecha_evento BETWEEN '$fecha_inicio' AND '$fecha_fin')
		AND id_servicio in (SELECT id_servicio FROM catalogo_servicio WHERE id_categoria_servicio = 17)";
		return ejecutarConsultaSimpleFila($sql);
	}

	//TODOS LOS PAGOS en un lapso de tiempo
	public function mostrarPagos($fecha_inicio, $fecha_fin)
	{
		$sql="SELECT SUM(monto_pago)
		FROM pagos 
		WHERE fecha_pago BETWEEN '$fecha_inicio' AND '$fecha_fin'";
		return ejecutarConsultaSimpleFila($sql);
	}	


	//TODOS LOS PAGOS en un lapso de tiempo de la CATEGORIA
	public function mostrarPagosHacienda($fecha_inicio, $fecha_fin)
	{
		$sql="SELECT SUM(pagos.monto_pago) AS monto 
		FROM pagos 
		LEFT JOIN categoria_servicio ON categoria_servicio.id_categoria_servicio = pagos.id_categoria_servicio 
		WHERE categoria_servicio.id_categoria_servicio = 11 AND
		 fecha_pago BETWEEN '$fecha_inicio' AND '$fecha_fin'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function mostrarPagosBanquete($fecha_inicio, $fecha_fin)
	{
		$sql="SELECT SUM(pagos.monto_pago) AS monto 
		FROM pagos 
		LEFT JOIN categoria_servicio ON categoria_servicio.id_categoria_servicio = pagos.id_categoria_servicio 
		WHERE categoria_servicio.id_categoria_servicio = 12 AND
		 fecha_pago BETWEEN '$fecha_inicio' AND '$fecha_fin'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function mostrarPagosHotel($fecha_inicio, $fecha_fin)
	{
		$sql="SELECT SUM(pagos.monto_pago) AS monto 
		FROM pagos 
		LEFT JOIN categoria_servicio ON categoria_servicio.id_categoria_servicio = pagos.id_categoria_servicio 
		WHERE categoria_servicio.id_categoria_servicio = 13 AND
		 fecha_pago BETWEEN '$fecha_inicio' AND '$fecha_fin'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function mostrarPagosMobi($fecha_inicio, $fecha_fin)
	{
		$sql="SELECT SUM(pagos.monto_pago) AS monto 
		FROM pagos 
		LEFT JOIN categoria_servicio ON categoria_servicio.id_categoria_servicio = pagos.id_categoria_servicio 
		WHERE categoria_servicio.id_categoria_servicio = 14 AND
		 fecha_pago BETWEEN '$fecha_inicio' AND '$fecha_fin'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function mostrarPagosProveedor($fecha_inicio, $fecha_fin)
	{
		$sql="SELECT SUM(pagos.monto_pago) AS monto 
		FROM pagos 
		LEFT JOIN categoria_servicio ON categoria_servicio.id_categoria_servicio = pagos.id_categoria_servicio 
		WHERE categoria_servicio.id_categoria_servicio = 16 AND
		 fecha_pago BETWEEN '$fecha_inicio' AND '$fecha_fin'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function mostrarPagosCasa($fecha_inicio, $fecha_fin)
	{
		$sql="SELECT SUM(pagos.monto_pago) AS monto 
		FROM pagos 
		LEFT JOIN categoria_servicio ON categoria_servicio.id_categoria_servicio = pagos.id_categoria_servicio 
		WHERE categoria_servicio.id_categoria_servicio = 17 AND
		 fecha_pago BETWEEN '$fecha_inicio' AND '$fecha_fin'";
		return ejecutarConsultaSimpleFila($sql);
	}


	public function actualizarIngresos($fecha_inicio, $fecha_fin)
	{
		$sql="SELECT SUM(pagos.monto_pago) AS monto 
		FROM pagos 
		LEFT JOIN categoria_servicio ON categoria_servicio.id_categoria_servicio = pagos.id_categoria_servicio 
		WHERE categoria_servicio.id_categoria_servicio = 17 AND
		 fecha_pago BETWEEN '$fecha_inicio' AND '$fecha_fin'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Queries para graficar
	
	//Para agrupar por semanas
	public function indicadorTotalIngresosSemanas($fecha_inicio, $fecha_fin)
	{
		$sql="SELECT 
		YEAR(evento.fecha_evento) as anio,
		WEEK(evento.fecha_evento) as semanas,
		CONCAT(YEAR(evento.fecha_evento), '/', WEEK(evento.fecha_evento)) as tiempo, 
		SUM(detalle_evento.cantidad_detalle_evento * detalle_evento.precio_detalle_evento)  as total
		FROM detalle_evento
		LEFT JOIN evento ON detalle_evento.id_evento = evento.id_evento
		WHERE detalle_evento.id_evento in 
			(SELECT id_evento FROM evento WHERE fecha_evento BETWEEN '$fecha_inicio' AND '$fecha_fin')
		GROUP BY semanas
		ORDER BY anio, semanas;";
		return ejecutarConsulta($sql);
	}

	public function indicadorTotalEgresosSemanas($fecha_inicio, $fecha_fin)
	{
		$sql="SELECT 
		YEAR(fecha_pago) as anio, 
		WEEK(fecha_pago) as semanas, 
		CONCAT(YEAR(fecha_pago), '/', WEEK(fecha_pago)) as tiempo, 
		SUM(monto_pago) as total
		FROM pagos 
		WHERE fecha_pago BETWEEN '$fecha_inicio' AND '$fecha_fin'
		GROUP BY semanas
		ORDER BY anio, semanas";
		return ejecutarConsulta($sql);
	}

	//Para agrupar por meses
	public function indicadorTotalIngresosMes($fecha_inicio, $fecha_fin)
	{
		$sql="SELECT 
		YEAR(evento.fecha_evento) as anio,
		MONTH(evento.fecha_evento) as mes,
		CONCAT(YEAR(evento.fecha_evento), '/', MONTH(evento.fecha_evento)) as tiempo, 
		SUM(detalle_evento.cantidad_detalle_evento * detalle_evento.precio_detalle_evento)  as total
		FROM detalle_evento
		LEFT JOIN evento ON detalle_evento.id_evento = evento.id_evento
		WHERE detalle_evento.id_evento in 
			(SELECT id_evento FROM evento WHERE fecha_evento BETWEEN '$fecha_inicio' AND '$fecha_fin')
		GROUP BY mes
		ORDER BY anio, mes;";
		return ejecutarConsulta($sql);
	}

	public function indicadorTotalEgresosMes($fecha_inicio, $fecha_fin)
	{
		$sql="SELECT 
		YEAR(fecha_pago) as anio, 
		MONTH(fecha_pago) as mes, 
		CONCAT(YEAR(fecha_pago), '/', MONTH(fecha_pago)) as tiempo, 
		SUM(monto_pago) as total
		FROM pagos 
		WHERE fecha_pago BETWEEN '$fecha_inicio' AND '$fecha_fin'
		GROUP BY mes
		ORDER BY anio, mes;";
		return ejecutarConsulta($sql);
	}

		
}

?>