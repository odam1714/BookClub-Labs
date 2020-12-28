-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Värd: 127.0.0.1:3306
-- Tid vid skapande: 18 dec 2020 kl 18:53
-- Serverversion: 5.7.23
-- PHP-version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `library`
--
/*CREATE DATABASE IF NOT EXISTS `library` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;*/
USE `library1`;

-- --------------------------------------------------------

--
-- Tabellstruktur `authors`
--

DROP TABLE IF EXISTS `authors`;
CREATE TABLE IF NOT EXISTS `authors` (
  `author_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `social_security` int(11) DEFAULT NULL,
  `birthyear` int(11) DEFAULT NULL,
  `author_page` varchar(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`author_id`)
) ENGINE=InnoDB AUTO_INCREMENT=633 DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `authors`
--

INSERT INTO `authors` (`author_id`, `first_name`, `last_name`, `social_security`, `birthyear`, `author_page`) VALUES
(147, 'Amanda', 'Odina', 123456789, 1985, 'Here is the page.'),
(258, 'Sammi', 'Chen', 321654987, 1966, 'Here is the page.'),
(369, 'Zoe', 'Spoon', 987654321, 1991, 'Here is the page.'),
(478, 'Troy', 'Bolton', NULL, 1974, 'Here is the page.'),
(569, 'Lydia', 'Martin', 741852963, NULL, 'Here is the page.'),
(632, 'Onika', 'Maraj', 852741963, 1932, 'Here is the page.');

-- --------------------------------------------------------

--
-- Tabellstruktur `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE IF NOT EXISTS `books` (
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `ISBN` int(11) NOT NULL,
  `book_title` varchar(255) NOT NULL,
  `total_pages` int(11) NOT NULL,
  `edition_number` int(11) NOT NULL,
  `year_published` int(11) NOT NULL,
  `publishing_company_id` int(11) NOT NULL,
  PRIMARY KEY (`book_id`),
  KEY `fk_publisher_company` (`publishing_company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=999 DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `books`
--

INSERT INTO `books` (`book_id`, `ISBN`, `book_title`, `total_pages`, `edition_number`, `year_published`, `publishing_company_id`) VALUES
(123, 963852741, 'Book One', 145, 4, 2008, 1),
(234, 987654321, 'Book Two', 825, 2, 2009, 1),
(456, 753869412, 'Book Four', 564, 3, 2017, 1),
(567, 159263478, 'Book Five', 44, 8, 2001, 2),
(678, 584267913, 'Book Six', 88, 1, 1972, 2),
(789, 357689241, 'Book Seven', 413, 1, 1995, 2),
(890, 654823791, 'Book Eight', 90, 2, 2003, 2),
(901, 456789123, 'Book Nine', 120, 2, 1956, 2),
(991, 852963741, 'Book Ten', 720, 2, 1985, 1);

-- --------------------------------------------------------

--
-- Tabellstruktur `book_author`
--

DROP TABLE IF EXISTS `book_author`;
CREATE TABLE IF NOT EXISTS `book_author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_book_id` (`book_id`),
  KEY `fk_author_id` (`author_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1007 DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `book_author`
--

INSERT INTO `book_author` (`id`, `author_id`, `book_id`) VALUES
(101, 147, 123),
(201, 258, 234),
(401, 369, 456),
(501, 478, 567),
(601, 569, 678),
(701, 569, 789),
(801, 632, 890),
(901, 478, 901),
(1001, 147, 991);

-- --------------------------------------------------------

--
-- Tabellstruktur `book_status`
--

DROP TABLE IF EXISTS `book_status`;
CREATE TABLE IF NOT EXISTS `book_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `shelf_id` int(11) NOT NULL,
  `unique_barcode` varchar(255) NOT NULL,
  `date_added` date NOT NULL,
  `reserved` tinyint(1) NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `book_status`
--

INSERT INTO `book_status` (`status_id`, `book_id`, `shelf_id`, `unique_barcode`, `date_added`, `reserved`) VALUES
(1, 123, 963852741, '963852741', '2008-04-30', 1),
(2, 234, 987654321, '987654321', '2009-04-30', 0),
(3, 345, 741852963, '741852963', '2013-04-23', 0),
(4, 456, 753869412, '753869412', '2017-04-12', 0),
(5, 567, 159263478, '159263478', '2001-04-23', 1),
(6, 678, 584267913, '584267913', '1972-03-12', 0),
(7, 789, 357689241, '357689241', '1995-04-21', 1),
(8, 890, 654823791, '654823791', '2003-06-14', 0),
(9, 901, 456789123, '456789123', '1993-12-06', 1),
(10, 991, 852963741, '852963741', '1985-02-24', 0),
(11, 234, 987654325, '987654365', '2009-05-23', 0),
(14, 890, 987654327, '987654367', '2020-12-18', 0),
(15, 890, 987654328, '987654368', '2020-12-18', 0);

-- --------------------------------------------------------

--
-- Tabellstruktur `gallery`
--

DROP TABLE IF EXISTS `gallery`;
CREATE TABLE IF NOT EXISTS `gallery` (
  `gallery_ID` int(11) NOT NULL AUTO_INCREMENT,
  `imgFile` longtext NOT NULL,
  `galleryOrder` longtext NOT NULL,
  PRIMARY KEY (`gallery_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `gallery`
--

INSERT INTO `gallery` (`gallery_ID`, `imgFile`, `galleryOrder`) VALUES
(13, 'gallery.5ed6722d79fdc0.85853889.jpg', '2'),
(14, 'gallery.5ed672312a3bb4.30135025.jpg', '3'),
(15, 'gallery.5ed67234af1705.63273921.jpg', '4'),
(16, 'gallery.5ed672388cddc2.37170631.jpg', '5'),
(17, 'gallery.5ed6723c104f48.79515564.jpg', '6');

-- --------------------------------------------------------

--
-- Tabellstruktur `publisher`
--

DROP TABLE IF EXISTS `publisher`;
CREATE TABLE IF NOT EXISTS `publisher` (
  `publisher_id` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_name` varchar(64) NOT NULL,
  PRIMARY KEY (`publisher_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `publisher`
--

INSERT INTO `publisher` (`publisher_id`, `publisher_name`) VALUES
(1, 'Amanda\'s Publishing'),
(2, 'Haus of Publishing');

-- --------------------------------------------------------

--
-- Tabellstruktur `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `hashedpwd` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  PRIMARY KEY (`user_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `users`
--

INSERT INTO `users` (`user_ID`, `username`, `password`, `hashedpwd`, `role`) VALUES
(1, 'admin', 'admin', '0e8cd409a23c2e7ad1c5b22b101dfa16720550dc547921c7a099b75c7f405fd4', 1),
(2, 'moderator', 'moderator', '6e8b6aeec363100587866b902326814008ffe88fc8b4f51a7a20abf51cef1d8f', 2),
(3, 'test', 'test', 'a6e1acdd0cc7e00d02b90bccb2e21892289d1e93f622b8760cb0e076def1f42b', 3),
(4, 'user1', 'passwordtest', 'f49d4a0df7ef03d9d32c8843a897200d9a71d988afc15be7e8b9c8e2b8908c5c', 3),
(6, 'user2', 'user2', '0b4178850556728e781ccaf78df4c9848a88c6066eb8fbebce8d7f345937d4c5', 3),
(7, 'user3', 'user3', '95b06c08b6ea430c731783f2f70a7001c3e26ba1c99b0291c371871ffc459cd9', 3);

-- --------------------------------------------------------

--
-- Tabellstruktur `user_reserved_book`
--

DROP TABLE IF EXISTS `user_reserved_book`;
CREATE TABLE IF NOT EXISTS `user_reserved_book` (
  `reserved_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  PRIMARY KEY (`reserved_id`),
  KEY `fk_user_reserved` (`user_id`),
  KEY `fk_book_status` (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `user_reserved_book`
--

INSERT INTO `user_reserved_book` (`reserved_id`, `user_id`, `status_id`) VALUES
(9, 3, 5),
(10, 3, 9),
(11, 4, 7);

--
-- Restriktioner för dumpade tabeller
--

--
-- Restriktioner för tabell `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_publisher_company` FOREIGN KEY (`publishing_company_id`) REFERENCES `publisher` (`publisher_id`);

--
-- Restriktioner för tabell `book_author`
--
ALTER TABLE `book_author`
  ADD CONSTRAINT `fk_author_od` FOREIGN KEY (`author_id`) REFERENCES `authors` (`author_id`),
  ADD CONSTRAINT `fk_book_id` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);

--
-- Restriktioner för tabell `user_reserved_book`
--
ALTER TABLE `user_reserved_book`
  ADD CONSTRAINT `fk_book_status` FOREIGN KEY (`status_id`) REFERENCES `book_status` (`status_id`),
  ADD CONSTRAINT `fk_user_reserved` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
