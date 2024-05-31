-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: May 06, 2024 at 04:20 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `billing-system-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `analytics`
--

CREATE TABLE `analytics` (
  `id` int NOT NULL,
  `date` date NOT NULL,
  `total_orders` int DEFAULT '0',
  `total_revenue` decimal(10,2) DEFAULT '0.00',
  `daily_revenue` decimal(10,2) DEFAULT '0.00',
  `weekly_revenue` decimal(10,2) DEFAULT '0.00',
  `monthly_revenue` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `analytics`
--

INSERT INTO `analytics` (`id`, `date`, `total_orders`, `total_revenue`, `daily_revenue`, `weekly_revenue`, `monthly_revenue`) VALUES
(24, '2024-05-06', 6, 0.00, 400.00, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categoryId` int NOT NULL,
  `categoryName` varchar(255) NOT NULL,
  `categoryDesc` text NOT NULL,
  `categoryCreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categoryId`, `categoryName`, `categoryDesc`, `categoryCreateDate`) VALUES
(1, 'Milk Tea', 'Deliciously creamy milk tea varieties with a range of flavors.', '2024-04-20 00:31:39'),
(2, 'Frappe', 'A delightful mix of frappes, available in coffee-based and non-coffee-based options.', '2024-04-20 00:31:39'),
(3, 'Cold Brew', 'Smooth and rich cold brew selections for a refreshing caffeine fix.', '2024-04-20 00:31:39'),
(5, 'Cheesecake', 'Sweet and savory cheesecake beverages, a dessert in a cup.', '2024-04-20 00:31:39'),
(6, 'Fruit Tea', 'Refreshing water-based fruit teas, perfect for cooling down.', '2024-04-20 00:31:39'),
(7, 'Hot Brew', 'Traditional hot brews to warm and wake up your senses.', '2024-04-20 00:31:39'),
(8, 'Milk Series', 'Specially crafted milk-based beverages offering a comforting and creamy experience.', '2024-04-20 00:31:39');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `contactId` int NOT NULL,
  `userId` int NOT NULL,
  `email` varchar(35) NOT NULL,
  `phoneNo` bigint NOT NULL,
  `orderId` varchar(50) DEFAULT NULL COMMENT 'If problem is not related to the order then order id = 0',
  `message` text NOT NULL,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contactreply`
--

CREATE TABLE `contactreply` (
  `id` int NOT NULL,
  `contactId` int NOT NULL,
  `userId` int NOT NULL,
  `message` text NOT NULL,
  `datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `id` int NOT NULL,
  `orderId` varchar(50) NOT NULL,
  `prodId` int NOT NULL,
  `size` varchar(255) NOT NULL,
  `itemQuantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orderitems`
--

