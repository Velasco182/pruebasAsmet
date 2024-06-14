USE prueba;

#CREATE TABLE clientes(id INT AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(10), apellido VARCHAR(10), telefono BIGINT(10)); 
#CREATE TABLE compensatorios(id_compe INT AUTO_INCREMENT PRIMARY KEY, identificacion_compe BIGINT(10), nombre_compe VARCHAR(40), descripcion_compe TEXT , inicio_compe DATETIME, final_compe DATETIME, validacion_compe VARCHAR(10)); 
#CREATE TABLE colaboradores(id_colab INT AUTO_INCREMENT PRIMARY KEY,  identificacion_colab BIGINT(10), nombre_colab VARCHAR(40)); 

CREATE TABLE IF NOT EXISTS `compensatorios` (
  `id_compe` int(11) NOT NULL AUTO_INCREMENT,
  `colaborador_id_compe` int DEFAULT NULL,
  /*`nombre_compe` varchar(40) DEFAULT NULL,*/
  `descripcion_compe` text DEFAULT NULL,
  `inicio_compe` datetime DEFAULT NULL,
  `final_compe` datetime DEFAULT NULL,
  `validacion_compe` ENUM('Pendiente', 'Aceptado', 'Rechazado'),
  FOREIGN KEY (colaborador_id_compe) REFERENCES colaboradores(id_colab),
  PRIMARY KEY (`id_compe`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;