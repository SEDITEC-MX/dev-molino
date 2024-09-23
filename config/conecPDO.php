<?php
require_once "global.php";

$host = DB_HOST;
$dbname = DB_NAME;
$charset = DB_ENCODE;
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$user = DB_USERNAME;
$pass = DB_PASSWORD;

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$conexionPDO = new PDO($dsn, $user, $pass, $options);
try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // throw new \PDOException($e->getMessage(), (int)$e->getCode()); //la forma estándar de 'tirar' el error, lo dejo por si se necesita
	printf("Falló la conexión %s\n",$e->getMessage());
}


if(!function_exists('ejecutarConsulta'))
{
	function ejecutarConsulta($sql, $params = [] ) //Los parámetros son opcionales
	{
		global $conexionPDO;
		$query = $conexionPDO->prepare($sql)->execute($params);
		return $query;
	}
	
	function ejecutarSelect($sql, $params = [] ) //Los parámetros son opcionales
	{
		global $conexionPDO;
		$stmt = $conexionPDO->prepare($sql);
		$stmt->execute($params);
		$results = $stmt->fetchAll();
		return $results;
	}
	
	function ejecutarConsultaSimpleFila($sql, $params = [] )
	{
		global $conexionPDO;
		$stmt = $conexionPDO->prepare($sql);
		$stmt->execute($params);
		$row = $stmt->fetch();
		return $row;
	}
	
	function ejecutarConsulta_retornarID($sql)
	{
		global $conexionPDO;
		$query = $conexionPDO->query($sql);
		return $conexionPDO->insert_id;
	}
	
	function limpiarCadena($str)
	{
		global $conexionPDO;
		// $str = mysqli_real_escape_string ($conexionPDO,trim($str));
		return htmlspecialchars($str);
	}
}
?>