<?php 
    //Permitir solicitudes desde cualquier origen
    header('Access-Control-Allow-Origin: *');
    //Establece la respuesta como de tipo JSON 
    header('Content-Type: application/json');
    // Configuración de la conexión a la base de datos 
    $host = 'localhost'; 
    $username = 'root'; 
    $password = ''; 
    $database = 'prueba'; 
    
    // Conexión a la base de datos 
    $connection = mysqli_connect($host, $username, $password, $database); 
    
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
            // Consulta SQL para obtener todos los datos de la tabla 'clientes' accion
            $sql = "SELECT nombre, apellido, telefono FROM prueba.clientes";
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
            $sql = "INSERT INTO clientes (nombre, apellido, telefono) VALUES ('$nombre', '$apellido', $telefono)";
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
            $id = $input['id'];
            $nombre = $input['nombre'];
            $apellido = $input['apellido'];
            $telefono = $input['telefono'];
            // Consulta SQL para actualizar un registro en la tabla 'clientes'
            $sql = "UPDATE clientes SET nombre='$nombre', apellido='$apellido', telefono='$telefono' WHERE id=$id";
            // Verifica si la consulta se ejecuta correctamente
            if ($connection->query($sql) === TRUE) {
                echo json_encode(array("message" => "Registro actualizado con éxito"));
            } else {
                echo json_encode(array("message" => "Error al actualizar registro: " . $connection->error));
            }
            break;
        ############################ CIERRO ACTUALIZAR #############################
        ############################ ABRO ELIMINAR #############################
        case 'DELETE':
            // Decodifica los datos JSON enviados en el cuerpo de la solicitud
            $input = json_decode(file_get_contents('php://input'), true);
            // Recupera el valor de 'id'
            $id = $input['id'];
            // Consulta SQL para eliminar un registro de la tabla 'clientes'
            $sql = "DELETE FROM clientes WHERE id=$id";
            // Verifica si la consulta se ejecuta correctamente
            if ($connection->query($sql) === TRUE) {
                echo json_encode(array("message" => "Registro eliminado con éxito"));
            } else {
                echo json_encode(array("message" => "Error al eliminar registro: " . $connection->error));
            }
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
