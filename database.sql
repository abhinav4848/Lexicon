-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2020 at 04:52 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spsdaurm_users`
--

-- --------------------------------------------------------

--
-- Table structure for table `mbbs_categories`
--

CREATE TABLE `mbbs_categories` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `privacy` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` text NOT NULL,
  `comments` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mbbs_categories_entries_relation`
--

CREATE TABLE `mbbs_categories_entries_relation` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `created_at` text NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mbbs_comments`
--

CREATE TABLE `mbbs_comments` (
  `id` int(11) NOT NULL,
  `original_post_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `comment_by` int(11) NOT NULL,
  `timestamp` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mbbs_deleted_entries`
--

CREATE TABLE `mbbs_deleted_entries` (
  `id` int(11) NOT NULL,
  `heading` text NOT NULL,
  `body` longtext NOT NULL,
  `comment_by` int(11) NOT NULL,
  `timestamp` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mbbs_entries`
--

CREATE TABLE `mbbs_entries` (
  `id` int(11) NOT NULL,
  `heading` text NOT NULL,
  `body` longtext NOT NULL,
  `comment_by` int(11) NOT NULL,
  `timestamp` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mbbs_updates`
--

CREATE TABLE `mbbs_updates` (
  `id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `old_heading` text NOT NULL,
  `old_body` text NOT NULL,
  `updated_by_id` int(11) NOT NULL,
  `datetime` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mbbs_users`
--

CREATE TABLE `mbbs_users` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `roll` int(11) NOT NULL,
  `phone` bigint(11) NOT NULL,
  `email` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mbbs_categories`
--
ALTER TABLE `mbbs_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mbbs_categories_entries_relation`
--
ALTER TABLE `mbbs_categories_entries_relation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mbbs_comments`
--
ALTER TABLE `mbbs_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mbbs_deleted_entries`
--
ALTER TABLE `mbbs_deleted_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mbbs_entries`
--
ALTER TABLE `mbbs_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mbbs_updates`
--
ALTER TABLE `mbbs_updates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mbbs_users`
--
ALTER TABLE `mbbs_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mbbs_categories`
--
ALTER TABLE `mbbs_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mbbs_categories_entries_relation`
--
ALTER TABLE `mbbs_categories_entries_relation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mbbs_comments`
--
ALTER TABLE `mbbs_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mbbs_deleted_entries`
--
ALTER TABLE `mbbs_deleted_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mbbs_entries`
--
ALTER TABLE `mbbs_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mbbs_updates`
--
ALTER TABLE `mbbs_updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mbbs_users`
--
ALTER TABLE `mbbs_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
