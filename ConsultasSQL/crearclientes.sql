USE prueba;

#CREATE TABLE clientes(id INT AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(10), apellido VARCHAR(10), telefono BIGINT(10)); 
CREATE TABLE compensatorios(id_compe INT AUTO_INCREMENT PRIMARY KEY, identificacion_compe BIGINT(10), nombre_compe VARCHAR(40), descripcion_compe TEXT , inicio_compe DATETIME, final_compe DATETIME, validacion_compe VARCHAR(10)); 
#CREATE TABLE colaboradores(id_colab INT AUTO_INCREMENT PRIMARY KEY,  identificacion_colab BIGINT(10), nombre_colab VARCHAR(40)); 
