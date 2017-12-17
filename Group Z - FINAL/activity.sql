-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 15, 2017 at 03:07 PM
-- Server version: 5.6.34-log
-- PHP Version: 7.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `activity`
--

CREATE DATABASE IF NOT EXISTS `activity` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `activity`;

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `assess_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `activity_pic` varchar(255) DEFAULT NULL,
  `descr` text NOT NULL,
  `start_time` varchar(30) NOT NULL,
  `end_time` varchar(30) NOT NULL,
  `location` varchar(255) NOT NULL,
  `over` tinyint(10) DEFAULT NULL,
  `remark` tinyint(10) DEFAULT NULL,
  `price` int(10) DEFAULT NULL,
  `status` int(1) DEFAULT '0',
  `sale_time` varchar(30) DEFAULT NULL,
  `create_time` int(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`id`, `user_id`, `type_id`, `assess_id`, `name`, `title`, `activity_pic`, `descr`, `start_time`, `end_time`, `location`, `over`, `remark`, `price`, `status`, `sale_time`, `create_time`) VALUES
(14, 25, 3, NULL, 'Google Meet and Greet', NULL, 'c35532924209eee6761499a8786622bc.jpg', 'A half day meet and greet session perfect for students thinking of joining Google after graduation!\r\n', '1514959200', '1514977200', 'Room B', 50, 48, 5, 0, '2018-01-03T19:00', NULL),
(16, 26, 2, NULL, 'Michael and Sarah\'s 20th Anniversary', NULL, '71b48d671f331eac5ad72262dd969cfa.png', 'A warm invite to celebrate our twentieth anniversary with good food, good drinks and even better company.\r\n', '1516089600', '1516116600', 'Room D', 35, 33, 0, 0, '2018-01-14T00:00', NULL),
(15, 25, 10, NULL, 'Poker Tournament', NULL, '14170be87caab7542a2550bd38f24716.jpg', 'Join this poker tournament, beat your opponents, achieve first place and win the cash prize!', '1514505600', '1514548800', 'Room E', 30, 28, 100, 0, '2017-12-22T08:00', NULL),
(13, 24, 13, NULL, 'Programming Lecture', NULL, 'e56e2f73759d44adebc41d49930855cf.jpg', 'Please purchase a free ticket for this lecture if you are a programming student in COMP3103P.\r\n', '1513819800', '1513823400', 'Room E', 70, 68, 0, 0, '2017-12-21T09:30', NULL),
(12, 23, 10, NULL, 'Cooking Workshop', NULL, '945a7ac37139240b2aed5c2556654240.jpg', 'Join us in a fun packed day to hone up your culinary skills! Featuring a tutorial on how to make a three course meal.\r\n', '1513656000', '1513670400', 'Room A', 30, 28, 10, 0, '2017-12-18T12:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `assess`
--

CREATE TABLE `assess` (
  `id` int(11) NOT NULL,
  `enter_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `content` varchar(255) NOT NULL,
  `score` int(11) DEFAULT '5',
  `addtime` varchar(60) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assess`
--

INSERT INTO `assess` (`id`, `enter_id`, `user_id`, `activity_id`, `title`, `content`, `score`, `addtime`, `status`) VALUES
(3, 7, 25, 12, '', 'PERFECT!! I learnt so much in those few hours I can\'t believe it! I would definitely attend this again.', 5, '1513289759', 0),
(4, 8, 25, 13, '', 'The presentation was boring and dragged on and on! I didn\'t understand anything. Programming is just too hard for me.', 2, '1513289866', 0),
(5, 11, 23, 15, '', 'It was fun, but I did not win and I am disappointed.', 3, '1513290048', 0),
(6, 12, 23, 14, '', 'I met the CEO and vice president of Google and they gave me job offers on the spot. Amazing event!', 5, '1513290092', 0),
(7, 13, 24, 14, '', 'I went here for the food and drinks to be honest, but even then they were not very tasty.', 1, '1513290153', 0),
(8, 9, 26, 15, '', 'This tournament had very low skilled players, and although I won easily it wasn\'t very fun for me.', 4, '1513290205', 0);

-- --------------------------------------------------------

--
-- Table structure for table `enter`
--

CREATE TABLE `enter` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `create_time` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `user_phone` varchar(255) NOT NULL,
  `activity_name` varchar(255) NOT NULL,
  `activity_address` varchar(255) NOT NULL,
  `bank` varchar(30) NOT NULL,
  `number` int(10) NOT NULL,
  `code` varchar(255) NOT NULL,
  `status` int(1) DEFAULT '0',
  `user_email` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `enter`
--

INSERT INTO `enter` (`id`, `user_id`, `activity_id`, `type_id`, `create_time`, `username`, `user_phone`, `activity_name`, `activity_address`, `bank`, `number`, `code`, `status`, `user_email`) VALUES
(15, 24, 12, 10, '1513289465', 'ivan888', '07159382736', 'Cooking Workshop', 'Room A', '213123123', 1, '5a32f6f99efe1', 0, 'coolivan@hotmail.com'),
(14, 24, 16, 2, '1513289461', 'ivan888', '07159382736', 'Michael and Sarah\'s 20th Anniversary', 'Room D', '1232321323', 1, '5a32f6f53a017', 0, 'coolivan@hotmail.com'),
(13, 24, 14, 3, '1513289454', 'ivan888', '07159382736', 'Google Meet and Greet', 'Room B', '3412312323', 1, '5a32f6ee69209', 0, 'coolivan@hotmail.com'),
(12, 23, 14, 3, '1513289430', 'JRYaoYao', '07342126932', 'Google Meet and Greet', 'Room B', '45788888', 1, '5a32f6d63e6a9', 0, 'junrong@gmail.com'),
(11, 23, 15, 10, '1513289421', 'JRYaoYao', '07342126932', 'Poker Tournament', 'Room E', '12312333344', 1, '5a32f6cd98a9d', 0, 'junrong@gmail.com'),
(7, 25, 12, 10, '1513286947', 'jimlee1205', '07561062602', 'Cooking Workshop', 'Room A', '123456789', 1, '5a32ed2300050', 0, 'jimlee1205@gmail.com'),
(8, 25, 13, 13, '1513286969', 'jimlee1205', '07561062602', 'Programming Lecture', 'Room E', 'n/a', 2, '5a32ed39db4fb', 0, 'jimlee1205@gmail.com'),
(9, 26, 15, 10, '1513289123', 'johnjohn7', '07239302835', 'Poker Tournament', 'Room E', '1234213312', 1, '5a32f5a3e6ceb', 0, 'johnjohn7@gmail.com'),
(10, 25, 16, 2, '1513289351', 'jimlee1205', '07561062602', 'Michael and Sarah\'s 20th Anniversary', 'Room D', '123918234', 1, '5a32f6878e4d2', 0, 'jimlee1205@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `sendemail`
--

CREATE TABLE `sendemail` (
  `id` int(11) NOT NULL,
  `enter_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `addtime` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE `type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `display` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`id`, `name`, `pid`, `path`, `display`) VALUES
(3, 'Business', 0, '0,', 0),
(2, 'Social', 0, '0,', 0),
(1, 'Educational', 0, '0,', 0),
(10, 'Entertainment', 0, '0,', 0),
(15, 'Lab Session', 1, '0,1,', 0),
(13, 'Lecture', 1, '0,1,', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `realname` varchar(30) NOT NULL,
  `password` char(32) NOT NULL,
  `sex` int(1) DEFAULT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_phone` varchar(15) NOT NULL,
  `performance` varchar(255) DEFAULT NULL,
  `university` varchar(255) DEFAULT NULL,
  `user_address` varchar(255) DEFAULT NULL,
  `status` int(1) DEFAULT '0',
  `user_pic` varchar(255) DEFAULT NULL,
  `login_time` varchar(255) NOT NULL,
  `birthday` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `realname`, `password`, `sex`, `user_email`, `user_phone`, `performance`, `university`, `user_address`, `status`, `user_pic`, `login_time`, `birthday`) VALUES
