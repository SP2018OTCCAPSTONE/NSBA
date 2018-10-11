-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2018 at 05:57 AM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nsba`
--

-- --------------------------------------------------------

--
-- Table structure for table `remembered_logins`
--

CREATE TABLE `remembered_logins` (
  `token` varchar(40) NOT NULL,
  `userId` int(11) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userId` int(11) NOT NULL,
  `firstName` varchar(128) NOT NULL,
  `lastName` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `emailSecondary` varchar(128) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `password_reset_token` varchar(40) DEFAULT NULL,
  `password_reset_expires_at` datetime DEFAULT NULL,
  `activation_token` varchar(40) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `firstName`, `lastName`, `email`, `emailSecondary`, `password`, `password_reset_token`, `password_reset_expires_at`, `activation_token`, `is_active`, `is_admin`) VALUES
(4, 'Justin', 'Mangan', 'cheetahchrome13@gmail.com', 'wtf@gmail.com', '$2y$10$d2MnGNOGxAYn92rSicxFOemoVckcJ44NaavKjXee6O/CI0WAUErxG', NULL, NULL, NULL, 1, 1),
(5, 'Joe', 'Strummer', 'nsbadummy1@mailsac.com', '', '$2y$10$yQ0g95IyHnmRKCfU7rtLEesgJdC9Wa/oiwNXfE.L/viYDtNVKXzg6', NULL, NULL, NULL, 1, 0),
(8, 'Poly', 'Styrene', 'nsbadummy2@mailsac.com', '', '$2y$10$vEtRHf4iP0LkFdUIy8kSFu/IGqT52I/Pk7MV/rG.KKIwsyExjZTRq', NULL, NULL, NULL, 1, 0),
(9, 'Buster', 'Bloodvessel', 'nsbadummy3@mailsac.com', '', '$2y$10$cBp9A3e2uSf1MiYUo5yOYeBTQgzrBbeBvNmyzgHUSRNQApLNWupDe', NULL, NULL, NULL, 1, 0),
(10, 'Stiv', 'Bators', 'nsbadummy4@mailsac.com', '', '$2y$10$vGL0XeD022VF56ZRgoDOEO8fU2e2W6L081pasjm6cBYSAo6BJomiO', NULL, NULL, NULL, 1, 0),
(11, 'Captain', 'Sensible', 'nsbadummy5@mailsac.com', '', '$2y$10$bfn.ljVKM9jitiGzBQXbK.A4rZf0wknQguYxN26IXdrIEOWrtx7PO', NULL, NULL, NULL, 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `remembered_logins`
--
ALTER TABLE `remembered_logins`
  ADD PRIMARY KEY (`token`),
  ADD KEY `userId` (`userId`),
  ADD KEY `expires_at` (`expires_at`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`),
  ADD UNIQUE KEY `activation_token` (`activation_token`),
  ADD KEY `firstName` (`firstName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `remembered_logins`
--
ALTER TABLE `remembered_logins`
  ADD CONSTRAINT `remembered_logins_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
