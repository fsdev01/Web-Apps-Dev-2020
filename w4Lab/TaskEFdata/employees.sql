-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 27, 2020 at 04:11 AM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `address` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(30) NOT NULL,
  `zip` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `first_name`, `last_name`, `address`, `city`, `state`, `zip`) VALUES
(101, 'Dennis', 'Blair', '204 Spruce Lane', 'Brookfield', 'MA', '01506'),
(102, 'Louis', 'Hernandez', '68 Boston Post Road', 'Spencer', 'MA', '01562'),
(103, 'Erica', 'King', '271 Baker Hill Road', 'Brookfield', 'MA', '01515'),
(104, 'Scott', 'Morinaga', '17 Ashley Road', 'Brookfield', 'MA', '01515'),
(105, 'Raymond', 'Picard', '1113 Oakham Road', 'Barre', 'MA', '01531'),
(106, 'Chengfei', 'Liu', 'NA', 'Melbourne', 'Victoria', '3000'),
(107, 'Liang', 'Yao', 'NA', 'Melbourne', 'Victoria', '3000'),
(108, 'Sira', 'Yongchareon', 'NA', 'Melbourne', 'Victoria', '3000'),
(109, 'John', 'Smith', 'NA', 'Brisbane', 'Queensland', '4000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
