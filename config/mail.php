<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

//Load Composer's autoloader
// require 'vendor/autoload.php';


Class Mail{

	private $mail;


	public function __construct(){
		
	}


	public function enviarCorreoCobro($correoDestinatario, $correoRemitente, $fecha, $monto, $metodo, $notas, $nombre_evento){
		try {
			$mail = new PHPMailer(true);
			//Server settings
			// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
			$mail->isSMTP();                                            //Send using SMTP
			
			// Host
			$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			$mail->Username   = 'pagos@casaelmolino.com.mx';                     //SMTP username
			$mail->Password   = 'pftqvczyfjjyirzg';                               //SMTP password

			//$mail->Host       = 'mail.seditec.com.mx';                     //Set the SMTP server to send through
			//$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			//$mail->Username   = 'pruebas@seditec.com.mx';                     //SMTP username
			//$mail->Password   = 'wj9fvj8jaZ1D';                               //SMTP password
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
			$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
			$mail->CharSet = 'UTF-8';

			
			//Host
			$mail->setFrom('pagos@casaelmolino.com.mx', 'Pagos Casa El Molino');
		    $mail->addAddress($correoDestinatario);     //Add a recipient
		    $mail->addReplyTo('pagos@casaelmolino.com.mx');


		    //Recipients
		   // $mail->setFrom('pruebas@seditec.com.mx', 'Pruebas');
		    //$mail->addAddress($correoDestinatario);     //Add a recipient
		    //$mail->addReplyTo('pruebas@seditec.com.mx');
		    $mail->addBCC($correoRemitente);

		    //Content
		    $mail->isHTML(true);                                  //Set email format to HTML
		    $mail->Subject = "Cobro de evento por ". $monto. " con fecha de: ".$fecha;
		    $mail->Body    = "Nombre del evento: " .$nombre_evento."<br>".
		    				 "Fecha del cobro: " .$fecha."<br>".
		    				 "Monto del cobro: " .$monto."<br>".
		    				 "Método del cobro: " .$metodo."<br>".
		    				 "Notas: " .$notas;
		    //$mail->AltBody = "Fecha del cobro: " .$fecha." Monto del cobro: " .$monto.
		    				 "  Método del cobro: " .$metodo. "  Notas: " .$notas;

		    return $mail->send();
		} catch (Exception $e) {
		    // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		    return false;
		}
	}

	public function enviarCorreoCobroEliminado($correoDestinatario, $correoRemitente, $fecha, $monto, $metodo, $nombre_evento){
		try {
			$mail = new PHPMailer(true);
			//Server settings
			// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
			$mail->isSMTP();                                            //Send using SMTP
			$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			$mail->Username   = 'pagos@casaelmolino.com.mx';                     //SMTP username
			$mail->Password   = 'pftqvczyfjjyirzg';                               //SMTP password
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
			$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
			$mail->CharSet = 'UTF-8';

		    //Recipients
		    $mail->setFrom('pagos@casaelmolino.com.mx', 'Pagos Casa El Molino');
		    $mail->addAddress($correoDestinatario);     //Add a recipient
		    $mail->addReplyTo('pagos@casaelmolino.com.mx');
		    $mail->addBCC($correoRemitente);

		    //Content
		    $mail->isHTML(true);                                  //Set email format to HTML
		    $mail->Subject = "Cobro eliminado de evento por ". $monto. " con fecha de: ".$fecha;
		    $mail->Body    = "El siguiente cobro ha sido eliminado de los registros: <br>".
		    				 "Nombre del evento: " .$nombre_evento."<br>".
		    				 "Fecha del cobro: " .$fecha."<br>".
		    				 "Monto del cobro: " .$monto."<br>".
		    				 "Método del cobro: " .$metodo."<br>";
		    //$mail->AltBody = "El siguiente cobro ha sido eliminado de los registros: Fecha del cobro: " .$fecha.
		    				 " Monto del cobro: " .$monto."  Método del cobro: " .$metodo;

		    return $mail->send();
		} catch (Exception $e) {
		    // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		    return false;
		}
	}

	public function enviarCorreoPago($correoDestinatario, $correoRemitente, $fecha, $monto, $metodo, $nombre_proveedor, $notas, $nombre_evento){
		try {
			$mail = new PHPMailer(true);
			//Server settings
			// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
			$mail->isSMTP();                                            //Send using SMTP
			
			// Host
			$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			$mail->Username   = 'pagos@casaelmolino.com.mx';                     //SMTP username
			$mail->Password   = 'pftqvczyfjjyirzg';                               //SMTP password

			//$mail->Host       = 'mail.seditec.com.mx';                     //Set the SMTP server to send through
			//$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			//$mail->Username   = 'pruebas@seditec.com.mx';                     //SMTP username
			//$mail->Password   = 'wj9fvj8jaZ1D';                               //SMTP password
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
			$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
			$mail->CharSet = 'UTF-8';

			
			//Host
			$mail->setFrom('pagos@casaelmolino.com.mx', 'Pagos Casa El Molino');
		    $mail->addAddress($correoDestinatario);     //Add a recipient
		    $mail->addReplyTo('pagos@casaelmolino.com.mx');


		    //Recipients
		   // $mail->setFrom('pruebas@seditec.com.mx', 'Pruebas');
		    //$mail->addAddress($correoDestinatario);     //Add a recipient
		    //$mail->addReplyTo('pruebas@seditec.com.mx');
		    $mail->addBCC($correoRemitente);

		    //Content
		    $mail->isHTML(true);                                  //Set email format to HTML
		    $mail->Subject = "Pago del evento por ". $monto. " con fecha de: ".$fecha;
		    $mail->Body    = "Fecha del pago: " .$fecha."<br>".
		    				 "Nombre del evento: " .$nombre_evento."<br>".
		    				 "Nombre del proveedor: " .$nombre_proveedor."<br>".
		    				 "Monto del pago: " .$monto."<br>".
		    				 "Método del pago: " .$metodo."<br>".
		    				 "Notas: " .$notas;
		    //$mail->AltBody = "Fecha del cobro: " .$fecha." Monto del cobro: " .$monto.
		    				 "  Método del pago: " .$metodo. "  Notas: " .$notas;

		    return $mail->send();
		} catch (Exception $e) {
		    // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		    return false;
		}
	}

	public function enviarCorreoPagoEliminado($correoDestinatario, $correoRemitente, $fecha, $monto, $nombre_proveedor, $metodo, $nombre_evento){
		try {
			$mail = new PHPMailer(true);
			//Server settings
			// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
			$mail->isSMTP();                                            //Send using SMTP
			$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			$mail->Username   = 'pagos@casaelmolino.com.mx';                     //SMTP username
			$mail->Password   = 'pftqvczyfjjyirzg';                               //SMTP password
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
			$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
			$mail->CharSet = 'UTF-8';

		    //Recipients
		    $mail->setFrom('pagos@casaelmolino.com.mx', 'Pagos Casa El Molino');
		    $mail->addAddress($correoDestinatario);     //Add a recipient
		    $mail->addReplyTo('pagos@casaelmolino.com.mx');
		    $mail->addBCC($correoRemitente);

		    //Content
		    $mail->isHTML(true);                                  //Set email format to HTML
		    $mail->Subject = "Pago eliminado de evento por ". $monto. " con fecha de: ".$fecha;
		    $mail->Body    = "El siguiente cobro ha sido eliminado de los registros: <br>".
		    				 "Fecha del cobro: " .$fecha."<br>".
		    				 "Nombre del evento: " .$nombre_evento."<br>".
		    				 "Nombre del proveedor: " .$nombre_proveedor."<br>".
		    				 "Monto del cobro: " .$monto."<br>".
		    				 "Método del cobro: " .$metodo."<br>";
		    //$mail->AltBody = "El siguiente cobro ha sido eliminado de los registros: Fecha del cobro: " .$fecha.
		    				 " Monto del cobro: " .$monto."  Método del cobro: " .$metodo;

		    return $mail->send();
		} catch (Exception $e) {
		    // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		    return false;
		}
	}

	
}