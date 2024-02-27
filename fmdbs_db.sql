-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2024 at 05:54 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fmdbs_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_employee`
--

CREATE TABLE `tbl_employee` (
  `EMPLOYEE_ID` int(11) NOT NULL,
  `FIRST_NAME` varchar(255) DEFAULT NULL,
  `MIDDLE_NAME` varchar(255) DEFAULT NULL,
  `LAST_NAME` varchar(255) DEFAULT NULL,
  `DOB` date DEFAULT NULL,
  `AGE` int(11) DEFAULT NULL,
  `CITY` varchar(255) DEFAULT NULL,
  `BARANGAY` varchar(255) DEFAULT NULL,
  `STREET` varchar(255) DEFAULT NULL,
  `EMAIL_ADD` varchar(255) DEFAULT NULL,
  `CONTACT_NO` varchar(20) DEFAULT NULL,
  `PASSWORD` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_employee`
--

INSERT INTO `tbl_employee` (`EMPLOYEE_ID`, `FIRST_NAME`, `MIDDLE_NAME`, `LAST_NAME`, `DOB`, `AGE`, `CITY`, `BARANGAY`, `STREET`, `EMAIL_ADD`, `CONTACT_NO`, `PASSWORD`) VALUES
(1, 'Ian', 'Ortega', 'Dalumpines', '2004-01-22', 20, 'Quezon', '176', 'Don Antonio', 'ianortega@gmail.com', '09683124407', '$2y$10$k4.rBCdlnA6DdUhI8nawN..80j3SO04yH2EiNkxSkUb20uKl16Ss.');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_facility`
--

CREATE TABLE `tbl_facility` (
  `FACILITY_ID` int(11) NOT NULL,
  `FACILITY_NAME` varchar(255) NOT NULL,
  `IMAGE_URL` varchar(255) DEFAULT NULL,
  `CARD_TEXT` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_facility`
--

INSERT INTO `tbl_facility` (`FACILITY_ID`, `FACILITY_NAME`, `IMAGE_URL`, `CARD_TEXT`) VALUES
(1, 'Meeting Room', 'DATAMA\\Images\\facility_1.png\r\n', 'Your text goes here'),
(2, 'Conference Room', 'DATAMA\\Images\\facility_2.png', 'DATAMA\\Images\\facility_3.png'),
(3, 'Banquet Hall', 'DATAMA\\Images\\facility_3.png', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reservation`
--

CREATE TABLE `tbl_reservation` (
  `RESERVATION_ID` int(11) NOT NULL,
  `FACILITY_ID` int(11) DEFAULT NULL,
  `USER_ID` int(11) DEFAULT NULL,
  `RESERVATION_DATE` date DEFAULT NULL,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reservation_status`
--

CREATE TABLE `tbl_reservation_status` (
  `STATUS_ID` int(11) NOT NULL,
  `STATUS_NAME` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_reservation_status`
--

INSERT INTO `tbl_reservation_status` (`STATUS_ID`, `STATUS_NAME`) VALUES
(1, 'Pending'),
(2, 'Approved'),
(3, 'Declined');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `USER_ID` int(11) NOT NULL,
  `FIRST_NAME` varchar(255) DEFAULT NULL,
  `MIDDLE_NAME` varchar(255) DEFAULT NULL,
  `LAST_NAME` varchar(255) DEFAULT NULL,
  `DOB` date DEFAULT NULL,
  `AGE` int(11) DEFAULT NULL,
  `CITY` varchar(255) DEFAULT NULL,
  `BARANGAY` varchar(255) DEFAULT NULL,
  `STREET` varchar(255) DEFAULT NULL,
  `EMAIL_ADD` varchar(255) DEFAULT NULL,
  `CONTACT_NO` varchar(20) DEFAULT NULL,
  `PASSWORD` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`USER_ID`, `FIRST_NAME`, `MIDDLE_NAME`, `LAST_NAME`, `DOB`, `AGE`, `CITY`, `BARANGAY`, `STREET`, `EMAIL_ADD`, `CONTACT_NO`, `PASSWORD`) VALUES
(1, 'Richard Gabriel', 'Espaldon', 'Amor', '2004-01-22', 20, 'Caloocan', '176', 'Langit Road', 'richardgabrielamor@gmail.com', '09334649776', '$2y$10$uH9F.9aJjtJYv3bGfZlNF.FjDG5k0WNbcLXVyxtQKIDzFrrDDxiDO');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_employee`
--
ALTER TABLE `tbl_employee`
  ADD PRIMARY KEY (`EMPLOYEE_ID`);

--
-- Indexes for table `tbl_facility`
--
ALTER TABLE `tbl_facility`
  ADD PRIMARY KEY (`FACILITY_ID`);

--
-- Indexes for table `tbl_reservation`
--
ALTER TABLE `tbl_reservation`
  ADD PRIMARY KEY (`RESERVATION_ID`),
  ADD KEY `FACILITY_ID` (`FACILITY_ID`),
  ADD KEY `USER_ID` (`USER_ID`),
  ADD KEY `STATUS_ID` (`STATUS_ID`);

--
-- Indexes for table `tbl_reservation_status`
--
ALTER TABLE `tbl_reservation_status`
  ADD PRIMARY KEY (`STATUS_ID`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`USER_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_employee`
--
ALTER TABLE `tbl_employee`
  MODIFY `EMPLOYEE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `USER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_reservation`
--
ALTER TABLE `tbl_reservation`
  ADD CONSTRAINT `tbl_reservation_ibfk_1` FOREIGN KEY (`FACILITY_ID`) REFERENCES `tbl_facility` (`FACILITY_ID`),
  ADD CONSTRAINT `tbl_reservation_ibfk_2` FOREIGN KEY (`USER_ID`) REFERENCES `tbl_user` (`USER_ID`),
  ADD CONSTRAINT `tbl_reservation_ibfk_3` FOREIGN KEY (`STATUS_ID`) REFERENCES `tbl_reservation_status` (`STATUS_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
