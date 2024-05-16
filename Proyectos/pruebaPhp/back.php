<?php 
    // Configuración de la conexión a la base de datos 
    $host = 'localhost'; 
    $username = 'tu_usuario'; 
    $password = 'tu_contraseña'; 
    $database = 'nombre_de_tu_base_de_datos'; 
    
    // Conexión a la base de datos 
    $connection = new mysqli($host, $username, $password, $database); 
    
    // Verificar la conexión 
    if ($connection->connect_error) { 
        die("Error de conexión: " . $connection->connect_error); 
    } 
    
    // Consulta para obtener datos (ejemplo) 
    $sql = "SELECT * FROM tu_tabla"; 
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
