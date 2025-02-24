-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2025 at 06:15 PM
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
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role_as` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` tinyint(4) NOT NULL DEFAULT current_timestamp(),
  `email` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-visible,1=hidden,2=deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `username`, `password`, `role_as`, `created_at`, `email`, `status`) VALUES
(2, 'Marco', 'user', 'pass', 0, 127, 'hakdog@gmail.com', 0),
(4, 'Luffy', 'admin', 'password123', 1, 127, 'admin@gmail.com', 0),
(6, 'User Sample', 'Sample', 'Password123!', 0, 127, '', 2),
(7, '', '', '', 0, 127, '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `sa_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `day` varchar(20) DEFAULT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `num_hour` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `sa_id`, `date`, `day`, `time_in`, `time_out`, `status`, `num_hour`, `created_at`) VALUES
(47, 133, '2025-02-01', 'Saturday', '14:03:45', NULL, 'Present', 0, '2025-02-01 06:03:45'),
(48, 135, '2025-02-01', 'Saturday', '14:04:30', '14:05:09', 'Present', 0, '2025-02-01 06:04:30'),
(49, 2, '2025-02-16', NULL, '07:13:02', '09:16:01', 'Completed', 2.033333333333333, '2025-02-03 12:13:09'),
(50, 5, '2025-02-04', NULL, '08:31:00', '10:35:00', 'Completed', 0, '2025-02-03 12:17:36'),
(51, 12, '2025-02-03', NULL, '20:26:42', '20:26:52', 'Completed', 0, '2025-02-03 12:26:42'),
(52, 17, '2025-02-03', NULL, '20:27:17', '20:27:22', 'Completed', 0, '2025-02-03 12:27:17'),
(53, 2, '2025-02-16', NULL, '13:03:23', '15:04:04', 'Completed', 2, '2025-02-03 12:34:26'),
(54, 2, '2025-02-17', NULL, '09:35:01', '12:01:02', 'Completed', 2.4333333333333336, '2025-02-03 12:35:01'),
(55, 5, '2025-02-04', NULL, '01:00:00', '03:00:00', 'Completed', 0, '2025-02-03 12:42:36'),
(56, 132, '2025-02-03', NULL, '06:00:00', '09:00:00', 'Completed', 0, '2025-02-03 12:46:13'),
(57, 132, '2025-02-03', NULL, '12:58:45', '16:12:00', 'Completed', 0, '2025-02-03 12:52:54'),
(58, 133, '2025-02-03', NULL, '20:52:57', '20:53:06', 'Completed', 0, '2025-02-03 12:52:57'),
(59, 134, '2025-02-03', NULL, '20:52:59', '20:53:08', 'Completed', 0, '2025-02-03 12:52:59'),
(60, 134, '2025-02-03', NULL, '20:55:42', '20:55:50', 'Completed', 0, '2025-02-03 12:55:42'),
(61, 132, '2025-02-08', NULL, '06:59:22', '08:31:15', 'Completed', 0, '2025-02-08 12:31:29'),
(62, 134, '2025-02-08', NULL, '20:31:38', '20:31:45', 'Completed', 0, '2025-02-08 12:31:38'),
(63, 133, '2025-02-08', NULL, '20:31:40', '20:31:43', 'Completed', 0, '2025-02-08 12:31:40'),
(64, 136, '2025-02-08', NULL, '21:16:29', '21:17:32', 'Completed', 0, '2025-02-08 13:16:29'),
(66, 136, '2025-02-08', NULL, '21:19:40', '21:19:52', 'Completed', 0, '2025-02-08 13:19:40'),
(67, 133, '2025-02-17', NULL, '20:35:32', '20:35:39', 'Completed', 0, '2025-02-17 12:35:32'),
(68, 132, '2025-02-17', NULL, '08:06:27', '10:20:33', 'Completed', 0, '2025-02-17 12:42:27'),
(69, 134, '2025-02-17', NULL, '09:51:00', '11:51:00', 'Completed', 3, '2025-02-17 13:51:02'),
(70, 132, '2025-02-17', NULL, '10:01:46', '12:03:27', 'Completed', 0, '2025-02-17 13:53:46'),
(71, 133, '2025-02-17', NULL, '21:54:22', '21:54:28', 'Completed', 0, '2025-02-17 13:54:22'),
(72, 134, '2025-02-17', NULL, '01:00:00', '04:00:00', 'Completed', 3, '2025-02-17 13:54:25'),
(73, 2, '2025-02-18', NULL, '07:06:02', '10:01:00', 'Completed', 2.9, '2025-02-17 15:53:08'),
(74, 136, '2025-02-18', NULL, '06:00:00', '10:00:00', 'Completed', 0, '2025-02-17 16:53:18'),
(75, 5, '2025-02-18', NULL, '06:00:00', '08:00:00', 'Completed', 0, '2025-02-17 17:09:20'),
(76, 5, '2025-02-18', NULL, '15:00:00', '17:00:00', 'Completed', 0, '2025-02-17 17:09:20'),
(77, 136, '2025-02-19', NULL, '09:05:24', '12:03:45', 'Completed', 0, '2025-02-18 16:53:18'),
(78, 134, '2025-02-18', NULL, '04:54:56', '04:55:10', 'Completed', 0, '2025-02-17 20:54:56'),
(79, 132, '2025-02-18', NULL, '04:59:15', '04:59:24', 'Completed', 0, '2025-02-17 20:59:15'),
(80, 132, '2025-02-18', NULL, '20:43:23', '20:43:32', 'Completed', 0, '2025-02-18 12:43:23'),
(81, 136, '2025-02-18', NULL, '21:33:38', '21:42:27', 'Completed', 0, '2025-02-18 13:33:38'),
(82, 137, '2025-02-18', NULL, '06:00:04', '10:00:31', 'Completed', 4, '2025-02-18 13:41:04'),
(83, 136, '2025-02-18', NULL, '21:42:39', '21:53:51', 'Completed', 0, '2025-02-18 13:42:39'),
(84, 137, '2025-02-18', NULL, '21:43:05', '21:53:12', 'Completed', 0, '2025-02-18 13:43:05'),
(85, 136, '2025-02-18', NULL, '21:54:17', '22:54:37', 'Completed', 1, '2025-02-18 13:54:17'),
(87, 136, '2025-02-18', NULL, '22:12:08', '22:12:14', 'Completed', 0, '2025-02-18 14:12:08'),
(89, 24, '2025-02-18', 'Tuesday', '22:21:56', '22:22:15', 'Completed', 0, '2025-02-18 14:21:56'),
(90, 136, '2025-02-18', 'TUESDAY', '22:30:01', '22:30:07', 'Completed', 0, '2025-02-18 14:30:01'),
(91, 137, '2025-02-18', 'Tuesday', '22:32:01', '22:32:08', 'Completed', 0, '2025-02-18 14:32:01'),
(93, 132, '2025-02-21', 'Friday', '00:15:37', '01:17:45', 'Completed', 0, '2025-02-20 16:15:37');

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `id` int(11) NOT NULL,
  `description` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `rate` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `register_sa`
--

CREATE TABLE `register_sa` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `student_id` int(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register_sa`
--

INSERT INTO `register_sa` (`id`, `name`, `student_id`, `email`, `password`) VALUES
(1, 'Vence Espinosa', 52690, 'v.espinosa711@gmail.com', '$2y$10$/kAOWKN08rPzLs5VrX/bueSBTiBaNFlfxD/9/c1oq1LTNV6j.dCHi');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_assistant`
--

CREATE TABLE `student_assistant` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `program` varchar(100) NOT NULL,
  `year` int(11) NOT NULL,
  `work` varchar(255) NOT NULL,
  `fingerprint_id` int(11) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-visible,1=hidden,2=deleted',
  `age` int(11) NOT NULL,
  `sex` varchar(100) NOT NULL,
  `civil_status` varchar(100) NOT NULL,
  `date_of_birth` varchar(100) NOT NULL,
  `city_address` text NOT NULL,
  `contact_no1` varchar(15) NOT NULL,
  `contact_no2` varchar(15) NOT NULL,
  `contact_no3` varchar(15) NOT NULL,
  `province_address` text NOT NULL,
  `guardian` varchar(100) NOT NULL,
  `honor_award` text NOT NULL,
  `past_scholar` varchar(100) NOT NULL,
  `present_scholar` varchar(999) NOT NULL,
  `work_experience` text NOT NULL,
  `special_talent` text NOT NULL,
  `out_name1` varchar(100) DEFAULT NULL,
  `comp_add1` varchar(255) DEFAULT NULL,
  `cn1` varchar(20) DEFAULT NULL,
  `out_name2` varchar(100) DEFAULT NULL,
  `comp_add2` varchar(255) DEFAULT NULL,
  `cn2` varchar(20) DEFAULT NULL,
  `out_name3` varchar(100) DEFAULT NULL,
  `comp_add3` varchar(255) DEFAULT NULL,
  `cn3` varchar(20) DEFAULT NULL,
  `from_wit1` varchar(100) DEFAULT NULL,
  `comp_add4` varchar(255) DEFAULT NULL,
  `cn4` varchar(20) DEFAULT NULL,
  `from_wit2` varchar(100) DEFAULT NULL,
  `comp_add5` varchar(255) DEFAULT NULL,
  `cn5` varchar(20) DEFAULT NULL,
  `from_wit3` varchar(100) DEFAULT NULL,
  `comp_add6` varchar(255) DEFAULT NULL,
  `cn6` varchar(20) DEFAULT NULL,
  `fathers_name` varchar(100) DEFAULT NULL,
  `fathers_occ` varchar(100) DEFAULT NULL,
  `fathers_income` decimal(10,2) DEFAULT NULL,
  `mothers_name` varchar(100) DEFAULT NULL,
  `mothers_occ` varchar(100) DEFAULT NULL,
  `mothers_income` decimal(10,2) DEFAULT NULL,
  `siblings` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_assistant`
--

INSERT INTO `student_assistant` (`id`, `student_id`, `last_name`, `first_name`, `program`, `year`, `work`, `fingerprint_id`, `image`, `status`, `age`, `sex`, `civil_status`, `date_of_birth`, `city_address`, `contact_no1`, `contact_no2`, `contact_no3`, `province_address`, `guardian`, `honor_award`, `past_scholar`, `present_scholar`, `work_experience`, `special_talent`, `out_name1`, `comp_add1`, `cn1`, `out_name2`, `comp_add2`, `cn2`, `out_name3`, `comp_add3`, `cn3`, `from_wit1`, `comp_add4`, `cn4`, `from_wit2`, `comp_add5`, `cn5`, `from_wit3`, `comp_add6`, `cn6`, `fathers_name`, `fathers_occ`, `fathers_income`, `mothers_name`, `mothers_occ`, `mothers_income`, `siblings`) VALUES
(2, 52690, 'Espinosa', 'Vence', 'BSIT', 4, 'C.E. Laboratory', 1, '', 0, 12, 'Male', 'Single', '07-20-2002', 'Iloilo', '123123123', '123123123', '123123123', 'Ajuy', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 0.00, 'none', 'none', 0.00, ''),
(4, 0, 'Pedroso', 'Jade Irish', 'BSCE', 3, ' P.E. Department', 0, '', 0, 0, '', '', '', '', '0', '0', '0', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 0, 'Montinola', 'Zybryx', 'BSEE', 1, '', 2, '', 0, 0, 'Male', 'Single', '', '', '0', '0', '0', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0.00, '', '', 0.00, ''),
(11, 0, 'Castro', 'Vence', 'BSCE', 3, ' C.E. Laboratory', 0, '', 0, 0, '', '', '', '', '0', '0', '0', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 0, 'Kirk', 'Linda', 'BSBA', 3, ' Physical Plant Facilities Dept.', 3, '', 0, 0, '', '', '', '', '0', '0', '0', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 0, 'Franklin', 'Elvarin', 'BSEE', 2, ' Registrar, Office of the Heads, CAS', 4, '', 0, 0, '', '', '', '', '0', '0', '0', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 0, 'Day', 'Johanna', 'BSIT', 3, 'President Office, Biology Laboratory', 0, '', 0, 24, 'Female', 'Single', '07-20-2002', '999 Southroad St., Jude Luxury Homes, Tandang Sora, Quezon City', '09202418909', '', '', 'Tandang Sora, Quezon City', 'Zelma Henson', 'Honor roll, High GPA', 'New York University Scholarship', 'Questbridge Scholarship', 'Developed recruitment plan , Designed training program for retirees under EO 366', 'Acting', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 0, 'Smith', 'Alice', 'BSIT', 3, 'Swimming Pool', NULL, NULL, 0, 21, 'Female', 'Single', '2002-07-10', '234 City Rd.', '1234567890', '9876543210', '1122334455', '789 Province St.', 'John Smith', 'Dean\'s List', 'Merit Scholar', 'University Grant', 'Intern at ABC Co.', 'Robotics, Math', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 0, 'Johnson', 'Robert', 'BSCE', 2, '', NULL, NULL, 0, 20, 'Male', 'Single', '2003-11-22', '678 City Blvd.', '2345678901', '8765432109', '2233445566', '123 Province Rd.', 'Laura Johnson', 'Honor Roll', 'Excellence Scholarship', 'Financial Aid', 'Customer Service Rep', 'Public Speaking, Negotiation', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0.00, '', '', 0.00, ''),
(28, 0, 'Brown', 'Emily', 'BSEE', 4, 'Physical Plant Facilities Dept.', NULL, NULL, 0, 23, 'Female', 'Married', '2000-05-16', '789 City Ln.', '3456789012', '7654321098', '3344556677', '456 Province Ln.', 'Michael Brown', 'Summa Cum Laude', 'Nursing Fellow', 'Med School Grant', 'Clinical Assistant', 'Patient Care, First Aid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 0, 'Davis', 'Michael', 'BSBA', 3, 'Registrar', NULL, NULL, 0, 22, 'Male', 'Single', '2001-12-25', '890 City Ct.', '4567890123', '6543210987', '4455667788', '567 Province Ct.', 'Linda Davis', 'Magna Cum Laude', 'Legal Scholarship', 'Public Scholarship', 'Paralegal Intern', 'Legal Research, Debate', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 0, 'Garcia', 'Isabella', 'BSIT', 1, 'Computer Laboratory (Main Bldg.)', NULL, NULL, 0, 19, 'Female', 'Single', '2005-01-30', '901 City Ave.', '5678901234', '5432109876', '5566778899', '678 Province Ave.', 'Maria Garcia', 'Scholarship of Excellence', 'Science Grant', 'Bio Research Fellow', 'Research Assistant', 'Lab Skills, Microscopy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 0, 'Martinez', 'James', 'BSCE', 2, 'Techno Library', NULL, NULL, 0, 20, 'Male', 'Single', '2003-03-05', '112 City St.', '6789012345', '4321098765', '6677889900', '789 Province St.', 'Jose Martinez', 'Honor Student', 'Chemistry Grant', 'STEM Scholarship', 'Lab Intern', 'Analytical Skills, Experimentation', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 0, 'Rodriguez', 'Olivia', 'BSEE', 4, 'VP/Comptroller', NULL, NULL, 0, 24, 'Female', 'Married', '1999-09-15', '223 City Dr.', '7890123456', '3210987654', '7788990011', '890 Province Dr.', 'Anna Rodriguez', 'Cum Laude', 'Psychology Fellow', 'Mental Health Grant', 'Intern at Clinic', 'Behavior Analysis, Counseling', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 0, 'Wilson', 'William', 'BSBA', 1, 'Machine Shop', NULL, NULL, 0, 18, 'Male', 'Single', '2005-02-14', '334 City Blvd.', '8901234567', '2109876543', '8899001122', '901 Province Blvd.', 'Edward Wilson', 'Physics Award', 'National Scholar', 'Physics Fellow', 'Lab Assistant', 'Problem Solving, Critical Thinking', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 0, 'Anderson', 'Sophia', 'BSIT', 2, 'Security Unit/Civil Security Officer', NULL, NULL, 0, 20, 'Female', 'Single', '2003-04-07', '445 City Ave.', '9012345678', '1098765432', '9900112233', '112 Province Ave.', 'Sarah Anderson', 'Math Olympiad', 'Excellence in Math', 'STEM Scholar', 'Private Tutor', 'Mathematics, Logic', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 0, 'Thomas', 'Alexander', 'BSCE', 3, 'Shipboard Training Office', NULL, NULL, 0, 22, 'Male', 'Single', '2001-07-21', '556 City Rd.', '0123456789', '9876543210', '0011223344', '223 Province Rd.', 'Thomas Lee', 'Magna Cum Laude', 'Philosophy Grant', 'Humanities Fellow', 'Research Intern', 'Critical Thinking, Writing', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 0, 'Harris', 'Ava', 'BSEE', 4, 'HRM Laboratory', NULL, NULL, 0, 23, 'Female', 'Single', '2000-12-31', '667 City Ln.', '1234567890', '8765432109', '3344556677', '334 Province Ln.', 'George Harris', 'Cum Laude', 'Teaching Fellow', 'Education Grant', 'Classroom Assistant', 'Organization, Teaching', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 0, 'Clark', 'Mason', 'BSBA', 1, 'Sanitation Services Dept.', NULL, NULL, 0, 19, 'Male', 'Single', '2004-11-03', '778 City St.', '2345678901', '7654321098', '4455667788', '445 Province St.', 'Alice Clark', 'Dean\'s List', 'Tech Scholarship', 'Coding Grant', 'IT Intern', 'Programming, Troubleshooting', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 0, 'Lee', 'Mia', 'BSIT', 3, 'DIO/Photo-Room', NULL, NULL, 0, 21, 'Female', 'Single', '2002-05-05', '889 City Blvd.', '5678901234', '5432109876', '5566778899', '556 Province Blvd.', 'Henry Lee', 'Excellence in Art', 'Art Fellowship', 'Creativity Grant', 'Gallery Intern', 'Painting, Sculpting', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 0, 'Walker', 'Benjamin', 'BSCE', 2, 'Physics Laboratory', NULL, NULL, 0, 20, 'Male', 'Single', '2003-07-18', '990 City Ave.', '6789012345', '4321098765', '6677889900', '667 Province Ave.', 'Carol Walker', 'Economics Scholar', 'Merit Scholar', 'Financial Grant', 'Intern at Bank', 'Data Analysis, Economics', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 0, 'Hall', 'Emma', 'BSEE', 4, 'Physical Plant Facilities Dept.', NULL, NULL, 0, 23, 'Female', 'Married', '2000-10-25', '001 City Dr.', '7890123456', '3210987654', '7788990011', '778 Province Dr.', 'James Hall', 'Dean\'s List', 'Social Science Fellow', 'Research Grant', 'Survey Coordinator', 'Statistics, Research', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 0, 'Perez', 'Liam', 'BSBA', 2, 'Graduate School Library', NULL, NULL, 0, 20, 'Male', 'Single', '2003-03-17', '223 City Blvd.', '4567890123', '5432109876', '1122334455', '778 Province Blvd.', 'Margaret Perez', 'History Award', 'Humanities Scholar', 'Historical Grant', 'Archivist Intern', 'Research, Archiving', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 0, 'Young', 'Abigail', 'BSIT', 3, 'Main Library', NULL, NULL, 0, 21, 'Female', 'Single', '2002-11-03', '334 City Ln.', '5678901234', '8765432109', '3344556677', '889 Province Ln.', 'Elena Young', 'Tech Leader', 'Engineering Grant', 'Science Fellow', 'IT Technician', 'Problem Solving, Coding', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 0, 'King', 'Ethan', 'BSCE', 4, 'Security Unit/Civil Security Officer', NULL, NULL, 0, 23, 'Male', 'Married', '2000-06-15', '445 City Ave.', '6789012345', '7654321098', '4455667788', '990 Province Ave.', 'David King', 'Cum Laude', 'Political Science Fellow', 'Social Scholar', 'Paralegal Intern', 'Critical Thinking, Writing', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(44, 0, 'Wright', 'Hannah', 'BSEE', 1, 'Canteen (RTS Campus)', NULL, NULL, 0, 19, 'Female', 'Single', '2004-09-09', '556 City St.', '7890123456', '6543210987', '5566778899', '001 Province St.', 'Evelyn Wright', 'Psychology Scholar', 'Health Science Fellow', 'Mind Scholar', 'Lab Assistant', 'Behavior Analysis, Research', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 0, 'Scott', 'Samuel', 'BSBA', 3, 'Canteen (RTS Campus)', NULL, NULL, 0, 21, 'Male', 'Single', '2002-02-02', '667 City Rd.', '8901234567', '4321098765', '6677889900', '112 Province Rd.', 'Gregory Scott', 'Linguistics Award', 'Language Scholar', 'Communication Fellow', 'Translator', 'Multilingual, Analysis', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 0, 'Torres', 'Amelia', 'BSIT', 2, 'Field AssistantDean\'s Office, CBA', NULL, NULL, 0, 20, 'Female', 'Single', '2003-08-08', '778 City Blvd.', '9012345678', '3210987654', '7788990011', '223 Province Blvd.', 'John Torres', 'Eco Award', 'Environmental Fellow', 'Climate Grant', 'Conservation Intern', 'Field Research, Botany', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 0, 'Nguyen', 'Oliver', 'BSCE', 4, 'Cultural Affairs Office', NULL, NULL, 0, 23, 'Male', 'Single', '2000-12-13', '889 City Ave.', '0123456789', '9876543210', '8899001122', '334 Province Ave.', 'Tina Nguyen', 'Honor Roll', 'Engineering Fellow', 'STEM Scholar', 'Junior Engineer', 'Design, Innovation', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(49, 0, 'Doe', 'John', 'BSEE', 3, 'Part-timeCultural Affairs Office', NULL, NULL, 0, 20, 'Male', 'Single', '2004-05-10', '123 Main St', '1234567890', '9876543210', '1122334455', 'Metro City', 'Jane Doe', 'Dean’s List', 'None', 'Academic Scholarship', 'Internship at ABC Corp', 'Coding', 'Referee Name', 'Referee Address', '1234567890', 'Referee Name 2', 'Referee Address 2', '1234567891', 'Referee Name 3', 'Referee Address 3', '1234567892', 'WIT Referee', 'WIT Address', '1234567893', 'WIT Referee 2', 'WIT Address 2', '1234567894', 'WIT Referee 3', 'WIT Address 3', '1234567895', 'Father Doe', 'Engineer', 5000.00, 'Mother Doe', 'Teacher', 4000.00, 'Sibling1, Sibling2'),
(132, 0, 'Sotto', 'Kai', 'BSIT', 4, 'M.E. Office', 7, 'images/uploads/profileImages/1738388031_679db23fc65d9.jpg', 0, 22, 'Male', 'Single', '05-11-2002', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0.00, '', '', 0.00, '[]'),
(133, 0, 'Ronaldo', 'Cristo', 'BSCE', 4, 'HRM Office', 8, 'images/uploads/profileImages/1738388204_679db2ece53c5.jpg', 0, 39, 'Male', 'Marriage', '07-11-1982', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0.00, '', '', 0.00, '[]'),
(134, 0, 'Babaw', 'Julius', 'BSME', 2, 'Guidance Services', 9, 'images/uploads/profileImages/1738388386_679db3a21953e.jpg', 0, 45, 'Male', 'Divorce', '07-11-1982', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0.00, '', '', 0.00, '[]'),
(135, 0, 'James', 'Kai', 'BSME', 4, 'Computer Laboratory (RTS Campus)', 10, '', 0, 31, 'Female', 'Widowed', '05-11-1993', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0.00, '', '', 0.00, '[]'),
(136, 112233, 'Lacson', 'Badrea', 'BSED', 1, 'P.E. Department', 24, 'images/uploads/profileImages/1739020575_67a7591f6c88c.jfif', 0, 19, 'Female', 'Single', '04-11-2005', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0.00, '', '', 0.00, '[]'),
(137, 0, 'asd', 'asdasd', 'BSBA', 2, 'P.E. Department', 25, 'images/uploads/profileImages/1739886010_67b48dba281e1.jpg', 0, 19, 'Male', 'Single', '11-01-2000', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0.00, '', '', 0.00, '[]');

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
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `register_sa`
--
ALTER TABLE `register_sa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_assistant`
--
ALTER TABLE `student_assistant`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `work`
--
ALTER TABLE `work`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `register_sa`
--
ALTER TABLE `register_sa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_assistant`
--
ALTER TABLE `student_assistant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `work`
--
ALTER TABLE `work`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
