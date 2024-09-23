<?php
// Conexión a la BD
// require "../config/conec.php";
require "../config/conecPDO.php";

Class Registro_cobro
{
	public function __construct()
	{

	}


/*
Columnas de la tabla 'cobros'
id_cobro int(11)
id_evento int(11)
fecha_cobro datetime(6)
monto_cobro decimal(15,2)
metodo_cobro varchar(45)
email_cobro varchar(200)

*/

	public function insertar(
		$id_evento,
		$fecha_cobro,
		$monto_cobro,
		$metodo_cobro,
		$email_cobro,
		$notas_cobro
	)
	{
		$arregloDatos = array($id_evento, $fecha_cobro, $monto_cobro, 
			$metodo_cobro, $email_cobro, $notas_cobro);
		$sql="INSERT INTO cobros (id_evento, fecha_cobro, monto_cobro, metodo_cobro, email_cobro, notas_cobro) 
		VALUES (?,?,?,?,?,? )";

		return ejecutarConsulta($sql, $arregloDatos);

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

	public function verificarTotalCobros($id_evento) //Checa la suma de los cobros
	{
		$arregloDatos = array($id_evento);
		$sql="SELECT SUM(cobros.monto_cobro) AS cobrado
			FROM evento
			LEFT JOIN cobros ON evento.id_evento = cobros.id_evento
			WHERE evento.id_evento = ?
			GROUP BY evento.id_evento";
		return ejecutarSelect($sql, $arregloDatos);
	}

	public function eliminar($id_cobro)
	{
		$arregloDatos = array($id_cobro);
		$sql="DELETE FROM cobros WHERE id_cobro = ?";
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

	// Listar eventos
	public function listarNombreEventos()
	{
		$sql="SELECT id_evento, nombre_evento 
		FROM evento 
		WHERE estado_evento = 'Programado' 
		ORDER BY TIMESTAMP(fecha_evento)";
		return ejecutarSelect($sql);
	}

	// Listar eventos
	public function listarCobros($id_evento)
	{
		$arregloDatos = array($id_evento);
		$sql="SELECT id_cobro, fecha_cobro, monto_cobro, metodo_cobro, email_cobro, notas_cobro 
		FROM cobros
		WHERE id_evento = ? 
		ORDER BY TIMESTAMP(fecha_cobro)";
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
	public function editarCobros(
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
