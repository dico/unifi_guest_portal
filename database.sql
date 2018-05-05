-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 05. Mai, 2018 00:32 AM
-- Server-versjon: 5.7.21-0ubuntu0.16.04.1
-- PHP Version: 7.0.28-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unifi_guest_portal`
--

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `connections`
--

CREATE TABLE `connections` (
  `id` int(11) NOT NULL,
  `time_created` datetime NOT NULL,
  `auth_method` enum('sms','login','skole') NOT NULL,
  `username` varchar(32) NOT NULL,
  `ip_address` varchar(32) NOT NULL,
  `guest_mac` varchar(32) NOT NULL,
  `ap_mac` varchar(32) NOT NULL,
  `ssid` varchar(128) NOT NULL,
  `time` datetime NOT NULL,
  `url` varchar(256) NOT NULL,
  `controller_feedback` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `customers`
--

CREATE TABLE `customers` (
  `id` varchar(32) NOT NULL,
  `name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `devices_static`
--

CREATE TABLE `devices_static` (
  `id` int(11) NOT NULL,
  `time_added` datetime NOT NULL,
  `site_id` varchar(32) NOT NULL,
  `mac_adress` varchar(64) NOT NULL,
  `comment` varchar(256) NOT NULL,
  `user` varchar(64) NOT NULL,
  `raw_feedback` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `login_ad_groups`
--

CREATE TABLE `login_ad_groups` (
  `group_name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `login_attempt`
--

CREATE TABLE `login_attempt` (
  `client_mac` varchar(32) NOT NULL,
  `time_attempt` datetime NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0=failed, 1=success'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `login_methods`
--

CREATE TABLE `login_methods` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `login_methods_groups`
--

CREATE TABLE `login_methods_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `login_methods_groups_has_method`
--

CREATE TABLE `login_methods_groups_has_method` (
  `group_id` int(11) NOT NULL,
  `method_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `mobile_auth`
--

CREATE TABLE `mobile_auth` (
  `id` int(11) NOT NULL,
  `time_created` datetime NOT NULL,
  `firstname` varchar(64) NOT NULL,
  `lastname` varchar(64) NOT NULL,
  `number` varchar(16) NOT NULL,
  `code` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `client_ip` varchar(64) NOT NULL,
  `client_mac` varchar(64) NOT NULL,
  `unifi_ap` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `lastname` varchar(128) NOT NULL,
  `room` varchar(64) NOT NULL,
  `pin` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `room_locations`
--

CREATE TABLE `room_locations` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `sites`
--

CREATE TABLE `sites` (
  `id` varchar(64) NOT NULL COMMENT 'Unifi api parameter',
  `site_desc` varchar(128) NOT NULL COMMENT 'Unifi api parameter',
  `name` varchar(128) NOT NULL COMMENT 'Unifi api parameter',
  `role` varchar(64) NOT NULL COMMENT 'Unifi api parameter',
  `app_customer_id` varchar(32) NOT NULL,
  `app_displayname` varchar(128) NOT NULL,
  `app_login_group` int(11) NOT NULL,
  `app_auth_group` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `system_log`
--

CREATE TABLE `system_log` (
  `id` int(11) NOT NULL,
  `time_created` datetime NOT NULL,
  `user` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `severity` enum('low','medium','high') NOT NULL,
  `raw_data` text NOT NULL,
  `ip_address` varchar(32) NOT NULL,
  `unifi_guest_mac` varchar(128) NOT NULL,
  `unifi_ap_mac` varchar(128) NOT NULL,
  `unifi_ssid` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(128) NOT NULL,
  `ldap` tinyint(1) NOT NULL,
  `domain` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `user_access_groups`
--

CREATE TABLE `user_access_groups` (
  `group_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `user_has_access_group`
--

CREATE TABLE `user_has_access_group` (
  `group_id` int(11) NOT NULL,
  `username` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `connections`
--
ALTER TABLE `connections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `devices_static`
--
ALTER TABLE `devices_static`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_ad_groups`
--
ALTER TABLE `login_ad_groups`
  ADD PRIMARY KEY (`group_name`);

--
-- Indexes for table `login_attempt`
--
ALTER TABLE `login_attempt`
  ADD PRIMARY KEY (`client_mac`,`time_attempt`);

--
-- Indexes for table `login_methods`
--
ALTER TABLE `login_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_methods_groups`
--
ALTER TABLE `login_methods_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_methods_groups_has_method`
--
ALTER TABLE `login_methods_groups_has_method`
  ADD PRIMARY KEY (`group_id`,`method_id`);

--
-- Indexes for table `mobile_auth`
--
ALTER TABLE `mobile_auth`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_locations`
--
ALTER TABLE `room_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_log`
--
ALTER TABLE `system_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_access_groups`
--
ALTER TABLE `user_access_groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `user_has_access_group`
--
ALTER TABLE `user_has_access_group`
  ADD PRIMARY KEY (`username`,`group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `connections`
--
ALTER TABLE `connections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `devices_static`
--
ALTER TABLE `devices_static`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `login_methods`
--
ALTER TABLE `login_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `login_methods_groups`
--
ALTER TABLE `login_methods_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `mobile_auth`
--
ALTER TABLE `mobile_auth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `room_locations`
--
ALTER TABLE `room_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `system_log`
--
ALTER TABLE `system_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `user_access_groups`
--
ALTER TABLE `user_access_groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
