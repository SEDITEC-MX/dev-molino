<?php
// Conexión a la BD
require "../config/conecPDO.php";

Class Proveedor
{
	public function __construct()
	{
		
	}
	
	// Insertar nuevos servicios
	public function insertar($nombre_proveedor)
	{
		$arregloDatos = array($nombre_proveedor);
		$sql = "INSERT INTO proveedores(nombre_proveedor) VALUES (?)";
		return ejecutarConsulta($sql, $arregloDatos);
	}
	
	// Editar servicios
	public function editar($nombre_proveedor, $id_proveedor)
	{
		$arregloDatos = array($nombre_proveedor, $id_proveedor);

		$sql="UPDATE proveedores
		SET nombre_proveedor = ?
		WHERE id_proveedor = ?";
		return ejecutarConsulta($sql, $arregloDatos);
	}
	
	// Mostrar 1 proveedor
	public function mostrar($id_proveedor)
	{
		$arregloDatos = array($id_proveedor);
		$sql="SELECT * FROM proveedores
		WHERE id_proveedor = ? ";
		return ejecutarConsultaSimpleFila($sql, $arregloDatos);
	}



	public function desactivar($id_proveedor)
	{
		$arregloDatos = array($id_proveedor);
		$sql="UPDATE proveedores
		SET condicion_proveedor = 0
		WHERE id_proveedor = ? ";
		return ejecutarConsulta($sql, $arregloDatos);
	}

	public function activar($id_proveedor)
	{
		$arregloDatos = array($id_proveedor);
		$sql="UPDATE proveedores
		SET condicion_proveedor = 1
		WHERE id_proveedor = ? ";
		return ejecutarConsulta($sql, $arregloDatos);
	}
	
	// Listar proveedores
	public function listar()
	{
		$sql="SELECT 
		id_proveedor,
		nombre_proveedor,
		condicion_proveedor
		FROM proveedores";
		return ejecutarSelect($sql);
	}


}
?>