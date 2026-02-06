-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2024 at 06:18 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `imageextractor`
--

-- --------------------------------------------------------

--
-- Table structure for table `userfile`
--

CREATE TABLE `userfile` (
  `id` int(11) NOT NULL,
  `sitelogo` mediumtext,
  `sitetitle` mediumtext,
  `description` mediumtext,
  `footer` mediumtext,
  `ads1` mediumtext,
  `ads2` mediumtext,
  `username` mediumtext,
  `password` mediumtext,
  `email` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userfile`
--

INSERT INTO `userfile` (`id`, `sitelogo`, `sitetitle`, `description`, `footer`, `ads1`, `ads2`, `username`, `password`, `email`) VALUES
(1, 'aia.png', 'extractor text dari gambar', 'mengambil text dari sebuah gambar', 'Â© 2024 | <a href="https://sissmart.com/" target="_blank">Sekretariat DPRD Kota Magelang</a>', '<script>document.write("DPRD Kota Magelang")</script>', '<script>document.write("DPRD Kota Magelang")</script>', 'admin', 'admin', 'sistarteknologi@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `userfile`
--
ALTER TABLE `userfile`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `userfile`
--
ALTER TABLE `userfile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
