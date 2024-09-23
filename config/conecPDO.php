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

try {
    $conexionPDO = new PDO($dsn, $user, $pass, $options);
    //echo "Conexión exitosa a la base de datos '$dbname' en '$host'."; // Mensaje de éxito
} catch (\PDOException $e) {
    printf("Falló la conexión: %s\n", $e->getMessage()); // Mensaje de error
    exit; // Terminar la ejecución si la conexión falla
}

if (!function_exists('ejecutarConsulta')) {
    function ejecutarConsulta($sql, $params = []) {
        global $conexionPDO;
        $query = $conexionPDO->prepare($sql)->execute($params);
        return $query;
    }

    function ejecutarSelect($sql, $params = []) {
        global $conexionPDO;
        $stmt = $conexionPDO->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll();
        return $results;
    }

    function ejecutarConsultaSimpleFila($sql, $params = []) {
        global $conexionPDO;
        $stmt = $conexionPDO->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row;
    }

    function ejecutarConsulta_retornarID($sql) {
        global $conexionPDO;
        $query = $conexionPDO->query($sql);
        return $conexionPDO->lastInsertId(); // Cambié insert_id a lastInsertId
    }

    function limpiarCadena($str) {
        return htmlspecialchars($str);
    }
}
?>
