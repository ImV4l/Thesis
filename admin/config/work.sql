-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2025 at 03:03 PM
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
-- Database: `sa_management_systen`
--

-- --------------------------------------------------------

--
-- Table structure for table `work`
--

CREATE TABLE `work` (
  `id` int(11) NOT NULL,
  `type` enum('Office','Laboratory','Manpower Services') NOT NULL,
  `work_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `work`
--

INSERT INTO `work` (`id`, `type`, `work_name`) VALUES
(1, 'Office', 'President Office'),
(2, 'Office', 'Registrar'),
(3, 'Office', 'Accounting Department'),
(4, 'Office', 'Academic Affairs Office'),
(5, 'Office', 'Cultural Affairs Office'),
(6, 'Office', 'DIO/Photo-Room'),
(7, 'Office', 'Dean\'s Office, CBA'),
(8, 'Office', 'Office of the Heads, CAS'),
(9, 'Office', 'Accountancy Office'),
(10, 'Office', 'C.E. Office'),
(11, 'Office', 'E.E. Office'),
(12, 'Office', 'M.E. Office'),
(13, 'Office', 'Shipboard Training Office'),
(14, 'Office', 'Engineering Research Center'),
(15, 'Office', 'ASCCORD Office'),
(16, 'Office', 'Engineering Community Outreach Center CWTS/LTS Office'),
(17, 'Office', 'VP/Comptroller'),
(18, 'Office', 'Treasurer'),
(19, 'Office', 'Guidance Services'),
(20, 'Office', 'Supply & Inventory Office'),
(21, 'Office', 'CRD Office, COE'),
(22, 'Office', 'Deans Office Graduate School'),
(23, 'Office', 'HRM Office'),
(24, 'Office', 'Office Admin'),
(25, 'Office', 'Comp.E. Office'),
(26, 'Office', 'I.T. Office'),
(27, 'Office', 'Mar.E. Office'),
(28, 'Laboratory', 'Typing Room'),
(29, 'Laboratory', 'HRM Laboratory'),
(30, 'Laboratory', 'Biology Laboratory'),
(31, 'Laboratory', 'Chemistry Laboratory'),
(32, 'Laboratory', 'Physics Laboratory'),
(33, 'Laboratory', 'P.E. Department'),
(34, 'Laboratory', 'Machine Shop'),
(35, 'Laboratory', 'Microprocessor Laboratory'),
(36, 'Laboratory', 'Computer Laboratory (RTS Campus)'),
(37, 'Laboratory', 'Computer Laboratory (Main Bldg.)'),
(38, 'Laboratory', 'E.E. Laboratory'),
(39, 'Laboratory', 'Materials Testing Laboratory'),
(40, 'Laboratory', 'C.E. Laboratory'),
(41, 'Laboratory', 'Marine Engineering Laboratory'),
(42, 'Laboratory', 'M.E. Laboratory'),
(43, 'Laboratory', 'Swimming Pool'),
(44, 'Manpower Services', 'Physical Plant Facilities Dept.'),
(45, 'Manpower Services', 'Sanitation Services Dept.'),
(46, 'Manpower Services', 'Canteen (RTS Campus)'),
(47, 'Manpower Services', 'Canteen (Main Bldg.)'),
(48, 'Manpower Services', 'Security Unit/Civil Security Officer'),
(49, 'Manpower Services', 'Main Library'),
(50, 'Manpower Services', 'Techno Library'),
(51, 'Manpower Services', 'Graduate School Library');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `work`
--
ALTER TABLE `work`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `work`
--
ALTER TABLE `work`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
