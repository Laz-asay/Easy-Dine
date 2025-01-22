-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2025 at 04:19 AM
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

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Admin_ID`, `username`, `password`) VALUES
(2, 'admin', 'admin123');

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
(11, 'Beverages'),
(9, 'Main Dish'),
(10, 'Side Dish');

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
  `dish_category` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`Dish_ID`, `dish_name`, `dish_image`, `dish_price`, `dish_desc`, `dish_category`) VALUES
(19, 'Beef Bulgogi', '67904baee54a5_Beef Bulgogi.jpg', 12.20, 'Beef Bulgogi contains beef, chives, and broth', 'Main Dish'),
(20, 'Chicken Bibimbap', '67904ce571859_Chicken Bibimbap.jpg', 10.20, 'Chicken Bibimbap contains chicken, chives, onions, scallops...', 'Main Dish'),
(21, 'Iced Tea', '67904d152600c_Iced Tea.jpg', 3.50, 'Tea served with ice', 'Beverages'),
(22, 'Tteobokki', '6790569fb4143_Tteobokki.jpg', 11.50, 'Traditional Rice Cakes', 'Side Dish'),
(23, 'Caramel Macchiato', '679056d527729_Caramel Macchiato.jpg', 4.50, 'Ice Blended Caramel Macchiato', 'Beverages'),
(24, 'Strawberry Milkshake', '679057272a175_Strawberry Milkshake.jpg', 5.50, 'Strawberry Milkshake made with strawberry', 'Beverages');

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
  `Table_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderlist`
--

INSERT INTO `orderlist` (`Order_ID`, `order_date`, `total_amount`, `order_status`, `price`, `Table_ID`) VALUES
(11, '2025-01-22 02:38:03', 24.40, 'Pending', 24.40, 105),
(12, '2025-01-22 02:44:20', 10.20, 'Pending', 10.20, 107),
(13, '2025-01-22 02:57:25', 24.40, 'Pending', 24.40, 107),
(14, '2025-01-22 03:07:02', 10.20, 'Pending', 10.20, 107),
(15, '2025-01-22 03:09:19', 3.50, 'Completed', 3.50, 107),
(16, '2025-01-22 03:13:18', 7.00, 'Completed', 7.00, 107),
(17, '2025-01-22 03:19:09', 15.70, 'Completed', 15.70, 107);

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

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`Report_ID`, `order_status`, `profit`, `Order_ID`) VALUES
(37, 'Done', 15.70, 17),
(38, 'Pending', 7.00, 16),
(39, 'Done', 3.50, 15);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `Staff_ID` int(10) NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `staff_role` varchar(255) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`Staff_ID`, `staff_name`, `password`, `staff_role`, `phone_number`) VALUES
(8, 'hazmi', '$2y$10$JlcQhDS7DLZTnwMjqyBgAuINFoDJTjn9ap70YASZe.V6PMypNoz26', 'Masterchef', '011 10665952');

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
(102, 1, '../images/QR/table_1.png'),
(105, 2, '../images/QR/table_4.png'),
(106, 3, '../images/QR/table_5.png'),
(107, 4, '../images/QR/table_6.png'),
(108, 5, '../images/QR/table_7.png'),
(109, 6, '../images/QR/table_8.png'),
(110, 7, '../images/QR/table_9.png'),
(111, 8, '../images/QR/table_10.png'),
(112, 9, '../images/QR/table_11.png'),
(113, 10, '../images/QR/table_12.png'),
(114, 11, '../images/QR/table_13.png'),
(115, 12, '../images/QR/table_14.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Admin_ID`);

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
  ADD KEY `Table_ID` (`Table_ID`);

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
  MODIFY `Admin_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `food_category`
--
ALTER TABLE `food_category`
  MODIFY `Category_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `Dish_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `orderlist`
--
ALTER TABLE `orderlist`
  MODIFY `Order_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `Report_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `Staff_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tablelist`
--
ALTER TABLE `tablelist`
  MODIFY `Table_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- Constraints for dumped tables
--

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
  ADD CONSTRAINT `orderlist_ibfk_1` FOREIGN KEY (`Table_ID`) REFERENCES `tablelist` (`Table_ID`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`Order_ID`) REFERENCES `orderlist` (`Order_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
