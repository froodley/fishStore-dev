-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2016 at 02:14 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fishstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_address_country`
--

CREATE TABLE `tbl_address_country` (
  `country_id` int(11) NOT NULL,
  `country_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_address_state`
--

CREATE TABLE `tbl_address_state` (
  `state_id` int(11) NOT NULL,
  `state_country_id` int(11) NOT NULL,
  `state_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `admin_id` int(11) NOT NULL,
  `admin_uname` varchar(50) NOT NULL,
  `admin_password` varchar(50) NOT NULL,
  `admin_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer_address`
--

CREATE TABLE `tbl_customer_address` (
  `cust_addr_cust_id` int(11) NOT NULL,
  `cust_addr_street_1` varchar(100) NOT NULL,
  `cust_addr_street_2` varchar(100) NOT NULL,
  `cust_addr_city` varchar(50) NOT NULL,
  `cust_addr_state` int(11) NOT NULL,
  `cust_addr_code` varchar(20) NOT NULL,
  `cust_addr_country` int(11) NOT NULL,
  `cust_addr_created` datetime NOT NULL,
  `cust_addr_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer_payment_type`
--

CREATE TABLE `tbl_customer_payment_type` (
  `cust_pay_id` int(11) NOT NULL,
  `cust_pay_cust_id` int(11) NOT NULL,
  `cust_pay_card_type` int(11) NOT NULL,
  `cust_pay_card_num` int(30) NOT NULL,
  `cust_pay_card_exp` date NOT NULL,
  `cust_pay_card_cvv` int(3) NOT NULL,
  `cust_pay_created` datetime NOT NULL,
  `cust_pay_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_fish_species`
--

CREATE TABLE `tbl_fish_species` (
  `species_id` int(10) NOT NULL,
  `species_name` varchar(100) NOT NULL,
  `species_desc` text NOT NULL,
  `species_img` varchar(255) NOT NULL,
  `species_color` int(10) NOT NULL,
  `species_is_saltwater` tinyint(1) NOT NULL,
  `species_cost` decimal(10,0) NOT NULL,
  `species_created` datetime NOT NULL,
  `species_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_fish_species_color`
--

CREATE TABLE `tbl_fish_species_color` (
  `species_color_id` int(10) NOT NULL,
  `species_color_name` varchar(50) NOT NULL,
  `species_color_value` int(6) NOT NULL,
  `species_color_created` datetime NOT NULL,
  `species_color_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_item`
--

CREATE TABLE `tbl_item` (
  `item_id` int(11) NOT NULL,
  `item_ref_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_other_items`
--

CREATE TABLE `tbl_other_items` (
  `other_item_id` int(11) NOT NULL,
  `other_item_name` varchar(100) NOT NULL,
  `other_item_desc` text NOT NULL,
  `other_item_img` varchar(255) NOT NULL,
  `other_item_cost` decimal(10,0) NOT NULL,
  `other_item_created` datetime NOT NULL,
  `other_item_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sale`
--

CREATE TABLE `tbl_sale` (
  `sale_id` int(11) NOT NULL,
  `sale_cust_id` int(11) NOT NULL,
  `sale_invoice_paid` tinyint(1) NOT NULL,
  `sale_created` datetime NOT NULL,
  `sale_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sale_line_item`
--

CREATE TABLE `tbl_sale_line_item` (
  `sale_li_id` int(11) NOT NULL,
  `sale_li_sale_id` int(11) NOT NULL,
  `sale_li_item_id` int(11) NOT NULL,
  `sale_li_quantity` int(11) NOT NULL,
  `sale_li_subtotal` decimal(10,0) NOT NULL,
  `sale_li_created` datetime NOT NULL,
  `sale_li_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_session`
--

CREATE TABLE `tbl_session` (
  `session_id` varchar(255) NOT NULL,
  `session_usr_id` int(11) NOT NULL,
  `session_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_session`
--

INSERT INTO `tbl_session` (`session_id`, `session_usr_id`, `session_created`) VALUES
('jlubla1n8b1q0fb725kkhu4gf1', 50, '2016-05-26 22:05:27');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `usr_id` int(11) NOT NULL,
  `usr_email` varchar(100) NOT NULL,
  `usr_password` varchar(255) NOT NULL,
  `usr_first_name` varchar(100) NOT NULL,
  `usr_middle_init` char(1) DEFAULT NULL,
  `usr_last_name` varchar(100) NOT NULL,
  `usr_phone` varchar(30) DEFAULT NULL,
  `usr_birthday` date DEFAULT NULL,
  `usr_profile_img` varchar(255) DEFAULT NULL,
  `usr_is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `usr_created` datetime NOT NULL,
  `usr_modified` datetime DEFAULT NULL,
  `usr_is_suspended` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`usr_id`, `usr_email`, `usr_password`, `usr_first_name`, `usr_middle_init`, `usr_last_name`, `usr_phone`, `usr_birthday`, `usr_profile_img`, `usr_is_admin`, `usr_created`, `usr_modified`, `usr_is_suspended`) VALUES
(39, 'email@domain.com', 'd41d8cd98f00b204e9800998ecf8427e', 'John', NULL, 'Fishowner', '557-555-1212', '1980-01-29', '10498358_537221636400714_618355659944936148_o (1).jpg', 1, '2016-05-26 17:43:54', '2016-05-26 21:25:03', 1),
(40, 'name@name.com', '5690dddfa28ae085d23518a035707282', 'Three', NULL, 'Names', '555-555-5555', '1980-01-30', NULL, 0, '2016-05-26 19:36:59', NULL, 0),
(41, 'four@four.com', '5690dddfa28ae085d23518a035707282', 'Four', NULL, 'Four', NULL, '2016-05-26', NULL, 0, '2016-05-26 19:38:13', NULL, 0),
(42, 'five@five.com', 'd41d8cd98f00b204e9800998ecf8427e', 'Five', NULL, 'Five', NULL, '2016-05-25', NULL, 1, '2016-05-26 19:39:31', '2016-05-26 21:55:02', 1),
(43, 'seven@seven.com', 'd41d8cd98f00b204e9800998ecf8427e', 'Seven', NULL, 'Seven', NULL, '2016-05-25', NULL, 1, '2016-05-26 19:47:56', '2016-05-26 21:59:13', 1),
(44, 'eight@eight.com', '5690dddfa28ae085d23518a035707282', 'Eight', NULL, 'Eight', NULL, '2016-05-26', NULL, 0, '2016-05-26 19:56:26', NULL, 0),
(45, 'nine@nine.com', '5690dddfa28ae085d23518a035707282', 'Nine', NULL, 'Nine', NULL, '2016-05-26', NULL, 0, '2016-05-26 19:58:41', NULL, 0),
(46, 'ten@ten.com', 'd41d8cd98f00b204e9800998ecf8427e', 'Ten', NULL, 'Ten', NULL, '2016-05-23', NULL, 0, '2016-05-26 19:59:15', '2016-05-26 22:03:22', 1),
(47, 'asdf@asdf.com1', 'd41d8cd98f00b204e9800998ecf8427e', 'asdf', NULL, 'asdf', NULL, '2016-05-24', NULL, 1, '2016-05-26 20:01:35', '2016-05-26 21:54:41', 1),
(48, 'fdsa@fdsa.com', '5690dddfa28ae085d23518a035707282', 'fdsa', NULL, 'fdsa', NULL, '2016-05-26', NULL, 0, '2016-05-26 20:02:23', NULL, 0),
(49, 'bob@bobevans.com', '5690dddfa28ae085d23518a035707282', 'Bob', NULL, 'Evans', NULL, '2016-05-26', NULL, 0, '2016-05-26 20:03:23', NULL, 0),
(50, 'pburkind@gmail.com', '8fb95bba5ab2b6c2a029dcd73061cfac', 'Pete', NULL, 'Burkindine', '979-329-1619', '1980-01-30', 'turtle burger.jpg', 1, '2016-05-26 20:08:00', NULL, 0),
(51, 'jmcgoi@email.com', '5690dddfa28ae085d23518a035707282', 'John', NULL, 'McGoi', '444-333-1212', '1945-01-23', 'undertale - the toy knife.png', 0, '2016-05-26 22:05:12', NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_address_country`
--
ALTER TABLE `tbl_address_country`
  ADD PRIMARY KEY (`country_id`),
  ADD UNIQUE KEY `country_name` (`country_name`);

--
-- Indexes for table `tbl_address_state`
--
ALTER TABLE `tbl_address_state`
  ADD PRIMARY KEY (`state_id`),
  ADD UNIQUE KEY `IX_STATE` (`state_country_id`,`state_name`);

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `tbl_customer_address`
--
ALTER TABLE `tbl_customer_address`
  ADD PRIMARY KEY (`cust_addr_cust_id`);

--
-- Indexes for table `tbl_customer_payment_type`
--
ALTER TABLE `tbl_customer_payment_type`
  ADD PRIMARY KEY (`cust_pay_id`);

--
-- Indexes for table `tbl_fish_species`
--
ALTER TABLE `tbl_fish_species`
  ADD PRIMARY KEY (`species_id`),
  ADD UNIQUE KEY `IX_SPECIES` (`species_name`);

--
-- Indexes for table `tbl_fish_species_color`
--
ALTER TABLE `tbl_fish_species_color`
  ADD PRIMARY KEY (`species_color_id`),
  ADD UNIQUE KEY `IX_SPECIES_COLOR_NAME` (`species_color_name`),
  ADD UNIQUE KEY `IX_SPECIES_COLOR_VAL` (`species_color_value`);

--
-- Indexes for table `tbl_item`
--
ALTER TABLE `tbl_item`
  ADD PRIMARY KEY (`item_id`),
  ADD UNIQUE KEY `IX_ITEM` (`item_id`,`item_ref_id`) USING BTREE;

--
-- Indexes for table `tbl_other_items`
--
ALTER TABLE `tbl_other_items`
  ADD PRIMARY KEY (`other_item_id`),
  ADD UNIQUE KEY `IX_OTHER_ITEM_NAME` (`other_item_name`);

--
-- Indexes for table `tbl_sale`
--
ALTER TABLE `tbl_sale`
  ADD PRIMARY KEY (`sale_id`);

--
-- Indexes for table `tbl_sale_line_item`
--
ALTER TABLE `tbl_sale_line_item`
  ADD PRIMARY KEY (`sale_li_id`) USING BTREE,
  ADD UNIQUE KEY `IX_SALE_LI` (`sale_li_sale_id`,`sale_li_item_id`);

--
-- Indexes for table `tbl_session`
--
ALTER TABLE `tbl_session`
  ADD PRIMARY KEY (`session_id`),
  ADD UNIQUE KEY `session_usr_id` (`session_usr_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`usr_id`),
  ADD UNIQUE KEY `IX_CUST_EMAIL` (`usr_email`,`usr_password`) USING BTREE,
  ADD KEY `IX_CUST_NAME` (`usr_first_name`,`usr_middle_init`,`usr_last_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_customer_payment_type`
--
ALTER TABLE `tbl_customer_payment_type`
  MODIFY `cust_pay_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_fish_species`
--
ALTER TABLE `tbl_fish_species`
  MODIFY `species_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_fish_species_color`
--
ALTER TABLE `tbl_fish_species_color`
  MODIFY `species_color_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_item`
--
ALTER TABLE `tbl_item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_other_items`
--
ALTER TABLE `tbl_other_items`
  MODIFY `other_item_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_sale`
--
ALTER TABLE `tbl_sale`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_sale_line_item`
--
ALTER TABLE `tbl_sale_line_item`
  MODIFY `sale_li_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `usr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
