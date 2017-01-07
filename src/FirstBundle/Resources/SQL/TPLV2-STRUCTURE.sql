-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 20, 2016 at 06:12 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `field`
--

CREATE TABLE `field` (
  `id` int(11) NOT NULL,
  `workset_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `order_custom` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fos_user`
--

CREATE TABLE `fos_user` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expire_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_mikbook`
--

CREATE TABLE `item_mikbook` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL COMMENT 'FK REFERENCES item(id)',
  `user_id` int(11) NOT NULL COMMENT 'FK REFERENCES user(id)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structure for view `kanbanXitem`
-- (See below for the actual view)
--
CREATE TABLE `kanbanXitem` (
`item_id` int(11)
,`item_name` varchar(255)
,`iteration` int(11)
,`field_id` int(11)
,`user_id` int(11)
,`step` int(11)
,`done` int(1)
);

-- --------------------------------------------------------

--
-- Table structure for table `kanban_item_step`
--

CREATE TABLE `kanban_item_step` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `iteration` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `step` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `link_tour_field`
--

CREATE TABLE `link_tour_field` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL COMMENT 'FK references Tour(id)',
  `field_id` int(11) NOT NULL COMMENT 'FK references Field(id)',
  `user_id` int(11) NOT NULL COMMENT 'FK references User(id)',
  `done` int(1) NOT NULL COMMENT '0 or 1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `link_tour_item`
--

CREATE TABLE `link_tour_item` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL COMMENT 'FK references Tour(id)',
  `item_id` int(11) NOT NULL COMMENT 'FK references Item(id)',
  `user_id` int(11) NOT NULL COMMENT 'FK references User(id)',
  `done` int(1) NOT NULL COMMENT '0 or 1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reminder`
--

CREATE TABLE `reminder` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `workset_id` int(11) NOT NULL,
  `xcoord` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `ycoord` int(11) NOT NULL,
  `text` varchar(2000) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tour`
--

CREATE TABLE `tour` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `iteration` int(11) NOT NULL,
  `workset_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `tourXitem`
-- (See below for the actual view)
--
CREATE TABLE `tourXitem` (
`tour_id` int(11)
,`iteration` int(11)
,`workset_id` int(11)
,`item_id` int(11)
,`field_id` int(11)
,`done` int(1)
,`user_id` int(11)
,`login` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_link_user_field`
-- (See below for the actual view)
--
CREATE TABLE `view_link_user_field` (
`tour_id` int(11)
,`iteration` int(11)
,`user_id` int(11)
,`user` varchar(255)
,`field_id` int(11)
,`done` int(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_link_user_item`
-- (See below for the actual view)
--
CREATE TABLE `view_link_user_item` (
`tour_id` int(11)
,`iteration` int(11)
,`user_id` int(11)
,`user` varchar(255)
,`item_id` int(11)
,`done` int(1)
);

-- --------------------------------------------------------

--
-- Table structure for table `workset`
--

CREATE TABLE `workset` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `generic` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure for view `kanbanXitem`
--
DROP TABLE IF EXISTS `kanbanXitem`;

CREATE ALGORITHM=UNDEFINED DEFINER=`zolano`@`localhost` SQL SECURITY DEFINER VIEW `kanbanXitem`  AS  select `t`.`item_id` AS `item_id`,`i`.`name` AS `item_name`,`t`.`iteration` AS `iteration`,`t`.`field_id` AS `field_id`,`t`.`user_id` AS `user_id`,`k`.`step` AS `step`,`ti`.`done` AS `done` from (((`tourXitem` `t` left join `kanban_item_step` `k` on(((`k`.`item_id` = `t`.`item_id`) and (`k`.`user_id` = `t`.`user_id`) and (`k`.`iteration` = `t`.`iteration`)))) left join `item` `i` on((`t`.`item_id` = `i`.`id`))) left join `link_tour_item` `ti` on(((`t`.`tour_id` = `ti`.`tour_id`) and (`t`.`user_id` = `ti`.`user_id`) and (`t`.`item_id` = `ti`.`item_id`)))) ;

-- --------------------------------------------------------

--
-- Structure for view `tourXitem`
--
DROP TABLE IF EXISTS `tourXitem`;

CREATE ALGORITHM=UNDEFINED DEFINER=`zolano`@`localhost` SQL SECURITY DEFINER VIEW `tourXitem`  AS  select `t`.`id` AS `tour_id`,`t`.`iteration` AS `iteration`,`t`.`workset_id` AS `workset_id`,`li`.`item_id` AS `item_id`,`i`.`field_id` AS `field_id`,`li`.`done` AS `done`,`u`.`id` AS `user_id`,`u`.`login` AS `login` from ((`item` `i` left join (`tour` `t` left join `link_tour_item` `li` on((`li`.`tour_id` = `t`.`id`))) on((`li`.`item_id` = `i`.`id`))) left join `user` `u` on((`u`.`id` = `t`.`user_id`))) order by `u`.`id`,`t`.`iteration` ;

-- --------------------------------------------------------

--
-- Structure for view `view_link_user_field`
--
DROP TABLE IF EXISTS `view_link_user_field`;

CREATE ALGORITHM=UNDEFINED DEFINER=`zolano`@`localhost` SQL SECURITY DEFINER VIEW `view_link_user_field`  AS  select `t`.`id` AS `tour_id`,`t`.`iteration` AS `iteration`,`u`.`id` AS `user_id`,`u`.`login` AS `user`,`lf`.`field_id` AS `field_id`,`lf`.`done` AS `done` from ((`tour` `t` left join `link_tour_field` `lf` on((`lf`.`tour_id` = `t`.`id`))) left join `user` `u` on((`t`.`user_id` = `u`.`id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view_link_user_item`
--
DROP TABLE IF EXISTS `view_link_user_item`;

CREATE ALGORITHM=UNDEFINED DEFINER=`zolano`@`localhost` SQL SECURITY DEFINER VIEW `view_link_user_item`  AS  select `t`.`id` AS `tour_id`,`t`.`iteration` AS `iteration`,`u`.`id` AS `user_id`,`u`.`login` AS `user`,`li`.`item_id` AS `item_id`,`li`.`done` AS `done` from ((`tour` `t` left join `link_tour_item` `li` on((`li`.`tour_id` = `t`.`id`))) left join `user` `u` on((`t`.`user_id` = `u`.`id`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `field`
--
ALTER TABLE `field`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5BF54558B2389AAB` (`workset_id`);

--
-- Indexes for table `fos_user`
--
ALTER TABLE `fos_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_957A647992FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_957A6479A0D96FBF` (`email_canonical`),
  ADD UNIQUE KEY `UNIQ_957A6479C05FB297` (`confirmation_token`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1F1B251E443707B0` (`field_id`);

--
-- Indexes for table `item_mikbook`
--
ALTER TABLE `item_mikbook`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kanban_item_step`
--
ALTER TABLE `kanban_item_step`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `link_tour_field`
--
ALTER TABLE `link_tour_field`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `link_tour_item`
--
ALTER TABLE `link_tour_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reminder`
--
ALTER TABLE `reminder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tour`
--
ALTER TABLE `tour`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workset`
--
ALTER TABLE `workset`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `field`
--
ALTER TABLE `field`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `fos_user`
--
ALTER TABLE `fos_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `item_mikbook`
--
ALTER TABLE `item_mikbook`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `kanban_item_step`
--
ALTER TABLE `kanban_item_step`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `link_tour_field`
--
ALTER TABLE `link_tour_field`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `link_tour_item`
--
ALTER TABLE `link_tour_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `reminder`
--
ALTER TABLE `reminder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `tour`
--
ALTER TABLE `tour`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `workset`
--
ALTER TABLE `workset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `field`
--
ALTER TABLE `field`
  ADD CONSTRAINT `FK_5BF54558B2389AAB` FOREIGN KEY (`workset_id`) REFERENCES `workset` (`id`);

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `FK_1F1B251E443707B0` FOREIGN KEY (`field_id`) REFERENCES `field` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;