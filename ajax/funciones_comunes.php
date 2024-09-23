<?php
require_once "../modelos/Registro_cobro.php";
require_once "../config/mail.php";

Class FuncionesComunes{
	public function __construct(){
		
	}

	public function asignarMetodoCobro($metodo_cobro){
		$nuevoMetodoCobro = "";
		switch ($metodo_cobro) {
			case 'TR':
				$nuevoMetodoCobro = "Transferencia";
				break;
			case 'EV':
				$nuevoMetodoCobro = "Cuenta EVENTOS";
				break;
			case 'FE':
				$nuevoMetodoCobro = "Cuenta FERCON";
				break;
			case 'EF':
				$nuevoMetodoCobro = "Efectivo";
				break;
			case 'FV':
				$nuevoMetodoCobro = "Cuenta FVG";
				break;
			case 'AV':
				$nuevoMetodoCobro = "Cuenta AVU";
				break;
			case 'Otro':
				$nuevoMetodoCobro = "Otro";
				break;
			default:
				$nuevoMetodoCobro = "";
				break;
		}
		return $nuevoMetodoCobro;
	}
}