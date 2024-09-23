<?php
require_once "../config/sesion.php";
require_once "../modelos/Usuario.php";





$usuario= new Usuario();



$id_usuario=isset($_POST["id_usuario"])? limpiarCadena($_POST["id_usuario"]):"";

$nombre_usuario=isset($_POST["nombre_usuario"])? limpiarCadena($_POST["nombre_usuario"]):"";

$telefono_usuario=isset($_POST["telefono_usuario"])? limpiarCadena($_POST["telefono_usuario"]):"";

$email_usuario=isset($_POST["email_usuario"])? limpiarCadena($_POST["email_usuario"]):"";

$usr_usuario=isset($_POST["usr_usuario"])? limpiarCadena($_POST["usr_usuario"]):"";

$pass_usuario=isset($_POST["pass_usuario"])? limpiarCadena($_POST["pass_usuario"]):"";

$checkboxes=isset($_POST["checkboxes"])? limpiarCadena($_POST["checkboxes"]):"";







switch ($_GET["op"])

{

	case 'guardaryeditar' :

	Sesion::existeSesion();

	

	$clavehash=hash("SHA256",$pass_usuario);

	

		if (empty($id_usuario))

		{

				$respuesta = $usuario->insertar($nombre_usuario,$telefono_usuario,$email_usuario,$usr_usuario,$clavehash,$checkboxes);

				echo $respuesta ? "Usuario Registrado" : "Usuario no se pudo registrar";

		}

		else{

				//$respuesta = $usuario->editar($id_usuario,$nombre_usuario,$telefono_usuario,$email_usuario,$usr_usuario,$clavehash);

				$respuesta = $usuario->editar($id_usuario,$nombre_usuario,$telefono_usuario,$email_usuario,$usr_usuario,$clavehash,$checkboxes);

				echo $respuesta ? "Usuario Actualizado" : "Usuario no se pudo actualizar";

		}

	break;

	

	

	case 'desactivar' :

		Sesion::existeSesion();

		$respuesta = $usuario->desactivar($id_usuario);

		echo $respuesta ? "Usuario desactivado" : "Usuario no se pudo desactivar";			

	break;

	

	

	case 'activar' :

		Sesion::existeSesion();

		$respuesta = $usuario->activar($id_usuario);

		echo $respuesta ? "Usuario activado" : "Usuario no se pudo activar";		

	break;

	

	

	case 'mostrar' :

		Sesion::existeSesion();

		$respuesta = $usuario->mostrar($id_usuario);

		echo json_encode($respuesta);

	break;

	

	

	case 'listar' :

		Sesion::existeSesion();

		$respuesta = $usuario->listar();

		$data= Array();

		

		while ($reg=$respuesta->fetch_object())

		{

			$data[]=array(

			"0"=>($reg->condicio_usuario)?'<botton class="btn btn-warning" onclick="mostrar('.$reg->id_usuario.')"><i class="fa fa-pencil"></i></botton>'.

			' <botton class="btn btn-danger" onclick="desactivar('.$reg->id_usuario.')"><i class="fa fa-close"></i></botton>':'<botton class="btn btn-warning" onclick="mostrar('.$reg->id_usuario.')"><i class="fa fa-pencil"></i></botton>'.

			' <botton class="btn btn-primary" onclick="activar('.$reg->id_usuario.')"><i class="fa fa-check"></i></botton>',

			"1"=>$reg->nombre_usuario,

			"2"=>$reg->telefono_usuario,

			"3"=>$reg->email_usuario,

			"4"=>$reg->usr_usuario,

			"5"=>($reg->condicio_usuario)?'<span class="label bg-green">Activado</span>':

 				'<span class="label bg-red">Desactivado</span>'

			);

		}

	

		

		$results = array(

			"sEcho"=>1,

			"iTotalRecords"=>count($data),

			"iTotalDisplayRecords"=>count($data),

			"aaData"=>$data

		);

		

		echo json_encode($results);

		

	break;

	

	case 'nombre_permiso':

		Sesion::existeSesion();

		//Obtenemos todos los permisos de la tabla permisos

		require_once "../modelos/Permiso.php";

		$permiso = new Permiso();

		$rspta = $permiso->listar();



		

		//Mostramos la lista de permisos en la vista y si están o no marcados

		while ($reg = $rspta->fetch_object())

				{

					//$sw=in_array($reg->id_permiso,$valores)?'checked':'';

					echo '<li> <input type="checkbox" '.$sw.'  name="permiso[]" value="'.$reg->id_permiso.'">'.$reg->nombre_permiso.'</li>';

				}

	break;

	

	case 'verificar':

		 $usr_usuario=$_POST['usr_usuario'];

		 $pass_usuario=$_POST['pass_usuario'];

		

		// $usr_usuario = "adriana";

		// $pass_usuario = "adriana.vega";



		$clavehash=hash("SHA256",$pass_usuario);

		

		$respuesta=$usuario->verificar($usr_usuario,$clavehash);

		

		$fetch=$respuesta->fetch_object();

		

		if (isset($fetch))

		{

			$_SESSION['loggedin']=true;

			$_SESSION['id_usuario']=$fetch->id_usuario;

			$_SESSION['nombre_usuario']=$fetch->nombre_usuario;

			$_SESSION['usr_usuario']=$fetch->usr_usuario;

			$_SESSION['email_usuario']=$fetch->email_usuario;

			$_SESSION['checkboxes']=$fetch->checkboxes;

			

		}

		

		echo json_encode($fetch);

	

	break;



	case 'salir':


		//Limpiamos las variables de sesión   

        session_unset();

        //Destruìmos la sesión

        session_destroy();

        //Redireccionamos al login

        header("Location: ../index.php");



	break;



}



?>