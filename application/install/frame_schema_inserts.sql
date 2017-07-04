-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         5.6.17 - MySQL Community Server (GPL)
-- SO del servidor:              Win64
-- HeidiSQL Versión:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla frame.comments: ~9 rows (aproximadamente)
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` (`id`, `comment`, `id_post`, `id_user`, `date_added`) VALUES
	(16, 'Este es un comentario :P', 61, 57, '2017-07-04 00:48:11'),
	(17, 'asdfasdf', 61, 57, '2017-07-04 00:51:27'),
	(18, NULL, 61, 57, '2017-07-04 00:52:08'),
	(20, '1234', 61, 57, '2017-07-04 00:55:16'),
	(21, 'qwer', 61, 57, '2017-07-04 00:56:10'),
	(22, 'asdfasdfasdfasdf', 61, 57, '2017-07-04 00:58:20'),
	(23, 'asdfasdfasdfasdf', 61, 57, '2017-07-04 00:58:28'),
	(24, 'asdfasdf', 61, 57, '2017-07-04 01:03:05'),
	(25, 'asdfasdfasdfasdf', 61, 57, '2017-07-04 01:03:07');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;

-- Volcando estructura para tabla frame.posts
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `image` varchar(30) DEFAULT 'default.jpg',
  `content` text,
  `date_added` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_user` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla frame.posts: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` (`id`, `title`, `image`, `content`, `date_added`, `id_user`) VALUES
	(61, 'Este post', 'default.jpg', 'Deberia tener fecha', '2017-07-04 03:29:15', 57),
	(62, 'asdf', 'default.jpg', 'asdf', '2017-07-03 23:08:23', 57),
	(64, 'Creo un post por seguridad', 'default.jpg', 'tiene un titulo mas largo de lo normal', '2017-07-03 23:27:47', 57),
	(65, 'Un ultimo post', 'default.jpg', 'ASDASDASD', '2017-07-04 01:03:43', 57),
	(66, 'asdfasdf', 'default.jpg', NULL, '2017-07-04 01:08:26', 57);
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
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla frame.users: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `last_name`, `email`, `password`, `token`, `image`) VALUES
	(57, 'Bautista', 'Vigier', 'vigierbautista@gmail.com', '$2y$10$fZ56rZuMutKqLDLGYHQaIe61ck1GJd1Rli.dSqKU6wr9Rtwc.Lsgm', '', 'default.png'),
	(58, 'asdf', 'asdf', '', '$2y$10$06snYH.q8./CYZuKgqAHnuLXckww/f4pZAFotWyeJo5i8HX71Ttoq', '', 'default.png');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
