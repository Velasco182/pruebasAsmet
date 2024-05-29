<?php
//Información adicional: https://www.aprenderaprogramar.com/index.php?option=com_content&view=article&id=612:php-consultas-mysql-mysqliconnect-selectdb-query-fetcharray-freeresult-close-ejemplos-cu00841b&catid=70&Itemid=193
//Habilitar la visualización de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
//Permitir solicitudes desde cualquier origen
header('Access-Control-Allow-Origin: *');
//Establece la respuesta como de tipo JSON 
header('Content-Type: application/json');
// Configuración de la conexión a la base de datos 
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'prueba';

try {
    // Crear una instancia de PDO para conectarse a una base de datos MySQL
    $dsn = "mysql:host=$host;dbname=$database;charset=utf8";
    $options = [
        // Habilitar excepciones para errores
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
        // Establecer el modo de obtención de resultados a asociativo
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
    ];

    //Definición de la sentencia PDO para conectar a la base de datos
    $pdo = new PDO($dsn, $username, $password, $options);

} catch (PDOException $e) {
    // Manejar errores de conexión
    die("Error de conexión: " . $e->getMessage());
}
