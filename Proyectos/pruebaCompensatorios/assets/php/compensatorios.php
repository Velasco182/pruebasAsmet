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
            $data = $stmt->fetchAll();
            //$data = array();
            // Verifica si hay resultados y los agrega al array
            /*if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }*/
            // Devuelve los datos en formato JSON
            //echo json_encode($data);
            // Crea un array para devolver los datos
            /*$response = array(
                'draw' => $request['draw'],
                'recordsTotal' => $stmt->rowCount(),
                'recordsFiltered' => $stmt->rowCount(),
                'data' => $data
            );*/
            
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
        //Funcionó +/-
        $fechaInicio = date("Y-m-d H:i:s", strtotime($inicio_compe));
        $fechaFinal = date("Y-m-d H:i:s", strtotime($final_compe));

        //$fechaInicio = date_create_from_format('Y-m-d H:i:s',  strtotime($inicio_compe));
        //$fechaFinal = date_create_from_format('Y-m-d H:i:s', strtotime($final_compe));

        //$fechaInicio = DateTime::createFromFormat('Y-m-d H:i:s', $inicio_compe);
        //$fechaFinal = DateTime::createFromFormat('Y-m-d H:i:s', $final_compe);
        
        //echo "Fecha inicio $fechaInicio";
        //echo "Fecha final $fechaFinal";
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

        break;
    default:
        // Maneja métodos HTTP no soportados
        header('HTTP/1.1 405 Method Not Allowed');
        header('Allow: GET, POST, PUT, DELETE');
        $response = array('message' => 'Método no permitido');
        echo json_encode($response);
        break;
}

?>