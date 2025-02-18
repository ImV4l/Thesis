-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 31, 2025 at 08:54 PM
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
(2, 'Marco', 'user', '', 1, 127, 'hakdog@gmail.com', 0),
(4, 'Luffy', 'admin', 'password123', 1, 127, 'admin@gmail.com', 0),
(6, 'User Sample', 'Sample', 'Password123!', 0, 127, '', 0);

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_assistant`
--

CREATE TABLE `student_assistant` (
  `id` int(11) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `program` varchar(100) NOT NULL,
  `year` int(11) NOT NULL,
  `work` varchar(255) NOT NULL,
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
  `siblings` text DEFAULT NULL,
  `fingerprint_id` INT UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_assistant`
--

INSERT INTO `student_assistant` (`id`, `last_name`, `first_name`, `program`, `year`, `work`, `image`, `status`, `age`, `sex`, `civil_status`, `date_of_birth`, `city_address`, `contact_no1`, `contact_no2`, `contact_no3`, `province_address`, `guardian`, `honor_award`, `past_scholar`, `present_scholar`, `work_experience`, `special_talent`, `out_name1`, `comp_add1`, `cn1`, `out_name2`, `comp_add2`, `cn2`, `out_name3`, `comp_add3`, `cn3`, `from_wit1`, `comp_add4`, `cn4`, `from_wit2`, `comp_add5`, `cn5`, `from_wit3`, `comp_add6`, `cn6`, `fathers_name`, `fathers_occ`, `fathers_income`, `mothers_name`, `mothers_occ`, `mothers_income`, `siblings`, `fingerprint_id`) VALUES
(2, 'Espinosa', 'Vence', 'BSIT', 4, 'C.E. Laboratory', '', 0, 12, 'Male', 'Single', '07-20-2002', 'none', '123123123', '123123123', '123123123', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'asd', 'asd', 'asd', 'asd', 'asd', 'asdasd', 'asd', 'asd', 'asd', 'asd', 'asd', '123123', 'as', 'asd', '231231231', 'dasd', 'asd', '123123123', 'asd', 'asd', 10.00, 'asd', 'asd', 20.00, '', NULL),
(4, 'Pedroso', 'Jade Irish', 'BSCE', 3, ' P.E. Department', '', 0, 0, '', '', '', '', '0', '0', '0', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'Montinola', 'Zybryx', 'BSEE', 1, ' Mar.E. Office, Biology Laboratory', '', 0, 0, '', '', '', '', '0', '0', '0', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'Castro', 'Vence', 'BSCE', 3, ' C.E. Laboratory', '', 0, 0, '', '', '', '', '0', '0', '0', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 'Kirk', 'Linda', 'BSBA', 3, ' Physical Plant Facilities Dept.', '', 0, 0, '', '', '', '', '0', '0', '0', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 'Franklin', 'Elvarin', 'BSEE', 2, ' Registrar, Office of the Heads, CAS', '', 0, 0, '', '', '', '', '0', '0', '0', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 'Day', 'Johanna', 'BSIT', 3, 'President Office, Biology Laboratory', '', 0, 24, 'Female', 'Single', '07-20-2002', '999 Southroad St., Jude Luxury Homes, Tandang Sora, Quezon City', '09202418909', '', '', 'Tandang Sora, Quezon City', 'Zelma Henson', 'Honor roll, High GPA', 'New York University Scholarship', 'Questbridge Scholarship', 'Developed recruitment plan , Designed training program for retirees under EO 366', 'Acting', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 'Smith', 'Alice', 'BSIT', 3, 'Swimming Pool', NULL, 0, 21, 'Female', 'Single', '2002-07-10', '234 City Rd.', '1234567890', '9876543210', '1122334455', '789 Province St.', 'John Smith', 'Dean\'s List', 'Merit Scholar', 'University Grant', 'Intern at ABC Co.', 'Robotics, Math', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 'Johnson', 'Robert', 'BSCE', 2, 'M.E. Office', NULL, 0, 20, 'Male', 'Single', '2003-11-22', '678 City Blvd.', '2345678901', '8765432109', '2233445566', '123 Province Rd.', 'Laura Johnson', 'Honor Roll', 'Excellence Scholarship', 'Financial Aid', 'Customer Service Rep', 'Public Speaking, Negotiation', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 'Brown', 'Emily', 'BSEE', 4, 'Physical Plant Facilities Dept.', NULL, 0, 23, 'Female', 'Married', '2000-05-16', '789 City Ln.', '3456789012', '7654321098', '3344556677', '456 Province Ln.', 'Michael Brown', 'Summa Cum Laude', 'Nursing Fellow', 'Med School Grant', 'Clinical Assistant', 'Patient Care, First Aid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 'Davis', 'Michael', 'BSBA', 3, 'Registrar', NULL, 0, 22, 'Male', 'Single', '2001-12-25', '890 City Ct.', '4567890123', '6543210987', '4455667788', '567 Province Ct.', 'Linda Davis', 'Magna Cum Laude', 'Legal Scholarship', 'Public Scholarship', 'Paralegal Intern', 'Legal Research, Debate', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 'Garcia', 'Isabella', 'BSIT', 1, 'Computer Laboratory (Main Bldg.)', NULL, 0, 19, 'Female', 'Single', '2005-01-30', '901 City Ave.', '5678901234', '5432109876', '5566778899', '678 Province Ave.', 'Maria Garcia', 'Scholarship of Excellence', 'Science Grant', 'Bio Research Fellow', 'Research Assistant', 'Lab Skills, Microscopy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 'Martinez', 'James', 'BSCE', 2, 'Techno Library', NULL, 0, 20, 'Male', 'Single', '2003-03-05', '112 City St.', '6789012345', '4321098765', '6677889900', '789 Province St.', 'Jose Martinez', 'Honor Student', 'Chemistry Grant', 'STEM Scholarship', 'Lab Intern', 'Analytical Skills, Experimentation', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 'Rodriguez', 'Olivia', 'BSEE', 4, 'VP/Comptroller', NULL, 0, 24, 'Female', 'Married', '1999-09-15', '223 City Dr.', '7890123456', '3210987654', '7788990011', '890 Province Dr.', 'Anna Rodriguez', 'Cum Laude', 'Psychology Fellow', 'Mental Health Grant', 'Intern at Clinic', 'Behavior Analysis, Counseling', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 'Wilson', 'William', 'BSBA', 1, 'Machine Shop', NULL, 0, 18, 'Male', 'Single', '2005-02-14', '334 City Blvd.', '8901234567', '2109876543', '8899001122', '901 Province Blvd.', 'Edward Wilson', 'Physics Award', 'National Scholar', 'Physics Fellow', 'Lab Assistant', 'Problem Solving, Critical Thinking', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 'Anderson', 'Sophia', 'BSIT', 2, 'Security Unit/Civil Security Officer', NULL, 0, 20, 'Female', 'Single', '2003-04-07', '445 City Ave.', '9012345678', '1098765432', '9900112233', '112 Province Ave.', 'Sarah Anderson', 'Math Olympiad', 'Excellence in Math', 'STEM Scholar', 'Private Tutor', 'Mathematics, Logic', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 'Thomas', 'Alexander', 'BSCE', 3, 'Shipboard Training Office', NULL, 0, 22, 'Male', 'Single', '2001-07-21', '556 City Rd.', '0123456789', '9876543210', '0011223344', '223 Province Rd.', 'Thomas Lee', 'Magna Cum Laude', 'Philosophy Grant', 'Humanities Fellow', 'Research Intern', 'Critical Thinking, Writing', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 'Harris', 'Ava', 'BSEE', 4, 'HRM Laboratory', NULL, 0, 23, 'Female', 'Single', '2000-12-31', '667 City Ln.', '1234567890', '8765432109', '3344556677', '334 Province Ln.', 'George Harris', 'Cum Laude', 'Teaching Fellow', 'Education Grant', 'Classroom Assistant', 'Organization, Teaching', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 'Clark', 'Mason', 'BSBA', 1, 'Sanitation Services Dept.', NULL, 0, 19, 'Male', 'Single', '2004-11-03', '778 City St.', '2345678901', '7654321098', '4455667788', '445 Province St.', 'Alice Clark', 'Dean\'s List', 'Tech Scholarship', 'Coding Grant', 'IT Intern', 'Programming, Troubleshooting', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 'Lee', 'Mia', 'BSIT', 3, 'DIO/Photo-Room', NULL, 0, 21, 'Female', 'Single', '2002-05-05', '889 City Blvd.', '5678901234', '5432109876', '5566778899', '556 Province Blvd.', 'Henry Lee', 'Excellence in Art', 'Art Fellowship', 'Creativity Grant', 'Gallery Intern', 'Painting, Sculpting', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 'Walker', 'Benjamin', 'BSCE', 2, 'Physics Laboratory', NULL, 0, 20, 'Male', 'Single', '2003-07-18', '990 City Ave.', '6789012345', '4321098765', '6677889900', '667 Province Ave.', 'Carol Walker', 'Economics Scholar', 'Merit Scholar', 'Financial Grant', 'Intern at Bank', 'Data Analysis, Economics', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 'Hall', 'Emma', 'BSEE', 4, 'Physical Plant Facilities Dept.', NULL, 0, 23, 'Female', 'Married', '2000-10-25', '001 City Dr.', '7890123456', '3210987654', '7788990011', '778 Province Dr.', 'James Hall', 'Dean\'s List', 'Social Science Fellow', 'Research Grant', 'Survey Coordinator', 'Statistics, Research', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 'Perez', 'Liam', 'BSBA', 2, 'Graduate School Library', NULL, 0, 20, 'Male', 'Single', '2003-03-17', '223 City Blvd.', '4567890123', '5432109876', '1122334455', '778 Province Blvd.', 'Margaret Perez', 'History Award', 'Humanities Scholar', 'Historical Grant', 'Archivist Intern', 'Research, Archiving', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 'Young', 'Abigail', 'BSIT', 3, 'Main Library', NULL, 0, 21, 'Female', 'Single', '2002-11-03', '334 City Ln.', '5678901234', '8765432109', '3344556677', '889 Province Ln.', 'Elena Young', 'Tech Leader', 'Engineering Grant', 'Science Fellow', 'IT Technician', 'Problem Solving, Coding', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 'King', 'Ethan', 'BSCE', 4, 'Security Unit/Civil Security Officer', NULL, 0, 23, 'Male', 'Married', '2000-06-15', '445 City Ave.', '6789012345', '7654321098', '4455667788', '990 Province Ave.', 'David King', 'Cum Laude', 'Political Science Fellow', 'Social Scholar', 'Paralegal Intern', 'Critical Thinking, Writing', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(44, 'Wright', 'Hannah', 'BSEE', 1, 'Canteen (RTS Campus)', NULL, 0, 19, 'Female', 'Single', '2004-09-09', '556 City St.', '7890123456', '6543210987', '5566778899', '001 Province St.', 'Evelyn Wright', 'Psychology Scholar', 'Health Science Fellow', 'Mind Scholar', 'Lab Assistant', 'Behavior Analysis, Research', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 'Scott', 'Samuel', 'BSBA', 3, 'Canteen (RTS Campus)', NULL, 0, 21, 'Male', 'Single', '2002-02-02', '667 City Rd.', '8901234567', '4321098765', '6677889900', '112 Province Rd.', 'Gregory Scott', 'Linguistics Award', 'Language Scholar', 'Communication Fellow', 'Translator', 'Multilingual, Analysis', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
