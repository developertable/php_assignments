-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 30, 2026 at 04:54 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tech_support`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrators`
--

CREATE TABLE `administrators` (
  `adminID` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administrators`
--

INSERT INTO `administrators` (`adminID`, `username`, `password`, `firstName`, `lastName`) VALUES
(1, 'admin', 'sesame', 'Admin', 'User'),
(2, 'manager', 'sesame', 'System', 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `countryID` int(11) NOT NULL,
  `countryCode` varchar(2) NOT NULL,
  `countryName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`countryID`, `countryCode`, `countryName`) VALUES
(1, 'US', 'United States'),
(2, 'CA', 'Canada'),
(3, 'MX', 'Mexico'),
(4, 'UK', 'United Kingdom'),
(5, 'FR', 'France'),
(6, 'DE', 'Germany'),
(7, 'JP', 'Japan'),
(8, 'IN', 'India');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customerID` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `postalCode` varchar(20) NOT NULL,
  `countryID` int(11) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customerID`, `firstName`, `lastName`, `address`, `city`, `state`, `postalCode`, `countryID`, `phone`, `email`, `password`) VALUES
(1, 'John', 'Smith', '123 Main St', 'New York', 'NY', '10001', 1, '(212) 555-0100', 'john.smith@example.com', 'password123'),
(2, 'Sarah', 'Johnson', '456 Oak Ave', 'Los Angeles', 'CA', '90001', 1, '(310) 555-0200', 'sarah.johnson@example.com', 'password123'),
(3, 'Michael', 'Williams', '789 Pine Rd', 'Chicago', 'IL', '60601', 1, '(312) 555-0300', 'michael.williams@example.com', 'password123'),
(4, 'Emily', 'Brown', '321 Elm St', 'Houston', 'TX', '77001', 1, '(713) 555-0400', 'emily.brown@example.com', 'password123'),
(5, 'David', 'Jones', '654 Maple Dr', 'Phoenix', 'AZ', '85001', 1, '(602) 555-0500', 'david.jones@example.com', 'password123');

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

CREATE TABLE `incidents` (
  `incidentID` int(11) NOT NULL,
  `customerID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `techID` int(11) DEFAULT NULL,
  `dateOpened` datetime NOT NULL DEFAULT current_timestamp(),
  `dateClosed` datetime DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incidents`
--

INSERT INTO `incidents` (`incidentID`, `customerID`, `productID`, `techID`, `dateOpened`, `dateClosed`, `title`, `description`) VALUES
(1, 1, 1, 1, '2023-02-15 10:30:00', '2023-02-16 14:20:00', 'Installation issue', 'Cannot install Draft Manager on Windows 11'),
(2, 2, 2, 2, '2024-03-10 09:15:00', NULL, 'License key not working', 'License key shows as invalid'),
(3, 3, 1, 1, '2023-04-05 11:45:00', '2023-04-06 16:30:00', 'Crashes on startup', 'Application crashes immediately after launch'),
(4, 4, 2, NULL, '2024-04-01 14:20:00', NULL, 'Missing feature', 'Export to Excel option not available'),
(5, 5, 3, NULL, '2023-06-10 08:30:00', NULL, 'Data sync error', 'Schedule data not syncing with mobile app');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `productID` int(11) NOT NULL,
  `productCode` varchar(10) NOT NULL,
  `productName` varchar(100) NOT NULL,
  `version` varchar(20) NOT NULL,
  `releaseDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`productID`, `productCode`, `productName`, `version`, `releaseDate`) VALUES
(1, 'DRAFT10', 'Draft Manager', '1.0', '2023-01-15'),
(2, 'DRAFT20', 'Draft Manager', '2.0', '2024-02-20'),
(3, 'LEAG10', 'League Scheduler', '1.0', '2023-03-10'),
(4, 'LEAG20', 'League Scheduler', '2.0', '2024-04-15'),
(5, 'TRNY10', 'Tournament Master', '1.0', '2023-05-20'),
(6, 'TRNY20', 'Tournament Master', '2.0', '2024-06-25');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `customerID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `registrationDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`customerID`, `productID`, `registrationDate`) VALUES
(1, 1, '2023-02-01'),
(1, 3, '2023-04-01'),
(2, 2, '2024-03-01'),
(2, 4, '2024-05-01'),
(3, 1, '2023-03-15'),
(3, 5, '2023-06-01'),
(4, 2, '2024-03-20'),
(5, 3, '2023-05-10');

-- --------------------------------------------------------

--
-- Table structure for table `technicians`
--

CREATE TABLE `technicians` (
  `techID` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `technicians`
--

INSERT INTO `technicians` (`techID`, `firstName`, `lastName`, `email`, `phone`, `password`) VALUES
(1, 'Alice', 'Anderson', 'alice.anderson@techsupport.com', '(555) 111-1111', 'tech123'),
(2, 'Bob', 'Baker', 'bob.baker@techsupport.com', '(555) 222-2222', 'tech123'),
(3, 'Charlie', 'Chen', 'charlie.chen@techsupport.com', '(555) 333-3333', 'tech123'),
(4, 'Diana', 'Davis', 'diana.davis@techsupport.com', '(555) 444-4444', 'tech123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrators`
--
ALTER TABLE `administrators`
  ADD PRIMARY KEY (`adminID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`countryID`),
  ADD UNIQUE KEY `countryCode` (`countryCode`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customerID`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `countryID` (`countryID`);

--
-- Indexes for table `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`incidentID`),
  ADD KEY `customerID` (`customerID`),
  ADD KEY `productID` (`productID`),
  ADD KEY `techID` (`techID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`productID`),
  ADD UNIQUE KEY `productCode` (`productCode`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`customerID`,`productID`),
  ADD KEY `productID` (`productID`);

--
-- Indexes for table `technicians`
--
ALTER TABLE `technicians`
  ADD PRIMARY KEY (`techID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrators`
--
ALTER TABLE `administrators`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `countryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `incidentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `productID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `technicians`
--
ALTER TABLE `technicians`
  MODIFY `techID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`countryID`) REFERENCES `countries` (`countryID`);

--
-- Constraints for table `incidents`
--
ALTER TABLE `incidents`
  ADD CONSTRAINT `incidents_ibfk_1` FOREIGN KEY (`customerID`) REFERENCES `customers` (`customerID`),
  ADD CONSTRAINT `incidents_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`),
  ADD CONSTRAINT `incidents_ibfk_3` FOREIGN KEY (`techID`) REFERENCES `technicians` (`techID`);

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`customerID`) REFERENCES `customers` (`customerID`),
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
