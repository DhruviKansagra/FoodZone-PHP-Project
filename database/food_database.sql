-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2026 at 01:54 PM
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
-- Database: `food`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `f_id` int(11) NOT NULL,
  `food_name` varchar(100) NOT NULL,
  `food_price` decimal(10,2) NOT NULL,
  `quality` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `food_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `c_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`c_id`, `name`, `created_at`) VALUES
(2, 'Pizza', '2026-07-16 13:53:06'),
(3, 'Burger', '2026-07-17 05:28:39'),
(4, 'Pasta', '2026-07-17 05:33:34'),
(5, 'Fries', '2026-07-17 05:33:50');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `name`, `email`, `message`) VALUES
(6, 'Dhruvi Kansagra', 'dhruvikansagra204@gmail.com', 'good food'),
(10, 'diya', 'diya@gmail.com', 'yummy and good food ');

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `f_id` int(11) NOT NULL,
  `c_id` int(11) NOT NULL,
  `food_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`f_id`, `c_id`, `food_name`, `description`, `price`, `image`) VALUES
(3, 2, 'Veg Cheese Pizza', 'Veg Cheese Pizza – Tomato sauce, mozzarella cheese, onion, capsicum, sweet corn, and fresh vegetable toppings.\r\n', 150.00, '1784453088_v1.jpg'),
(6, 2, 'Paneer Tika Pizza', 'Paneer Tikka Pizza – Tomato sauce, spicy paneer tikka, mozzarella cheese, onion, capsicum, and aromatic Indian spices.\r\n', 270.00, '1784453334_p1.jpg'),
(7, 2, '7 Cheese Pizza', '7 Cheese Pizza – A rich blend of seven delicious cheeses, tomato sauce, extra mozzarella, and Italian herbs on a crispy golden crust.\r\n', 350.00, '1784453436_c2.jpg'),
(8, 3, 'Cheeseburger', 'Classic burger topped with cheese (cheddar, American, Swiss, etc.).', 150.00, '1784461376_b1.jpg'),
(9, 3, 'Veggie Burger', 'Veggie Burger – Made from vegetables, beans, or plant-based patties.', 99.00, '1784461749_b2.jpg'),
(10, 3, 'Spicy Burger', 'Spicy Burger – Includes jalapeños, spicy sauce, or pepper jack cheese.', 150.00, '1784461854_b3.jpg'),
(11, 3, 'Paneer Burger', 'Paneer Burger – Grilled or crispy paneer patty with vegetables.', 200.00, '1784462344_b4.jpg'),
(12, 4, 'Cheese Sauce Pasta', 'Made with melted cheeses like cheddar, mozzarella, or Parmesan\r\nFlavor: Extra cheesy and creamy', 120.00, '1784462732_pa.jpg'),
(13, 4, 'Red Sauce Pasta', 'Made with tomato sauce\r\nFlavor: Tangy and slightly sweet\r\nCommon seasonings: Garlic, basil, oregano, chili flakes', 100.00, 'pa1.jpg'),
(14, 4, 'White Sauce Pasta', 'Made with butter, flour, milk, and cheese\r\nFlavor: Rich, creamy, and mild', 125.00, '1784463018_pa2.jpg'),
(15, 4, 'Pink Sauce (Rosé) Pasta', 'Combination of red and white sauces\r\nFlavor: Creamy with a tangy tomato taste', 150.00, '1784463138_pa3.jpg'),
(18, 5, 'Cheese Fries', 'Cheese Fries – Topped with melted cheese.', 150.00, '1784463581_fr1.jpg'),
(19, 5, 'Loaded Fries', 'Loaded Fries – Topped with cheese, sauces, jalapeños, onions, and more.', 170.00, '1784463678_fr2.jpg'),
(20, 5, 'Peri-Peri Fries', 'Peri-Peri Fries – Tossed with spicy peri-peri seasoning.', 90.00, '1784463850_fr3.jpg'),
(21, 2, 'Margherita Pizza', 'Margherita Pizza – Tomato sauce, fresh mozzarella, basil, and olive oil.', 200.00, '1793364618_p2.jpg'),
(22, 5, 'French Fries', 'French Fries – Thin, crispy potato fries.', 70.00, '1793364744_f.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `f_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `food_price` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `food_name` varchar(255) NOT NULL,
  `food_image` varchar(255) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `f_id`, `quantity`, `food_price`, `payment_method`, `food_name`, `food_image`, `order_date`) VALUES
