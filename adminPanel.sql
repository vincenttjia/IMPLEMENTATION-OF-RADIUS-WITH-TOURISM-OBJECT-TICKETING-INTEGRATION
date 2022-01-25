-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2021 at 04:02 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `radius`
--

-- --------------------------------------------------------


--
-- Dumping data for table `radgroupcheck`
--

INSERT INTO `radgroupcheck` (`id`, `groupname`, `attribute`, `op`, `value`) VALUES
(1, 'guest', 'Framed-Protocol', '==', 'PPP');

-- --------------------------------------------------------

--
-- Dumping data for table `radgroupreply`
--

INSERT INTO `radgroupreply` (`id`, `groupname`, `attribute`, `op`, `value`) VALUES
(1, 'guest', 'Mikrotik-Rate-Limit', ':=', '2M/2M 20M/20M 100M/100M 60/60'),
(2, 'guest', 'Framed-Pool', ':=', 'guest_pool');

INSERT INTO `radusergroup` (`username`, `groupname`, `priority`) VALUES
('guest_profile', 'guest', 10);

-- --------------------------------------------------------

--
-- Table structure for table `registry_admin`
--

CREATE TABLE `registry_admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `needReset` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `registry_admin`
--

INSERT INTO `registry_admin` (`id`, `username`, `password`, `role`, `needReset`) VALUES
(1, 'root', '$2y$10$TLnkMCP7XOV4HfmB1.chNuwkdwLvFvoHQgucyfoJD8mtFaVRIDHsC', 'SuperAdmin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `registry_data`
--

CREATE TABLE `registry_data` (
  `id` int(11) NOT NULL,
  `phoneno` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `totalprice` int(11) NOT NULL,
  `jumlahticket` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `registry_price`
--

CREATE TABLE `registry_price` (
  `id` int(11) NOT NULL,
  `ticketprice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `registry_price`
--

INSERT INTO `registry_price` (`id`, `ticketprice`) VALUES
(1, 10000);

--
-- Indexes for dumped tables
--

--
ALTER TABLE `registry_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registry_data`
--
ALTER TABLE `registry_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registry_price`
--
ALTER TABLE `registry_price`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `registry_admin`
--
ALTER TABLE `registry_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `registry_data`
--
ALTER TABLE `registry_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `registry_price`
--
ALTER TABLE `registry_price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
