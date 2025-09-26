-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 26, 2025 at 05:25 PM
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
-- Database: `airlinesdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `aircrafts`
--

CREATE TABLE `aircrafts` (
  `aircraft_id` int(11) NOT NULL,
  `model` varchar(100) NOT NULL,
  `capacity` int(11) NOT NULL,
  `maintenance_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aircrafts`
--

INSERT INTO `aircrafts` (`aircraft_id`, `model`, `capacity`, `maintenance_status`) VALUES
(101, 'Boeing 737', 180, 'Operational'),
(102, 'Airbus A320', 160, 'Operational'),
(103, 'Embraer E190', 100, 'Under Maintenance'),
(104, 'Boeing 777', 300, 'Operational'),
(105, 'Airbus A350', 280, 'Operational'),
(106, 'Bombardier Q400', 90, 'Operational'),
(107, 'Boeing 787', 250, 'Operational'),
(108, 'ATR 72', 70, 'Operational'),
(109, 'Airbus A330', 260, 'Operational'),
(110, 'Boeing 767', 220, 'Under Maintenance'),
(111, 'Boeing 777', 350, 'Operational');

-- --------------------------------------------------------

--
-- Table structure for table `airports`
--

CREATE TABLE `airports` (
  `airport_code` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `airports`
--

INSERT INTO `airports` (`airport_code`, `name`, `location`) VALUES
(8801, 'Dhaka International Airport', 'Dhaka, Bangladesh'),
(8802, 'Changi Airport', 'Singapore'),
(8803, 'Heathrow Airport', 'London, UK'),
(8804, 'JFK Intl', 'New York, USA'),
(8805, 'Dubai Intl', 'Dubai, UAE'),
(8806, 'Suvarnabhumi', 'Bangkok, Thailand'),
(8807, 'Frankfurt Intl', 'Frankfurt, Germany'),
(8808, 'Narita Intl', 'Tokyo, Japan'),
(8809, 'Indira Gandhi Intl', 'Delhi, India'),
(8810, 'Hamad Intl', 'Doha, Qatar'),
(8811, 'KUET International Airport', 'Khulna, Bangladesh');

-- --------------------------------------------------------

--
-- Table structure for table `crew_members`
--

CREATE TABLE `crew_members` (
  `crew_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `certifications` varchar(100) NOT NULL,
  `assigned_aircraft` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crew_members`
--

INSERT INTO `crew_members` (`crew_id`, `first_name`, `last_name`, `role`, `certifications`, `assigned_aircraft`) VALUES
(601, 'Farhana', 'Islam', 'Flight Attendant', 'CPR, Safety', 101),
(602, 'Rafia', 'Chowdhury', 'Engineer', 'Maintenance Level 2', 102),
(603, 'Lily', 'Tan', 'Flight Attendant', 'CPR', 104),
(604, 'Shizuka', 'Sato', 'Flight Attendant', 'CPR, Fire Safety', 105),
(605, 'Maria', 'Gomez', 'Engineer', 'Level 3 Cert', 107),
(606, 'Ahmed', 'Hridita', 'Flight Attendant', 'CPR', 109),
(607, 'Chloe', 'Nguyen', 'Flight Attendant', 'CPR, Safety', 106),
(608, 'Azmain Mehreen', 'Khan', 'Engineer', 'Level 2 Cert', 108),
(609, 'Julia', 'Roberts', 'Flight Attendant', 'CPR', 110),
(610, 'Hasan', 'Tahiya', 'Flight Attendant', 'CPR', 107),
(611, 'Mayesha', 'Rahman', 'Engineer', 'Maintenance Level 1', 105),
(612, 'Elena', 'Petrova', 'Flight Attendant', 'CPR, Safety', 103),
(613, 'Megha', 'Tania', 'Flight Attendant', 'CPR, Safety', 111);

-- --------------------------------------------------------

--
-- Table structure for table `flights`
--

CREATE TABLE `flights` (
  `flight_number` int(11) NOT NULL,
  `departure_airport` int(11) DEFAULT NULL,
  `arrival_airport` int(11) DEFAULT NULL,
  `departure_time` datetime NOT NULL,
  `arrival_time` datetime NOT NULL,
  `aircraft_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flights`
--

INSERT INTO `flights` (`flight_number`, `departure_airport`, `arrival_airport`, `departure_time`, `arrival_time`, `aircraft_id`) VALUES
(3001, 8801, 8802, '2025-10-01 08:00:00', '2025-10-01 11:30:00', 101),
(3002, 8802, 8803, '2025-10-02 14:00:00', '2025-10-02 18:00:00', 102),
(3003, 8803, 8804, '2025-10-03 09:00:00', '2025-10-03 13:30:00', 104),
(3004, 8804, 8805, '2025-10-04 07:00:00', '2025-10-04 11:00:00', 105),
(3005, 8805, 8806, '2025-10-05 12:00:00', '2025-10-05 16:00:00', 107),
(3006, 8806, 8807, '2025-10-06 10:00:00', '2025-10-06 14:30:00', 106),
(3007, 8807, 8808, '2025-10-07 15:00:00', '2025-10-07 20:00:00', 108),
(3008, 8808, 8809, '2025-10-08 06:00:00', '2025-10-08 10:00:00', 109),
(3009, 8809, 8810, '2025-10-09 13:00:00', '2025-10-09 17:00:00', 110),
(3010, 8810, 8801, '2025-10-10 09:00:00', '2025-10-10 13:30:00', 103),
(3011, 8801, 8803, '2025-10-11 08:00:00', '2025-10-11 12:00:00', 101),
(3012, 8802, 8805, '2025-10-12 14:00:00', '2025-10-12 18:00:00', 102),
(3013, 8803, 8806, '2025-10-13 09:00:00', '2025-10-13 13:30:00', 104),
(3014, 8804, 8807, '2025-10-14 07:00:00', '2025-10-14 11:00:00', 105),
(3015, 8805, 8808, '2025-10-15 12:00:00', '2025-10-15 16:00:00', 107),
(3016, 8803, 8805, '2025-09-26 15:38:10', '2025-09-26 19:38:10', 111);

-- --------------------------------------------------------

--
-- Table structure for table `passengers`
--

CREATE TABLE `passengers` (
  `passengers_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `frequently_flyer_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `passengers`
--

INSERT INTO `passengers` (`passengers_id`, `first_name`, `last_name`, `phone`, `email`, `frequently_flyer_status`) VALUES
(701, 'Karim', 'Hossain', '01711112222', 'karim@example.com', 'Gold'),
(702, 'Sara', 'Lee', '01888883333', 'sara.lee@example.com', 'Silver'),
(703, 'David', 'Roy', '01999994444', 'david.roy@example.com', 'Regular'),
(704, 'Arunima', 'Chowdhury', '01722223333', 'arunima.c@example.com', 'Gold'),
(705, 'Tom', 'Becker', '01833334444', 'tom.becker@example.com', 'Silver'),
(706, 'Nabila', 'Islam', '01944445555', 'nabila.islam@example.com', 'Regular'),
(707, 'Hiroshi', 'Tanaka', '01755556666', 'hiroshi.t@example.com', 'Gold'),
(708, 'Fatima', 'Zahra', '01866667777', 'fatima.z@example.com', 'Silver'),
(709, 'Alex', 'Morgan', '01977778888', 'alex.morgan@example.com', 'Regular'),
(710, 'Rashed', 'Khan', '01788889999', 'rashed.k@example.com', 'Gold'),
(711, 'Emily', 'Davis', '01899990000', 'emily.d@example.com', 'Silver'),
(712, 'Omar', 'Siddiqi', '01900001111', 'omar.s@example.com', 'Regular'),
(713, 'Priya', 'Sen', '01711112233', 'priya.sen@example.com', 'Gold'),
(714, 'John', 'Carter', '01822223344', 'john.carter@example.com', 'Silver'),
(715, 'Mei', 'Lin', '01933334455', 'mei.lin@example.com', 'Regular'),
(716, 'Rubayet', 'Nabil', '01521741507', 'nabil@example.cpm', 'Regular');

-- --------------------------------------------------------

--
-- Table structure for table `pilots`
--

CREATE TABLE `pilots` (
  `pilot_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `license_number` varchar(100) NOT NULL,
  `experience_years` int(11) NOT NULL,
  `rank` varchar(100) NOT NULL,
  `assigned_aircraft` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pilots`
--

INSERT INTO `pilots` (`pilot_id`, `first_name`, `last_name`, `license_number`, `experience_years`, `rank`, `assigned_aircraft`) VALUES
(501, 'Ayesha', 'Rahman', 'LIC-BD-2023', 12, 'Captain', 101),
(502, 'Tanvir', 'Ahmed', 'LIC-BD-2021', 8, 'First Officer', 102),
(503, 'John', 'Smith', 'LIC-UK-2019', 15, 'Captain', 104),
(504, 'Emily', 'Zhang', 'LIC-SG-2020', 10, 'Captain', 105),
(505, 'Omar', 'Khalid', 'LIC-UAE-2018', 14, 'Captain', 107),
(506, 'Rajesh', 'Mehta', 'LIC-IN-2022', 6, 'First Officer', 106),
(507, 'Fatima', 'Noor', 'LIC-QA-2021', 9, 'Captain', 109),
(508, 'Alex', 'Turner', 'LIC-US-2017', 16, 'Captain', 110),
(509, 'Hana', 'Lee', 'LIC-JP-2020', 11, 'First Officer', 108),
(510, 'Nabil', 'Hasan', 'LIC-BD-2024', 5, 'First Officer', 101),
(511, 'Sofia', 'Costa', 'LIC-PT-2019', 13, 'Captain', 103),
(512, 'Marco', 'Rossi', 'LIC-IT-2020', 7, 'First Officer', 105),
(513, 'Zarif', 'Naheean', 'LIC-BD-2027', 2, 'First Officer', 111),
(514, 'Maruf', 'Shafiq', 'LIC-BD-1999', 15, 'Captain', 111);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `passenger_id` int(11) DEFAULT NULL,
  `flight_number` int(11) DEFAULT NULL,
  `seat_number` varchar(20) NOT NULL,
  `Booking_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `passenger_id`, `flight_number`, `seat_number`, `Booking_date`) VALUES
