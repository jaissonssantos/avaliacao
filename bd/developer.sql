# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.35)
# Database: avaliame
# Generation Time: 2017-05-15 22:05:23 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table cliente
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cliente`;

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) NOT NULL,
  `email` varchar(160) NOT NULL,
  `telefone` varchar(14) NOT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;

INSERT INTO `cliente` (`id`, `nome`, `email`, `telefone`, `senha`, `created_at`, `updated_at`)
VALUES
	(1,'Eaton','laoreet@sagittisDuis.co.uk','(98) 1316-9306',NULL,'2017-05-23 17:32:36','2016-09-04 11:27:45'),
	(2,'Samuel','dolor.Nulla.semper@aliquameuaccumsan.com','(04) 0114-8397',NULL,'2017-03-24 10:02:32','2018-04-05 04:39:57'),
	(3,'Nero','sodales@ipsumDonecsollicitudin.org','(02) 3965-1271',NULL,'2016-08-08 00:20:07','2016-05-22 11:30:08'),
	(4,'Tiger','non.lorem@placerat.com','(65) 0041-5713',NULL,'2016-12-12 09:24:40','2017-10-13 02:15:56'),
	(5,'Griffin','rhoncus@Nuncuterat.com','(05) 2294-2335',NULL,'2016-12-13 15:36:08','2016-08-11 13:30:14'),
	(6,'Zahir','pellentesque@molestie.org','(32) 9435-6909',NULL,'2017-08-21 02:02:16','2017-02-26 15:39:30'),
	(7,'Slade','gravida.non.sollicitudin@eueleifendnec.net','(21) 9991-3287',NULL,'2017-01-12 14:18:42','2016-12-12 17:57:21'),
	(8,'Erich','Curabitur.vel@etipsumcursus.co.uk','(61) 9309-7178',NULL,'2017-08-14 15:16:26','2017-09-07 04:08:08'),
	(9,'Zachery','ridiculus.mus@eutellus.org','(18) 0598-4151',NULL,'2017-01-21 09:57:09','2017-04-01 05:03:36'),
	(10,'Hilel','ac.turpis@euodio.net','(28) 5350-8239',NULL,'2017-07-10 16:08:12','2018-03-24 07:16:46'),
	(11,'Abbot','Morbi.non@malesuadaiderat.co.uk','(81) 8197-5283',NULL,'2016-08-30 15:43:37','2016-11-22 05:54:52'),
	(12,'Owen','eu@musAeneaneget.org','(76) 5792-7762',NULL,'2016-06-20 08:22:41','2016-08-20 09:26:07'),
	(13,'Ezra','Vestibulum.ut@interdum.edu','(36) 4010-3589',NULL,'2017-05-16 05:30:34','2017-12-06 19:37:48'),
	(14,'Silas','Cras.vehicula.aliquet@tellus.edu','(83) 3451-3618',NULL,'2017-09-06 10:19:36','2017-09-22 04:26:32'),
	(15,'Fletcher','at@accumsanconvallis.edu','(81) 5320-1571',NULL,'2017-02-21 12:28:15','2017-08-18 09:19:12'),
	(16,'Wayne','eu.eros.Nam@necdiamDuis.co.uk','(10) 7816-9069',NULL,'2017-04-12 10:28:46','2017-08-13 14:50:40'),
	(17,'Fitzgerald','consequat.dolor@dictumcursus.co.uk','(49) 9277-3191',NULL,'2016-05-14 00:50:39','2017-03-29 03:14:14'),
	(18,'Jin','auctor.nunc.nulla@pellentesqueSed.net','(71) 6246-0582',NULL,'2016-09-07 22:44:21','2018-04-05 04:34:37'),
	(19,'Lucius','lobortis.ultrices@tempusnon.net','(36) 7270-7868',NULL,'2017-08-22 09:45:25','2017-08-05 08:47:10'),
	(20,'Phillip','erat.volutpat@penatibuset.edu','(63) 3567-7440',NULL,'2017-07-20 19:45:56','2018-02-19 14:15:10'),
	(21,'Cooper','risus.Nunc@libero.net','(46) 4542-0106',NULL,'2016-07-22 10:28:14','2017-12-23 06:04:02'),
	(22,'Kato','quam@sitamet.edu','(77) 0166-7051',NULL,'2016-11-29 09:40:16','2016-07-15 14:09:39'),
	(23,'Trevor','Integer@ullamcorperviverraMaecenas.net','(28) 6542-4442',NULL,'2016-11-26 11:44:29','2017-10-05 09:51:25'),
	(24,'Clayton','Phasellus.dolor@Curabiturvellectus.net','(44) 5215-2248',NULL,'2016-10-10 13:35:18','2017-06-20 14:47:02'),
	(25,'Hamish','consequat@augueeu.ca','(49) 4235-4238',NULL,'2017-03-17 13:31:25','2018-01-23 01:21:22'),
	(26,'Chandler','in@erat.org','(07) 4060-0376',NULL,'2017-07-01 02:58:28','2017-01-30 12:45:41'),
	(27,'Jack','id@euodio.com','(36) 1405-7201',NULL,'2017-01-23 06:39:48','2017-04-12 05:55:28'),
	(28,'Vance','ante.lectus.convallis@faucibusidlibero.com','(61) 1834-9704',NULL,'2016-06-01 10:01:48','2017-08-11 14:12:10'),
	(29,'Michael','Mauris.magna@Maecenas.ca','(58) 1283-8864',NULL,'2016-12-07 18:32:19','2017-12-31 17:25:04'),
	(30,'Brenden','varius@pharetraNam.edu','(37) 2048-1716',NULL,'2017-03-17 00:23:54','2017-08-08 21:54:48'),
	(31,'Harrison','mi.eleifend@augue.ca','(74) 4848-0289',NULL,'2016-11-14 10:07:52','2017-10-05 17:24:52'),
	(32,'Stephen','Cras@tincidunt.ca','(10) 3022-8580',NULL,'2018-03-13 08:36:12','2017-01-14 05:01:22'),
	(33,'Axel','magna@Fuscediamnunc.edu','(99) 3974-1212',NULL,'2016-12-06 01:49:44','2016-05-29 22:33:48'),
	(34,'Chancellor','a.facilisis.non@mattis.co.uk','(84) 4854-4025',NULL,'2016-06-18 12:02:38','2017-07-26 07:52:39'),
	(35,'Scott','volutpat.Nulla@elitfermentumrisus.ca','(16) 6987-1469',NULL,'2017-12-17 19:19:52','2017-12-23 13:37:31'),
	(36,'Zeus','quam.elementum@quamvel.edu','(82) 5367-6897',NULL,'2017-07-31 02:48:45','2018-04-18 09:21:03'),
	(37,'Dominic','Morbi.sit.amet@aliquetnec.com','(99) 3978-7104',NULL,'2017-11-25 19:07:10','2017-08-13 02:29:48'),
	(38,'Maxwell','faucibus@consequat.net','(22) 9550-4822',NULL,'2018-01-23 18:24:01','2017-09-02 07:33:22'),
	(39,'Preston','Donec.felis.orci@risus.com','(79) 5408-2353',NULL,'2017-04-12 11:17:41','2016-07-03 18:18:22'),
	(40,'Ferris','metus.In.nec@eu.net','(26) 3412-2476',NULL,'2018-01-09 09:45:49','2017-10-27 18:57:15'),
	(41,'Jelani','erat@Loremipsumdolor.edu','(93) 7594-5784',NULL,'2016-08-10 12:10:35','2017-12-04 22:01:53'),
	(42,'Giacomo','eget.ipsum@fringillaDonecfeugiat.org','(46) 2535-3305',NULL,'2017-09-28 10:33:41','2016-05-26 14:31:42'),
	(43,'Prescott','euismod@Craslorem.ca','(79) 8081-6883',NULL,'2016-10-08 23:03:09','2017-05-10 15:41:48'),
	(44,'Kieran','mauris.Suspendisse@inhendreritconsectetuer.net','(78) 5717-0566',NULL,'2018-04-23 19:09:39','2018-03-27 05:56:28'),
	(45,'Fuller','Nunc.pulvinar.arcu@vitae.edu','(69) 8166-1761',NULL,'2017-06-20 16:39:36','2017-01-28 04:20:37'),
	(46,'Thor','faucibus.lectus.a@nisi.org','(81) 2795-3696',NULL,'2017-12-20 14:55:50','2018-04-30 05:20:09'),
	(47,'Ezra','tortor.Integer@Integer.ca','(06) 7478-7552',NULL,'2017-05-07 18:59:44','2016-05-24 08:30:56'),
	(48,'Hop','bibendum.sed.est@malesuadautsem.org','(07) 9595-5813',NULL,'2017-04-26 00:19:58','2017-04-27 18:30:27'),
	(49,'Cyrus','facilisis@eratSed.edu','(38) 1087-3088',NULL,'2016-09-03 08:02:34','2017-04-10 00:10:49'),
	(50,'Jonah','consectetuer@id.org','(96) 1930-0969',NULL,'2017-07-13 14:07:30','2017-07-15 11:05:43'),
	(51,'Upton','Integer@ullamcorperDuis.edu','(36) 4807-3581',NULL,'2016-12-13 22:08:22','2018-01-18 03:48:55'),
	(52,'Thomas','sed.orci@id.com','(50) 6017-1753',NULL,'2017-02-05 20:41:42','2017-10-10 20:09:32'),
	(53,'Noble','lobortis@ultriciesdignissim.co.uk','(28) 2951-0111',NULL,'2017-05-12 11:31:25','2016-12-28 16:32:29'),
	(54,'Hiram','mollis@Pellentesquehabitantmorbi.org','(88) 2602-5679',NULL,'2017-01-16 23:32:44','2018-04-04 04:13:06'),
	(55,'Gabriel','Proin.dolor.Nulla@Quisquetinciduntpede.ca','(51) 7162-1486',NULL,'2018-04-18 21:27:54','2018-02-14 23:37:31'),
	(56,'Rafael','sit@egetmagna.com','(67) 1357-3806',NULL,'2017-10-28 22:51:47','2018-01-21 13:08:06'),
	(57,'David','iaculis.nec.eleifend@estac.edu','(73) 4058-5835',NULL,'2018-02-26 18:54:46','2016-05-16 01:30:18'),
	(58,'Lars','Cum@telluslorem.net','(82) 7428-1584',NULL,'2016-09-13 14:21:47','2017-02-25 13:34:31'),
	(59,'Steven','sit.amet.risus@vel.net','(93) 5469-9520',NULL,'2017-09-20 05:56:38','2016-12-18 23:25:00'),
	(60,'Cullen','Vivamus.sit.amet@enimnisl.edu','(11) 6197-8455',NULL,'2017-01-18 08:28:49','2018-03-02 11:12:24'),
	(61,'Quinn','nibh.Donec@Aliquamornarelibero.net','(81) 0234-8131',NULL,'2018-01-12 22:54:56','2016-07-02 10:54:50'),
	(62,'Brett','risus@erategetipsum.org','(28) 9252-4355',NULL,'2017-08-25 13:30:07','2016-12-03 16:03:24'),
	(63,'Ralph','a@fringillami.net','(86) 5246-7314',NULL,'2018-01-08 04:51:44','2017-06-23 16:17:38'),
	(64,'Aquila','ligula.Aenean@vitae.net','(28) 4684-9448',NULL,'2016-07-09 07:07:26','2016-08-13 15:57:45'),
	(65,'Kelly','augue@risusDonec.co.uk','(75) 6581-6241',NULL,'2017-04-14 20:11:02','2017-05-28 00:36:35'),
	(66,'Cyrus','vitae@Phaselluselit.ca','(01) 9020-7226',NULL,'2016-11-07 08:12:14','2016-06-18 23:58:41'),
	(67,'Perry','fringilla.purus.mauris@velitjusto.com','(35) 9841-5834',NULL,'2016-05-15 12:16:30','2017-03-22 01:14:46'),
	(68,'Galvin','massa.lobortis@metussit.net','(70) 5929-5340',NULL,'2017-04-22 20:41:51','2016-08-08 08:01:19'),
	(69,'Louis','Aenean.eget@dui.ca','(27) 5176-5903',NULL,'2016-10-17 07:04:18','2018-01-19 00:41:08'),
	(70,'Davis','tempor.erat.neque@acmattisornare.com','(56) 9888-2567',NULL,'2017-03-05 14:56:20','2017-04-05 16:21:48'),
	(71,'Emerson','mi.eleifend@tristiquealiquet.org','(85) 2823-2903',NULL,'2017-05-11 10:31:46','2016-10-17 18:13:17'),
	(72,'Aristotle','ultrices.posuere@purusaccumsan.net','(78) 6336-9543',NULL,'2017-11-07 19:39:16','2016-09-23 11:18:23'),
	(73,'Abdul','leo.in@rutrumFuscedolor.co.uk','(69) 5822-3444',NULL,'2016-09-15 17:02:28','2017-08-06 06:32:50'),
	(74,'Raymond','posuere.at@iaculisaliquetdiam.ca','(03) 0473-2628',NULL,'2017-10-24 03:39:10','2017-05-02 03:17:49'),
	(75,'Raja','dui.Cum.sociis@turpisIn.net','(35) 2520-8880',NULL,'2017-12-24 16:23:04','2017-11-02 11:00:56'),
	(76,'Harlan','elementum.lorem.ut@ipsumSuspendisse.com','(44) 8880-4629',NULL,'2018-01-28 02:19:40','2016-08-30 18:28:50'),
	(77,'Joseph','convallis.in@viverra.org','(06) 8403-0085',NULL,'2016-07-13 21:35:32','2017-02-28 15:21:06'),
	(78,'Kirk','in.faucibus@interdum.net','(98) 4779-1643',NULL,'2017-11-24 21:32:47','2016-06-11 03:33:23'),
	(79,'Howard','magna@lobortistellus.ca','(35) 0126-4960',NULL,'2016-09-17 04:14:48','2016-08-05 09:24:54'),
	(80,'Caldwell','cursus.non@mollis.edu','(23) 9881-1339',NULL,'2016-08-26 22:05:39','2016-12-05 02:18:58'),
	(81,'Nehru','hymenaeos@magnaDuis.com','(00) 2939-3295',NULL,'2017-05-26 06:55:43','2016-06-10 00:52:56'),
	(82,'Brandon','orci@metusInlorem.net','(51) 8887-1420',NULL,'2017-06-03 04:56:55','2017-05-11 13:29:56'),
	(83,'Andrew','conubia.nostra@Donecdignissim.org','(33) 7711-9708',NULL,'2018-03-04 18:58:06','2017-12-11 23:41:37'),
	(84,'Tiger','vitae.odio.sagittis@Maurisnondui.edu','(85) 7602-6016',NULL,'2016-08-01 19:00:30','2017-06-01 19:29:24'),
	(85,'Nicholas','tempor.diam.dictum@facilisisnon.org','(08) 9920-2759',NULL,'2017-06-13 19:57:59','2016-06-04 12:48:26'),
	(86,'Maxwell','Donec@montesnascetur.ca','(75) 9301-0643',NULL,'2016-05-11 17:49:13','2016-08-28 02:29:44'),
	(87,'Ezekiel','dictum.eleifend@vehiculaaliquetlibero.co.uk','(52) 1984-7239',NULL,'2017-03-23 12:00:43','2016-09-12 06:21:00'),
	(88,'Geoffrey','sed.pede@vehicularisusNulla.net','(33) 6778-1318',NULL,'2017-03-03 16:35:11','2016-09-25 03:54:18'),
	(89,'Declan','nibh.Donec@magnaPhasellus.com','(24) 8027-3477',NULL,'2016-06-12 20:50:07','2017-03-27 21:44:45'),
	(90,'Mannix','metus@tempordiamdictum.com','(04) 4996-4515',NULL,'2017-12-31 06:25:03','2016-11-19 06:17:16'),
	(91,'Ethan','aliquet.odio.Etiam@tinciduntnunc.org','(59) 6989-6664',NULL,'2017-01-28 08:09:52','2017-01-30 00:29:54'),
	(92,'Roth','Aenean.euismod@Vivamuseuismodurna.com','(50) 9481-9213',NULL,'2017-12-27 23:49:35','2018-04-06 06:32:40'),
	(93,'Raymond','ut.molestie@temporeratneque.com','(04) 2028-4975',NULL,'2017-01-07 07:19:42','2017-06-05 17:39:58'),
	(94,'Adrian','aliquam@enimEtiam.org','(43) 2137-6382',NULL,'2017-02-01 03:21:48','2018-03-04 20:55:30'),
	(95,'Dexter','vehicula.Pellentesque@lorem.co.uk','(31) 2415-0680',NULL,'2017-01-16 10:16:29','2017-05-02 03:10:27'),
	(96,'Dominic','mi.lacinia.mattis@eleifendCrassed.ca','(78) 9079-5188',NULL,'2016-08-20 15:29:21','2017-05-25 05:34:26'),
	(97,'Tyrone','malesuada.id@pellentesque.net','(29) 7757-5892',NULL,'2016-07-07 10:48:52','2016-07-29 21:55:52'),
	(98,'Lucian','nec@Nuncsollicitudincommodo.edu','(56) 8801-0705',NULL,'2017-08-10 18:15:30','2017-04-07 01:32:40'),
	(99,'Gage','hendrerit.id@sodalesMaurisblandit.ca','(81) 2579-5215',NULL,'2016-08-19 22:14:18','2018-03-03 21:05:30'),
	(100,'Wing','lobortis.tellus.justo@famesacturpis.net','(82) 6605-2473',NULL,'2018-01-04 11:36:21','2017-02-09 12:54:26');

/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
