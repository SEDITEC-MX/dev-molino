<?php
// Conexión a la BD
require "../config/conecPDO.php";


Class Empleado
{
	public function __construct()
	{
		
	}
	
	// Insertar nuevos servicios
	public function insertar($nombre_empleado, $id_categoria_servicio, $notas_empleado)
	{
		$arregloDatos = array($nombre_empleado, $id_categoria_servicio, $notas_empleado);
		$sql = "INSERT INTO empleados(nombre_empleado,id_categoria_servicio,notas_empleado) VALUES (?,?,?)";
		return ejecutarConsulta($sql, $arregloDatos);
	}
	
	// Editar servicios
	public function editar($id_empleado,$nombre_empleado, $id_categoria_servicio, $notas_empleado)
	{
		$sql="UPDATE empleados
		SET
		nombre_empleado='$nombre_empleado',
		id_categoria_servicio='$id_categoria_servicio',
		notas_empleado='$notas_empleado'
		WHERE id_empleado='$id_empleado'";
		return ejecutarConsulta($sql);
	}
	
	// Mostrar 1 proveedor
	public function mostrar($id_empleado)
	{
		$arregloDatos = array($id_empleado);
		$sql="SELECT * FROM empleados
		WHERE id_empleado = ? ";
		return ejecutarConsultaSimpleFila($sql, $arregloDatos);
	}



	public function desactivar($id_empleado)
	{
		$arregloDatos = array($id_empleado);
		$sql="UPDATE empleados
		SET condicion_empleado = 0
		WHERE id_empleado = ? ";
		return ejecutarConsulta($sql, $arregloDatos);
	}

	public function activar($id_empleado)
	{
		$arregloDatos = array($id_empleado);
		$sql="UPDATE empleados
		SET condicion_empleado = 1
		WHERE id_empleado = ? ";
		return ejecutarConsulta($sql, $arregloDatos);
	}
	
	// Listar proveedores
	public function listar()
	{

		$sql="SELECT 
		a.id_empleado,
		a.nombre_empleado,
		c.nombre_categoria_servicio as id_categoria_servicio,
		a.condicion_empleado
		FROM empleados a 
		INNER JOIN categoria_servicio c 
		ON a.id_categoria_servicio=c.id_categoria_servicio";
		return ejecutarSelect($sql);



	}

	// Listar servicios activos
	public function listarActivos()
	{
		$sql="SELECT 
		a.id_empleado, 
		a.nombre_empleado,
		a.id_categoria_servicio, 
		c.nombre_categoria_servicio as categoria, 
		a.notas_empleado 
		FROM empleados a 
		INNER JOIN categoria_servicio c 
		ON a.id_categoria_servicio=c.id_categoria_servicio 
		WHERE a.condicion_empleado ='1'";
		return ejecutarSelect($sql);
	}


}

?>