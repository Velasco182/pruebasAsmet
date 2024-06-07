-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.27-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.7.0.6850
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para prueba
CREATE DATABASE IF NOT EXISTS `prueba` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `prueba`;

-- Volcando estructura para tabla prueba.clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(10) DEFAULT NULL,
  `apellido` varchar(10) DEFAULT NULL,
  `telefono` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla prueba.clientes: ~13 rows (aproximadamente)
INSERT IGNORE INTO `clientes` (`id`, `nombre`, `apellido`, `telefono`) VALUES
	(2, 'Carlos', 'Pérez', 3115555678),
	(4, 'Luis', 'Martínez', 3115554321),
	(12, 'Diego', 'Bravo', 3114445565),
	(16, 'Luis', 'Villa', 3113336701),
	(18, 'Fernando', 'Zuñiga', 3221113320),
	(19, 'Fernando', 'Velasco', 3128169636),
	(35, 'Rubén', 'Velasco', 3114155696),
	(37, 'Valentina', 'Castro', 3116669988),
	(38, 'Manuel', 'Camacho', 3114445566),
	(43, 'Gustavo', 'Petro', 3193332211),
	(44, 'Camilo', 'Vargas', 3114155696),
	(45, 'Daniela', 'Arango', 3223336699),
	(46, 'Rubén Vela', NULL, NULL);

-- Volcando estructura para tabla prueba.colaboradores
CREATE TABLE IF NOT EXISTS `colaboradores` (
  `id_colab` int(11) NOT NULL AUTO_INCREMENT,
  `identificacion_colab` bigint(10) DEFAULT NULL,
  `nombre_colab` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id_colab`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla prueba.colaboradores: ~50 rows (aproximadamente)
INSERT IGNORE INTO `colaboradores` (`id_colab`, `identificacion_colab`, `nombre_colab`) VALUES
	(1, 8421356790, 'Sofía Gutiérrez'),
	(2, 6543987211, 'Juan Carlos Pérez'),
	(3, 2198765430, 'María del Carmen López'),
	(4, 9834210967, 'Pedro José García'),
	(5, 4678901234, 'Lucía Elena Sánchez'),
	(6, 1357924680, 'Carlos Alberto Martínez'),
	(7, 7531924680, 'Ana Isabel Díaz'),
	(8, 3948271560, 'Javier Francisco González'),
	(9, 6289417530, 'Elena María Hernández'),
	(10, 8173625490, 'Miguel Ángel Álvarez'),
	(11, 5492736180, 'Cristina Sofía Torres'),
	(12, 9827341560, 'Rafael Antonio Moreno'),
	(13, 7539842100, 'Isabel María Jiménez'),
	(14, 6543210987, 'Francisco Javier Gómez'),
	(15, 2193847560, 'Laura Elena Sánchez'),
	(16, 4671238900, 'Héctor Manuel Ramírez'),
	(17, 9834562100, 'Sandra Elena López'),
	(18, 1357924680, 'Óscar Antonio García'),
	(19, 7531924680, 'María José Hernández'),
	(20, 3948271560, 'Jesús María Díaz'),
	(21, 6289417530, 'Carmen Elena Pérez'),
	(22, 8173625490, 'Alejandro Francisco Sánchez'),
	(23, 5492736180, 'Verónica Elena Gómez'),
	(24, 9827341560, 'Fernando Antonio González'),
	(25, 7539842100, 'María del Carmen López'),
	(26, 6543210987, 'Ricardo Antonio Torres'),
	(27, 2193847560, 'Sonia Elena Hernández'),
	(28, 4671238900, 'Manuel Antonio Álvarez'),
	(29, 9834562100, 'Eva Elena Díaz'),
	(30, 1357924680, 'Jorge Antonio Moreno'),
	(31, 7531924680, 'Cristina Sofía Jiménez'),
	(32, 3948271560, 'Luis Antonio Gutiérrez'),
	(33, 6289417530, 'María Teresa García'),
	(34, 8173625490, 'Pedro Antonio Ramírez'),
	(35, 5492736180, 'Sandra Elena Sánchez'),
	(36, 9827341560, 'Óscar Antonio López'),
	(37, 7539842100, 'María José García'),
	(38, 6543210987, 'Jesús Antonio Díaz'),
	(39, 2193847560, 'Carmen Elena Pérez'),
	(40, 4671238900, 'Alejandro Antonio Sánchez'),
	(41, 9834562100, 'Verónica Elena Gómez'),
	(42, 1357924680, 'Fernando Antonio González'),
	(43, 7531924680, 'María del Carmen López'),
	(44, 3948271560, 'Ricardo Antonio Torres'),
	(45, 6289417530, 'Sonia Elena Hernández'),
	(46, 8173625490, 'Manuel Antonio Álvarez'),
	(47, 5492736180, 'Eva Elena Díaz'),
	(48, 9827341560, 'Jorge Antonio Moreno'),
	(49, 7539842100, 'Cristina Sofía Jiménez'),
	(50, 6543210987, 'Luis Antonio Gutiérrez');

-- Volcando estructura para tabla prueba.compensatorios
CREATE TABLE IF NOT EXISTS `compensatorios` (
  `id_compe` int(11) NOT NULL AUTO_INCREMENT,
  `identificacion_compe` bigint(10) DEFAULT NULL,
  `nombre_compe` varchar(40) DEFAULT NULL,
  `descripcion_compe` text DEFAULT NULL,
  `inicio_compe` datetime DEFAULT NULL,
  `final_compe` datetime DEFAULT NULL,
  `validacion_compe` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_compe`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla prueba.compensatorios: ~6 rows (aproximadamente)
INSERT IGNORE INTO `compensatorios` (`id_compe`, `identificacion_compe`, `nombre_compe`, `descripcion_compe`, `inicio_compe`, `final_compe`, `validacion_compe`) VALUES
	(3, 2198765430, 'María del Carmen López', '3', '2024-06-06 15:00:00', '2024-06-06 16:00:00', 'Aceptado'),
	(4, 2198765430, 'María del Carmen López', '4', '2024-06-01 12:00:00', '2024-06-01 16:00:00', 'Rechazado'),
	(6, 8421356790, 'Sofía Gutiérrez', '1', '2024-06-01 13:00:00', '2024-06-06 14:00:00', 'Pendiente'),
	(7, 5492736180, 'Eva Elena Díaz', 'Horas Extras.', '2024-06-06 16:00:00', '2024-06-06 17:00:00', 'Rechazado'),
	(8, 8421356790, 'Sofía Gutiérrez', '2', '2024-06-06 16:00:00', '2024-06-06 17:00:00', 'Pendiente');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
