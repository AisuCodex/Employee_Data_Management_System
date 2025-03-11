-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2025 at 05:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `employee_data_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_acc`
--

CREATE TABLE `admin_acc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_acc`
--

INSERT INTO `admin_acc` (`id`, `email`, `password`) VALUES
(1, 'admin@gmail.com', 'adminpass');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `posted_by` varchar(100) NOT NULL,
  `date_posted` timestamp NOT NULL DEFAULT current_timestamp(),
  `importance` enum('normal','important','urgent') DEFAULT 'normal',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `message`, `posted_by`, `date_posted`, `importance`) VALUES
(7, 'test', 'test', 'admin@gmail.com', '2025-01-07 11:33:23', 'urgent'),
(8, 'try', 'welcome', 'admin@gmail.com', '2025-01-11 03:36:30', 'urgent');

-- --------------------------------------------------------

--
-- Table structure for table `employee_acc`
--

CREATE TABLE `employee_acc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `code` varchar(6) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_acc`
--

INSERT INTO `employee_acc` (`id`, `email`, `password`, `code`, `registration_date`) VALUES
(20, 'marc@gmail.com', 'test123', 'MVA064', '2025-01-10 11:48:05'),
(21, 'test@gmail.com', 'test123', 'VOI2DL', '2025-01-10 11:50:04'),
(22, 'john@gmail.com', 'test123', 'PU5EKO', '2025-01-11 03:49:08');

-- --------------------------------------------------------

--
-- Table structure for table `employee_data`
--

