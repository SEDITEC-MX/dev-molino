<?php
// Conexión a la BD
// require "../config/conec.php";
require "../config/conecPDO.php";

Class Registro_pago
{
	public function __construct()
	{

	}


/*
Columnas de la tabla 'pagos'
id_pago int(11)
id_evento int(11)
id_proveedor int(11)
fecha_pago datetime(6)
monto_pago decimal(15,2)
metodo_pago varchar(45)
email_pago varchar(200)

*/

	public function insertar(
		$id_evento,
		$id_proveedor,
		$fecha_pago,
		$monto_pago,
		$metodo_pago,
		$email_pago,
		$notas_pago,
		$id_categoria_servicio,
		$id_usuario
	)
	{
		$sql="INSERT INTO pagos (id_evento, id_proveedor, fecha_pago, monto_pago, metodo_pago, 
		email_pago, notas_pago, id_categoria_servicio,id_usuario) 
		VALUES (
		'$id_evento', '$id_proveedor', '$fecha_pago', '$monto_pago', 
			'$metodo_pago', '$email_pago', '$notas_pago', '$id_categoria_servicio', '$id_usuario'
		)";

		return ejecutarConsulta($sql);

	}

	public function insertarCaja(
		$id_evento,
		$id_proveedor,
		$fecha_pago,
		$monto_pago,
		$metodo_pago,
		$email_pago,
		$notas_pago,
		$id_categoria_servicio,
		$id_usuario
	)
	{
		$sql="INSERT INTO pagos (id_evento, id_proveedor, fecha_pago, monto_pago, metodo_pago, 
		email_pago, notas_pago, id_categoria_servicio, id_usuario) 
		VALUES (
		'$id_evento', '$id_proveedor', '$fecha_pago', '$monto_pago', 
			'$metodo_pago',  '$email_pago', '$notas_pago', '$id_categoria_servicio', '$id_usuario'
		)";

		$id_pago_new = ejecutarConsulta_retornarID($sql);

		$nombre_archivo = 'prueba';

		$sql_comprobante = "INSERT INTO archivos (
							nombreArchivo, 
							categoriaArchivo, 
							fechaSubida, 
							id_pago
							) VALUES (
							'$nombre_archivo',
							'comprobante',
							'$fecha_pago',
							'$id_pago_new')";
		
		return ejecutarConsulta($sql_comprobante);
	}


	public function verificarTotalEventos($id_evento) //Checa la suma del evento
	{
		$arregloDatos = array($id_evento);
		$sql="SELECT SUM(detalle_evento.cantidad_detalle_evento * detalle_evento.precio_detalle_evento) AS total
			FROM evento
			LEFT JOIN detalle_evento ON evento.id_evento = detalle_evento.id_evento
			WHERE evento.id_evento = ?
			GROUP BY evento.id_evento";
		return ejecutarSelect($sql, $arregloDatos);
	}

	public function verificarTotalPagos($id_evento) //Checa la suma de los pagos
	{
		$arregloDatos = array($id_evento);
		$sql="SELECT SUM(pagos.monto_pago) AS cobrado
			FROM evento
			LEFT JOIN pagos ON evento.id_evento = pagos.id_evento
			WHERE evento.id_evento = ?
			GROUP BY evento.id_evento";
		return ejecutarSelect($sql, $arregloDatos);
	}

	public function eliminar($id_pago)
	{
		$arregloDatos = array($id_pago);
		$sql="DELETE FROM pagos WHERE id_pago = ?";
		return ejecutarConsulta($sql, $arregloDatos);
	}

	public function mostrar($id_evento)
	{
		$sql="SELECT 
		id_evento, 
		DATE_FORMAT(fecha_evento, '%Y-%m-%d') as fecha_evento, 
		nombre_evento, 
		tipo_evento, 
		cotizacion_evento, 
		notas_evento, 
		total_evento, 
		invitados_evento, 
		estado_evento 
		FROM evento
		WHERE id_evento='$id_evento'";

		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarDetalle($id_evento)
	{
		$sql="SELECT 
		de.id_evento,
		de.id_servicio,
		s.nombre_servicio,
		de.cantidad_detalle_evento,
		de.precio_detalle_evento 
		FROM detalle_evento de 
		inner join catalogo_servicio s on de.id_servicio=s.id_servicio
		where de.id_evento='$id_evento'";
		return ejecutarConsulta($sql);
	}

	// Listar eventos
	public function listarEventos()
	{
		$sql="SELECT
		id_evento,
        DATE_FORMAT(fecha_evento, '%d/%m/%Y') as fecha_evento,
        nombre_evento,
		invitados_evento,
        tipo_evento,
        cotizacion_evento,
        notas_evento,
        total_evento,
        estado_evento
		FROM evento
		ORDER BY TIMESTAMP(fecha_evento);
		";
		return ejecutarConsulta($sql);
	}

	// Listar nombre eventos
	public function listarNombreEventos()
	{
		$sql="SELECT id_evento, nombre_evento 
		FROM evento 
		WHERE estado_evento = 'Programado' 
		ORDER BY TIMESTAMP(fecha_evento)";
		return ejecutarSelect($sql);
	}

	// Listar nombre proveedores
	public function listarNombreProveedores()
	{
		$sql="SELECT id_proveedor, nombre_proveedor 
		FROM proveedores 
		WHERE condicion_proveedor = 1";
		return ejecutarSelect($sql);
	}

	// Listar pagos
	public function listarPagos($id_evento)
	{
		$arregloDatos = array($id_evento);
		$sql="SELECT pagos.id_pago, pagos.fecha_pago, pagos.monto_pago, pagos.metodo_pago, 
		pagos.email_pago, pagos.notas_pago, proveedores.nombre_proveedor, 
		servicio.nombre_categoria_servicio, servicio.id_categoria_servicio
		FROM pagos
		INNER JOIN proveedores ON pagos.id_proveedor = proveedores.id_proveedor
		LEFT JOIN categoria_servicio AS servicio ON pagos.id_categoria_servicio = servicio.id_categoria_servicio
		WHERE id_evento = ? 
		ORDER BY TIMESTAMP(fecha_pago)";
		return ejecutarSelect($sql, $arregloDatos);
	}

	public function listarPagosCaja($id_usuario)
	{
		$arregloDatos = array($id_usuario);
		$sql="SELECT pagos.id_pago, pagos.fecha_pago, pagos.monto_pago, evento.nombre_evento, pagos.metodo_pago, 
		pagos.email_pago, pagos.notas_pago, status_pago, proveedores.nombre_proveedor, 
		servicio.nombre_categoria_servicio, servicio.id_categoria_servicio
		FROM pagos
		INNER JOIN proveedores ON pagos.id_proveedor = proveedores.id_proveedor
		LEFT JOIN categoria_servicio AS servicio ON pagos.id_categoria_servicio = servicio.id_categoria_servicio
		LEFT JOIN evento AS evento ON pagos.id_evento = evento.id_evento   
		WHERE metodo_pago = 'EF' AND id_usuario = ? 
		ORDER BY TIMESTAMP(fecha_pago)";
		return ejecutarSelect($sql, $arregloDatos);
	}

	public function ingresocabecera($id_evento)
	{
		$sql="SELECT 
		id_evento,
		date(fecha_evento) as fecha,
		nombre_evento,
		tipo_evento,
		cotizacion_evento,
		notas_evento
		invitados_evento,
		total_evento,
		estado_evento 
		FROM evento 
		WHERE id_evento='$id_evento'";
		return ejecutarConsulta($sql);
	}

	public function ingresodetalle($id_evento)
	{
		$sql="SELECT 
		s.nombre_servicio as servicio,
		d.cantidad_detalle_evento,
		d.precio_detalle_evento,
		(d.cantidad_detalle_evento*d.precio_detalle_evento) as subtotal 
		FROM detalle_evento d 
		INNER JOIN catalogo_servicio s ON d.id_servicio=s.id_servicio 
		WHERE d.id_evento='$id_evento'";
		return ejecutarConsulta($sql);
	}

	// Editar editarEncabezado
	public function editarPagos(
		$id_evento,
		$fecha_evento,
		$nombre_evento,
		$tipo_evento,
		$cotizacion_evento,
		$notas_evento,
		$total_evento,
		$invitados_evento,
		$id_servicio,
		$cantidad_detalle_evento,
		$precio_detalle_evento
	)
	{
		$sql="UPDATE evento
		SET 
		fecha_evento = '$fecha_evento',
		nombre_evento = '$nombre_evento',
		tipo_evento = '$tipo_evento',
		cotizacion_evento = '$cotizacion_evento',
		notas_evento = '$notas_evento',
		total_evento = '$total_evento',
		invitados_evento = '$invitados_evento'
		WHERE id_evento='$id_evento'";

		ejecutarConsulta($sql);

		$sqldel="DELETE FROM detalle_evento WHERE id_evento='$id_evento'";
		ejecutarConsulta($sqldel);
			
			$num_elementos=0;
			$sw=true;
			
			while ($num_elementos < count($id_servicio))
			{
			$sql_detalle = "INSERT INTO detalle_evento (
				id_evento,
				id_servicio,
				cantidad_detalle_evento,
				precio_detalle_evento
				) VALUES (
				'$id_evento',
				'$id_servicio[$num_elementos]',
				'$cantidad_detalle_evento[$num_elementos]',
				'$precio_detalle_evento[$num_elementos]'
				)";

			ejecutarConsulta($sql_detalle) or $sw = false;
			$num_elementos=$num_elementos + 1;
			}

			return $sw;

		}

		public function actualizarCategoriaServicio(
			$id_pago,
			$id_categoria_servicio,
		)
		{
			$arregloDatos = array($id_categoria_servicio, $id_pago);
			$sql="UPDATE pagos 
			SET id_categoria_servicio = ?
			WHERE id_pago = ? ";
			
			return ejecutarConsulta($sql, $arregloDatos);
	
		}
}

?>
