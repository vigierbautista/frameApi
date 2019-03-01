-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.1.30-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win32
-- HeidiSQL Versión:             10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Volcando estructura de base de datos para frame
CREATE DATABASE IF NOT EXISTS `frame` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `frame`;

-- Volcando estructura para tabla frame.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla frame.categories: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`id`, `name`) VALUES
	(2, 'Pop'),
	(3, 'Clásico'),
	(4, 'Realismo'),
	(5, 'Abstracto'),
	(6, 'Contemporaneo');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Volcando estructura para tabla frame.comments
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment` text,
  `id_post` int(10) unsigned NOT NULL DEFAULT '0',
  `id_user` int(10) unsigned NOT NULL DEFAULT '0',
  `date_added` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_posts` (`id_post`),
  KEY `fk_users` (`id_user`),
  CONSTRAINT `fk_posts` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla frame.comments: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` (`id`, `comment`, `id_post`, `id_user`, `date_added`) VALUES
	(13, 'sdfsd', 148, 84, '2019-03-01 02:45:49'),
	(14, 'terrible pete', 148, 84, '2019-03-01 02:50:35'),
	(15, 'Comento mi post', 149, 84, '2019-03-01 14:34:21'),
	(16, 'un comentario larguisimooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo', 148, 84, '2019-03-01 16:10:24'),
	(17, 'asdfasd', 143, 84, '2019-03-01 18:01:06');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;

-- Volcando estructura para tabla frame.liked_posts
CREATE TABLE IF NOT EXISTS `liked_posts` (
  `id_user` int(10) unsigned NOT NULL,
  `id_post` int(10) unsigned NOT NULL,
  KEY `id_user` (`id_user`),
  KEY `id_post` (`id_post`),
  CONSTRAINT `id_post` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id`),
  CONSTRAINT `id_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla frame.liked_posts: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `liked_posts` DISABLE KEYS */;
INSERT INTO `liked_posts` (`id_user`, `id_post`) VALUES
	(90, 143),
	(86, 149),
	(87, 149),
	(86, 149),
	(84, 148),
	(84, 146),
	(84, 143),
	(84, 149);
/*!40000 ALTER TABLE `liked_posts` ENABLE KEYS */;

-- Volcando estructura para tabla frame.posts
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `image` varchar(250) DEFAULT 'default.jpg',
  `content` text,
  `date_added` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_user` int(10) unsigned NOT NULL,
  `id_category` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_category` (`id_category`)
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla frame.posts: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` (`id`, `title`, `image`, `content`, `date_added`, `id_user`, `id_category`) VALUES
	(143, 'Mi primer post', 'new-rolling-stones-png-logo-14.png', 'Vamos a guardar una lengua stone!', '0000-00-00 00:00:00', 85, 2),
	(144, 'Un post sin foto', '', '', '0000-00-00 00:00:00', 85, 2),
	(145, 'sdfasd', '', '', '2019-02-26 02:10:15', 85, 2),
	(146, 'fdsd', '', '', '2019-02-26 03:29:00', 84, 2),
	(148, 'dfda', 'Diamond_Dogs.png', '', '2019-02-26 04:03:00', 84, 2),
	(149, 'Titulo del post', 'Trials Rising - Open Beta2019-2-23-11-27-54.jpg', 'Derscripción de mi post', '2019-03-01 14:10:18', 84, 4);
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;

-- Volcando estructura para tabla frame.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT 'default.png',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla frame.users: ~8 rows (aproximadamente)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `last_name`, `email`, `password`, `token`, `image`) VALUES
	(57, 'Bautista', 'Vigier', 'vigierbautista@gmail.com', '$2y$10$fZ56rZuMutKqLDLGYHQaIe61ck1GJd1Rli.dSqKU6wr9Rtwc.Lsgm', '', ''),
	(84, 'Bauti', 'Vigier', 'personal@mail.com', '$2y$10$geAgXeI4Mfm46lMvP/HfaOQOf8UPWNF3RKm.pPJFMAA8415k/KIxe', '', 'profile.jpg'),
	(85, 'asd', 'asd', 'hola@asdf.com', '$2y$10$CFVIu9pkPpVMtU4Air6P3eVmNL/LNlwG4W6kNPVb0CeMsB0wBqUe2', '', ''),
	(86, 'Adsads', 'asdasd', 'asdas@asdasd.com', '$2y$10$4QNzwc5ENNv1C.D2xQgeNOCu1xwNRefGmkdUpQ9UF6BrU5CvLaK1S', '', ''),
	(87, 'asdf', 'asdf', 'asd@asdf.com', '$2y$10$mgY8KkGp183GAYhTQlh2iefRLsJ0A.RN7VCkbYWrXmDsnduoYvtWS', '', ''),
	(88, 'Bautista', 'Vigier', 'vigierbautista@asd.com', '$2y$10$ZTubRDbDiZQM0jLTPw4rVujECF.qxha8ctA4onrEe4QiGOV0axwaq', '', ''),
	(89, 'qwe', 'qwe', 'qwe@gmail.com', '$2y$10$iJwFC7/Nnla.ShxtBnSKGeazLGsP3tQ5bVjxr5E2wLScS.GniIavy', '', ''),
	(90, 'Nuevo', 'Usuario', 'asd@asd1.com', '$2y$10$38n04Vszjis7U8V95dHMbO.GuAcGyrLsfQl9XIqFDvVrEfq76//9i', '', '');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
