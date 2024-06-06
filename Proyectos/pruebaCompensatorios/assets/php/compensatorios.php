<?php
// Establecer la zona horaria por defecto
date_default_timezone_set('America/Bogota');
//Requerir lineas de código de otro archivo
require 'conexiondb.php';
// Obtiene el método HTTP de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

switch($method){
    case 'GET':
        // Procesar solicitud GET
        if (isset($_GET['id_compe'])) {
            // Obtener un solo cliente por ID
            $id_compe = $_GET['id_compe'];
            $stmt = $pdo->prepare("SELECT * FROM prueba.compensatorios WHERE id_compe = :id_compe");
            //$stmt->bind_param("i", $id);
            $stmt->execute([':id_compe'=>$id_compe]);
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
            echo json_encode($data);*/

            //$stmt->close();
        } else {
            // Obtiene la solicitud de DataTables
            $request = $_REQUEST;
            // Consulta SQL para obtener todos los datos de la tabla 'clientes' accion nombre, apellido, telefono
            $sql = "SELECT * FROM prueba.compensatorios";
            // Ejecuta la consulta
            $stmt = $pdo->query($sql);
            // Array para almacenar los datos
            //$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data = $stmt->fetchAll();

            // Iterar sobre cada fila para calcular la diferencia de fecha y hora
            foreach ($data as &$row) {
                //columnas de la base de datos
                $startTime = $row['inicio_compe'];
                $endTime = $row['final_compe'];

                // Convertir las cadenas de fecha y hora en objetos DateTime con el formato adecuado
                $startDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $startTime);
                $endDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $endTime);

                if ($startDateTime && $endDateTime) {
                    // Calcular la diferencia entre las dos fechas
                    $interval = $startDateTime->diff($endDateTime);
                    // Añadir la diferencia al array de resultados
                    $row['diferencia'] = $interval->format('%d días, %h horas, %i minutos');
                } else {
                    $row['diferencia'] = 'Formato de fecha y hora no válido';
                }
        
            }
            // Devuelve los datos en formato JSON
            echo json_encode($data);
        }
        break;
    case 'POST':
        // Procesar solicitud POST
        // Decodifica los datos JSON enviados en el cuerpo de la solicitud
        $input = json_decode(file_get_contents('php://input'), true);
        // Recupera los valores de 'nombre', 'apellido' y 'telefono'
        $identificacion_compe = $input['identificacion_compe'];
        $nombre_compe = $input['nombre_compe'];
        $descripcion_compe = $input['descripcion_compe'];
        $inicio_compe = $input['inicio_compe'];
        $final_compe = $input['final_compe'];
        $validacion_compe = $input['validacion_compe'];
        //$accion = $cadena['accion'];

        //Convertir a DateTime
        $fechaInicio = parsearFecha($inicio_compe);
        $fechaFinal = parsearFecha($final_compe);

        // Consulta SQL para insertar un nuevo registro en la tabla 'clientes' accion
        $sql = "INSERT INTO prueba.compensatorios (identificacion_compe, nombre_compe, descripcion_compe, inicio_compe, final_compe, validacion_compe) VALUES (:identificacion_compe, :nombre_compe, :descripcion_compe, :inicio_compe, :final_compe, :validacion_compe)";
        $stmt = $pdo->prepare($sql);
        // Verifica si la consulta se ejecuta correctamente
        if ($stmt->execute(['identificacion_compe' => $identificacion_compe,':nombre_compe' => $nombre_compe, ':descripcion_compe' => $descripcion_compe, ':inicio_compe' => $fechaInicio, ':final_compe' => $fechaFinal, ':validacion_compe' => $validacion_compe]) === TRUE) {
            echo json_encode(array("message" => "Registro creado con éxito"));
        } else {
            echo json_encode(array("message" => "Error al crear registro: " . $e->getMessage()));
        }
        break;
    case 'PUT':
        // Procesar solicitud PUT

        break;
    case 'DELETE':
        // Procesar solicitud DELETE

        // Convierte el cuerpo de la solicitud DELETE en un array asociativo
        parse_str(file_get_contents("php://input"), $_DELETE);
        // Verifica si el parámetro 'id' está presente en la URL
        if (isset($_GET['id_compe'])) {
            // Asigna el valor del parámetro 'id' a la variable $id
            $id_compe = $_GET['id_compe'];
            // Prepara la declaración SQL para eliminar un registro por ID
            $stmt = $pdo->prepare("DELETE FROM prueba.compensatorios WHERE id_compe = :id_compe");
            // Verifica si la preparación de la declaración falló
            if (!$stmt) {
                $response = array('success' => false, 'message' => 'Error en la preparación de la declaración.');
                echo json_encode($response);
                exit();
            }
            // Vincula el parámetro $id al marcador de posición en la declaración SQL
            //$stmt->bind_param("i", $id);
            // Ejecuta la declaración SQL
            if ($stmt->execute([':id_compe' => $id_compe])) {
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
    default:
        // Maneja métodos HTTP no soportados
        header('HTTP/1.1 405 Method Not Allowed');
        header('Allow: GET, POST, PUT, DELETE');
        $response = array('message' => 'Método no permitido');
        echo json_encode($response);
        break;
}

//Función para convertir fecha de texto al formato que acepta la DB
function parsearFecha($fecha){
    //$fecha = "06/06/2024 9:00 A. M.";
    //Separo la cadena que llega por espacios
    $date_parts = preg_split('/\s+/u', $fecha);
    //Defino el primer tercio como fecha
    $date_day = $date_parts[0];
    //Defino el segundo tercio como hora
    $date_time = $date_parts[1];
    //Defino la tercera posición del arreglo como AM o PM
    $ampm = $date_parts[2];
    //Separo la fecha por slash "/"
    $date_day_parts = explode("/", $date_day);
    //La primera parte es el día
    $day = $date_day_parts[0];
    //La segunda parte es el mes
    $month = $date_day_parts[1];
    //La última parte es el año
    $year = $date_day_parts[2];

    //Verifico si es una fecha valida con Checkdate
    if (!checkdate($month, $day, $year)) {
        echo "Error: la fecha no es válida";
        exit;
    }

    //Divido el campo de hora por dos puntos ":"
    $time_parts = explode(":", $date_time);
    //La primera parte es la hora
    $hour = $time_parts[0];
    //La segunda parte son los minútos
    $minute = $time_parts[1];

    //Evalúo si la tercera parte del arreglo principal que llega del front
    //incluye A. de AM
    //con el fin de convertir la hora a formato militar, que es el que reconoce
    //La DB
    if ($ampm == "A.") {
        //Si es así, y la hora es igual a 12
        if ($hour == "12") {
            //, es asingada como 00
            $hour = "00";
        }
        //Sino y si la hora que llega incluye P. de PM
    } elseif ($ampm == "P.") {
        //Y si la hora es diferente de 12
        if ($hour != "12") {
            //Se le suman 12, para que nos dé el formato de hora militar
            $hour = $hour + 12;
        }
    } else {
        echo "Error: el formato de hora no es válido";
        exit;
    }

    //Guardo en una cadena la fecha formateada para que la DB la reconozca
    //Concatenando elemento por elemento 
    $fecha_formateada = $year."-". $month. "-" . $day . " " . str_pad($hour, 2, "0", STR_PAD_LEFT) . ":" . str_pad($minute, 2, "0", STR_PAD_LEFT) . ":00";
    //echo $fecha_formateada;
    //Retornamos la cadena que va a ser guardada en una variable, donde se llame a la función
    return $fecha_formateada;
}

?>