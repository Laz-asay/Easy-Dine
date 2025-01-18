-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2025 at 07:29 PM
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
-- Database: `food4thought`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Admin_ID` int(5) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `Cart_ID` int(6) NOT NULL,
  `quantity` int(6) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `Dish_ID` int(5) NOT NULL,
  `Table_ID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`Cart_ID`, `quantity`, `price`, `Dish_ID`, `Table_ID`) VALUES
(8, 1, 12.40, 16, 63),
(9, 1, 12.30, 18, 63),
(10, 3, 12.30, 18, 63),
(11, 2, 12.40, 16, 63),
(12, 2, 10.00, 10, 63),
(13, 1, 10.00, 10, 59),
(14, 1, 10.20, 14, 59);

-- --------------------------------------------------------

--
-- Table structure for table `food_category`
--

CREATE TABLE `food_category` (
  `Category_ID` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_category`
--

INSERT INTO `food_category` (`Category_ID`, `category_name`) VALUES
(8, 'Beverages'),
(7, 'Desserts'),
(6, 'Main Dish'),
(4, 'Side Dish');

-- --------------------------------------------------------

--
-- Table structure for table `kitchen`
--

CREATE TABLE `kitchen` (
  `Kitchen_ID` int(11) NOT NULL,
  `order_status` varchar(255) NOT NULL,
  `Order_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `Dish_ID` int(5) NOT NULL,
  `dish_name` varchar(255) NOT NULL,
  `dish_image` varchar(255) NOT NULL,
  `dish_price` decimal(10,2) NOT NULL,
  `dish_desc` varchar(255) NOT NULL,
  `dish_category` varchar(30) NOT NULL,
  `dish_availability` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`Dish_ID`, `dish_name`, `dish_image`, `dish_price`, `dish_desc`, `dish_category`, `dish_availability`) VALUES
(10, 'nasi lemak', '6784b792a007b_Nasi lemak.jpg', 10.00, 'nasi lemak', 'Side Dish', 0),
(14, 'Sushi Platter', '6784fb4115309_Sushi.jpg', 10.20, 'Sushi Platter', 'Main Dish', 0),
(15, 'Nasi Lemak', '67850351f0b5b_Nasi lemak.jpg', 12.40, 'Contains sambal, e.g, etc...', 'Main Dish', 0),
(16, 'Kimchi ', '6785537a43bf8_Kimchi.jpg', 12.40, 'Kimchi is korean food', 'Side Dish', 0),
(17, 'Kimchi ', '6787882593a60_Kimchi.jpg', 12.45, 'dessert test', 'Desserts', 0),
(18, 'long dish name example', '67881f81346e3_Kimchi.jpg', 12.30, 'lorem ipsum dolor sit amet', 'Side Dish', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orderlist`
--

CREATE TABLE `orderlist` (
  `Order_ID` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_status` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `Table_ID` int(11) NOT NULL,
  `Cart_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `Report_ID` int(11) NOT NULL,
  `order_status` varchar(255) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `Order_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `Staff_ID` int(10) NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tablelist`
--

CREATE TABLE `tablelist` (
  `Table_ID` int(5) NOT NULL,
  `table_number` int(4) NOT NULL,
  `qr_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tablelist`
--

INSERT INTO `tablelist` (`Table_ID`, `table_number`, `qr_code`) VALUES
(59, 1, '../images/QR/table_1.png'),
(60, 2, '../images/QR/table_2.png'),
(61, 3, '../images/QR/table_3.png'),
(62, 4, '../images/QR/table_4.png'),
(63, 5, '../images/QR/table_5.png'),
(64, 6, '../images/QR/table_6.png'),
(65, 7, '../images/QR/table_7.png'),
(66, 8, '../images/QR/table_8.png'),
(67, 9, '../images/QR/table_9.png'),
(68, 10, '../images/QR/table_10.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Admin_ID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`Cart_ID`),
  ADD KEY `Dish_ID` (`Dish_ID`),
  ADD KEY `Table_ID` (`Table_ID`);

--
-- Indexes for table `food_category`
--
ALTER TABLE `food_category`
  ADD PRIMARY KEY (`Category_ID`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `kitchen`
--
ALTER TABLE `kitchen`
  ADD PRIMARY KEY (`Kitchen_ID`),
  ADD KEY `Order_ID` (`Order_ID`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`Dish_ID`),
  ADD KEY `fk_dish_category` (`dish_category`);

--
-- Indexes for table `orderlist`
--
ALTER TABLE `orderlist`
  ADD PRIMARY KEY (`Order_ID`),
  ADD KEY `Table_ID` (`Table_ID`),
  ADD KEY `Cart_ID` (`Cart_ID`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`Report_ID`),
  ADD KEY `Order_ID` (`Order_ID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`Staff_ID`);

--
-- Indexes for table `tablelist`
--
ALTER TABLE `tablelist`
  ADD PRIMARY KEY (`Table_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `Admin_ID` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `Cart_ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `food_category`
--
ALTER TABLE `food_category`
  MODIFY `Category_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `Dish_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `orderlist`
--
ALTER TABLE `orderlist`
  MODIFY `Order_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `Report_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `Staff_ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tablelist`
--
ALTER TABLE `tablelist`
  MODIFY `Table_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`Dish_ID`) REFERENCES `menu` (`Dish_ID`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`Table_ID`) REFERENCES `tablelist` (`Table_ID`);

--
-- Constraints for table `kitchen`
--
ALTER TABLE `kitchen`
  ADD CONSTRAINT `kitchen_ibfk_1` FOREIGN KEY (`Order_ID`) REFERENCES `orderlist` (`Order_ID`);

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fk_dish_category` FOREIGN KEY (`dish_category`) REFERENCES `food_category` (`category_name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orderlist`
--
ALTER TABLE `orderlist`
  ADD CONSTRAINT `orderlist_ibfk_1` FOREIGN KEY (`Table_ID`) REFERENCES `tablelist` (`Table_ID`),
  ADD CONSTRAINT `orderlist_ibfk_2` FOREIGN KEY (`Cart_ID`) REFERENCES `cart` (`Cart_ID`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`Order_ID`) REFERENCES `orderlist` (`Order_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
