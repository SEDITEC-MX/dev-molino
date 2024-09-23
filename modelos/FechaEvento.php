<?php
// Conexión a la BD

require "../config/conecPDO.php";

Class FechaEvento
{
	public function __construct()
	{

	}

	//Saber si hay un evento en esa fecha
	public function selectEventoExistenteEnFecha($fecha){

        $arregloDatos = array($fecha);
        $sql='SELECT 1, nombre_evento FROM evento WHERE fecha_evento = ?';

		return ejecutarConsultaSimpleFila($sql, $arregloDatos);
	}


	public function selectEventosEnFecha($fecha){

        $arregloDatos = array($fecha);
        $sql='SELECT id_evento, nombre_evento FROM evento WHERE fecha_evento = ?';

		return ejecutarSelect($sql, $arregloDatos);
	}
}

?>
