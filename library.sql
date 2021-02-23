-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Feb 23, 2021 at 06:27 PM
-- Server version: 5.7.25
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `library1`
--

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `author_id` int(11) NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `social_security` int(11) DEFAULT NULL,
  `birthyear` int(11) DEFAULT NULL,
  `author_page` varchar(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `authors`
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
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `ISBN` int(11) NOT NULL,
  `book_title` varchar(255) NOT NULL,
  `total_pages` int(11) NOT NULL,
  `edition_number` int(11) NOT NULL,
  `year_published` int(11) NOT NULL,
  `publishing_company_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `ISBN`, `book_title`, `total_pages`, `edition_number`, `year_published`, `publishing_company_id`) VALUES
(123, 963852741, 'Book One', 145, 4, 2008, 1),
(456, 753869412, 'Book Four', 564, 3, 2017, 1),
(567, 159263478, 'Book Five', 44, 8, 2001, 2),
(678, 584267913, 'Book Six', 88, 1, 1972, 2),
(789, 357689241, 'Book Seven', 413, 1, 1995, 2),
(890, 654823791, 'Book Eight', 90, 2, 2003, 2),
(901, 456789123, 'Book Nine', 120, 2, 1956, 2),
(991, 852963741, 'Book Ten', 720, 2, 1985, 1),
(992, 2, 'New Book', 123, 3, 1991, 1);

-- --------------------------------------------------------

--
-- Table structure for table `book_author`
--

CREATE TABLE `book_author` (
  `id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `book_author`
--

INSERT INTO `book_author` (`id`, `author_id`, `book_id`) VALUES
(101, 147, 123),
(401, 369, 456),
(501, 478, 567),
(601, 569, 678),
(701, 569, 789),
(801, 632, 890),
(901, 478, 901),
(1001, 147, 991),
(1002, 478, 992);

-- --------------------------------------------------------

--
-- Table structure for table `book_status`
--

CREATE TABLE `book_status` (
  `status_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `shelf_id` int(11) NOT NULL,
  `unique_barcode` varchar(255) NOT NULL,
  `date_added` date NOT NULL,
  `reserved` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `book_status`
--

INSERT INTO `book_status` (`status_id`, `book_id`, `shelf_id`, `unique_barcode`, `date_added`, `reserved`) VALUES
(1, 123, 963852741, '963852741', '2008-04-30', 1),
(3, 345, 741852963, '741852963', '2013-04-23', 0),
(4, 456, 753869412, '753869412', '2017-04-12', 0),
(5, 567, 159263478, '159263478', '2001-04-23', 1),
(6, 678, 584267913, '584267913', '1972-03-12', 0),
(7, 789, 357689241, '357689241', '1995-04-21', 1),
(8, 890, 654823791, '654823791', '2003-06-14', 0),
(9, 901, 456789123, '456789123', '1993-12-06', 1),
(10, 991, 852963741, '852963741', '1985-02-24', 0),
(14, 890, 987654327, '987654367', '2020-12-18', 0),
(15, 890, 987654328, '987654368', '2020-12-18', 0),
(16, 992, 987654329, '987654369', '2021-01-28', 0);

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `gallery_ID` int(11) NOT NULL,
  `imgFile` longtext NOT NULL,
  `galleryOrder` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`gallery_ID`, `imgFile`, `galleryOrder`) VALUES
(13, 'gallery.5ed6722d79fdc0.85853889.jpg', '2'),
(14, 'gallery.5ed672312a3bb4.30135025.jpg', '3'),
(15, 'gallery.5ed67234af1705.63273921.jpg', '4'),
(16, 'gallery.5ed672388cddc2.37170631.jpg', '5'),
(17, 'gallery.5ed6723c104f48.79515564.jpg', '6'),
(18, 'gallery.600eaebb4bb591.36113012.jpg', '6');

-- --------------------------------------------------------

--
-- Table structure for table `publisher`
--

CREATE TABLE `publisher` (
  `publisher_id` int(11) NOT NULL,
  `publisher_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `publisher`
--

INSERT INTO `publisher` (`publisher_id`, `publisher_name`) VALUES
(1, 'Amanda\'s Publishing'),
(2, 'Haus of Publishing');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_ID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `hashedpwd` varchar(255) NOT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_ID`, `username`, `password`, `hashedpwd`, `role`) VALUES
(1, 'admin', 'admin', '0e8cd409a23c2e7ad1c5b22b101dfa16720550dc547921c7a099b75c7f405fd4', 1),
(2, 'moderator', 'moderator', '6e8b6aeec363100587866b902326814008ffe88fc8b4f51a7a20abf51cef1d8f', 2),
(3, 'test', 'test', 'a6e1acdd0cc7e00d02b90bccb2e21892289d1e93f622b8760cb0e076def1f42b', 3),
(4, 'user1', 'passwordtest', 'f49d4a0df7ef03d9d32c8843a897200d9a71d988afc15be7e8b9c8e2b8908c5c', 3),
(6, 'user2', 'user2', '0b4178850556728e781ccaf78df4c9848a88c6066eb8fbebce8d7f345937d4c5', 3),
(7, 'user3', 'user3', '95b06c08b6ea430c731783f2f70a7001c3e26ba1c99b0291c371871ffc459cd9', 3),
(8, 'usernew', NULL, '37fca346aefb4e2e7ccecd1b7fec9a9fcb7bdc60fa87386ae48eb3f297e42ddd', 3),
(9, 'usernew1', NULL, 'b0f784fe99f37c57188d100f79bffa0e877f38c8ad50baf7e474b7596a02b5bf', 3);

-- --------------------------------------------------------

--
-- Table structure for table `user_reserved_book`
--

CREATE TABLE `user_reserved_book` (
  `reserved_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_reserved_book`
--

INSERT INTO `user_reserved_book` (`reserved_id`, `user_id`, `status_id`) VALUES
(9, 3, 5),
(10, 3, 9),
(11, 4, 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`author_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `fk_publisher_company` (`publishing_company_id`);

--
-- Indexes for table `book_author`
--
ALTER TABLE `book_author`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_book_id` (`book_id`),
  ADD KEY `fk_author_id` (`author_id`) USING BTREE;

--
-- Indexes for table `book_status`
--
ALTER TABLE `book_status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`gallery_ID`);

--
-- Indexes for table `publisher`
--
ALTER TABLE `publisher`
  ADD PRIMARY KEY (`publisher_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_ID`);

--
-- Indexes for table `user_reserved_book`
--
ALTER TABLE `user_reserved_book`
  ADD PRIMARY KEY (`reserved_id`),
  ADD KEY `fk_user_reserved` (`user_id`),
  ADD KEY `fk_book_status` (`status_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `author_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=633;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=993;

--
-- AUTO_INCREMENT for table `book_author`
--
ALTER TABLE `book_author`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1003;

--
-- AUTO_INCREMENT for table `book_status`
--
ALTER TABLE `book_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `gallery_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `publisher`
--
ALTER TABLE `publisher`
  MODIFY `publisher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_reserved_book`
--
ALTER TABLE `user_reserved_book`
  MODIFY `reserved_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_publisher_company` FOREIGN KEY (`publishing_company_id`) REFERENCES `publisher` (`publisher_id`);

--
-- Constraints for table `book_author`
--
ALTER TABLE `book_author`
  ADD CONSTRAINT `fk_author_od` FOREIGN KEY (`author_id`) REFERENCES `authors` (`author_id`),
  ADD CONSTRAINT `fk_book_id` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);

--
-- Constraints for table `user_reserved_book`
--
ALTER TABLE `user_reserved_book`
  ADD CONSTRAINT `fk_book_status` FOREIGN KEY (`status_id`) REFERENCES `book_status` (`status_id`),
  ADD CONSTRAINT `fk_user_reserved` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_ID`);
