-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2025 at 02:52 PM
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
  `siblings` text DEFAULT NULL,
  `status1` ENUM('Active', 'Not Active') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_assistant`
--

INSERT INTO `student_assistant` (`id`, `student_id`, `last_name`, `first_name`, `program`, `year`, `work`, `fingerprint_id`, `image`, `status`, `age`, `sex`, `civil_status`, `date_of_birth`, `city_address`, `contact_no1`, `contact_no2`, `contact_no3`, `province_address`, `guardian`, `honor_award`, `past_scholar`, `present_scholar`, `work_experience`, `special_talent`, `out_name1`, `comp_add1`, `cn1`, `out_name2`, `comp_add2`, `cn2`, `out_name3`, `comp_add3`, `cn3`, `from_wit1`, `comp_add4`, `cn4`, `from_wit2`, `comp_add5`, `cn5`, `from_wit3`, `comp_add6`, `cn6`, `fathers_name`, `fathers_occ`, `fathers_income`, `mothers_name`, `mothers_occ`, `mothers_income`, `siblings`) VALUES
(2, 52690, 'Castro', 'Vence', 'BSIT', 4, 'C.E. Laboratory', 1, 'profile_52690_1740668760.jpg', 0, 12, 'Male', 'Single', '07-20-2002', 'Iloilo', '123123123', '123123123', '123123123', 'Ajuy', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 0.00, 'none', 'none', 0.00, ''),
(132, 0, 'Sotto', 'Kai', 'BSIT', 4, 'M.E. Office', 7, 'images/uploads/profileImages/1738388031_679db23fc65d9.jpg', 0, 22, 'Male', 'Single', '05-11-2002', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0.00, '', '', 0.00, '[]'),
(133, 0, 'Ronaldo', 'Cristo', 'BSCE', 4, 'HRM Office', 8, 'images/uploads/profileImages/1738388204_679db2ece53c5.jpg', 0, 39, 'Male', 'Marriage', '07-11-1982', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0.00, '', '', 0.00, '[]'),
(134, 0, 'Babaw', 'Julius', 'BSME', 2, 'Guidance Services', 9, 'images/uploads/profileImages/1738388386_679db3a21953e.jpg', 0, 45, 'Male', 'Divorce', '07-11-1982', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0.00, '', '', 0.00, '[]'),
(135, 0, 'James', 'Kai', 'BSME', 4, 'Computer Laboratory (RTS Campus)', 10, '', 0, 31, 'Female', 'Widowed', '05-11-1993', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0.00, '', '', 0.00, '[]'),
(136, 112233, 'Lacson', 'Badrea', 'BSED', 1, 'P.E. Department', 24, 'profile_112233_1740679232.jpg', 0, 19, 'Female', 'Single', '04-11-2005', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0.00, '', '', 0.00, '[]'),
(137, 0, 'asd', 'asdasd', 'BSBA', 2, 'P.E. Department', 25, 'images/uploads/profileImages/1739886010_67b48dba281e1.jpg', 0, 19, 'Male', 'Single', '11-01-2000', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0.00, '', '', 0.00, '[]');

INSERT INTO student_assistant (
    last_name, first_name, program, year, work, image, status, age, sex, civil_status, date_of_birth, 
    city_address, contact_no1, contact_no2, contact_no3, province_address, guardian, honor_award, 
    past_scholar, present_scholar, work_experience, special_talent, out_name1, comp_add1, cn1, 
    out_name2, comp_add2, cn2, out_name3, comp_add3, cn3, from_wit1, comp_add4, cn4, from_wit2, 
    comp_add5, cn5, from_wit3, comp_add6, cn6, fathers_name, fathers_occ, fathers_income, 
    mothers_name, mothers_occ, mothers_income, siblings
)
SELECT
    CONCAT('Last', FLOOR(RAND() * 1000)) AS last_name,
    CONCAT('First', FLOOR(RAND() * 1000)) AS first_name,
    ELT(FLOOR(1 + RAND() * 4), 'BSIT', 'BSCS', 'BSBA', 'BSED') AS program,
    FLOOR(1 + RAND() * 4) AS year,
    (SELECT work_name FROM work ORDER BY RAND() LIMIT 1) AS work,
    NULL AS image,
    FLOOR(RAND() * 3) AS status,
    FLOOR(18 + RAND() * 10) AS age,
    ELT(FLOOR(1 + RAND() * 2), 'Male', 'Female') AS sex,
    ELT(FLOOR(1 + RAND() * 4), 'Single', 'Married', 'Divorced', 'Widowed') AS civil_status,
    DATE_SUB(CURDATE(), INTERVAL FLOOR(18 + RAND() * 10) YEAR) AS date_of_birth,
    CONCAT('City Address ', FLOOR(RAND() * 1000)) AS city_address,
    CONCAT('09', LPAD(FLOOR(RAND() * 100000000), 8, '0')) AS contact_no1,
    CONCAT('09', LPAD(FLOOR(RAND() * 100000000), 8, '0')) AS contact_no2,
    CONCAT('09', LPAD(FLOOR(RAND() * 100000000), 8, '0')) AS contact_no3,
    CONCAT('Province Address ', FLOOR(RAND() * 1000)) AS province_address,
    CONCAT('Guardian ', FLOOR(RAND() * 1000)) AS guardian,
    CONCAT('Honor Award ', FLOOR(RAND() * 1000)) AS honor_award,
    ELT(FLOOR(1 + RAND() * 2), 'Yes', 'No') AS past_scholar,
    ELT(FLOOR(1 + RAND() * 2), 'Yes', 'No') AS present_scholar,
    CONCAT('Work Experience ', FLOOR(RAND() * 1000)) AS work_experience,
    CONCAT('Special Talent ', FLOOR(RAND() * 1000)) AS special_talent,
    CONCAT('Out Name 1 ', FLOOR(RAND() * 1000)) AS out_name1,
    CONCAT('Company Address 1 ', FLOOR(RAND() * 1000)) AS comp_add1,
    CONCAT('09', LPAD(FLOOR(RAND() * 100000000), 8, '0')) AS cn1,
    CONCAT('Out Name 2 ', FLOOR(RAND() * 1000)) AS out_name2,
    CONCAT('Company Address 2 ', FLOOR(RAND() * 1000)) AS comp_add2,
    CONCAT('09', LPAD(FLOOR(RAND() * 100000000), 8, '0')) AS cn2,
    CONCAT('Out Name 3 ', FLOOR(RAND() * 1000)) AS out_name3,
    CONCAT('Company Address 3 ', FLOOR(RAND() * 1000)) AS comp_add3,
    CONCAT('09', LPAD(FLOOR(RAND() * 100000000), 8, '0')) AS cn3,
    CONCAT('From Witness 1 ', FLOOR(RAND() * 1000)) AS from_wit1,
    CONCAT('Company Address 4 ', FLOOR(RAND() * 1000)) AS comp_add4,
    CONCAT('09', LPAD(FLOOR(RAND() * 100000000), 8, '0')) AS cn4,
    CONCAT('From Witness 2 ', FLOOR(RAND() * 1000)) AS from_wit2,
    CONCAT('Company Address 5 ', FLOOR(RAND() * 1000)) AS comp_add5,
    CONCAT('09', LPAD(FLOOR(RAND() * 100000000), 8, '0')) AS cn5,
    CONCAT('From Witness 3 ', FLOOR(RAND() * 1000)) AS from_wit3,
    CONCAT('Company Address 6 ', FLOOR(RAND() * 1000)) AS comp_add6,
    CONCAT('09', LPAD(FLOOR(RAND() * 100000000), 8, '0')) AS cn6,
    CONCAT('Father Name ', FLOOR(RAND() * 1000)) AS fathers_name,
    CONCAT('Father Occupation ', FLOOR(RAND() * 1000)) AS fathers_occ,
    ROUND(RAND() * 100000, 2) AS fathers_income,
    CONCAT('Mother Name ', FLOOR(RAND() * 1000)) AS mothers_name,
    CONCAT('Mother Occupation ', FLOOR(RAND() * 1000)) AS mothers_occ,
    ROUND(RAND() * 100000, 2) AS mothers_income,
    CONCAT('Siblings ', FLOOR(RAND() * 1000)) AS siblings
FROM
    (SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION
     SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION
     SELECT 11 UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15 UNION
     SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION SELECT 19 UNION SELECT 20 UNION
     SELECT 21 UNION SELECT 22 UNION SELECT 23 UNION SELECT 24 UNION SELECT 25 UNION
     SELECT 26 UNION SELECT 27 UNION SELECT 28 UNION SELECT 29 UNION SELECT 30 UNION
     SELECT 31 UNION SELECT 32 UNION SELECT 33 UNION SELECT 34 UNION SELECT 35 UNION
     SELECT 36 UNION SELECT 37 UNION SELECT 38 UNION SELECT 39 UNION SELECT 40 UNION
     SELECT 41 UNION SELECT 42 UNION SELECT 43 UNION SELECT 44 UNION SELECT 45 UNION
     SELECT 46 UNION SELECT 47 UNION SELECT 48 UNION SELECT 49 UNION SELECT 50) AS dummy;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `student_assistant`
--
ALTER TABLE `student_assistant`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `student_assistant`
--
ALTER TABLE `student_assistant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
