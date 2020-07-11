-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 27, 2020 at 04:12 AM
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
-- Table structure for table `experience`
--

CREATE TABLE `experience` (
  `employee_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `years` int(11) NOT NULL,
   PRIMARY KEY(employee_id,language_id),
   FOREIGN KEY(employee_id) REFERENCES employees(employee_id),
    FOREIGN KEY(language_id) REFERENCES languages(language_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `experience`
--

INSERT INTO `experience` (`employee_id`, `language_id`, `years`) VALUES
(101, 10, 5),
(101, 11, 4),
(102, 10, 3),
(102, 11, 2),
(102, 12, 3),
(103, 10, 2),
(103, 11, 3),
(103, 13, 3),
(104, 10, 7),
(104, 11, 5),
(104, 12, 8),
(105, 10, 4),
(105, 11, 2),
(106, 14, 5),
(107, 14, 10),
(108, 14, 6),
(109, 14, 5);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
