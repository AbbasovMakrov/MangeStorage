-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 17, 2018 at 07:52 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.0.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Sell&Buy`
--
CREATE DATABASE IF NOT EXISTS `Sell&Buy` DEFAULT CHARACTER SET utf32 COLLATE utf32_unicode_ci;
USE `Sell&Buy`;

-- --------------------------------------------------------

--
-- Table structure for table `sell_products`
--

CREATE TABLE `sell_products` (
  `id` int(8) NOT NULL,
  `product` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `image` text COLLATE utf32_unicode_ci NOT NULL,
  `rate` varchar(15) COLLATE utf32_unicode_ci NOT NULL,
  `order_product` int(3) NOT NULL,
  `added_time` date NOT NULL,
  `added_by` varchar(150) COLLATE utf32_unicode_ci NOT NULL,
  `dep` int(1) NOT NULL,
  `notes` text COLLATE utf32_unicode_ci NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

--
-- Dumping data for table `sell_products`
--

INSERT INTO `sell_products` (`id`, `product`, `image`, `rate`, `order_product`, `added_time`, `added_by`, `dep`, `notes`, `status`) VALUES
(13, 'aasasass', 'images/image-80368579.jpeg', 'V.G', 15, '2018-12-14', 'test', 4, 'FFF', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(2) NOT NULL,
  `username` varchar(66) COLLATE utf32_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf32_unicode_ci NOT NULL,
  `role` int(1) NOT NULL,
  `dep` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `dep`) VALUES
(4, 'abbas', '$2y$11$23TzUzrHX9wmLgVc45xrJewatFAOSXCH1On3SAZQJ/oKy0uFFXXg6', 1, NULL),
(5, 'test', '$2y$11$nBPqWK4LqcvoJ9jO5k49rehd8JeL49wJHEcUDNibduBF9MTzkAdfe', 0, 4),
(6, 'user', '$2y$11$BfAMfi96FfbCK0C660rwYOyPj4VaEr0ERi7TbdjJ9x3Px2eT9N1Em', 2, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sell_products`
--
ALTER TABLE `sell_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sell_products`
--
ALTER TABLE `sell_products`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
