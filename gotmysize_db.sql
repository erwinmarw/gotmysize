-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 20, 2024 at 05:32 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gotmysize_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `size` varchar(20) DEFAULT NULL,
  `quantity` int DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `product_id`, `size`, `quantity`, `created_at`) VALUES
(9, 1, 20, 'US 10', 1, '2024-12-20 01:45:03');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(13, 'Lifestyle', '2024-12-19 01:46:01'),
(14, 'Running', '2024-12-19 01:46:01'),
(15, 'Basketball', '2024-12-19 01:46:01'),
(16, 'Training', '2024-12-19 01:46:01'),
(17, 'Soccer', '2024-12-19 01:46:01'),
(18, 'Skateboarding', '2024-12-19 01:46:01');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `product_id`, `created_at`) VALUES
(10, 1, 13, '2024-12-20 01:44:29'),
(11, 3, 3, '2024-12-20 01:56:19');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status_id` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status_id`, `created_at`) VALUES
(1, 2, 1799000.00, 1, '2024-12-20 01:23:32'),
(2, 2, 6796000.00, 1, '2024-12-20 01:30:10'),
(3, 1, 3798000.00, 1, '2024-12-20 01:44:50'),
(4, 3, 7497000.00, 1, '2024-12-20 01:56:43');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `size` varchar(10) NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `size`, `quantity`, `price`) VALUES
(1, 1, 1, 'US 9.5', 1, 1799000.00),
(2, 2, 7, 'US 10.5', 1, 1699000.00),
(3, 2, 7, 'US 7.5', 3, 1699000.00),
(4, 3, 13, 'US 10', 2, 1899000.00),
(5, 4, 3, 'US 10', 3, 2499000.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `id` int NOT NULL,
  `status_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`id`, `status_name`) VALUES
(1, 'Pending'),
(2, 'Processing'),
(3, 'Shipped'),
(4, 'Completed'),
(5, 'Cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `total_rating` decimal(3,2) DEFAULT '0.00',
  `rating_count` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_url`, `category_id`, `total_rating`, `rating_count`, `created_at`) VALUES
