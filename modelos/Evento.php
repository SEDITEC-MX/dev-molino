<?php
// Conexión a la BD
require "../config/conec.php";

Class Evento
{
	public function __construct()
	{

	}


/*

Columnas de la tabla 'evento'

id_evento 			int(11) AI PK 
fecha_evento 		datetime(6) 
nombre_evento 		varchar(250) 
tipo_evento 		varchar(45) 
cotizacion_evento 	varchar(45) 
notas_evento 		varchar(250) 
total_evento 		decimal(15,2) 
invitados_evento 	int(6) 
estado_evento 		varchar(45)

Columnas de la tabla 'detalle_evento'

id_detalle_evento		int(11) PK 
id_evento 				int(11) 
id_servicio 			int(11) 
cantidad_detalle_evento decimal(15,2) 
precio_detalle_evento 	decimal(15,2) 

*/

	// Crear nuevos eventos
	// Todos los campos de la tabla evento excepto id_evento y estado_evento
	// Todos los campos de la tabla detalle_evento excepto id_detalle_evento, id_evento
	public function insertar(
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
		$sql="INSERT INTO evento (
									fecha_evento,
									nombre_evento,
									tipo_evento,
                                    cotizacion_evento,
                                    notas_evento,
                                    total_evento,
									invitados_evento,
                                    estado_evento
			) VALUES (
								'$fecha_evento',
								'$nombre_evento',
								'$tipo_evento',
								'$cotizacion_evento',
								'$notas_evento',
								'$total_evento',
								'$invitados_evento',
								'Programado'
					)";

		$id_evento_new=ejecutarConsulta_retornarID($sql);

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
			'$id_evento_new',
			'$id_servicio[$num_elementos]',
			'$cantidad_detalle_evento[$num_elementos]',
			'$precio_detalle_evento[$num_elementos]'
			)";

			ejecutarConsulta($sql_detalle) or $sw = false;
			$num_elementos=$num_elementos + 1;
		}

		return $sw;


	}

	// Finalizar eventos
	public function anular($id_evento)
	{
		$sql="UPDATE evento
		SET estado_evento='Finalizado'
		WHERE id_evento='$id_evento'";
		return ejecutarConsulta($sql);
	}

	public function activar($id_evento)
	{
		$sql="UPDATE evento
		SET estado_evento='Programado'
		WHERE id_evento='$id_evento'";
		return ejecutarConsulta($sql);
	}

	// Mostrar 1 evento
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
		cs.nombre_categoria_servicio,
		de.cantidad_detalle_evento,
		de.precio_detalle_evento 
		FROM detalle_evento de 
		inner join catalogo_servicio s on de.id_servicio=s.id_servicio
		inner join categoria_servicio cs on s.id_categoria_servicio = cs.id_categoria_servicio
		where de.id_evento='$id_evento' order by 3";
		return ejecutarConsulta($sql);
	}

	// Listar eventos
	public function listar()
	{
		$sql="SELECT
			evento.id_evento,
	        DATE_FORMAT(evento.fecha_evento, '%d/%m/%Y') as fecha_evento,
	        evento.nombre_evento,
			evento.invitados_evento,
	        evento.tipo_evento,
	        evento.cotizacion_evento,
	        evento.notas_evento,
	        (SELECT SUM(detalle_evento.cantidad_detalle_evento * detalle_evento.precio_detalle_evento) FROM detalle_evento WHERE detalle_evento.id_evento = evento.id_evento GROUP BY detalle_evento.id_evento ) AS total_evento,
	        evento.estado_evento,
			TIMESTAMP(fecha_evento) AS 'timestamp'
			FROM evento
	        LEFT JOIN detalle_evento  ON evento.id_evento = detalle_evento.id_evento
			GROUP BY id_evento
	        ORDER BY TIMESTAMP(fecha_evento);";
		return ejecutarConsulta($sql);
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
	public function editarEventos(
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

}

?>
