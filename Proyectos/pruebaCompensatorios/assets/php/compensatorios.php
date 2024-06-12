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
            //Si no hay datos, se crear un arreglo vacío
            if(!$data){
                $data = [];
            } /*else {
                // sino se Parsean las fechas antes de enviarse al front-end
                $data['inicio_compe'] = parsearFechaJS($data['inicio_compe']);
                $data['final_compe'] = parsearFechaJS($data['final_compe']);
            }*/
            //Se encía un objeto json con los datos
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
            // Consulta SQL para obtener todos los datos de la tabla 
            $sql = "SELECT 
                    id_compe,
                    identificacion_colab,
                    nombre_colab,
                    descripcion_compe,
                    /*De esta forma formateamos el como se ve en la tabla de nuestro front la consulta
                    Aunque en la DB esté en formato DATETIME, la tabla se verá dia/mes/año hora:minuto y si es am o pm*/
                    DATE_FORMAT(inicio_compe, '%d/%m/%Y %l:%i %p') AS inicio_compe,
                    DATE_FORMAT(final_compe, '%d/%m/%Y %l:%i %p') AS final_compe,
                    /*De esta forma calculamos las horas, pero no es tan funcional ya que genera la info en formato decimal, 
                    que puede ser algo más Confuso al final
                    EXTRACT(HOUR FROM (final_compe - inicio_compe)) + 
                    (EXTRACT(DAY FROM (final_compe - inicio_compe)) * 24) +
                    (EXTRACT(MINUTE FROM (final_compe - inicio_compe)) / 60) AS diferencia,
                    De esta forma podemos calcular las horas que vienen de la db de una manera más sencilla, sólo se crea una casilla, 
                    No viene directamente de la base de datos, esto en MySQL con MariaDB*/
                    TIMEDIFF(final_compe, inicio_compe) AS diferencia,
                    validacion_compe
                FROM 
                    prueba.compensatorios
                JOIN 
                    prueba.colaboradores ON colaborador_id_compe = id_colab";
            /*$sql = "SELECT id_compe, identificacion_colab, nombre_colab, descripcion_compe, inicio_compe, final_compe, validacion_compe 
            FROM prueba.compensatorios
            JOIN prueba.colaboradores ON colaborador_id_compe = id_colab";*/
            // Ejecuta la consulta
            $stmt = $pdo->query($sql);
            // Array para almacenar los datos
            //$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data = $stmt->fetchAll();

            // Iterar sobre cada fila para calcular la diferencia de fecha y hora
            // Además de parsear las filas de fecha inicio y final
            /*foreach ($data as &$row) {

                // Parsear las fechas antes de enviar an front
                /*$row['inicio_compe'] = parsearFechaJS($row['inicio_compe']);
                $row['final_compe'] = parsearFechaJS($row['final_compe']);*/

                //se asigna el contenido de las columnas de la base de datos a las variables
                /*$startTime = $row['inicio_compe'];
                $endTime = $row['final_compe'];

                // Convertir las cadenas de fecha y hora en objetos DateTime con el formato adecuado
                //y se parsean de nuevo para que estén en el formato admitido por la clase diff
                $startDateTime = DateTime::createFromFormat('Y-m-d H:i:s', parsearFecha($startTime));
                $endDateTime = DateTime::createFromFormat('Y-m-d H:i:s', parsearFecha($endTime));
                //Si existen las fecha de tipo DateTime
                if ($startDateTime && $endDateTime) {

                    // Calcular la diferencia entre las dos fechas
                    $interval = $startDateTime->diff($endDateTime);

                    // Añadir la diferencia al array de resultados 
                    //para que el Front-End acceda a esos resultados 
                    $row['diferencia'] = $interval->format('%d días, %h horas, %i minutos');
                
                } else {
                    //Se muestra en la columna de Horas
                    $row['diferencia'] = 'Formato de fecha y hora no válido';
                }
        
            }*/
            
            // Devuelve los datos en formato JSON
            echo json_encode($data);
        }
        break;
    case 'POST':
        // Procesar solicitud POST
        // Decodifica los datos JSON enviados en el cuerpo de la solicitud
        $input = json_decode(file_get_contents('php://input'), true);
        // Recupera los valores de 'nombre', 'apellido' y 'telefono'
        //$identificacion_compe = $input['identificacion_compe'];
        //$nombre_compe = $input['nombre_compe'];
        $colaborador_id_compe = $input['colaborador_id_compe'];
        $descripcion_compe = $input['descripcion_compe'];
        $inicio_compe = $input['inicio_compe'];
        $final_compe = $input['final_compe'];
        $validacion_compe = $input['validacion_compe'];
        //$accion = $cadena['accion'];

        //Convertir a DateTime
        /*$fechaInicio = parsearFechaJS($inicio_compe);
        $fechaFinal = parsearFechaJS($final_compe);*/
        $fechaInicio = formatMeridianIndicator($inicio_compe);
        $fechaFinal = formatMeridianIndicator($final_compe);

        // Consulta SQL para insertar un nuevo registro en la tabla 'clientes' accion
        $sql = "INSERT INTO prueba.compensatorios (colaborador_id_compe, descripcion_compe, inicio_compe, final_compe, validacion_compe) 
        VALUES (:colaborador_id_compe, :descripcion_compe, 
        CONVERT(STR_TO_DATE(:inicio_compe, '%d/%m/%Y %l:%i %p'),DATETIME),
        CONVERT(STR_TO_DATE(:final_compe, '%d/%m/%Y %l:%i %p'), DATETIME), 
        :validacion_compe)";
        //se prepara la consulta para la posterior ejecición, y así evitar inyección de código sql
        $stmt = $pdo->prepare($sql);
        // Verifica si la consulta se ejecuta correctamente
        if ($stmt->execute([':colaborador_id_compe' => $colaborador_id_compe, ':descripcion_compe' => $descripcion_compe, ':inicio_compe' => $inicio_compe, ':final_compe' => $final_compe, ':validacion_compe' => $validacion_compe]) === TRUE) {
            echo json_encode(array("message" => "Registro creado con éxito"));
        } else {
            echo json_encode(array("message" => "Error al crear registro: " . $e->getMessage()));
        }
        break;
    case 'PUT':
        // Procesar solicitud PUT
        // Decodifica los datos JSON enviados en el cuerpo de la solicitud
        $input = json_decode(file_get_contents('php://input'), true);
        // Recupera los valores de 'id', 'nombre', 'apellido' y 'telefono'
        if (isset($input['id_compe'])) {
            // Convierte el cuerpo de la solicitud PUT en un array asociativo
            //json_decode(file_get_contents('php://input'), $_POST);
            // Asigna el valor del parámetro 'id' al variable $id
            $id_compe = $input['id_compe'];
            //ID del colaborador
            $colaborador_id_compe = $input['colaborador_id_compe'];
            // Asigna el valor del parámetro 'nombre' al variable $nombre
            $descripcion_compe = $input['descripcion_compe'];
            // Asigna el valor del parámetro 'apellido' al variable $apellido
            $inicio_compe = $input['inicio_compe'];
            //Fecha de final compensatorio
            $final_compe = $input['final_compe'];
            // Asigna el valor del parámetro 'telefono' al variable $telefono
            $validacion_compe = $input['validacion_compe'];
            //Sentencia SQL
            $sql = "UPDATE prueba.compensatorios SET 
            colaborador_id_compe = :colaborador_id_compe, 
            descripcion_compe = :descripcion_compe, 
            /*inicio_compe = :inicio_compe, final_compe = :final_compe, */
            inicio_compe = CONVERT(STR_TO_DATE(:inicio_compe, '%d/%m/%Y %l:%i %p'),DATETIME),
            final_compe = CONVERT(STR_TO_DATE(:final_compe, '%d/%m/%Y %l:%i %p'), DATETIME),
            validacion_compe = :validacion_compe  WHERE id_compe = :id_compe";
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
            if ($stmt->execute([':colaborador_id_compe' => $colaborador_id_compe, 
            ':descripcion_compe' => $descripcion_compe, 
            ':inicio_compe' => $inicio_compe,':final_compe' => $final_compe, 
            ':validacion_compe' => $validacion_compe, ':id_compe' => $id_compe])) {

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

function parsearFechaJS($fecha){
    // Fecha y hora en formato 'Y-m-d H:i:s'
    //$datetimeString = '2024-06-08 09:00:00';

    // Crear un objeto DateTime a partir de la cadena de fecha y hora
    $datetime = new DateTime($fecha);

    // Formatear la fecha y hora en el nuevo formato
    $formattedDate = $datetime->format('d/m/Y g:i A');

    // Reemplazar el espacio entre A. M./P. M. con un espacio especial
    $formattedDate = str_replace('AM', 'A. M.', $formattedDate);
    $formattedDate = str_replace('PM', 'P. M.', $formattedDate);

    return $formattedDate;
}

function formatMeridianIndicator($fecha) {
    //"12/06/2024 02:00 P. M."
    //Separo la cadena que llega por espacios
    $date_parts = preg_split('/\s+/u', $fecha);
    //Defino el primer tercio como fecha
    $date_day = $date_parts[0];
    //Defino el segundo tercio como hora
    $date_time = $date_parts[1];
    //Defino la tercera posición del arreglo como AM o PM
    $ampm = $date_parts[2];
    
    // Remove space and dots[' ', ]
    $formattedMeridian = str_replace('.', '', $ampm);
  
    // Convert to uppercase
    $formattedMeridian = strtoupper($formattedMeridian);
  
    // Replace "A. M." with "AM"
    if ($formattedMeridian === 'A') {
      $formattedMeridian = str_replace('A. M.', 'AM', $fecha);
    }
  
    // Replace "P. M." with "PM"
    if ($formattedMeridian === 'P') {
      $formattedMeridian = str_replace('P. M.', 'PM', $fecha);
    }
  
    return $formattedMeridian;
  }
  

?>