(1, 'Nike Air Force 1 07', 'The radiance lives on in the Nike Air Force 1 07, the b-ball OG that puts a fresh spin on what you know best: durably stitched overlays, clean finishes and the perfect amount of flash to make you shine.', 1799000.00, 'assets/images/products/nike-af1.jpg', 13, 4.50, 128, '2024-12-19 01:46:01'),
(2, 'Adidas Ultraboost Light', 'Experience epic energy with the new Ultraboost Light, our lightest Ultraboost ever. The magic lies in the Light BOOST midsole, a new generation of adidas BOOST.', 3300000.00, 'assets/images/products/adidas-ultraboost.jpg', 14, 4.80, 95, '2024-12-19 01:46:01'),
(3, 'Nike Zoom Lebron NXXT Gen', 'LeBron thrives when stakes are high and the pressures on. The LeBron NXXT Gen is built to help every athlete feel fast, secure and responsive.', 2499000.00, 'assets/images/products/nike-lebron.jpg', 15, 4.60, 75, '2024-12-19 01:46:01'),
(4, 'New Balance 550', 'The 550 is a throwback to the basketball shoes of the 1980s. Simple but technical, retro but contemporary.', 1999000.00, 'assets/images/products/nb-550.jpg', 13, 4.40, 88, '2024-12-19 01:46:01'),
(5, 'Jordan 1 Retro High OG', 'The Air Jordan 1 High is the shoe that started it all. Made famous by Michael Jordan, its timeless design and premium materials set the standard.', 2899000.00, 'assets/images/products/aj1.jpg', 13, 4.90, 156, '2024-12-19 01:46:01'),
(6, 'Nike Kobe 6 Protro', 'The Kobe 6 Protro updates the original with new technology while maintaining the same look and feel of the iconic shoe.', 2699000.00, 'assets/images/products/kobe-6.jpg', 15, 4.70, 92, '2024-12-19 01:46:01'),
(7, 'Adidas Samba OG', 'A timeless classic that has transcended its origins as an indoor soccer shoe to become a lifestyle icon.', 1699000.00, 'assets/images/products/adidas-samba.jpg', 13, 4.60, 112, '2024-12-19 01:46:01'),
(8, 'Nike Air Max 270', 'Nikes first lifestyle Air unit showcases the brands greatest innovation with its large window and 270 degrees of visibility.', 2199000.00, 'assets/images/products/airmax-270.jpg', 13, 4.50, 143, '2024-12-19 01:46:01'),
(9, 'Puma RS-X', 'The RS-X celebrates extreme reinvention with its bulky design, bold color combinations, and super-comfy cushioning.', 1599000.00, 'assets/images/products/puma-rsx.jpg', 13, 4.30, 67, '2024-12-19 01:46:01'),
(10, 'Nike Mercurial Vapor 15', 'Built for speed and precision, the Mercurial Vapor features innovative studs and lightweight materials.', 2799000.00, 'assets/images/products/mercurial.jpg', 17, 4.70, 84, '2024-12-19 01:46:01'),
(11, 'Vans Old Skool', 'The classic side stripe skate shoe that has become a fashion staple worldwide.', 999000.00, 'assets/images/products/vans-oldskool.jpg', 18, 4.60, 198, '2024-12-19 01:46:01'),
(12, 'Nike SB Dunk Low', 'Originally a hoops shoe, the Dunk was organically adopted by skate culture and has since become an icon.', 1699000.00, 'assets/images/products/sb-dunk.jpg', 18, 4.80, 145, '2024-12-19 01:46:01'),
(13, 'Adidas Dame 8', 'Damian Lillards signature shoe featuring Bounce Pro cushioning for elite performance.', 1899000.00, 'assets/images/products/dame-8.jpg', 15, 4.50, 76, '2024-12-19 01:46:01'),
(14, 'Under Armour Curry 10', 'Stephen Currys latest signature shoe with UA Flow technology for unmatched court feel.', 2499000.00, 'assets/images/products/curry-10.jpg', 15, 4.70, 89, '2024-12-19 01:46:01'),
(15, 'Nike ZoomX Vaporfly', 'The racing shoe that started the carbon plate revolution, designed for marathon performance.', 3499000.00, 'assets/images/products/vaporfly.jpg', 14, 4.90, 167, '2024-12-19 01:46:01'),
(16, 'Hoka Bondi 8', 'Maximum cushioned running shoe perfect for long distances and recovery runs.', 2499000.00, 'assets/images/products/hoka-bondi.jpg', 14, 4.60, 92, '2024-12-19 01:46:01'),
(17, 'Nike Metcon 8', 'The ultimate training shoe designed for weightlifting and high-intensity workouts.', 1999000.00, 'assets/images/products/metcon-8.jpg', 16, 4.70, 134, '2024-12-19 01:46:01'),
(18, 'Adidas Predator Edge', 'Revolutionary soccer boot with enhanced grip zones for precise ball control.', 3299000.00, 'assets/images/products/predator.jpg', 17, 4.50, 78, '2024-12-19 01:46:01'),
(19, 'New Balance Fresh Foam X', 'Plush cushioning meets responsive performance in this versatile running shoe.', 1899000.00, 'assets/images/products/fresh-foam.jpg', 14, 4.40, 86, '2024-12-19 01:46:01'),
(20, 'Jordan Tatum 1', 'Jayson Tatums first signature shoe, designed for explosive play and quick cuts.', 2299000.00, 'assets/images/products/tatum-1.jpg', 15, 4.60, 45, '2024-12-19 01:46:01');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `rating` int NOT NULL,
  `comment` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

-- --------------------------------------------------------

--
-- Table structure for table `product_sizes`
--