CREATE TABLE `employee_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Fname` varchar(255) NOT NULL,
  `Lname` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `date_birth` date NOT NULL,
  `Address` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `salary` decimal(10,0) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_data`
--

 INSERT INTO employee_data (Fname, Lname, gender, date_birth, Address, position, salary, email, phone) 
VALUES
('John', 'Doe', 'Male', '1985-02-15', '1234 Elm St, Cityville', 'Software Engineer', 75000, 'john.doe@example.com', '555-1234'),
('Jane', 'Smith', 'Female', '1990-05-22', '5678 Oak Rd, Townsville', 'Project Manager', 85000, 'jane.smith@example.com', '555-5678'),
('Michael', 'Johnson', 'Male', '1982-11-03', '9876 Maple Ave, Villagecity', 'Data Analyst', 70000, 'michael.johnson@example.com', '555-9876'),
('Emily', 'Davis', 'Female', '1995-07-19', '4321 Pine St, Capital City', 'HR Manager', 65000, 'emily.davis@example.com', '555-4321'),
('David', 'Martinez', 'Male', '1980-08-29', '2468 Birch Blvd, Riverside', 'Marketing Director', 95000, 'david.martinez@example.com', '555-2468'),
('Sophia', 'Lee', 'Female', '1992-01-11', '1357 Cedar Dr, Seaside', 'Software Developer', 70000, 'sophia.lee@example.com', '555-1357'),
('James', 'Garcia', 'Male', '1983-09-15', '8765 Elm St, Greenfield', 'Product Manager', 80000, 'james.garcia@example.com', '555-8765'),
('Olivia', 'Taylor', 'Female', '1991-04-04', '2469 Oak St, Westtown', 'Graphic Designer', 60000, 'olivia.taylor@example.com', '555-2469'),
('Daniel', 'Brown', 'Male', '1994-12-28', '1359 Maple St, Northlake', 'Sales Executive', 55000, 'daniel.brown@example.com', '555-1359'),
('Ava', 'Wilson', 'Female', '1993-10-17', '7531 Pine St, Eastbrook', 'Business Analyst', 72000, 'ava.wilson@example.com', '555-7531'),
('Liam', 'Harris', 'Male', '1990-02-12', '2468 Walnut Rd, Brightfield', 'Team Lead', 78000, 'liam.harris@example.com', '555-2468'),
('Mia', 'Clark', 'Female', '1989-06-25', '1587 Cedar Ln, Oldtown', 'Database Administrator', 74000, 'mia.clark@example.com', '555-1587'),
('Charlotte', 'Martinez', 'Female', '1992-05-21', '4827 Birch Ave, Stonehill', 'IT Support', 52000, 'charlotte.martinez@example.com', '555-4827'),
('Benjamin', 'Evans', 'Male', '1985-03-13', '7392 Elm Rd, Brookside', 'UX/UI Designer', 68000, 'benjamin.evans@example.com', '555-7392'),
('Lucas', 'Young', 'Male', '1987-09-10', '3845 Maple St, Midtown', 'Content Writer', 55000, 'lucas.young@example.com', '555-3845'),
('Amelia', 'King', 'Female', '1991-12-04', '6721 Birch Ave, Rivertown', 'Operations Manager', 82000, 'amelia.king@example.com', '555-6721'),
('Ethan', 'Scott', 'Male', '1994-06-11', '7416 Oak Blvd, Clearwater', 'Product Designer', 67000, 'ethan.scott@example.com', '555-7416'),
('Isabella', 'Green', 'Female', '1988-02-21', '9512 Pine Rd, Woodland', 'Customer Support', 48000, 'isabella.green@example.com', '555-9512'),
('Jacob', 'Adams', 'Male', '1993-07-18', '2357 Cedar Rd, Kingsfield', 'Marketing Specialist', 69000, 'jacob.adams@example.com', '555-2357'),
('Madison', 'Nelson', 'Female', '1990-08-09', '5368 Oak St, Ridgeview', 'SEO Specialist', 72000, 'madison.nelson@example.com', '555-5368'),
('William', 'Hall', 'Male', '1985-04-27', '8675 Maple Ln, Clearwater', 'Graphic Designer', 61000, 'william.hall@example.com', '555-8675'),
('Harper', 'Lee', 'Female', '1992-11-13', '4582 Pine Dr, Greenhill', 'Data Scientist', 85000, 'harper.lee@example.com', '555-4582'),
('Henry', 'Carter', 'Male', '1991-03-03', '9874 Oak Rd, Lakeview', 'Software Engineer', 78000, 'henry.carter@example.com', '555-9874'),
('Zoe', 'Perez', 'Female', '1994-04-02', '2345 Cedar Ave, Woodbury', 'HR Assistant', 52000, 'zoe.perez@example.com', '555-2345'),
('Jackson', 'Miller', 'Male', '1986-06-08', '8124 Birch St, Rivertown', 'Database Administrator', 75000, 'jackson.miller@example.com', '555-8124'),
('Lily', 'Baker', 'Female', '1990-09-21', '1425 Maple Ave, Springside', 'Sales Manager', 80000, 'lily.baker@example.com', '555-1425'),
('Sebastian', 'Martinez', 'Male', '1987-07-16', '5731 Oak Ln, Parkview', 'Network Administrator', 69000, 'sebastian.martinez@example.com', '555-5731'),
('Ella', 'Gonzalez', 'Female', '1992-02-17', '3145 Cedar Blvd, Stonefield', 'Content Writer', 53000, 'ella.gonzalez@example.com', '555-3145'),
('Gabriel', 'Moore', 'Male', '1989-11-06', '6412 Pine Rd, Windyhill', 'IT Consultant', 78000, 'gabriel.moore@example.com', '555-6412'),
('Chloe', 'Perez', 'Female', '1993-01-28', '7532 Oak Ave, Hillcrest', 'Marketing Coordinator', 68000, 'chloe.perez@example.com', '555-7532'),
('Oliver', 'Hernandez', 'Male', '1985-12-01', '9823 Maple Dr, Eastlake', 'Business Development', 88000, 'oliver.hernandez@example.com', '555-9823'),
('Sofia', 'Roberts', 'Female', '1991-10-05', '3845 Birch Ln, Timberlake', 'Account Manager', 73000, 'sofia.roberts@example.com', '555-3845'),
('Jack', 'Young', 'Male', '1994-03-09', '2921 Oak Blvd, Highland', 'Software Tester', 62000, 'jack.young@example.com', '555-2921'),
('Aria', 'Wilson', 'Female', '1993-05-16', '1537 Maple Rd, Redwood', 'QA Engineer', 65000, 'aria.wilson@example.com', '555-1537'),
('Leo', 'Johnson', 'Male', '1986-10-23', '4735 Pine St, Clearview', 'Product Specialist', 75000, 'leo.johnson@example.com', '555-4735'),
('Victoria', 'Walker', 'Female', '1990-07-01', '7389 Birch Rd, Fairview', 'HR Specialist', 57000, 'victoria.walker@example.com', '555-7389'),
('Matthew', 'Allen', 'Male', '1992-03-15', '5893 Cedar Dr, Crestwood', 'Project Lead', 80000, 'matthew.allen@example.com', '555-5893'),
('Mason', 'Thomas', 'Male', '1984-05-10', '9274 Oak Rd, Rockridge', 'Full Stack Developer', 85000, 'mason.thomas@example.com', '555-9274'),
('Scarlett', 'Scott', 'Female', '1989-04-19', '4638 Pine Rd, Northwood', 'Digital Marketer', 72000, 'scarlett.scott@example.com', '555-4638'),
('Zachary', 'Adams', 'Male', '1993-11-02', '5829 Cedar Ln, Brookview', 'Business Analyst', 68000, 'zachary.adams@example.com', '555-5829'),
('Grace', 'Davis', 'Female', '1994-07-06', '2738 Oak Blvd, Cedarfield', 'HR Generalist', 55000, 'grace.davis@example.com', '555-2738'),
('Evan', 'Morris', 'Male', '1987-12-16', '3912 Birch Rd, Woodbury', 'Systems Administrator', 75000, 'evan.morris@example.com', '555-3912'),
('Hannah', 'Robinson', 'Female', '1988-05-13', '2457 Pine Ave, Sunshine', 'Web Designer', 69000, 'hannah.robinson@example.com', '555-2457'),
('Oliver', 'Harris', 'Male', '1991-03-27', '5342 Cedar Rd, Oldtown', 'Front-End Developer', 67000, 'oliver.harris@example.com', '555-5342'),
('Alice', 'Hall', 'Female', '1989-10-31', '2874 Maple Blvd, Greenhill', 'Social Media Manager', 63000, 'alice.hall@example.com', '555-2874'),
('Maverick', 'Carter', 'Male', '1986-11-04', '1025 Pine Blvd, Westend', 'Security Analyst', 71000, 'maverick.carter@example.com', '555-1025'),
('Lily', 'Graham', 'Female', '1992-09-22', '6843 Oak Rd, Crestwood', 'Content Manager', 68000, 'lily.graham@example.com', '555-6843'),
('Eli', 'Jackson', 'Male', '1994-08-14', '3729 Birch Ave, Summit', 'Financial Analyst', 74000, 'eli.jackson@example.com', '555-3729'),
('Jasmine', 'Foster', 'Female', '1989-01-20', '8156 Maple St, Riverside', 'Operations Coordinator', 72000, 'jasmine.foster@example.com', '555-8156'),
('Luke', 'Miller', 'Male', '1993-04-08', '2845 Pine St, Brightview', 'Backend Developer', 75000, 'luke.miller@example.com', '555-2845'),
('Chloe', 'Wright', 'Female', '1991-08-25', '5974 Oak Dr, Oakwood', 'Sales Coordinator', 65000, 'chloe.wright@example.com', '555-5974'),
('Owen', 'Roberts', 'Male', '1992-12-14', '4713 Birch Ave, Hillcrest', 'Network Engineer', 77000, 'owen.roberts@example.com', '555-4713'),
('Nina', 'Nelson', 'Female', '1990-05-29', '8569 Maple Blvd, Highland', 'Operations Lead', 79000, 'nina.nelson@example.com', '555-8569'),
('Lucas', 'Lopez', 'Male', '1988-03-30', '4720 Cedar St, Ridgewood', 'Project Manager', 85000, 'lucas.lopez@example.com', '555-4720'),
('Zoe', 'King', 'Female', '1993-08-18', '3931 Oak Rd, Pleasantview', 'Product Designer', 72000, 'zoe.king@example.com', '555-3931'),
('Evan', 'Thomas', 'Male', '1992-07-04', '8734 Birch Rd, Greenhill', 'Business Development', 74000, 'evan.thomas@example.com', '555-8734'),
('Sophie', 'Taylor', 'Female', '1994-01-25', '2491 Pine Ln, Eastwood', 'Web Developer', 78000, 'sophie.taylor@example.com', '555-2491'),
('Jackson', 'Moore', 'Male', '1991-12-18', '3729 Maple Rd, Lakeside', 'Sales Representative', 62000, 'jackson.moore@example.com', '555-3729'),
('Natalie', 'Hughes', 'Female', '1993-06-09', '4571 Oak Blvd, Westbrook', 'IT Consultant', 76000, 'natalie.hughes@example.com', '555-4571'),
('Carter', 'Gonzalez', 'Male', '1989-03-19', '3847 Cedar Blvd, Lakeview', 'Full Stack Developer', 78000, 'carter.gonzalez@example.com', '555-3847'),
('Dylan', 'Warren', 'Male', '1987-12-06', '7384 Birch Rd, Eastwood', 'Content Strategist', 70000, 'dylan.warren@example.com', '555-7384'),
('Madeline', 'Smith', 'Female', '1992-10-10', '1943 Pine Rd, Springside', 'Accountant', 67000, 'madeline.smith@example.com', '555-1943'),
('Elijah', 'Clark', 'Male', '1988-07-14', '6231 Oak Rd, Meadowview', 'Operations Lead', 80000, 'elijah.clark@example.com', '555-6231'),
('Leah', 'Young', 'Female', '1993-11-28', '5319 Birch Ln, Maplewood', 'Product Manager', 85000, 'leah.young@example.com', '555-5319');


-- --------------------------------------------------------

--
-- Table structure for table `employee_logs`
--

CREATE TABLE `employee_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `login_time` datetime DEFAULT current_timestamp(),
  `logout_time` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_logs`
--

INSERT INTO `employee_logs` (`id`, `email`, `login_time`, `logout_time`, `status`) VALUES
(2, 'ash@gmail.com', '2025-01-09 20:15:28', '2025-01-09 20:15:36', 'logged_out'),
(3, 'ash@gmail.com', '2025-01-09 20:16:51', '2025-01-09 20:16:56', 'logged_out'),
(4, 'test@gmail.com', '2025-01-09 20:17:28', '2025-01-09 20:21:26', 'logged_out'),
(5, 'ash@gmail.com', '2025-01-09 20:21:49', '2025-01-09 20:58:54', 'logged_out'),
(6, 'fat@gmail.com', '2025-01-09 20:59:23', '2025-01-09 21:00:19', 'logged_out'),
(7, 'ash1@gmail.com', '2025-01-09 21:00:29', '2025-01-10 18:25:46', 'logged_out'),
(8, 'new@gmail.com', '2025-01-10 18:26:13', '2025-01-10 18:48:56', 'logged_out'),
(9, 'test@gmail.com', '2025-01-10 19:50:40', '2025-01-10 19:51:56', 'logged_out'),
(10, 'marc@gmail.com', '2025-01-10 19:52:07', '2025-01-10 19:59:23', 'logged_out'),
(11, 'marc@gmail.com', '2025-01-11 11:24:18', '2025-01-11 11:48:48', 'logged_out'),
(12, 'john@gmail.com', '2025-01-11 11:49:22', '2025-01-11 11:49:36', 'logged_out'),
(13, 'john@gmail.com', '2025-01-11 11:50:19', NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `leave_type` enum('vacation','sick','personal','emergency') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `email`, `leave_type`, `start_date`, `end_date`, `reason`, `status`, `created_at`) VALUES
(22, 'marc@gmail.com', 'vacation', '2025-01-11', '2025-01-18', 'wellll', 'approved', '2025-01-11 03:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `pending_approvals`
--

CREATE TABLE `pending_approvals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Fname` varchar(50) DEFAULT NULL,
  `Lname` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `date_birth` date DEFAULT NULL,
  `Address` varchar(100) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('pending','approved','denied') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `action_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pending_approvals`
--

INSERT INTO `pending_approvals` (`id`, `Fname`, `Lname`, `gender`, `date_birth`, `Address`, `position`, `salary`, `email`, `phone`, `status`, `created_at`, `action_date`) VALUES
(10, 'marc', 'me', 'Male', '2025-01-07', 'asfasd', 'asdawd', 13123.00, 'marc@gmail.com', '11234151', 'approved', '2025-01-07 12:52:15', '2025-01-07 12:52:24'),
(11, 'asfasd', 'aasd', 'Male', '2025-01-07', 'asdasd', 'asdasd', 1234123.00, 'asd@gmail.com', '1234123', 'approved', '2025-01-07 13:04:28', '2025-01-07 13:04:33'),
(12, 'ash', 'me', 'Male', '2025-01-09', 'asdasd', 'asdasd', 124123.00, 'marc@gmail.com', '12412312', 'approved', '2025-01-09 12:39:57', '2025-01-09 12:40:06'),
(13, 'ash', 'me', 'Male', '2025-01-09', 'bulacan', 'manager', 1234123.00, 'ash@gmail.com', '123123', 'approved', '2025-01-09 12:56:26', '2025-01-09 12:56:43'),
(14, 'fat', 'pat', 'Female', '2025-01-09', 'NV', 'coder', 124123.00, 'fat@gmail.com', '1412312', 'approved', '2025-01-09 12:59:56', '2025-01-09 13:00:12'),
(15, 'ashhh', 'lee', 'Male', '2025-01-09', 'asda', 'asdasd', 124123.00, 'ash1@gmail.com', '1234123', 'approved', '2025-01-09 13:01:58', '2025-01-09 13:02:11'),
(16, 'new', 'me', 'Male', '2025-01-10', 'adwafa', 'asdwa', 30000.00, 'new@gmail.com', '1412321321', 'approved', '2025-01-10 10:28:18', '2025-01-10 10:28:28'),
(17, 'test', 'me', 'Male', '2025-01-10', 'solano', 'manager', 30000.00, 'test@gmail.com', '123123948', 'approved', '2025-01-10 11:51:18', '2025-01-10 11:51:37'),
(18, 'marc', 'Crisostomo', 'Male', '2025-01-10', 'Bulacan', 'Project Manager', 150000.00, 'marc@gail.com', '09384473615', 'approved', '2025-01-10 11:53:13', '2025-01-10 11:53:20'),
(19, 'marc', 'me', 'Male', '2025-01-10', 'asdasd', 'asdasd', 141231.00, 'marc@gmail.com', '1243123', 'denied', '2025-01-10 11:56:02', '2025-01-10 11:58:22'),
(20, 'Marc', 'Crisostomo', 'Male', '2003-04-23', 'Bulacan', 'Project Manager', 150000.00, 'marc@gmail.com', '09283374652', 'approved', '2025-01-10 11:57:51', '2025-01-10 11:58:36'),
(21, 'marccc', 'meee', 'Male', '2025-01-10', 'asdasd', 'asdasd', 123123.00, 'marc@gmail.com', '1331121', 'denied', '2025-01-10 11:58:15', '2025-01-10 11:58:33'),
(22, 'john', 'bert', 'Male', '2025-01-11', 'hagonoy', 'coder', 40000.00, 'john@gmail.com', '1245341233', 'approved', '2025-01-11 03:50:54', '2025-01-11 03:51:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_acc`
--
ALTER TABLE `employee_acc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_data`
--
ALTER TABLE `employee_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_logs`
--
ALTER TABLE `employee_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pending_approvals`
--
ALTER TABLE `pending_approvals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `employee_acc`
--
ALTER TABLE `employee_acc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `employee_data`
--
ALTER TABLE `employee_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `employee_logs`
--
ALTER TABLE `employee_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `pending_approvals`
--
ALTER TABLE `pending_approvals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
