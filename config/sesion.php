<?php
session_start();



Class Sesion{





	public static function existeSesion(){

		if ( !isset($_SESSION['loggedin']) || !$_SESSION['loggedin'] == true ){

			self::accesoNoAutorizado();

		}

	}



	private static function accesoNoAutorizado(){

		// echo "Nel";

		exit("Usuario no autorizado. Favor de iniciar sesión");

	}





	

}