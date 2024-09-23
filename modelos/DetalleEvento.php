<?php
// Conexión a la BD
require "../config/conecPDO.php";

Class DetalleEvento
{
	public function __construct()
	{

	}


	public function selectServiciosPorCategoria($id_evento){
		$arregloDatos = array($id_evento);
		$sql="SELECT categoria_servicio.nombre_categoria_servicio AS categoria, 
		SUM(detalle_evento.cantidad_detalle_evento * detalle_evento.precio_detalle_evento) AS costoServicio, 
		detalle_evento.id_servicio 
		FROM detalle_evento 
		INNER JOIN catalogo_servicio 
		ON detalle_evento.id_servicio = catalogo_servicio.id_servicio 
		INNER JOIN categoria_servicio 
		ON catalogo_servicio.id_categoria_servicio = categoria_servicio.id_categoria_servicio
		WHERE id_evento = ?
		GROUP BY categoria_servicio.nombre_categoria_servicio";
		return ejecutarSelect($sql, $arregloDatos);
	}

	public function selectCobrosPorMetodo($id_evento) //Checa la suma del evento
	{
		$arregloDatos = array($id_evento);
		$sql="SELECT SUM(cobros.monto_cobro) AS monto, metodo_cobro
		FROM cobros
		WHERE id_evento = ?
		GROUP BY cobros.metodo_cobro
		ORDER BY cobros.metodo_cobro";
		return ejecutarSelect($sql, $arregloDatos);
	}

	public function selectPagosPorMetodo($id_evento){
		$arregloDatos = array($id_evento);
		$sql="SELECT  SUM(monto_pago) AS monto,  metodo_pago
		FROM pagos
		WHERE id_evento = ?
		GROUP BY metodo_pago
		ORDER BY metodo_pago";
		return ejecutarSelect($sql, $arregloDatos);
	}

	public function selectPagosPorCategoria($id_evento){
		$arregloDatos = array($id_evento);
		$sql="SELECT COALESCE(servicio.nombre_categoria_servicio, 'No definido') AS categoria, 
		SUM(pagos.monto_pago) AS monto
		FROM pagos
		LEFT JOIN categoria_servicio AS servicio 
		ON servicio.id_categoria_servicio = pagos.id_categoria_servicio 
		WHERE id_evento = ?
		GROUP BY servicio.nombre_categoria_servicio;";
		return ejecutarSelect($sql, $arregloDatos);
	}

	public function listarCategorias(){
		$sql="SELECT * FROM categoria_servicio";
		return ejecutarSelect($sql);
	}
}

?>
