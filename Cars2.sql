-- Adminer 4.8.4 MySQL 8.0.40-0ubuntu0.24.04.1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `cars`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_name` varchar(25) UNIQUE NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `privileges` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `cars` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `make` varchar(25) NOT NULL,
  `model` varchar(40) NOT NULL,
  `year` year NOT NULL,
  `description` text NOT NULL,
  `user_name` varchar(25) NOT NULL,
  `image_path` varchar(150) CHARACTER SET utf8mb4 NOT NULL DEFAULT 'images/default_no_image.jpeg',
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `cars_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `cars` (`id`, `user_id`, `make`, `model`, `year`, `description`, `user_name`, `image_path`, `deleted`) VALUES
(1,	4,	'Alfa Romeo',	'G6',	'2021',	'2.0L 280HP I4 DI Turbo Engine w/ Engine Stop/Start. It looks awesome but is not that fast.',	'steve',	'images/erik-mclean-54WyjJurNN8-unsplash.jpg',	0),
(2,	3,	'Volkswagen',	'Beetle',	'1960',	'1,192 cc air-cooled engine delivered 36 factory-rated horsepower. Slow but unique!',	'bob',	'images/dan-gold-N7RiDzfF2iw-unsplash.jpg',	0),
(3,	3,	'Porsche',	'Panamera Turbo',	'2019',	'2.9-liter twin-turbo V6 engine with power output of 260 kW (348 hp)',	'bob',	'images/campbell-3ZUsNJhi_Ik-unsplash.jpg',	0),
(4,	4,	'Nissan',	'370zx',	'2022',	'3.7-liter V6 engine that puts out 332 horsepower. What a cool car!',	'steve',	'images/erik-mclean-dbLgODXOPgo-unsplash.jpg',	0),
(5,	4,	'BMW ',	'M3',	'1984',	'200 hp 4 cylinder. Wow what a classic car!',	'steve',	'images/hayes-potter-dHuKem53H0w-unsplash.jpg',	0),
(6,	3,	'Audi',	'R8',	'2020',	'5.2 L V10 600hp. This car is FAST, and just look at it! Beautiful.',	'bob',	'images/tyler-clemmensen-d1Jum1vVLew-unsplash.jpg',	0),
(7,	3,	'Nissan',	'GT-R',	'2024',	'3.8 L V6 600hp. This car is sweet, and relatively cheap!',	'bob',	'images/josh-berquist-9nrPNX1QWEM-unsplash.jpg',	0),
(8,	5,	'BMW',	'M3',	'2000',	'What a nice car!',	'john',	'images/hayes-potter-dHuKem53H0w-unsplash.jpg',	1);



INSERT INTO `users` (`user_id`, `user_name`, `password_hash`, `privileges`) VALUES
(3,	'bob',	'$2y$10$2AR68C64xVbqgpqlpEHpAupzs24RSPsDBohSnpUzRsDB05XCGpvSu',	0),
(4,	'steve',	'$2y$10$kkj/3APKDut2Dux2v0HaQOAu7XoWdSvrSH1.H9npnSLYEBEO/N.me',	0),
(5,	'john',	'$2y$10$PaApy2zuZE8AfNB8SKD/1u5gNaK.VhTkGGFYHzdpWxpI5Vd/87sHS',	0);

-- 2025-05-10 03:50:10