INSERT INTO `orderitems` (`id`, `orderId`, `prodId`, `size`, `itemQuantity`, `price`) VALUES
(34, 'ORD20240501182112-00001-23b05', 6, 'Large', 2, 55.00),
(35, '1b69d', 6, 'Large', 1, 55.00),
(36, 'a76a7', 6, 'Large', 1, 55.00),
(37, '2c0cf', 6, 'Large', 1, 55.00),
(38, 'e9997', 31, 'Large', 1, 45.00),
(39, 'b2578', 23, 'Large', 3, 45.00),
(40, '2240e', 31, 'Large', 1, 45.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderId` varchar(50) NOT NULL,
  `userId` int NOT NULL,
  `amount` int NOT NULL,
  `paymentMode` enum('0','1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '2' COMMENT '0=Maya, 1=Gcash, 2=Cash',
  `orderStatus` enum('1','2','3','4','5','6') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '1' COMMENT '1=Order Placed, 2=Preparing your Order, 3=Your order is ready for pick-up!, 4=Order Received, 5=Order Denied, 6=Order Cancelled.',
  `orderDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderId`, `userId`, `amount`, `paymentMode`, `orderStatus`, `orderDate`) VALUES
('1b69d', 11, 55, '2', '3', '2024-05-01 19:24:45'),
('2240e', 11, 45, '2', '6', '2024-05-06 12:13:04'),
('2c0cf', 11, 55, '2', '4', '2024-05-01 22:11:51'),
('b2578', 11, 90, '2', '3', '2024-05-06 12:08:28'),
('e9997', 11, 45, '2', '6', '2024-05-06 12:06:19');

-- --------------------------------------------------------

--
-- Table structure for table `prod`
--

CREATE TABLE `prod` (
  `prodId` int NOT NULL,
  `prodName` varchar(255) NOT NULL,
  `prodDesc` text NOT NULL,
  `prodCategoryId` int NOT NULL,
  `prodPubDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `prod`
--

INSERT INTO `prod` (`prodId`, `prodName`, `prodDesc`, `prodCategoryId`, `prodPubDate`) VALUES
(6, 'Java Chip', 'Frappe with mocha and chocolate chip pieces.', 2, '2024-04-20 00:31:39'),
(23, 'Spanish Latte', 'A creamy and sweet latte with a touch of spice.', 7, '2024-04-20 00:31:39'),
(31, 'Caramel Macchiato', 'This unique blend of ingredients gives the caramel macchiato a distinct, sweet flavor profile with a delightful hint of caramel. The vanilla syrup brings a touch of sweetness, while the espresso provides a bold, coffee-forward taste.', 1, '2024-04-23 01:30:34');

-- --------------------------------------------------------

--
-- Table structure for table `prod_sizes`
--

CREATE TABLE `prod_sizes` (
  `prodId` int NOT NULL,
  `size` varchar(255) NOT NULL,
  `price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `prod_sizes`
--

INSERT INTO `prod_sizes` (`prodId`, `size`, `price`) VALUES
(6, 'Large', 55),
(6, 'Medium', 45),
(23, 'Large', 45),
(31, 'Large', 45),
(31, 'Medium', 35);

-- --------------------------------------------------------

--
-- Table structure for table `queue`
--

CREATE TABLE `queue` (
  `queueId` int NOT NULL,
  `orderId` varchar(50) NOT NULL,
  `queueNumber` int NOT NULL,
  `dateAdded` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `queue`
--

INSERT INTO `queue` (`queueId`, `orderId`, `queueNumber`, `dateAdded`) VALUES
(6, '1b69d', 1, '2024-05-06'),
(7, '2c0cf', 2, '2024-05-06'),
(8, 'b2578', 3, '2024-05-06'),
(9, '2240e', 4, '2024-05-06');

-- --------------------------------------------------------

--
-- Table structure for table `sitedetail`
--

CREATE TABLE `sitedetail` (
  `tempId` int NOT NULL,
  `systemName` varchar(21) NOT NULL,
  `email` varchar(35) NOT NULL,
  `contact1` bigint NOT NULL,
  `contact2` bigint DEFAULT NULL COMMENT 'Optional',
  `address` text NOT NULL,
  `dateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sitedetail`
--

INSERT INTO `sitedetail` (`tempId`, `systemName`, `email`, `contact1`, `contact2`, `address`, `dateTime`) VALUES
(1, '1128 Tea & Cafe', 'contact@1128teaandcafe.com', 1234567890, NULL, '123 Tea Street, Beverage City', '2024-04-20 00:31:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int NOT NULL,
  `nickname` varchar(21) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `firstName` varchar(21) NOT NULL,
  `lastName` varchar(21) NOT NULL,
  `email` varchar(35) NOT NULL,
  `phone` bigint NOT NULL,
  `userType` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=customer, 1=admin',
  `password` varchar(255) NOT NULL,
  `joinDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `nickname`, `firstName`, `lastName`, `email`, `phone`, `userType`, `password`, `joinDate`) VALUES
(1, 'adminuser', 'Admin', 'User', 'admin@gmail.com', 9876543210, '1', '2yn.4fvaTgedM', '2024-04-20 00:31:39'),
(10, '', 'Mc Joseph', 'Agbanlog', 'mcagbanlog@gmail.com', 9762623231, '1', '2yLASFbyQ9/mA', '2024-05-01 18:14:06'),
(11, 'user', 'user', 'user', 'user@gmail.com', 9762623231, '0', '2y3Hotju5Vl5E', '2024-05-01 18:19:32');

-- --------------------------------------------------------

--
-- Table structure for table `viewcart`
--

CREATE TABLE `viewcart` (
  `cartItemId` int NOT NULL,
  `userId` int NOT NULL,
  `prodId` int NOT NULL,
  `size` varchar(10) NOT NULL,
  `itemQuantity` int NOT NULL,
  `addedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `analytics`
--
ALTER TABLE `analytics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_date` (`date`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoryId`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contactId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `orderId` (`orderId`);

--
-- Indexes for table `contactreply`
--
ALTER TABLE `contactreply`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contactId` (`contactId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orderId` (`orderId`),
  ADD KEY `fk_orderitems_product` (`prodId`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `prod`
--
ALTER TABLE `prod`
  ADD PRIMARY KEY (`prodId`),
  ADD KEY `fk_category` (`prodCategoryId`);

--
-- Indexes for table `prod_sizes`
--
ALTER TABLE `prod_sizes`
  ADD PRIMARY KEY (`prodId`,`size`);

--
-- Indexes for table `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`queueId`),
  ADD KEY `orderId` (`orderId`);

--
-- Indexes for table `sitedetail`
--
ALTER TABLE `sitedetail`
  ADD PRIMARY KEY (`tempId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `viewcart`
--
ALTER TABLE `viewcart`
  ADD PRIMARY KEY (`cartItemId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `viewcart_ibfk_2` (`prodId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `analytics`
--
ALTER TABLE `analytics`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoryId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `contactId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contactreply`
--
ALTER TABLE `contactreply`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `prod`
--
ALTER TABLE `prod`
  MODIFY `prodId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `queue`
--
ALTER TABLE `queue`
  MODIFY `queueId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sitedetail`
--
ALTER TABLE `sitedetail`
  MODIFY `tempId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `viewcart`
--
ALTER TABLE `viewcart`
  MODIFY `cartItemId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `contact_ibfk_2` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`);

--
-- Constraints for table `queue`
--
ALTER TABLE `queue`
  ADD CONSTRAINT `queue_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