(26, 'johnjohn7', 'John Smith', '75b71aa6842e450f12aca00fdf54c51d', 0, 'johnjohn7@gmail.com', '07239302835', NULL, NULL, NULL, 0, NULL, '1510151582', NULL),
(25, 'jimlee1205', 'Jim Lee', 'd8578edf8458ce06fbc5bb76a58c5ca4', 0, 'jimlee1205@gmail.com', '07561062602', NULL, NULL, NULL, 0, NULL, '1510151582', NULL),
(24, 'ivan888', 'Ivan Ke', '449a5f6d01d5f416810d04b4df596b6a', 0, 'coolivan@hotmail.com', '07159382736', NULL, NULL, NULL, 0, NULL, '1510151582', NULL),
(23, 'JRYaoYao', 'Junrong Yao', '827ccb0eea8a706c4c34a16891f84e7b', 0, 'junrong@gmail.com', '07342126932', NULL, NULL, NULL, 0, NULL, '1510151582', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assess`
--
ALTER TABLE `assess`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enter`
--
ALTER TABLE `enter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sendemail`
--
ALTER TABLE `sendemail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
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
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `assess`
--
ALTER TABLE `assess`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `enter`
--
ALTER TABLE `enter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `sendemail`
--
ALTER TABLE `sendemail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
