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
    
    // Cerrar la conexión 
    $connection->close(); 
?> 
