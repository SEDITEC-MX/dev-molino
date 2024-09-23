<?php
// Conexión a la BD
require "../config/conecPDO.php";



Class Empleado_Pagos
{
	public function __construct()
	{
		
	}

		// Listar servicios activos
	public function listar()
	{
		$sql="SELECT
				a.id_pago_empleado,
				a.fecha_pago_empleado,
				b.nombre_evento as id_evento,
				d.nombre_categoria_servicio as categoria,
				c.nombre_empleado as id_empleado,
				a.concepto,
				a.monto_pago,
				a.descuento,
				a.total
				FROM pagos_empleados a
				INNER JOIN empleados c
				ON a.id_empleado=c.id_empleado
				LEFT JOIN evento b
				ON a.id_evento=b.id_evento
				LEFT JOIN categoria_servicio d
				ON c.id_categoria_servicio=d.id_categoria_servicio
		";
		return ejecutarSelect($sql);
	}

		// Listar servicios activos
		public function eliminar($id_pago_empleado)
		{

			$arregloDatos = array($id_pago_empleado);
			$sql ="DELETE FROM pagos_empleados WHERE id_pago_empleado = ?";
			return ejecutarConsulta($sql, $arregloDatos);
	
	
		}


// Insertar nuevos servicios
public function insertar($id_empleado,$id_evento,$fecha_pago_empleado,$monto_pago,$descuento,$total,$concepto)
{
	$arregloDatos = array($id_empleado,$id_evento,$fecha_pago_empleado,$monto_pago,$descuento,$total,$concepto);
	$sql = "INSERT INTO pagos_empleados(id_empleado,id_evento,fecha_pago_empleado,monto_pago,descuento,total,concepto) VALUES (?,?,?,?,?,?,?)";
	return ejecutarConsulta($sql, $arregloDatos);
}

	// Insertar nuevos pagos empleados
	public function insertarMultiple($arregloEmp){
		global $conexionPDO;
		try {
			$conexionPDO->beginTransaction();
			
			foreach($arregloEmp as $emp){
				$idEmpleado = $emp['idEmpleado'];
				$fecha = $emp['fecha'];
				$monto = $emp['monto'];
				$descuento = $emp['descuento'];
				$total = $emp['total'];
				$concepto = $emp['concepto'];
				$notas = $emp['notas'];
				$idEvento = empty($emp['idEvento']) ? null : $emp['idEvento'];  
				print_r($idEvento);
				$arregloDatos = array($idEmpleado,$idEvento,$fecha,$monto,$descuento,$total,$concepto);
				$sql = "INSERT INTO pagos_empleados(id_empleado,id_evento,fecha_pago_empleado,monto_pago,descuento,total,concepto)
						VALUES(?,?,?,?,?,?,?)";
				ejecutarConsulta($sql, $arregloDatos);

				$arregloDatos = array($notas, $idEmpleado);
				$sql = "UPDATE empleados
						SET notas_empleado = ?
						WHERE id_empleado = ?";
				ejecutarConsulta($sql, $arregloDatos);

			}
			$conexionPDO->commit();
			// return $arregloEmp;
			return 1;
		}catch(\Throwable $e){
			return 0;
		}
		// $arregloDatos = array($id_empleado,$id_evento,$fecha_pago_empleado,$monto_pago,$descuento,$total,$concepto);
		// $sql = "INSERT INTO pagos_empleados(id_empleado,id_evento,fecha_pago_empleado,monto_pago,descuento,total,concepto) VALUES (?,?,?,?,?,?,?)";
		// return ejecutarConsulta($sql, $arregloDatos);
	}
}

?>