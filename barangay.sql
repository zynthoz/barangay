-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2026 at 02:36 AM
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
-- Database: `brms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_households`
--

CREATE TABLE `tbl_households` (
  `household_id` int(11) NOT NULL,
  `household_head` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_information` varchar(100) DEFAULT NULL,
  `number_of_members` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_households`
--

INSERT INTO `tbl_households` (`household_id`, `household_head`, `address`, `contact_information`, `number_of_members`) VALUES
(1, 'Household 1', 'Address 1', '09123456781', 3),
(2, 'Household 2', 'Address 2', '09123456782', 4),
(3, 'Household 3', 'Address 3', '09123456783', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_incident_reports`
--

CREATE TABLE `tbl_incident_reports` (
  `report_id` int(11) NOT NULL,
  `resident_id` int(11) DEFAULT NULL,
  `official_id` int(11) DEFAULT NULL,
  `incident_type` varchar(100) DEFAULT NULL,
  `incident_details` text DEFAULT NULL,
  `date_reported` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_incident_reports`
--

INSERT INTO `tbl_incident_reports` (`report_id`, `resident_id`, `official_id`, `incident_type`, `incident_details`, `date_reported`) VALUES
(1, 1, 1, 'Theft', 'Incident 1 details', '2023-01-10'),
(2, 2, 2, 'Noise Complaint', 'Incident 2 details', '2023-02-15'),
(3, 3, 3, 'Assault', 'Incident 3 details', '2023-03-20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_logs`
--

CREATE TABLE `tbl_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `date_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_logs`
--

INSERT INTO `tbl_logs` (`log_id`, `user_id`, `action`, `date_time`) VALUES
(1, 1, 'Log 1: System setup initialized', '2023-01-01 10:00:00'),
(2, 2, 'Log 2: Initial records added', '2023-01-02 11:30:00'),
(3, 3, 'Log 3: System check completed', '2023-01-03 14:45:00'),
(4, 5, 'New resident registered: resident-test', '2026-05-22 01:33:21');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_officials`
--

CREATE TABLE `tbl_officials` (
  `official_id` int(11) NOT NULL,
  `official_name` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `contact_information` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_officials`
--

INSERT INTO `tbl_officials` (`official_id`, `official_name`, `position`, `contact_information`) VALUES
(1, 'Official 1', 'Captain', '09223456781'),
(2, 'Official 2', 'Kagawad', '09223456782'),
(3, 'Official 3', 'Secretary', '09223456783');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projects`
--

CREATE TABLE `tbl_projects` (
  `project_id` int(11) NOT NULL,
  `project_name` varchar(100) DEFAULT NULL,
  `project_description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `project_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_projects`
--

INSERT INTO `tbl_projects` (`project_id`, `project_name`, `project_description`, `start_date`, `end_date`, `project_status`) VALUES
(1, 'Project 1', 'Project 1 description', '2023-01-01', '2023-06-01', 'Planned'),
(2, 'Project 2', 'Project 2 description', '2023-02-01', '2023-07-01', 'Ongoing'),
(3, 'Project 3', 'Project 3 description', '2023-03-01', '2023-08-01', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_residents`
--

CREATE TABLE `tbl_residents` (
  `resident_id` int(11) NOT NULL,
  `resident_name` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `contact_information` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_residents`
--

INSERT INTO `tbl_residents` (`resident_id`, `resident_name`, `date_of_birth`, `gender`, `contact_information`, `address`, `user_id`) VALUES
(1, 'Resident 1', '1990-01-01', 'Male', '09123456781', 'Address 1', NULL),
(2, 'Resident 2', '1992-02-02', 'Female', '09123456782', 'Address 2', NULL),
(3, 'Resident 3', '1994-03-03', 'Male', '09123456783', 'Address 3', NULL),
(4, 'resident-test', '2026-05-22', 'Male', '0912345678', 'Test Address', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `full_name`, `role`, `username`, `password`, `email`) VALUES
(1, 'System Admin', 'Admin', 'admin', 'admin', 'admin@brms.local'),
(2, 'User 1', 'Employee', 'user1', 'user1', 'user1@brms.local'),
(3, 'User 2', 'Employee', 'user2', 'user2', 'user2@brms.local'),
(4, 'User 3', 'Employee', 'user3', 'user3', 'user3@brms.local'),
(5, 'resident-test', 'Resident', 'resident-test', 'resident', 'resident-test@email.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_households`
--
ALTER TABLE `tbl_households`
  ADD PRIMARY KEY (`household_id`);

--
-- Indexes for table `tbl_incident_reports`
--
ALTER TABLE `tbl_incident_reports`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `tbl_logs`
--
ALTER TABLE `tbl_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `tbl_officials`
--
ALTER TABLE `tbl_officials`
  ADD PRIMARY KEY (`official_id`);

--
-- Indexes for table `tbl_projects`
--
ALTER TABLE `tbl_projects`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `tbl_residents`
--
ALTER TABLE `tbl_residents`
  ADD PRIMARY KEY (`resident_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_households`
--
ALTER TABLE `tbl_households`
  MODIFY `household_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_incident_reports`
--
ALTER TABLE `tbl_incident_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_logs`
--
ALTER TABLE `tbl_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_officials`
--
ALTER TABLE `tbl_officials`
  MODIFY `official_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_projects`
--
ALTER TABLE `tbl_projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_residents`
--
ALTER TABLE `tbl_residents`
  MODIFY `resident_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
