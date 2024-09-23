<?php
// Conexión a la BD
require "../config/conec.php";

Class Permiso
{
	public function __construct()
	{
		
	}
	
	// Listar categorias
	public function listar()
	{
		$sql="SELECT * FROM permiso";
		return ejecutarConsulta($sql);
	}
	
}

?>
