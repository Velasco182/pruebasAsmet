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

// Obtiene el método HTTP de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

//Para que se muestren las tildesx
//$tildes = $connection->query("SET NAMES 'utf8'"); 

############################ SWITCH PARA LOS METODOS HTTP #############################
try {
    switch ($method) {
        ############################ ABRO READ #############################
        case 'GET':
            if (isset($_GET['id'])) {
                // Obtener un solo cliente por ID
                $id = $_GET['id'];
                $stmt = $pdo->prepare("SELECT id, nombre, apellido, telefono FROM clientes WHERE id = :id");
                //$stmt->bind_param("i", $id);
                $stmt->execute([':id'=>$id]);
                //$result = $stmt->get_result();
                $data = $stmt->fetch();

                if(!$data){
                    $data = [];
                }

                echo json_encode($data);

                /*$data = array();
                if ($result->num_rows > 0) {
                    $data = $result->fetch_assoc();
                }
                header('Content-Type: application/json');
                echo json_encode($data);

                $stmt->close();*/
            } else {
                // Consulta SQL para obtener todos los datos de la tabla 'clientes' accion nombre, apellido, telefono
                $sql = "SELECT * FROM prueba.clientes";
                // Ejecuta la consulta
                $stmt = $pdo->query($sql);
                // Array para almacenar los datos
                $data = $stmt->fetchAll();
                //$data = array();
                // Verifica si hay resultados y los agrega al array
                /*if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                }*/
                // Devuelve los datos en formato JSON
                echo json_encode($data);
            }
            break;
        ############################ CIERRO READ #############################
        ############################ ABRO CREAR #############################
        case 'POST':
            // Decodifica los datos JSON enviados en el cuerpo de la solicitud
            $input = json_decode(file_get_contents('php://input'), true);
            // Recupera los valores de 'nombre', 'apellido' y 'telefono'
            $nombre = $input['nombre'];
            $apellido = $input['apellido'];
            $telefono = $input['telefono'];
            //$accion = $cadena['accion'];
            // Consulta SQL para insertar un nuevo registro en la tabla 'clientes' accion
            $sql = "INSERT INTO prueba.clientes (nombre, apellido, telefono) VALUES (:nombre, :apellido, :telefono)";
            $stmt = $pdo->prepare($sql);
            // Verifica si la consulta se ejecuta correctamente
            if ($stmt->execute([':nombre' => $nombre, ':apellido' => $apellido, ':telefono' => $telefono]) === TRUE) {
                echo json_encode(array("message" => "Registro creado con éxito"));
            } else {
                echo json_encode(array("message" => "Error al crear registro: " . $e->getMessage()));
            }

            break;
        ############################ CIERRO CREAR #############################
        ############################ ABRO ACTUALIZAR #############################
        case 'PUT':
            // Decodifica los datos JSON enviados en el cuerpo de la solicitud
            $input = json_decode(file_get_contents('php://input'), true);
            // Recupera los valores de 'id', 'nombre', 'apellido' y 'telefono'
            if (isset($input['idA'])) {
                // Convierte el cuerpo de la solicitud PUT en un array asociativo
                //json_decode(file_get_contents('php://input'), $_POST);
                // Asigna el valor del parámetro 'id' al variable $id
                $id = $input['idA'];
                // Asigna el valor del parámetro 'nombre' al variable $nombre
                $nombre = $input['nombreA'];
                // Asigna el valor del parámetro 'apellido' al variable $apellido
                $apellido = $input['apellidoA'];
                // Asigna el valor del parámetro 'telefono' al variable $telefono
                $telefono = $input['telefonoA'];
                //Sentencia SQL
                $sql = "UPDATE prueba.clientes SET nombre = :nombre, apellido = :apellido, telefono = :telefono WHERE id = :id";
                // Prepara la declaración SQL para actualizar un registro por ID
                $stmt = $pdo->prepare($sql);
                // Verifica si la preparación de la declaración falló
                if (!$stmt) {
                    $response = array('message' => 'Error en la preparación de la declaración.');
                    echo json_encode($response);
                    exit();
                }
                // Vincula los parámetros $nombre, $apellido, $telefono y $id a los marcadores de posición en la declaración SQL
                //$stmt->bind_param("sssi", $nombre, $apellido, $telefono, $id);
                // Ejecuta la declaración SQL
                if ($stmt->execute([':nombre' => $nombre, ':apellido' => $apellido, ':telefono' => $telefono, ':id' => $id])) {

                    $response = array('success' => true);

                } else {

                    //http_response_code(500);
                    $response = array('error' => 'Error al actualizar cliente.');

                }

                // Cierra la declaración preparada
                //$stmt->close();

            } else {

                $response = array('message' => 'ID no especificado.');

            }
            // Devuelve la respuesta en formato JSON
            echo json_encode($response);
            break;
        ############################ CIERRO ACTUALIZAR #############################
        ############################ ABRO ELIMINAR #############################
        case 'DELETE':
            // Convierte el cuerpo de la solicitud DELETE en un array asociativo
            parse_str(file_get_contents("php://input"), $_DELETE);
            // Verifica si el parámetro 'id' está presente en la URL
            if (isset($_GET['id'])) {
                // Asigna el valor del parámetro 'id' a la variable $id
                $id = $_GET['id'];
                // Prepara la declaración SQL para eliminar un registro por ID
                $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = :id");
                // Verifica si la preparación de la declaración falló
                if (!$stmt) {
                    $response = array('success' => false, 'message' => 'Error en la preparación de la declaración.');
                    echo json_encode($response);
                    exit();
                }
                // Vincula el parámetro $id al marcador de posición en la declaración SQL
                //$stmt->bind_param("i", $id);
                // Ejecuta la declaración SQL
                if ($stmt->execute([':id' => $id])) {
                    $response = array('success' => true);
                } else {
                    $response = array('success' => false, 'message' => 'Error al ejecutar la declaración.');
                }

                // Cierra la declaración preparada
                //$stmt->close();

            } else {

                $response = array('success' => false, 'message' => 'ID no especificado.');

            }
            //header('Content-Type: application/json');
            echo json_encode($response);

            break;
        ############################ CIERRO ELIMINAR #############################
        ############################ CASO POR DEFECTO #############################
        default:
            // Maneja métodos HTTP no soportados
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: GET, POST, PUT, DELETE');
            $response = array('message' => 'Método no permitido');
            echo json_encode($response);
            break;
    }
}catch (PDOException $e) {
    // Manejar errores de consulta y otros errores de PDO
    echo json_encode(['error' => "Error: " . $e->getMessage()]);
}
// Cerrar la conexión 
//$connection->close();
?>