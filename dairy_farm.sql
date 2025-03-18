-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2025 at 05:06 PM
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
-- Database: `dairy_farm`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `price`, `quantity`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Whole Milk 1L', 45.00, 50, 'Fresh whole milk - 1 liter', '2025-03-18 11:11:04', '2025-03-18 11:11:04'),
(2, 'Skimmed Milk 1L', 40.00, 45, 'Low fat skimmed milk - 1 liter', '2025-03-18 11:11:04', '2025-03-18 11:11:04'),
(3, 'Butter 500g', 180.00, 27, 'Pure dairy butter - 500 grams', '2025-03-18 11:11:04', '2025-03-18 16:02:28'),
(4, 'Cheese 200g', 120.00, 25, 'Processed cheese - 200 grams', '2025-03-18 11:11:04', '2025-03-18 11:11:04'),
(5, 'Yogurt 400g', 60.00, 40, 'Plain yogurt - 400 grams', '2025-03-18 11:11:04', '2025-03-18 11:11:04'),
(6, 'Paneer 200g', 80.00, 35, 'Fresh cottage cheese - 200 grams', '2025-03-18 11:11:04', '2025-03-18 11:11:04'),
(7, 'Ghee 500ml', 350.00, 20, 'Pure cow ghee - 500 ml', '2025-03-18 11:11:04', '2025-03-18 11:11:04'),
(8, 'Buttermilk 1L', 30.00, 59, 'Fresh buttermilk - 1 liter', '2025-03-18 11:11:04', '2025-03-18 16:05:09'),
(9, 'Cream 200ml', 75.00, 15, 'Fresh cream - 200 ml', '2025-03-18 11:11:04', '2025-03-18 11:11:04'),
(10, 'Curd 400g', 50.00, 40, 'Fresh curd - 400 grams', '2025-03-18 11:11:04', '2025-03-18 11:11:04');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `bill_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `date` datetime DEFAULT current_timestamp(),
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(15) DEFAULT NULL,
  `customer_city` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`bill_id`, `product_id`, `quantity`, `total`, `date`, `customer_name`, `customer_phone`, `customer_city`) VALUES
(1, 8, 1, 30.00, '2025-03-18 21:35:09', 'Patel om ashvinbhai', '0905 420 3388', 'Khambhat');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `fullname`, `email`, `role`, `created_at`) VALUES
(1, 'admin', 'admin123', 'Administrator', NULL, 'admin', '2025-03-18 11:11:04'),
(2, 'manager', 'manager123', 'Store Manager', NULL, 'manager', '2025-03-18 11:11:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`bill_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
