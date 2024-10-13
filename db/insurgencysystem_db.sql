-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2024 at 11:06 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `insurgencysystem_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `inbox`
--

CREATE TABLE `inbox` (
  `msg_id` varchar(50) NOT NULL,
  `sender` varchar(100) NOT NULL,
  `receiver` varchar(100) NOT NULL,
  `subject` varchar(300) NOT NULL,
  `message` varchar(5000) NOT NULL,
  `enc_key` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inbox`
--

INSERT INTO `inbox` (`msg_id`, `sender`, `receiver`, `subject`, `message`, `enc_key`, `date`, `status`) VALUES
('MSG-342328', '114bnbitta@gmail.com', '115tfbn@gmail.com', 'Sm9pbnQgVEYgT3Bz', 'aSBhbSBkaXIgdG8gaW5mbyB5b3UgdGhhdCAxMTUgVEYgQm4gd2lsbCBiZSBhdCB5b3VyIGVuZCB0b2RheSBieSAwNDAwIGhycyBwbGVhc2UgYWNrLy8v', 'MTExNA==', '2024-08-28', 'read'),
('MSG-410930', '115tfbn@gmail.com', '114bnbitta@gmail.com', 'UmU6IEpvaW50IFRGIE9wcw==', 'QUNLLy8=', 'MTExNA==', '2024-08-30', 'unread');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`username`, `password`, `role`, `status`) VALUES
('114bnbitta@gmail.com', '114bn', 'user', 'active'),
('115tfbn@gmail.com', '115tf', 'user', 'active'),
('122bn@gmail.com', '122bn', 'user', 'active'),
('26tfbde@gmail.com', '26tf', 'user', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `unit_name` varchar(100) NOT NULL,
  `unit_email` varchar(100) NOT NULL,
  `unit_phone` varchar(20) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`unit_name`, `unit_email`, `unit_phone`, `date`) VALUES
('114 BN BITTA', '114bnbitta@gmail.com', '09013829585', '2024-08-28'),
('115 TF BN ASKIRA UBA', '115tfbn@gmail.com', '09013829585', '2024-08-28'),
('122 bn Pluka', '122bn@gmail.com', '08089499898', '2024-08-28'),
('26 TF BDE Gwoza', '26tfbde@gmail.com', '09011112234', '2024-08-28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inbox`
--
ALTER TABLE `inbox`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`unit_email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
