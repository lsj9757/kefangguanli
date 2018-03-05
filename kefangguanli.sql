-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2017-12-18 13:48:29
-- 服务器版本： 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kefangguanli`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE `admin` (
  `admin_name` varchar(12) NOT NULL,
  `admin_password` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`admin_name`, `admin_password`) VALUES
('lsj9757', 'a27606354');

-- --------------------------------------------------------

--
-- 表的结构 `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `customer_number` char(11) NOT NULL,
  `customer_password` varchar(32) NOT NULL,
  `customer_name` varchar(32) NOT NULL,
  `customer_sex` enum('1','0') NOT NULL,
  `customer_state` tinyint(1) NOT NULL,
  `request_type` tinyint(1) NOT NULL,
  `request_message` varchar(256) NOT NULL,
  `customer_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--

-- --------------------------------------------------------

--
-- 表的结构 `log`
--

CREATE TABLE `log` (
  `log_id` int(11) NOT NULL,
  `log_time` datetime DEFAULT NULL,
  `log_type` tinyint(1) NOT NULL,
  `log_admin` varchar(12) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_number` char(11) NOT NULL,
  `customer_name` varchar(32) NOT NULL,
  `room_id` int(11) NOT NULL,
  `room_tablet` varchar(12) NOT NULL,
  `room_classification` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `room`
--

CREATE TABLE `room` (
  `room_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `room_tablet` varchar(12) NOT NULL,
  `room_state` tinyint(1) NOT NULL,
  `room_classification` varchar(30) NOT NULL,
  `admin_name` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_name`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `admin_name` (`admin_name`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `customer`
--
ALTER TABLE `customer`

  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1,
  ADD CONSTRAINT `C_room_customer_number` CHECK (cast(customer_number as SIGNED INTEGER)>0);
--
-- 使用表AUTO_INCREMENT `log`
--
ALTER TABLE `log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1,
  ADD CONSTRAINT `C_room_room_tablet` CHECK (cast(room_tablet as SIGNED INTEGER)>0);

--
-- 限制表 `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `C_log_room_tablet` CHECK (cast(room_tablet as SIGNED INTEGER)>0),
  ADD CONSTRAINT `C_log_customer_number` CHECK (cast(customer_number as SIGNED INTEGER)>0);



ALTER TABLE `room`
  ADD CONSTRAINT `F_room_customer_id` foreign KEY (`customer_id`) REFERENCES `customer`(`customer_id`),
  ADD CONSTRAINT `F_room_admin_name` foreign KEY (`admin_name`) REFERENCES `admin`(`admin_name`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
