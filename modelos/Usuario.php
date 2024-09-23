<?php
// Conexión a la BD
require "../config/conec.php";

Class Usuario
{
	public function __construct()
	{
		
	}
	

	
	
	// Insertar categorias nuevas
	public function insertar($nombre_usuario, $telefono_usuario, $email_usuario, $usr_usuario, $pass_usuario,$checkboxes)
	{
		$sql="INSERT INTO usuario (
								nombre_usuario,
								telefono_usuario,
								email_usuario,
								usr_usuario,
								pass_usuario,
								condicio_usuario,
								checkboxes
			) VALUES (
								'$nombre_usuario',
								'$telefono_usuario',
								'$email_usuario',
								'$usr_usuario',
								'$pass_usuario',
								'1',
								'$checkboxes')";
		return ejecutarConsulta($sql);
	}
	
	
	
	
	
	// Editar categorias
	//public function editar($id_usuario, $nombre_usuario, $telefono_usuario, $email_usuario, $usr_usuario, $pass_usuario)
	public function editar($id_usuario, $nombre_usuario, $telefono_usuario, $email_usuario, $usr_usuario, $pass_usuario,$checkboxes)
	{
		//$hashed_password = password_hash($pass_usuario, PASSWORD_BCRYPT);
		// $sql="UPDATE usuario
		// SET 
		// nombre_usuario='$nombre_usuario',
		// telefono_usuario=' $telefono_usuario',
		// email_usuario='$email_usuario',
		// usr_usuario='$usr_usuario',
		// pass_usuario='$pass_usuario'
		// WHERE id_usuario='$id_usuario'";
		// return ejecutarConsulta($sql);
		
		$sql="UPDATE usuario
		SET 
		nombre_usuario='$nombre_usuario',
		telefono_usuario=' $telefono_usuario',
		email_usuario='$email_usuario',
		usr_usuario='$usr_usuario',
		pass_usuario='$pass_usuario',
		checkboxes='$checkboxes'
		WHERE id_usuario='$id_usuario'";
		return ejecutarConsulta($sql);
		
		
	}
	
	// Descticar categorias
	public function desactivar($id_usuario)
	{
		$sql="UPDATE usuario
		SET condicio_usuario='0'
		WHERE id_usuario='$id_usuario'";
		return ejecutarConsulta($sql);
	}
	
	// Acticar categorias
	public function activar($id_usuario)
	{
		$sql="UPDATE usuario
		SET condicio_usuario='1'
		WHERE id_usuario='$id_usuario'";
		return ejecutarConsulta($sql);
	}
	
	// Mostrar 1 categoria
	public function mostrar($id_usuario)
	{
		$sql="SELECT * FROM usuario
		WHERE id_usuario='$id_usuario'";
		return ejecutarConsultaSimpleFila($sql);
	}
	
	// Listar categorias
	public function listar()
	{
		$sql="SELECT * FROM usuario";
		return ejecutarConsulta($sql);
	}
	
	// Verificar acceso al sistema
	public function verificar($usr_usuario,$pass_usuario)
	{
		$sql="SELECT id_usuario, nombre_usuario, telefono_usuario, email_usuario, usr_usuario, checkboxes FROM usuario 
		WHERE usr_usuario = '$usr_usuario' AND pass_usuario='$pass_usuario' AND condicio_usuario = '1' ";
		
		return ejecutarConsulta($sql);
	}
		
}

?>
