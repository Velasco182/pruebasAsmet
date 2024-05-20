<?php 
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
    
    // Conexión a la base de datos mysqli_connect
    $connection = new mysqli($host, $username, $password, $database); 
    
    // Verificar la conexión 
    if ($connection->connect_error) { 
        die("Error de conexión: " . $connection->connect_error); 
    }

    // Obtiene el método HTTP de la solicitud
    $method = $_SERVER['REQUEST_METHOD'];

    ############################ SWITCH PARA LOS METODOS HTTP #############################
    switch ($method) {
        ############################ ABRO READ #############################
        case 'GET':
            // Consulta SQL para obtener todos los datos de la tabla 'clientes' accion nombre, apellido, telefono
            $sql = "SELECT * FROM prueba.clientes";
            // Ejecuta la consulta
            $result = $connection->query($sql);
            // Array para almacenar los datos
            $data = array();
            // Verifica si hay resultados y los agrega al array
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            // Devuelve los datos en formato JSON
            echo json_encode($data);
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
            $sql = "INSERT INTO prueba.clientes (nombre, apellido, telefono) VALUES ('$nombre', '$apellido', $telefono)";
            // Verifica si la consulta se ejecuta correctamente
            if ($connection->query($sql) === TRUE) {
                echo json_encode(array("message" => "Registro creado con éxito"));
            } else {
                echo json_encode(array("message" => "Error al crear registro: " . $connection->error));
            }
        break;
        ############################ CIERRO CREAR #############################
        ############################ ABRO ACTUALIZAR #############################
        case 'PUT':
            // Decodifica los datos JSON enviados en el cuerpo de la solicitud
            $input = json_decode(file_get_contents('php://input'), true);
            // Recupera los valores de 'id', 'nombre', 'apellido' y 'telefono'
            $id = $_GET['id'];
            $nombre = $input['nombre'];
            $apellido = $input['apellido'];
            $telefono = $input['telefono'];
            // Consulta SQL para actualizar un registro en la tabla 'clientes'
            $sql = "UPDATE clientes SET nombre='$nombre', apellido='$apellido', telefono=$telefono WHERE id=$id";
            // Verifica si la consulta se ejecuta correctamente
            if ($connection->query($sql) === TRUE) {
                echo json_encode(array("message" => "Registro actualizado con éxito"));
            } else {
                echo json_encode(array("message" => "Error al actualizar registro: " . $connection->error));
            }
            /*parse_str(file_get_contents("php://input"), $_PUT); // Convierte el cuerpo de la solicitud PUT en un array asociativo
            $id = $_PUT['id']; // Asigna el valor del parámetro 'id' al variable $id
            $nombre = $_PUT['nombre']; // Asigna el valor del parámetro 'nombre' al variable $nombre
            $apellido = $_PUT['apellido']; // Asigna el valor del parámetro 'apellido' al variable $apellido
            $telefono = $_PUT['telefono']; // Asigna el valor del parámetro 'telefono' al variable $telefono

            $stmt = $connection->prepare("UPDATE clientes SET nombre = ?, apellido = ?, telefono = ? WHERE id = ?"); // Prepara la declaración SQL para actualizar un registro por ID
            $stmt->bind_param("sssi", $nombre, $apellido, $telefono, $id); // Vincula los parámetros $nombre, $apellido, $telefono y $id a los marcadores de posición en la declaración SQL

            if ($stmt->execute()) { // Ejecuta la declaración SQL
                $response = array('success' => true); // Crea una respuesta de éxito
            } else {
                $response = array('success' => false, 'message' => 'Error al actualizar cliente: ' . $stmt->error); // Crea una respuesta de error si la ejecución falla
            }
            header('Content-Type: application/json'); // Establece el tipo de contenido de la respuesta a JSON
            echo json_encode($response); // Devuelve la respuesta en formato JSON

            $stmt->close(); // Cierra la declaración preparada*/
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
                $stmt = $connection->prepare("DELETE FROM clientes WHERE id = ?");
                // Verifica si la preparación de la declaración falló
                if (!$stmt) {
                    $response = array('success' => false, 'message' => 'Error en la preparación de la declaración: ' . $connection->error);
                    echo json_encode($response);
                    exit();
                }
                // Vincula el parámetro $id al marcador de posición en la declaración SQL
                $stmt->bind_param("i", $id);
                // Ejecuta la declaración SQL
                if ($stmt->execute()) {
                    $response = array('success' => true);
                } else {
                    $response = array('success' => false, 'message' => 'Error al ejecutar la declaración: ' . $stmt->error);
                }
                
                // Cierra la declaración preparada
                $stmt->close();

            } else {

                $response = array('success' => false, 'message' => 'ID no especificado.');

            }
            header('Content-Type: application/json');
            echo json_encode($response);

        break;
        ############################ CIERRO ELIMINAR #############################
        ############################ CASO POR DEFECTO #############################
        default:
            // Maneja métodos HTTP no soportados
            echo json_encode(array("message" => "Método no soportado"));
        break;
    }

    // Cerrar la conexión 
    $connection->close(); 
?> 
