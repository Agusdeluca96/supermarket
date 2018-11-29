-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 29, 2018 at 02:07 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stock`
--

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `product_type_id` int(11) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `img_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cost_price` int(11) NOT NULL,
  `sale_price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `product_type_id`, `name`, `quantity`, `img_url`, `cost_price`, `sale_price`) VALUES
(1, 4, 'MacBook Pro Retina 15\'\' 256GB SSD 16GB RAM', 20, 'assets/images/uploads/macbook_pro_retina_15.jpg', 40000, 75000),
(2, 4, 'Celular Samsung Galaxy Note 8 Midnight Black N950', 15, 'assets/images/uploads/samsung_galaxy_note_8_950.png', 20000, 35000),
(3, 4, 'Sony PlayStation 4 1TB Ultra Slim + Fifa 2018', 15, 'assets/images/uploads/ps4_fifa_2018.png', 11000, 24000),
(4, 2, 'Galletitas Sonrisas 354 Gr', 20, 'assets/images/uploads/sonrisas.png', 30, 60),
(5, 2, 'Galletitas Oreo Clasica 351 Gr', 20, 'assets/images/uploads/oreo.png', 40, 80),
(6, 2, 'Galletitas Chocolinas 250 Gr', 20, 'assets/images/uploads/chocolinas.png', 30, 60),
(7, 2, 'Galletitas Pepitos Original 354 Gr', 20, 'assets/images/uploads/pepitos.png', 35, 70),
(8, 2, 'Galletitas Rumba 336 Gr', 20, 'assets/images/uploads/rumba.png', 35, 70),
(9, 2, 'Galletitas Surtido Bagley 400 Gr', 20, 'assets/images/uploads/surtidas_bagley.png', 30, 60);

-- --------------------------------------------------------

--
-- Table structure for table `product_type`
--

CREATE TABLE `product_type` (
  `id` int(11) NOT NULL,
  `initials` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `product_type`
--

INSERT INTO `product_type` (`id`, `initials`, `description`) VALUES
(1, 'BEBID', 'Bebidas'),
(2, 'GALLE', 'Galletitas'),
(3, 'LIMPI', 'Limpieza'),
(4, 'ELECT', 'Electrodomesticos');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D34A04AD14959723` (`product_type_id`);

--
-- Indexes for table `product_type`
--
ALTER TABLE `product_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `product_type`
--
ALTER TABLE `product_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04AD14959723` FOREIGN KEY (`product_type_id`) REFERENCES `product_type` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
