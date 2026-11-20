-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2026 at 02:16 PM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cinerent`
--

-- --------------------------------------------------------

--
-- Table structure for table `cartitems`
--

CREATE TABLE `cartitems` (
  `filmId` int(10) NOT NULL,
  `userId` int(10) NOT NULL,
  `addedDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cartitems`
--

INSERT INTO `cartitems` (`filmId`, `userId`, `addedDate`) VALUES
(4, 3, '2026-11-18'),
(6, 4, '2026-11-18');

-- --------------------------------------------------------

--
-- Table structure for table `films`
--

CREATE TABLE `films` (
  `id` int(10) NOT NULL,
  `genreId` int(10) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `description` varchar(300) DEFAULT NULL,
  `director` varchar(50) DEFAULT NULL,
  `releaseYear` int(10) DEFAULT NULL,
  `stock` int(10) DEFAULT NULL,
  `poster` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `films`
--

INSERT INTO `films` (`id`, `genreId`, `title`, `description`, `director`, `releaseYear`, `stock`, `poster`) VALUES
(1, 4, 'Inception', 'A thief who steals secrets through dreams is given a chance to plant an idea in a target\'s mind.', 'Christopher Nolan', 2010, 5, NULL),
(2, 1, 'The Dark Knight', 'Batman faces the Joker, a criminal mastermind who pushes Gotham City into chaos.', 'Christopher Nolan', 2008, 4, NULL),
(3, 2, 'Parasite', 'A poor family schemes to become employed by a wealthy household, with unexpected consequences.', 'Bong Joon-ho', 2019, 3, NULL),
(4, 5, 'Get Out', 'A young man visits his girlfriend\'s family estate and uncovers a disturbing secret.', 'Jordan Peele', 2017, 3, NULL),
(5, 8, 'Spirited Away', 'A girl wanders into a world of spirits and must work in a bathhouse to save her parents.', 'Hayao Miyazaki', 2001, 6, NULL),
(6, 3, 'Superbad', 'Two high school friends try to enjoy one last wild night before graduation.', 'Greg Mottola', 2007, 2, NULL),
(7, 6, 'The Notebook', 'A young couple falls in love in the 1940s, told through an elderly man reading from a notebook.', 'Nick Cassavetes', 2004, 4, NULL),
(8, 4, 'Interstellar', 'A team of explorers travels through a wormhole in search of a new home for humanity.', 'Christopher Nolan', 2014, 5, NULL),
(10, 1, 'Mad Max: Fury Road', 'In a desert wasteland, a drifter and a rebel soldier flee a tyrant across the desert.', 'George Miller', 2015, 0, NULL),
(11, 7, 'Se7en', 'Two detectives hunt a serial killer who bases his crimes on the seven deadly sins.', 'David Fincher', 1995, 1, NULL),
(13, 8, 'Toy Story', 'A cowboy doll named Woody is profoundly threatened and jealous when a flashy new spaceman action figure, Buzz Lightyear, supplants him as the top toy in a boy\'s bedroom. When circumstances separate them from their owner, the two rivals must put aside their differences and work together to find their', 'John Lasseter', 1995, 3, 'assets/1795137640_Toy Story.jpg'),
(14, 7, 'Silent of the Lamps', 'A young FBI cadet must confide in an incarcerated and manipulative cannibal killer, Dr. Hannibal Lecter, to receive his help on catching another serial killer who skins his victims.', 'Jonathan Demme', 1991, 4, 'assets/1795142704_Silent of the Lambs.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE `genre` (
  `id` int(10) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`id`, `name`) VALUES
(1, 'Action'),
(8, 'Animation'),
(3, 'Comedy'),
(2, 'Drama'),
(5, 'Horror'),
(6, 'Romance'),
(4, 'Sci-Fi'),
(7, 'Thriller');

-- --------------------------------------------------------

--
-- Table structure for table `rentaldetails`
--

CREATE TABLE `rentaldetails` (
  `rentalId` int(11) NOT NULL,
  `filmId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rentaldetails`
--

INSERT INTO `rentaldetails` (`rentalId`, `filmId`) VALUES
(1, 1),
(1, 2),
(2, 6),
(2, 10),
(3, 3),
(3, 8),
(4, 4),
(4, 5),
(5, 4),
(6, 8),
(7, 10),
(8, 1),
(9, 3),
(10, 3),
(11, 3),
(12, 8),
(13, 4),
(14, 13),
(15, 13),
(16, 13),
(17, 11);

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `id` int(10) NOT NULL,
  `userId` int(10) DEFAULT NULL,
  `borrowDate` date DEFAULT NULL,
  `dueDate` date DEFAULT NULL,
  `returnDate` date DEFAULT NULL,
  `status` varchar(15) DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rentals`
--

