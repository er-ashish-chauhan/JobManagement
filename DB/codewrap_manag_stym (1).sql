-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 07, 2021 at 02:26 AM
-- Server version: 5.7.23-23
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `codewrap_manag_stym`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `id` int(11) NOT NULL,
  `userName` varchar(120) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`id`, `userName`, `password`) VALUES
(1, 'admin', 'Test@12345');

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `id` int(11) NOT NULL,
  `firstName` varchar(200) DEFAULT NULL,
  `lastName` varchar(200) DEFAULT NULL,
  `emailId` varchar(200) DEFAULT NULL,
  `mobileNumber` char(12) DEFAULT NULL,
  `userPassword` varchar(255) DEFAULT NULL,
  `regDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `isActive` int(1) DEFAULT NULL,
  `lastUpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`id`, `firstName`, `lastName`, `emailId`, `mobileNumber`, `userPassword`, `regDate`, `isActive`, `lastUpdationDate`) VALUES
(1, 'Anuj', 'Kumar', 'test@gmail.com', '1234567890', 'Test@12345', '2018-12-17 18:30:00', 1, '2018-12-25 06:15:43'),
(2, 'Sarita', 'Pandey', 'phpgurukulofficial@gmail.com', '1234567890', 'Test@123', '2018-12-18 17:40:40', 1, '2018-12-22 05:33:41'),
(3, 'Testuser', 'User', 'user@test.com', '1111111112', 'Test@12345', '2018-12-25 17:57:43', 1, '2018-12-25 18:36:18'),
(4, 'Abc', 'Xyz', 'abc@xyz.com', '1234567908', 'Test@123', '2018-12-25 18:43:33', 1, NULL),
(5, 'Shubham', 'Rana', 'codewrap@test.in', '9865326598', 'admin@123', '2021-08-07 08:23:01', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT '',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `first_name`, `email`, `password`, `role`, `create_at`, `update_at`) VALUES
(4, 'Guri', 'singh', 'admin@gmail.com', 'YWRtaW4xMjM0NQ==', '2', '2020-06-09 14:10:53', '2020-06-09 14:10:53'),
(5, 'asdsad', 'asdasd', 'admin1@gmail.com', 'YWRtaW4xMjM0NQ==', '2', '2020-06-09 14:15:34', '2020-06-09 14:15:34'),
(6, 'WSD', 'ZCXF', 'adminWE@gmail.com', 'MTIzNDU2Nzg=', '2', '2020-06-09 14:54:03', '2020-06-09 14:54:03'),
(8, 'test', 'test', 'test@gmail.com', 'dGVzdDEyMzQ=', '2', '2020-07-25 15:23:24', '2020-07-25 15:23:24'),
(9, 'asdsad', 'asdasd', 'admin12@gmail.com', 'e6e061838856bf47e1de730719fb2609', '2', '2020-07-25 15:24:27', '2020-07-25 15:24:27'),
(11, 'Shubham', 'Rana', 'codewrap@test.in', 'YWRtaW5AMTIz', '2', '2021-08-07 08:21:15', '2021-08-07 08:21:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