(9001, 701, 3001, '12A', '2025-09-25 04:00:00'),
(9002, 702, 3002, '14C', '2025-09-26 06:30:00'),
(9003, 703, 3003, '15B', '2025-09-26 08:45:00'),
(9004, 704, 3004, '16D', '2025-09-27 03:00:00'),
(9005, 705, 3005, '17E', '2025-09-27 05:15:00'),
(9006, 706, 3006, '18F', '2025-09-28 02:30:00'),
(9007, 707, 3007, '19A', '2025-09-28 07:00:00'),
(9008, 708, 3008, '20B', '2025-09-29 04:45:00'),
(9009, 709, 3009, '21C', '2025-09-29 09:30:00'),
(9010, 710, 3010, '22D', '2025-09-30 03:00:00'),
(9011, 711, 3011, '23E', '2025-09-30 05:00:00'),
(9012, 712, 3012, '24F', '2025-10-01 01:30:00'),
(9013, 713, 3013, '25A', '2025-10-01 06:15:00'),
(9014, 714, 3014, '26B', '2025-10-02 02:45:00'),
(9015, 715, 3015, '27C', '2025-10-02 08:00:00'),
(9016, 716, 3016, 'C-23', '2025-09-26 15:10:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aircrafts`
--
ALTER TABLE `aircrafts`
  ADD PRIMARY KEY (`aircraft_id`);

--
-- Indexes for table `airports`
--
ALTER TABLE `airports`
  ADD PRIMARY KEY (`airport_code`);

--
-- Indexes for table `crew_members`
--
ALTER TABLE `crew_members`
  ADD PRIMARY KEY (`crew_id`),
  ADD KEY `assigned_aircraft_crew_fk` (`assigned_aircraft`);

--
-- Indexes for table `flights`
--
ALTER TABLE `flights`
  ADD PRIMARY KEY (`flight_number`),
  ADD KEY `aircraft_id_fk` (`aircraft_id`),
  ADD KEY `departure_airport_fk` (`departure_airport`),
  ADD KEY `arrival_airport_fk` (`arrival_airport`);

--
-- Indexes for table `passengers`
--
ALTER TABLE `passengers`
  ADD PRIMARY KEY (`passengers_id`);

--
-- Indexes for table `pilots`
--
ALTER TABLE `pilots`
  ADD PRIMARY KEY (`pilot_id`),
  ADD KEY `assigned_aircraft_fk` (`assigned_aircraft`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `passenger_id_fk` (`passenger_id`),
  ADD KEY `flight_number_fk` (`flight_number`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `crew_members`
--
ALTER TABLE `crew_members`
  ADD CONSTRAINT `assigned_aircraft_crew_fk` FOREIGN KEY (`assigned_aircraft`) REFERENCES `aircrafts` (`aircraft_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `flights`
--
ALTER TABLE `flights`
  ADD CONSTRAINT `aircraft_id_fk` FOREIGN KEY (`aircraft_id`) REFERENCES `aircrafts` (`aircraft_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `arrival_airport_fk` FOREIGN KEY (`arrival_airport`) REFERENCES `airports` (`airport_code`),
  ADD CONSTRAINT `departure_airport_fk` FOREIGN KEY (`departure_airport`) REFERENCES `airports` (`airport_code`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pilots`
--
ALTER TABLE `pilots`
  ADD CONSTRAINT `assigned_aircraft_fk` FOREIGN KEY (`assigned_aircraft`) REFERENCES `aircrafts` (`aircraft_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `flight_number_fk` FOREIGN KEY (`flight_number`) REFERENCES `flights` (`flight_number`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `passenger_id_fk` FOREIGN KEY (`passenger_id`) REFERENCES `passengers` (`passengers_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
