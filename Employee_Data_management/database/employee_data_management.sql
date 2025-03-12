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

INSERT INTO `employee_acc` (`id`, `full_name`, `email`, `password`, `code`, `registration_date`) VALUES
(1, 'John Doe', 'john.doe@example.com', 'test123', 'XKD872', '2025-02-15 10:30:22'),
(2, 'Jane Smith', 'jane.smith@example.com', 'test123', 'PLM639', '2025-02-16 14:12:47'),
(3, 'Michael Johnson', 'michael.johnson@example.com', 'test123', 'ZQT451', '2025-02-17 09:05:18'),
(4, 'Emily Davis', 'emily.davis@example.com', 'test123', 'BGY927', '2025-02-18 16:40:33'),
(5, 'William Brown', 'william.brown@example.com', 'test123', 'LWC305', '2025-02-19 08:25:55'),
(6, 'Sophia Martinez', 'sophia.martinez@example.com', 'test123', 'DRK784', '2025-02-20 13:17:29'),
(7, 'James Anderson', 'james.anderson@example.com', 'test123', 'VNP634', '2025-02-21 11:52:14'),
(8, 'Olivia Thomas', 'olivia.thomas@example.com', 'test123', 'TSQ189', '2025-02-22 15:05:41'),
(9, 'Benjamin Taylor', 'benjamin.taylor@example.com', 'test123', 'KXY721', '2025-02-23 12:30:59'),
(10, 'Ava White', 'ava.white@example.com', 'test123', 'FJM582', '2025-02-24 17:48:06'),
(11, 'Henry Harris', 'henry.harris@example.com', 'test123', 'RZL415', '2025-02-25 08:22:30'),
(12, 'Mia Clark', 'mia.clark@example.com', 'test123', 'GXD583', '2025-02-26 10:40:52'),
(13, 'Daniel Rodriguez', 'daniel.rodriguez@example.com', 'test123', 'HYN936', '2025-02-27 14:55:38'),
(14, 'Charlotte Lewis', 'charlotte.lewis@example.com', 'test123', 'TMP721', '2025-02-28 16:07:19'),
(15, 'Logan Walker', 'logan.walker@example.com', 'test123', 'WXO348', '2025-03-01 11:33:47'),
(16, 'Ella Hall', 'ella.hall@example.com', 'test123', 'JQK571', '2025-03-02 09:45:21'),
(17, 'Alexander Allen', 'alexander.allen@example.com', 'test123', 'BVN634', '2025-03-03 13:28:09'),
(18, 'Amelia Young', 'amelia.young@example.com', 'test123', 'CYZ248', '2025-03-04 15:55:37'),
(19, 'Jackson King', 'jackson.king@example.com', 'test123', 'LPW782', '2025-03-05 12:14:56'),
(20, 'Scarlett Scott', 'scarlett.scott@example.com', 'test123', 'MKO315', '2025-03-06 16:23:44'),
(21, 'Lucas Green', 'lucas.green@example.com', 'test123', 'TZQ741', '2025-03-07 10:07:31'),
(22, 'Grace Adams', 'grace.adams@example.com', 'test123', 'XYR584', '2025-03-08 14:39:25'),
(23, 'Liam Baker', 'liam.baker@example.com', 'test123', 'FNL963', '2025-03-09 11:48:50'),
(24, 'Sophie Nelson', 'sophie.nelson@example.com', 'test123', 'VDX125', '2025-03-10 13:59:27'),
(25, 'Ethan Carter', 'ethan.carter@example.com', 'test123', 'HQL784', '2025-03-11 15:10:14'),
(26, 'Isabella Mitchell', 'isabella.mitchell@example.com', 'test123', 'BPK359', '2025-03-12 12:37:42'),
(27, 'Noah Perez', 'noah.perez@example.com', 'test123', 'XWT582', '2025-03-13 10:58:33'),
(28, 'Ava Roberts', 'ava.roberts@example.com', 'test123', 'JYM748', '2025-03-14 14:19:51'),
(29, 'Oliver Evans', 'oliver.evans@example.com', 'test123', 'QLX357', '2025-03-15 09:28:17'),
(30, 'Emma Morris', 'emma.morris@example.com', 'test123', 'ZPN624', '2025-03-16 16:42:56'),
(31, 'Jacob Cook', 'jacob.cook@example.com', 'test123', 'DVL563', '2025-03-17 08:15:44'),
(32, 'Madison Bell', 'madison.bell@example.com', 'test123', 'RWY759', '2025-03-18 14:53:22'),
(33, 'Carter Ward', 'carter.ward@example.com', 'test123', 'NXP318', '2025-03-19 11:29:35'),
(34, 'Victoria James', 'victoria.james@example.com', 'test123', 'KYZ847', '2025-03-20 13:41:18'),
(35, 'Gabriel Bennett', 'gabriel.bennett@example.com', 'test123', 'LWM526', '2025-03-21 10:06:59'),
(36, 'Ella Rivera', 'ella.rivera@example.com', 'test123', 'FTQ974', '2025-03-22 15:38:24'),
(37, 'Chase Reed', 'chase.reed@example.com', 'test123', 'ZKL587', '2025-03-23 12:09:40'),
(38, 'Penelope Stewart', 'penelope.stewart@example.com', 'test123', 'GYP432', '2025-03-24 17:15:51'),
(39, 'Ryan Cooper', 'ryan.cooper@example.com', 'test123', 'QXN258', '2025-03-25 09:47:36'),
(40, 'Lily Flores', 'lily.flores@example.com', 'test123', 'MRT619', '2025-03-26 11:54:48'),
(41, 'Aiden Morris', 'aiden.morris@example.com', 'test123', 'WQZ851', '2025-03-27 14:26:57'),
(42, 'Zoe Morgan', 'zoe.morgan@example.com', 'test123', 'DRK462', '2025-03-28 16:02:34'),
(43, 'Nicholas Peterson', 'nicholas.peterson@example.com', 'test123', 'BLX793', '2025-03-29 10:17:29'),
(44, 'Chloe Bailey', 'chloe.bailey@example.com', 'test123', 'FTY527', '2025-03-30 12:45:11'),
(45, 'Elijah Long', 'elijah.long@example.com', 'test123', 'RMP398', '2025-03-31 15:32:20'),
(46, 'Natalie Price', 'natalie.price@example.com', 'test123', 'XVL624', '2025-04-01 09:09:15'),
(47, 'Caleb Ross', 'caleb.ross@example.com', 'test123', 'TQP873', '2025-04-02 11:19:47'),
(48, 'Hannah Diaz', 'hannah.diaz@example.com', 'test123', 'LWK386', '2025-04-03 14:56:31'),
(49, 'Owen Scott', 'owen.scott@example.com', 'test123', 'BVY214', '2025-04-04 16:41:22'),
(50, 'Rachel Wood', 'rachel.wood@example.com', 'test123', 'MNP537', '2025-04-05 08:30:55');




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
('Jane', 'Smith', 'Female', '1988-06-20', '5678 Oak St, Townsville', 'Project Manager', 82000, 'jane.smith@example.com', '555-5678'),
('Michael', 'Johnson', 'Male', '1990-09-12', '9101 Pine St, Villagetown', 'Data Analyst', 68000, 'michael.johnson@example.com', '555-9101'),
('Emily', 'Davis', 'Female', '1992-04-25', '1122 Maple St, Suburbia', 'HR Specialist', 62000, 'emily.davis@example.com', '555-1122'),
('William', 'Brown', 'Male', '1987-11-30', '3344 Birch St, Metropolis', 'Network Administrator', 70000, 'william.brown@example.com', '555-3344'),
('Sophia', 'Martinez', 'Female', '1995-07-19', '5566 Cedar St, Capital City', 'Marketing Coordinator', 60000, 'sophia.martinez@example.com', '555-5566'),
('James', 'Anderson', 'Male', '1983-05-14', '7788 Spruce St, Uptown', 'System Architect', 90000, 'james.anderson@example.com', '555-7788'),
('Olivia', 'Thomas', 'Female', '1991-03-08', '9900 Willow St, Downtown', 'Graphic Designer', 65000, 'olivia.thomas@example.com', '555-9900'),
('Benjamin', 'Taylor', 'Male', '1994-12-22', '2233 Fir St, Smalltown', 'IT Support', 55000, 'benjamin.taylor@example.com', '555-2233'),
('Ava', 'White', 'Female', '1996-08-17', '4455 Redwood St, New City', 'Content Writer', 58000, 'ava.white@example.com', '555-4455'),
('Daniel', 'Harris', 'Male', '1989-01-25', '6677 Aspen St, Riverside', 'Database Administrator', 73000, 'daniel.harris@example.com', '555-6677'),
('Mia', 'Clark', 'Female', '1993-09-09', '8899 Magnolia St, Harbor City', 'SEO Specialist', 61000, 'mia.clark@example.com', '555-8899'),
('Ethan', 'Lewis', 'Male', '1984-07-01', '1212 Cypress St, Lakeside', 'Software Developer', 77000, 'ethan.lewis@example.com', '555-1212'),
('Charlotte', 'Robinson', 'Female', '1997-11-11', '2323 Oakwood St, Springfield', 'Social Media Manager', 59000, 'charlotte.robinson@example.com', '555-2323'),
('Alexander', 'Walker', 'Male', '1992-06-03', '3434 Birchwood St, Greenfield', 'Business Analyst', 72000, 'alexander.walker@example.com', '555-3434'),
('Harper', 'Hall', 'Female', '1990-08-14', '4545 Beech St, Sunnyside', 'Operations Manager', 85000, 'harper.hall@example.com', '555-4545'),
('Mason', 'Allen', 'Male', '1981-12-30', '5656 Pineview St, Midtown', 'Cybersecurity Analyst', 88000, 'mason.allen@example.com', '555-5656'),
('Ella', 'Young', 'Female', '1995-05-07', '6767 Chestnut St, Hilltop', 'UX Designer', 64000, 'ella.young@example.com', '555-6767'),
('Logan', 'King', 'Male', '1986-02-27', '7878 Redwood St, City Central', 'IT Consultant', 78000, 'logan.king@example.com', '555-7878'),
('Grace', 'Wright', 'Female', '1994-10-10', '8989 Willowview St, Eastside', 'Customer Support Manager', 63000, 'grace.wright@example.com', '555-8989'),
('Henry', 'Scott', 'Male', '1983-11-20', '9090 Mapleview St, Westwood', 'Cloud Engineer', 85000, 'henry.scott@example.com', '555-9090'),
('Scarlett', 'Green', 'Female', '1996-04-18', '1010 Oakridge St, Grandville', 'Copywriter', 57000, 'scarlett.green@example.com', '555-1010'),
('Liam', 'Adams', 'Male', '1989-07-23', '1111 Aspenview St, Newtown', 'Software Tester', 71000, 'liam.adams@example.com', '555-1111'),
('Isabella', 'Baker', 'Female', '1993-03-15', '1212 Chestnutview St, Maplewood', 'Data Scientist', 88000, 'isabella.baker@example.com', '555-1213'),
('Lucas', 'Nelson', 'Male', '1991-05-22', '1313 Redwoodview St, Pineville', 'Technical Writer', 62000, 'lucas.nelson@example.com', '555-1313'),
('Amelia', 'Carter', 'Female', '1998-08-02', '1414 Willowridge St, Rivertown', 'Financial Analyst', 69000, 'amelia.carter@example.com', '555-1414'),
('Noah', 'Mitchell', 'Male', '1987-09-13', '1515 Oakforest St, Brookside', 'Security Specialist', 86000, 'noah.mitchell@example.com', '555-1515'),
('Emma', 'Perez', 'Female', '1992-12-08', '1616 Maplewood St, Woodland', 'Public Relations Specialist', 60000, 'emma.perez@example.com', '555-1616'),
('Sebastian', 'Roberts', 'Male', '1985-11-01', '1717 Birchwood St, Hillside', 'System Administrator', 79000, 'sebastian.roberts@example.com', '555-1717'),
('Victoria', 'Turner', 'Female', '1997-06-09', '1818 Cypressview St, Crestwood', 'Business Development Manager', 85000, 'victoria.turner@example.com', '555-1818'),
('David', 'Phillips', 'Male', '1980-04-29', '1919 Pineforest St, Baytown', 'Technical Support Lead', 68000, 'david.phillips@example.com', '555-1919'),
('Lily', 'Campbell', 'Female', '1994-09-26', '2020 Oakview St, Georgetown', 'E-commerce Manager', 72000, 'lily.campbell@example.com', '555-2020'),
('Samuel', 'Evans', 'Male', '1982-10-31', '2121 Maplewood St, Northside', 'Machine Learning Engineer', 95000, 'samuel.evans@example.com', '555-2121');


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
