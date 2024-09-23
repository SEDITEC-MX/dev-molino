<?php

require_once "../config/sesion.php";
require_once "funciones_comunes.php";
require_once "../modelos/Archivos.php";
require_once "../modelos/Evento.php";
Sesion::existeSesion();

$archivos =new Archivos();
//Ruta pro
//$base_dir = "./../../archivos";

//Ruta pre
$base_dir = "./../archivos";

/*
Columnas de la tabla 'archivos'
id_archivo int(11)
nombreArchivo varchar(100)
categoriaArchivo enum('cotizacion', 'documentacion', 'ine')
fechaSubida timestamp
id_evento int(11)
*/


switch ($_GET["op"]){
	case 'subirArchivo' :
		$id_evento = $_POST['idEvento'] ?? 0;
		$id_evento = (int)$id_evento;
		$categoriaArchivo =  $_POST['categoriaArchivo'] ?? '';
		$archivosValidos = ['documentacion', 'ine', 'cotizacion','comprobante'];


		if ($id_evento == 0 || !is_int($id_evento)){
			$resp = array("codigo" => 0, "mensaje" => "Datos no válidos" );
			echo json_encode($resp);
			exit;
		}

		if (!in_array($categoriaArchivo, $archivosValidos)){
			$resp = array("codigo" => 0, "mensaje" => "No es una categoría válida" );
			echo json_encode($resp);
			echo "cat: " . $categoriaArchivo;
			exit;
		}
		

		if (!empty($archivos->yaExisteArchivo($id_evento, $categoriaArchivo)) ){
			$resp = array("codigo" => 0, "mensaje" => "Ya hay un archivo para este evento de esta misma categoría, favor de borrarlo primero" );
			echo json_encode($resp);
			exit;
		}

		if (isset($_FILES['archivo'])) {
			$file = $_FILES['archivo'];
			esArchivoPDF($file['name']);
			tieneTamanioPermitido($file, 2); //en MB

			$target_dir = $base_dir . DIRECTORY_SEPARATOR . $categoriaArchivo . DIRECTORY_SEPARATOR ;
			$target_file = $target_dir . DIRECTORY_SEPARATOR . basename($_FILES["archivo"]["name"]);
			siNoExisteDirectorioCrear($base_dir);
			siNoExisteDirectorioCrear($target_dir);
			$new_name = generarNombre($target_dir, $target_file);
			$target_file = $target_dir . DIRECTORY_SEPARATOR . $new_name;

			if (move_uploaded_file($file['tmp_name'], $target_file)) {
				if ($archivos->insertarArchivo($id_evento, $new_name, $categoriaArchivo)){
					$resp = array("codigo" => 1, "mensaje" => "Archivo subido correctamente" );
					echo json_encode($resp);
				}else{
					unlink($target_file);
					$resp = array("codigo" => 0, "mensaje" => "El archivo no se pudo subir" );
					echo json_encode($resp);
				}
			} else {
				$resp = array("codigo" => 0, "mensaje" => "El archivo no se pudo subir" );
				echo json_encode($resp);
			}
			
		}else{
			$resp = array("codigo" => 0, "mensaje" => "Archivo no válido" );
			echo json_encode($resp);
			exit;
		}
	break;

	case 'mostrarArchivos' :
		$id_evento = $_POST['id_evento'] ?? 0;
		$id_evento = (int)$id_evento;
		if ($id_evento == 0 || !is_int($id_evento)){
			echo "Datos no válidos";
			exit;
		}
		echo json_encode($archivos->mostrarArchivos($id_evento));

	break;

	case 'descargarArchivo' :
		$id_evento = $_GET['id_evento'] ?? 0;
		$id_evento = (int)$id_evento;
		$categoriaArchivo =  $_GET['categoria'] ?? '';
		$archivosValidos = ['documentacion', 'ine', 'cotizacion'];
		
		if (!in_array($categoriaArchivo, $archivosValidos)){
			echo "No es una categoría válida";
			exit;
		}

		if ($id_evento == 0 || !is_int($id_evento)){
			echo "Datos no válidos";
			exit;
		}
		$data = $archivos->mostrarArchivoIndividual($id_evento, $categoriaArchivo);
		$nombre = $data[0]['nombreArchivo'];

		$file = $base_dir . DIRECTORY_SEPARATOR . $categoriaArchivo . DIRECTORY_SEPARATOR . $nombre;
		if(file_exists($file)) {

			//Define header information
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header("Cache-Control: no-cache, must-revalidate");
			header("Expires: 0");
			header('Content-Disposition: attachment; filename="'.basename($file).'"');
			header('Content-Length: ' . filesize($file));
			header('Pragma: public');
			
			//Clear system output buffer
			flush();
			
			//Read the size of the file
			readfile($file);
		}

		exit;

	break;

	case 'borrarArchivo' :
		$id_evento = $_POST['id_evento'] ?? 0;
		$id_evento = (int)$id_evento;
		$categoriaArchivo =  $_POST['categoria'] ?? '';
		$archivosValidos = ['documentacion', 'ine', 'cotizacion'];
		if (!in_array($categoriaArchivo, $archivosValidos)){
			$resp = array("codigo" => 0, "mensaje" => "No es una categoría válida" );
			echo json_encode($resp);
			exit;
		}

		if ($id_evento == 0 || !is_int($id_evento)){
			$resp = array("codigo" => 0, "mensaje" => "Datos no válidos" );
			echo json_encode($resp);
			exit;
		}
		$data = $archivos->mostrarArchivoIndividual($id_evento, $categoriaArchivo);
		$nombre = $data[0]['nombreArchivo'];
		$file = $base_dir . DIRECTORY_SEPARATOR . $categoriaArchivo . DIRECTORY_SEPARATOR . $nombre;
		if(file_exists($file)) {

			if ($archivos->borrarRegistroArchivo($id_evento, $categoriaArchivo, $nombre)){
				unlink($file);
				$resp = array("codigo" => 1, "mensaje" => "El archivo fue borrado correctamente" );
				echo json_encode($resp);
			}

		} else{
			$resp = array("codigo" => 0, "mensaje" => "No se pudo borrar el archivo" );
			echo json_encode($resp);
		}

		exit;

	break;
}


function generarNombre($dir, $filename){
	$name = "";
	do{
		$extension = pathinfo($filename, PATHINFO_EXTENSION);
		$basename = bin2hex(random_bytes(12));
		$name = sprintf('%s.%0.8s', $basename, $extension);
	} while(file_exists($dir . DIRECTORY_SEPARATOR . $name));
	return $name;
}

function siNoExisteDirectorioCrear($dir){
	if(!is_dir($dir)){
		mkdir($dir);
	}	
}

function esArchivoPDF($file){
	$ext = pathinfo($file, PATHINFO_EXTENSION);
	$ext = strtolower($ext);
	if ($ext != "pdf"){
		$resp = array("codigo" => 0, "mensaje" => "Solo se permiten archivos PDF" );
		echo json_encode($resp);
		exit;
	}
}

function tieneTamanioPermitido($file, $tamanioMaximo){
	$fileSize = $file['size'];
	$fileSizeMB = $fileSize / 1024 / 1024;
	if ($fileSizeMB > $tamanioMaximo) {
		$resp = array("codigo" => 0, "mensaje" => "No se pueden subir archivos mayores a " .$tamanioMaximo."MB" );
		echo json_encode($resp);
		exit;
	}
}


?>

