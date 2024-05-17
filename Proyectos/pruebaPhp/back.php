<?php 
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
    
    //echo "Conectado a la base de datos";
    ###ABRO READ###
    // Consulta para obtener datos (ejemplo) 
    $sql = "SELECT * FROM prueba.clientes"; 
    $result = $connection->query($sql); 
    
    // Array para almacenar los datos 
    $data = array(); 
    
    // Recuperar y guardar los datos en el array 
    if ($result->num_rows > 0) { 

        while($row = $result->fetch_assoc()) { 

            $data[] = $row; 

        } 
        
    } else { 
        echo "No se encontraron datos."; 
    } 
    
    // Devolver los datos como JSON 
    echo json_encode($data); 

    ###CIERRO READ###
    ###ABRO CREATE###
    // Procesar la petición
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Obtener los datos enviados en el cuerpo de la petición
        //$data = json_decode(file_get_contents('php://input'), true);
        $dato = $_POST['formulario'];
    /*$nombre = $_POST['nombreCliente'];
    $apellido = $_POST['apellidoCliente'];
    $telefono = $_POST['telefonoCliente'];*/
        // Insertar los datos en la base de datos
        $insertar = "INSERT INTO prueba.clientes (nombre, apellido, email) VALUES ($dato)";
        /*$stmt = $connection->prepare($sql);
        $stmt->bind_param("sss", $data['nombre'], $data['apellido'], $data['telefono']);
        $stmt->execute();*/
    //$result = $connection->query($insertar); 
        // Devolver un mensaje de éxito
        mysqli_query($con, $insertar);
        
        echo json_encode(array('message' => 'Registro creado exitosamente '.$dato));
    } else {
        // Devolver un mensaje de error si no se envió una petición POST
        echo json_encode(array('error' => 'No se envió una petición POST'));
    }

    ###CIERRO CREATE###

    
    // Cerrar la conexión 
    $connection->close(); 
?> 
