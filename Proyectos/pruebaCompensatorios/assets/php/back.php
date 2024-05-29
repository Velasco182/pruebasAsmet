<?
//Requerir lineas de código de otro archivo
require 'conexiondb.php';
// Obtiene el método HTTP de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

switch($method){
    case 'GET':
        // Procesar solicitud GET
        if (isset($_GET['id'])) {
            // Obtener un solo cliente por ID
            $id = $_GET['id'];
            $stmt = $pdo->prepare("SELECT * FROM compensatorios WHERE id = :id");
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
                }*/
            //}
            // Devuelve los datos en formato JSON
            //echo json_encode($data);
            // Crea un array para devolver los datos
            $response = array(
                'draw' => $request['draw'],
                'recordsTotal' => $stmt->rowCount(),
                'recordsFiltered' => $stmt->rowCount(),
                'data' => $data
            );
            
            // Devuelve los datos en formato JSON
            echo json_encode($response);
        }
        break;
    case 'POST':
        // Procesar solicitud POST
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