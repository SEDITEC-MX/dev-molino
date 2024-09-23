<?php
// Conexión a la BD
require "../config/conec.php";

Class Servicio
{
	public function __construct()
	{
		
	}
	
	// Insertar nuevos servicios
	public function insertar($id_categoria_servicio,$nombre_servicio,$descripcion_servicio,$imagen_servicio)
	{
		$sql="INSERT INTO catalogo_servicio (id_categoria_servicio,nombre_servicio,descripcion_servicio,imagen_servicio,condicion_servicio)
		VALUES (
		'$id_categoria_servicio',
		'$nombre_servicio',
		'$descripcion_servicio',
		'$imagen_servicio',
		'1')";
		return ejecutarConsulta($sql);
	}
	
	// Editar servicios
	public function editar($id_servicio,$id_categoria_servicio,$nombre_servicio,$descripcion_servicio,$imagen_servicio)
	{
		$sql="UPDATE catalogo_servicio
		SET 
		id_categoria_servicio='$id_categoria_servicio',
		nombre_servicio='$nombre_servicio',
		descripcion_servicio='$descripcion_servicio',
		imagen_servicio='$imagen_servicio'
		WHERE id_servicio='$id_servicio'";
		return ejecutarConsulta($sql);
	}
	
	// Desactivar servicios
	public function desactivar($id_servicio)
	{
		$sql="UPDATE catalogo_servicio
		SET condicion_servicio='0'
		WHERE id_servicio='$id_servicio'";
		return ejecutarConsulta($sql);
	}
	
	// Activar servicios
	public function activar($id_servicio)
	{
		$sql="UPDATE catalogo_servicio
		SET condicion_servicio='1'
		WHERE id_servicio='$id_servicio'";
		return ejecutarConsulta($sql);
	}
	
	// Mostrar 1 servicio
	public function mostrar($id_servicio)
	{
		$sql="SELECT * FROM catalogo_servicio
		WHERE id_servicio='$id_servicio'";
		return ejecutarConsultaSimpleFila($sql);
	}
	
	// Listar servicios
	public function listar()
	{
		$sql="SELECT 
		a.id_servicio,
		a.id_categoria_servicio,
		c.nombre_categoria_servicio as categoria,
		a.nombre_servicio,
		a.descripcion_servicio,
		a.imagen_servicio,
		a.condicion_servicio 
		FROM catalogo_servicio a 
		INNER JOIN categoria_servicio c 
		ON a.id_categoria_servicio=c.id_categoria_servicio";
		return ejecutarConsulta($sql);
	}

	// Listar servicios activos
	public function listarActivos()
	{
		$sql="SELECT 
		a.id_servicio, 
		a.id_categoria_servicio, 
		c.nombre_categoria_servicio as categoria, 
		a.nombre_servicio, 
		a.descripcion_servicio, 
		a.imagen_servicio 
		FROM catalogo_servicio a 
		INNER JOIN categoria_servicio c 
		ON a.id_categoria_servicio=c.id_categoria_servicio 
		WHERE a.condicion_servicio ='1'";
		return ejecutarConsulta($sql);
	}
}
?>