INSERT INTO `rentals` (`id`, `userId`, `borrowDate`, `dueDate`, `returnDate`, `status`, `createdAt`) VALUES
(1, 2, '2026-04-01', '2026-04-08', '2026-04-06', 'Returned', '2026-11-18 08:10:17'),
(2, 2, '2026-04-10', '2026-04-17', NULL, 'Active', '2026-11-18 08:10:17'),
(3, 3, '2026-04-05', '2026-04-12', '2026-04-11', 'Returned', '2026-11-18 08:10:17'),
(4, 2, '2026-04-21', '2026-04-28', NULL, 'Active', '2026-11-18 08:10:17'),
(5, 11, '2026-11-19', '2026-11-26', '2026-11-19', 'Returned', '2026-11-19 05:09:32'),
(6, 11, '2026-11-19', '2026-11-26', '2026-11-19', 'Returned', '2026-11-19 05:20:47'),
(7, 11, '2026-11-19', '2026-11-26', '2026-11-19', 'Returned', '2026-11-19 05:21:05'),
(8, 11, '2026-11-19', '2026-11-26', '2026-11-19', 'Returned', '2026-11-19 05:35:33'),
(9, 11, '2026-11-19', '2026-11-26', '2026-11-19', 'Returned', '2026-11-19 05:36:14'),
(10, 11, '2026-11-19', '2026-11-26', '2026-11-19', 'Returned', '2026-11-19 05:36:22'),
(11, 11, '2026-11-19', '2026-11-26', '2026-11-19', 'Returned', '2026-11-19 05:36:27'),
(12, 11, '2026-11-20', '2026-11-27', '2026-11-20', 'Returned', '2026-11-20 00:14:32'),
(13, 11, '2026-11-20', '2026-11-27', '2026-11-20', 'Returned', '2026-11-20 00:41:17'),
(14, 11, '2026-11-20', '2026-11-27', '2026-11-20', 'Returned', '2026-11-20 01:21:00'),
(15, 11, '2026-11-20', '2026-11-27', '2026-11-20', 'Returned', '2026-11-20 01:22:49'),
(16, 11, '2026-11-20', '2026-11-27', '2026-11-20', 'Returned', '2026-11-20 01:22:56'),
(17, 12, '2026-11-20', '2026-11-27', '2026-11-20', 'Returned', '2026-11-20 02:37:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `dateOfBirth` date NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'Member',
  `createdAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `gender`, `dateOfBirth`, `role`, `createdAt`) VALUES
(1, 'lauren', 'lauren@gmail.com', '$lauren123', 'Female', '1990-01-15', 'Admin', '2026-11-18 08:10:17'),
(2, 'boby_wong', 'boby@gmail.com', '$boby345', 'Male', '1995-06-20', 'Member', '2026-11-18 08:10:17'),
(3, 'alexander_jovan', 'alexander@gmail.com', '$alex567', 'Male', '1998-03-10', 'Member', '2026-11-18 08:10:17'),
(4, 'cika_alexabeth', 'cika@gmail.com', '$cika789', 'Female', '2000-11-05', 'Admin', '2026-11-18 08:10:17'),
(11, 'jesslyn', 'halobang@gmail.com', '$2y$12$162BnojgDCcMZyuBSUK6be005ji/fgm4/GyAn8OTpz9qqdwb7h5ha', 'Female', '2026-11-19', 'Admin', '2026-11-19 03:23:38'),
(12, 'wasap', 'gggeming@gmail.com', '$2y$12$ggHCz2EHVK9AzXHD4OJU5.mHjCmIF3bspRZFk3BQr6/rN3BczLAne', 'Male', '2026-11-20', 'Member', '2026-11-20 02:36:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cartitems`
--
ALTER TABLE `cartitems`
  ADD PRIMARY KEY (`filmId`,`userId`),
  ADD KEY `user_cart_fk` (`userId`);

--
-- Indexes for table `films`
--
ALTER TABLE `films`
  ADD PRIMARY KEY (`id`),
  ADD KEY `genre_genreid_fk` (`genreId`);

--
-- Indexes for table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `rentaldetails`
--
ALTER TABLE `rentaldetails`
  ADD PRIMARY KEY (`rentalId`,`filmId`),
  ADD KEY `filmId` (`filmId`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_userid_fk` (`userId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `films`
--
ALTER TABLE `films`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `genre`
--
ALTER TABLE `genre`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cartitems`
--
ALTER TABLE `cartitems`
  ADD CONSTRAINT `film_cart_fk` FOREIGN KEY (`filmId`) REFERENCES `films` (`id`),
  ADD CONSTRAINT `user_cart_fk` FOREIGN KEY (`userId`) REFERENCES `users` (`id`);

--
-- Constraints for table `films`
--
ALTER TABLE `films`
  ADD CONSTRAINT `genre_genreid_fk` FOREIGN KEY (`genreId`) REFERENCES `genre` (`id`);

--
-- Constraints for table `rentaldetails`
--
ALTER TABLE `rentaldetails`
  ADD CONSTRAINT `rentaldetails_ibfk_1` FOREIGN KEY (`rentalId`) REFERENCES `rentals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rentaldetails_ibfk_2` FOREIGN KEY (`filmId`) REFERENCES `films` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `users_userid_fk` FOREIGN KEY (`userId`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