(6, 2, 7, 3, 350.00, 'Cash On Delivery', '7 Cheese Pizza', '1784453436_c2.jpg', '2026-07-20 12:01:52'),
(7, 2, 19, 3, 170.00, 'Cash On Delivery', 'Loaded Fries', '1784463678_fr2.jpg', '2026-07-20 12:01:52'),
(8, 2, 20, 3, 90.00, 'Cash On Delivery', 'Peri-Peri Fries', '1784463850_fr3.jpg', '2026-07-20 12:01:52'),
(9, 2, 10, 1, 150.00, 'Cash On Delivery', 'Spicy Burger', '1784461854_b3.jpg', '2026-07-20 12:01:52'),
(12, 2, 10, 1, 150.00, 'UPI', 'Spicy Burger', '1784461854_b3.jpg', '2026-10-12 11:25:24'),
(13, 2, 19, 1, 170.00, 'Cash On Delivery', 'Loaded Fries', '1784463678_fr2.jpg', '2026-10-16 08:55:46'),
(14, 2, 13, 1, 100.00, 'Cash On Delivery', 'Red Sauce Pasta', 'pa1.jpg', '2026-10-17 11:55:46'),
(15, 2, 19, 1, 170.00, 'Cash On Delivery', 'Loaded Fries', '1784463678_fr2.jpg', '2026-10-17 11:57:23'),
(16, 2, 18, 1, 150.00, 'Cash On Delivery', 'Cheese Fries', '1784463581_fr1.jpg', '2026-10-17 11:57:57');

-- --------------------------------------------------------

--
-- Table structure for table `shipping`
--

CREATE TABLE `shipping` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping`
--

INSERT INTO `shipping` (`id`, `user_id`, `order_id`, `name`, `email`, `address`, `phone`, `created_at`) VALUES
(7, 2, 14, 'Diya', 'Diya@gmail.com', 'Morbi-2', '1234567890', '2026-10-17 11:55:46'),
(8, 2, 15, 'dhruvi', 'dhruvi@gmail.com', 'Morbi', '9510091184', '2026-10-17 11:57:23'),
(9, 2, 16, 'Char', 'charu@gmail.com', 'Morbi', '0987654321', '2026-10-17 11:57:57');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email_id` varchar(30) NOT NULL,
  `mobile_no` varchar(12) NOT NULL,
  `city` varchar(50) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email_id`, `mobile_no`, `city`, `pincode`, `address`) VALUES
(1, 'dhurvi', '$2y$10$T0nNdzjFCsZLVD236ZlKNuDb/n3X5f74mRhVz4cxHxdbbjCW9Dwdq', 'dhruvikansagra204@gmail.com', '9510091184', 'morbi', '363642', 'Mahendranagar , morbi-2'),
(2, 'dhruvi', '$2y$10$dMkE6381RVcs5jDL2urA3eA4OxCdS4W6ath02ffta75qd7fEU.Ls6', 'dhruvikansagra204@gmail.com', '9510091184', 'morbi', '363642', 'morbi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `fk_cart_user` (`user_id`),
  ADD KEY `fk_cart_food` (`f_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`f_id`),
  ADD KEY `c_id` (`c_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `f_id` (`f_id`);

--
-- Indexes for table `shipping`
--
ALTER TABLE `shipping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `food`
--
ALTER TABLE `food`
  MODIFY `f_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `shipping`
--
ALTER TABLE `shipping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_food` FOREIGN KEY (`f_id`) REFERENCES `food` (`f_id`),
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `food`
--
ALTER TABLE `food`
  ADD CONSTRAINT `food_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `categories` (`c_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`f_id`) REFERENCES `food` (`f_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
