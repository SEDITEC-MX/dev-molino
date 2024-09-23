<?php

// Conexión a la BD
require "../config/conec.php";

Class Categoria
{
	public function __construct()
	{
		
	}
	
	// Insertar categorias nuevas
	public function insertar($nombre_categoria_servicio)
	{
		$sql="INSERT INTO categoria_servicio (nombre_categoria_servicio,condicio_categoria_servicio)
		VALUES ('$nombre_categoria_servicio', '1')";
		return ejecutarConsulta($sql);
	}
	
	// Editar categorias
	public function editar($id_categoria_servicio,$nombre_categoria_servicio)
	{
		$sql="UPDATE categoria_servicio
		SET nombre_categoria_servicio='$nombre_categoria_servicio'
		WHERE id_categoria_servicio='$id_categoria_servicio'";
		return ejecutarConsulta($sql);
	}
	
	// Descticar categorias
	public function desactivar($id_categoria_servicio)
	{
		$sql="UPDATE categoria_servicio
		SET condicio_categoria_servicio='0'
		WHERE id_categoria_servicio='$id_categoria_servicio'";
		return ejecutarConsulta($sql);
	}
	
	// Acticar categorias
	public function activar($id_categoria_servicio)
	{
		$sql="UPDATE categoria_servicio
		SET condicio_categoria_servicio='1'
		WHERE id_categoria_servicio='$id_categoria_servicio'";
		return ejecutarConsulta($sql);
	}
	
	// Mostrar 1 categoria
	public function mostrar($id_categoria_servicio)
	{
		$sql="SELECT * FROM categoria_servicio
		WHERE id_categoria_servicio='$id_categoria_servicio'";
		return ejecutarConsultaSimpleFila($sql);
	}
	
	// Listar categorias
	public function listar()
	{
		$sql="SELECT * FROM categoria_servicio";
		return ejecutarConsulta($sql);
	}
	
	// Listar categorias
	public function select()
	{
		$sql="SELECT * FROM categoria_servicio where condicio_categoria_servicio=1";
		return ejecutarSelect($sql);
	}
		
}

?>