CREATE TABLE `product_sizes` (
  `id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `size` varchar(20) DEFAULT NULL,
  `stock` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_sizes`
--

INSERT INTO `product_sizes` (`id`, `product_id`, `size`, `stock`) VALUES
(1, 1, 'US 7', 16),
(2, 1, 'US 7.5', 10),
(3, 1, 'US 8', 12),
(4, 1, 'US 8.5', 18),
(5, 1, 'US 9', 20),
(6, 1, 'US 9.5', 12),
(7, 1, 'US 10', 7),
(8, 1, 'US 10.5', 7),
(9, 1, 'US 11', 12),
(10, 2, 'US 7', 15),
(11, 2, 'US 7.5', 11),
(12, 2, 'US 8', 12),
(13, 2, 'US 8.5', 18),
(14, 2, 'US 9', 20),
(15, 2, 'US 9.5', 14),
(16, 2, 'US 10', 6),
(17, 2, 'US 10.5', 7),
(18, 2, 'US 11', 12),
(19, 3, 'US 7', 16),
(20, 3, 'US 7.5', 11),
(21, 3, 'US 8', 12),
(22, 3, 'US 8.5', 18),
(23, 3, 'US 9', 20),
(24, 3, 'US 9.5', 14),
(25, 3, 'US 10', 3),
(26, 3, 'US 10.5', 7),
(27, 3, 'US 11', 12),
(28, 4, 'US 7', 16),
(29, 4, 'US 7.5', 11),
(30, 4, 'US 8', 12),
(31, 4, 'US 8.5', 18),
(32, 4, 'US 9', 20),
(33, 4, 'US 9.5', 14),
(34, 4, 'US 10', 7),
(35, 4, 'US 10.5', 7),
(36, 4, 'US 11', 12),
(37, 5, 'US 7', 16),
(38, 5, 'US 7.5', 11),
(39, 5, 'US 8', 12),
(40, 5, 'US 8.5', 18),
(41, 5, 'US 9', 20),
(42, 5, 'US 9.5', 14),
(43, 5, 'US 10', 7),
(44, 5, 'US 10.5', 7),
(45, 5, 'US 11', 12),
(46, 6, 'US 7', 15),
(47, 6, 'US 7.5', 11),
(48, 6, 'US 8', 12),
(49, 6, 'US 8.5', 18),
(50, 6, 'US 9', 20),
(51, 6, 'US 9.5', 14),
(52, 6, 'US 10', 7),
(53, 6, 'US 10.5', 7),
(54, 6, 'US 11', 12),
(55, 7, 'US 7', 16),
(56, 7, 'US 7.5', 7),
(57, 7, 'US 8', 12),
(58, 7, 'US 8.5', 18),
(59, 7, 'US 9', 20),
(60, 7, 'US 9.5', 14),
(61, 7, 'US 10', 7),
(62, 7, 'US 10.5', 5),
(63, 7, 'US 11', 12),
(64, 8, 'US 7', 16),
(65, 8, 'US 7.5', 11),
(66, 8, 'US 8', 12),
(67, 8, 'US 8.5', 18),
(68, 8, 'US 9', 20),
(69, 8, 'US 9.5', 14),
(70, 8, 'US 10', 7),
(71, 8, 'US 10.5', 7),
(72, 8, 'US 11', 12),
(73, 9, 'US 7', 16),
(74, 9, 'US 7.5', 11),
(75, 9, 'US 8', 12),
(76, 9, 'US 8.5', 18),
(77, 9, 'US 9', 20),
(78, 9, 'US 9.5', 14),
(79, 9, 'US 10', 7),
(80, 9, 'US 10.5', 7),
(81, 9, 'US 11', 12),
(82, 10, 'US 7', 16),
(83, 10, 'US 7.5', 11),
(84, 10, 'US 8', 12),
(85, 10, 'US 8.5', 18),
(86, 10, 'US 9', 20),
(87, 10, 'US 9.5', 14),
(88, 10, 'US 10', 7),
(89, 10, 'US 10.5', 7),
(90, 10, 'US 11', 12),
(91, 11, 'US 7', 16),
(92, 11, 'US 7.5', 11),
(93, 11, 'US 8', 12),
(94, 11, 'US 8.5', 18),
(95, 11, 'US 9', 20),
(96, 11, 'US 9.5', 14),
(97, 11, 'US 10', 7),
(98, 11, 'US 10.5', 7),
(99, 11, 'US 11', 12),
(100, 12, 'US 7', 16),
(101, 12, 'US 7.5', 11),
(102, 12, 'US 8', 12),
(103, 12, 'US 8.5', 18),
(104, 12, 'US 9', 20),
(105, 12, 'US 9.5', 14),
(106, 12, 'US 10', 7),
(107, 12, 'US 10.5', 7),
(108, 12, 'US 11', 12),
(109, 13, 'US 7', 16),
(110, 13, 'US 7.5', 11),
(111, 13, 'US 8', 12),
(112, 13, 'US 8.5', 18),
(113, 13, 'US 9', 20),
(114, 13, 'US 9.5', 14),
(115, 13, 'US 10', 4),
(116, 13, 'US 10.5', 7),
(117, 13, 'US 11', 12),
(118, 14, 'US 7', 16),
(119, 14, 'US 7.5', 11),
(120, 14, 'US 8', 12),
(121, 14, 'US 8.5', 18),
(122, 14, 'US 9', 20),
(123, 14, 'US 9.5', 14),
(124, 14, 'US 10', 7),
(125, 14, 'US 10.5', 7),
(126, 14, 'US 11', 12),
(127, 15, 'US 7', 16),
(128, 15, 'US 7.5', 11),
(129, 15, 'US 8', 12),
(130, 15, 'US 8.5', 18),
(131, 15, 'US 9', 20),
(132, 15, 'US 9.5', 14),
(133, 15, 'US 10', 7),
(134, 15, 'US 10.5', 7),
(135, 15, 'US 11', 12),
(136, 16, 'US 7', 16),
(137, 16, 'US 7.5', 11),
(138, 16, 'US 8', 12),
(139, 16, 'US 8.5', 18),
(140, 16, 'US 9', 20),
(141, 16, 'US 9.5', 14),
(142, 16, 'US 10', 7),
(143, 16, 'US 10.5', 7),
(144, 16, 'US 11', 12),
(145, 17, 'US 7', 16),
(146, 17, 'US 7.5', 11),
(147, 17, 'US 8', 12),
(148, 17, 'US 8.5', 18),
(149, 17, 'US 9', 20),
(150, 17, 'US 9.5', 14),
(151, 17, 'US 10', 7),
(152, 17, 'US 10.5', 7),
(153, 17, 'US 11', 12),
(154, 18, 'US 7', 16),
(155, 18, 'US 7.5', 11),
(156, 18, 'US 8', 12),
(157, 18, 'US 8.5', 18),
(158, 18, 'US 9', 20),
(159, 18, 'US 9.5', 14),
(160, 18, 'US 10', 7),
(161, 18, 'US 10.5', 7),
(162, 18, 'US 11', 12),
(163, 19, 'US 7', 16),
(164, 19, 'US 7.5', 11),
(165, 19, 'US 8', 12),
(166, 19, 'US 8.5', 18),
(167, 19, 'US 9', 20),
(168, 19, 'US 9.5', 14),
(169, 19, 'US 10', 7),
(170, 19, 'US 10.5', 7),
(171, 19, 'US 11', 12),
(172, 20, 'US 7', 16),
(173, 20, 'US 7.5', 11),
(174, 20, 'US 8', 12),
(175, 20, 'US 8.5', 18),
(176, 20, 'US 9', 20),
(177, 20, 'US 9.5', 14),
(178, 20, 'US 10', 6),
(179, 20, 'US 10.5', 7),
(180, 20, 'US 11', 12);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'geffa', 'geffawork@gmail.com', '$2y$10$36YFWy9v7pZAratOfRqIEu03UdKxqYjPSFOMINr0/0WUCgXWT80RW', '2024-12-19 01:47:54'),
(2, 'aldi', 'aldi@gmail.com', '$2y$10$1ll0Pe4PsqNgZvRhfrPEpebrJrbn.A874wP0KLkCnnt41l7tCxGCm', '2024-12-19 06:33:07'),
(3, 'agus', 'agusbuntung@gmail.com', '$2y$10$5dJu.Zvw8CkQ6.0k0imoueNM8M367IsGilT2zT7lii.5jFJCQU4K2', '2024-12-20 01:55:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_favorite` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_status`
--
ALTER TABLE `order_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_sizes`
--
ALTER TABLE `product_sizes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `order_status` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD CONSTRAINT `product_sizes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
